import { expect, test, type Page } from '../support/test';
import { expectInstructorChoice } from '../support/courses';
import { expectTabs, login } from '../support/helpers';
import { COURSES, FACULTIES, USERS } from '../support/seed';
import { createStudent, newStudent, setUserRoles, type NewUser } from '../support/users';

// Ported from app/tests/cases/system/edit_role.test.php.

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

async function createMech328Student(
  page: Page
): Promise<{ user: NewUser; id: string; password: string }> {
  const user = newStudent('e2edual', 'Dual', 'Role', '90000001', [COURSES.mech328.id]);
  const { id, password } = await createStudent(page, user);
  return { user, id, password };
}

test('a student of one course who is made instructor of another sees both views', async ({
  page,
}) => {
  const student = await createMech328Student(page);

  await setUserRoles(page, student.id, {
    primary: 'instructor',
    faculty: FACULTIES[0],
    courses: { [COURSES.apsc201.id]: 'instructor' },
  });

  await login(page, student.user.username, student.password);
  await page.goto('/home');
  await expect(page.getByRole('heading', { level: 1, name: 'Instructor View' })).toBeVisible();
  // The instructor half lists the course they teach, not the course they study.
  await expect(
    page.getByRole('heading', { level: 3, name: 'APSC 201 - Technical Communication' })
  ).toBeVisible();
  await expect(page.getByRole('heading', { level: 1, name: 'Student View' })).toBeVisible();
  await expect(page.locator('#StudentHome')).toBeVisible();
  await expectTabs(page, ['Home', 'Courses', 'Evaluation']);

  await login(page, USERS.superAdmin);
  await setUserRoles(page, student.id, {
    primary: 'student',
    courses: { [COURSES.apsc201.id]: 'none' },
  });

  await login(page, student.user.username, student.password);
  await page.goto('/home');
  await expect(page.getByRole('heading', { level: 1, name: 'Instructor View' })).toHaveCount(0);
  await expect(page.locator('#StudentHome')).toBeVisible();
  await expectTabs(page, ['Home']);
});

test('a student of a course is not offered as an instructor of that same course', async ({
  page,
}) => {
  const student = await createMech328Student(page);
  await setUserRoles(page, student.id, {
    primary: 'instructor',
    faculty: FACULTIES[0],
    courses: { [COURSES.apsc201.id]: 'instructor' },
  });

  // They are an instructor system wide, so a course they do not study offers
  // them. MECH 328, where they are a student, must not.
  await expectInstructorChoice(page, COURSES.cpsc404.id, student.user.name, true);
  await expectInstructorChoice(page, COURSES.mech328.id, student.user.name, false);
});
