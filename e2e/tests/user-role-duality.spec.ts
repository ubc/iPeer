import { expect, test, type Page } from '../support/test';
import { expectInstructorChoice } from '../support/courses';
import { expectTabs, login } from '../support/helpers';
import { COURSES, FACULTIES, STUDENTS, USERS, fullName } from '../support/seed';
import { classListUserId, setUserRoles } from '../support/users';

// Ported from app/tests/cases/system/edit_role.test.php.

// A MECH 328 student with evaluations still to do, so the student half of the
// home page has work to show.
const STUDENT = STUDENTS.alex;

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

async function makeInstructorOfApsc201(page: Page): Promise<string> {
  const id = await classListUserId(page, COURSES.mech328.id, STUDENT);
  await setUserRoles(page, id, {
    primary: 'instructor',
    faculty: FACULTIES[0],
    courses: { [COURSES.apsc201.id]: 'instructor' },
  });
  return id;
}

function pendingStudentWork(page: Page) {
  return page.locator('#StudentHome .eventSummary.pending');
}

test('a student of one course who is made instructor of another sees both views', async ({
  page,
}) => {
  const id = await makeInstructorOfApsc201(page);

  await login(page, STUDENT.username);
  await page.goto('/home');
  await expect(page.getByRole('heading', { level: 1, name: 'Instructor View' })).toBeVisible();
  // The instructor half lists the course they teach, not the course they study.
  const instructorHalf = page.locator('.instructorView');
  await expect(
    instructorHalf.getByRole('heading', { level: 3, name: fullName(COURSES.apsc201) })
  ).toBeVisible();
  await expect(
    instructorHalf.getByRole('heading', { level: 3, name: fullName(COURSES.mech328) })
  ).toHaveCount(0);
  await expect(page.getByRole('heading', { level: 1, name: 'Student View' })).toBeVisible();
  // The student half still shows the evaluations they have to do.
  await expect(pendingStudentWork(page)).toContainText('Pending Event(s) Total');
  await expectTabs(page, ['Home', 'Courses', 'Evaluation']);

  await login(page, USERS.superAdmin);
  await setUserRoles(page, id, {
    primary: 'student',
    courses: { [COURSES.apsc201.id]: 'none' },
  });

  await login(page, STUDENT.username);
  await page.goto('/home');
  // The page is no longer split, so neither half has its heading.
  await expect(page.getByRole('heading', { level: 1, name: 'Instructor View' })).toHaveCount(0);
  await expect(page.getByRole('heading', { level: 1, name: 'Student View' })).toHaveCount(0);
  await expect(pendingStudentWork(page)).toContainText('Pending Event(s) Total');
  await expectTabs(page, ['Home']);
});

test('a student of a course is not offered as an instructor of that same course', async ({
  page,
}) => {
  await makeInstructorOfApsc201(page);

  // They are an instructor system wide, so a course they do not study offers
  // them. MECH 328, where they are a student, must not.
  await expectInstructorChoice(page, COURSES.cpsc404.id, STUDENT.name, true);
  await expectInstructorChoice(page, COURSES.mech328.id, STUDENT.name, false);
});
