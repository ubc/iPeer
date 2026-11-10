import { expect, test, type Page } from '../support/test';
import { expectClassSize, expectCourseTutors } from '../support/courses';
import { flashMessage, goodMessage, login } from '../support/helpers';
import { COURSES, STUDENTS, TUTORS, USERS } from '../support/seed';
import {
  dropFromClassList,
  enrolExistingUser,
  expectInClassList,
  expectNotInClassList,
  removeTutorFromCourse,
  typeExistingUsername,
  usernameWarning,
} from '../support/users';

// Ported from app/tests/cases/system/enrol_student.test.php.

const SEEDED_MECH328_TUTORS = [TUTORS.tutor1.name, TUTORS.tutor2.name];

async function enrolChrisInApsc201(page: Page): Promise<void> {
  await enrolExistingUser(page, COURSES.apsc201.id, STUDENTS.chris.username);
  await expect(goodMessage(page)).toHaveText('User is successfully enrolled.');
}

async function enrolTutor3InMech328(page: Page): Promise<void> {
  await enrolExistingUser(page, COURSES.mech328.id, TUTORS.tutor3.username);
  await expect(goodMessage(page)).toHaveText('User is successfully enrolled.');
}

test('a super admin enrols an existing student and the student joins the class list', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  await enrolChrisInApsc201(page);

  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.chris);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount + 1);
});

test('a super admin unenrols a student and the student leaves the class list', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await enrolChrisInApsc201(page);

  await dropFromClassList(page, COURSES.apsc201.id, STUDENTS.chris);

  await expectNotInClassList(page, COURSES.apsc201.id, STUDENTS.chris);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});

test('a super admin enrols a tutor and the tutor joins the course tutor list', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await enrolTutor3InMech328(page);

  await expectCourseTutors(page, COURSES.mech328.id, [
    ...SEEDED_MECH328_TUTORS,
    TUTORS.tutor3.name,
  ]);
  // A tutor is not a student, so the class list must not grow.
  await expectClassSize(page, COURSES.mech328.id, COURSES.mech328.studentCount);
});

test('a super admin unenrols a tutor from a course', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await enrolTutor3InMech328(page);

  await removeTutorFromCourse(page, COURSES.mech328.id, TUTORS.tutor3.name);

  await expectCourseTutors(page, COURSES.mech328.id, SEEDED_MECH328_TUTORS);
});

test('the Add User page outside a course offers no enrolment shortcut', async ({ page }) => {
  await login(page, USERS.superAdmin);

  // Reached from the Users index, the form has no course to enrol into, so the
  // name clash is reported without the link that the course form offers.
  await page.goto('/users/add');
  await typeExistingUsername(page, STUDENTS.matt.username);
  await expect(usernameWarning(page).getByRole('link')).toHaveCount(0);
});

test('enrolling a user who is neither a student nor a tutor is refused', async ({ page }) => {
  await login(page, USERS.superAdmin);

  // An instructor ranks below the super admin, so only the check that the
  // user is a student or a tutor can refuse this.
  await enrolExistingUser(page, COURSES.mech328.id, USERS.instructor1);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to enrol this user.'
  );

  await expectClassSize(page, COURSES.mech328.id, COURSES.mech328.studentCount);
  await expectCourseTutors(page, COURSES.mech328.id, SEEDED_MECH328_TUTORS);
});

test('enrolling a student who is already in the course is refused', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await enrolExistingUser(page, COURSES.mech328.id, STUDENTS.ed.username);
  await expect(flashMessage(page)).toHaveText('Error: The student is already enrolled.');

  // The database refuses a second enrolment row in any case, so the message is
  // the check. The class list shows that nothing else changed.
  await expectInClassList(page, COURSES.mech328.id, STUDENTS.ed);
  await expectClassSize(page, COURSES.mech328.id, COURSES.mech328.studentCount);
});

test('enrolling a tutor who already tutors the course is refused', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await enrolExistingUser(page, COURSES.mech328.id, TUTORS.tutor1.username);
  await expect(flashMessage(page)).toHaveText('Error: The student is already enrolled.');

  await expectCourseTutors(page, COURSES.mech328.id, SEEDED_MECH328_TUTORS);
});
