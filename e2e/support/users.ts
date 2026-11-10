import { expect, type Locator, type Page } from '@playwright/test';
import { goodMessage } from './helpers';
import type { Person } from './seed';

export function usernameWarning(page: Page): Locator {
  return page.locator('#usernameErr');
}

export async function typeExistingUsername(page: Page, username: string): Promise<void> {
  await page.getByLabel('Username', { exact: true }).fill(username);
  // The warning arrives through a field observer that polls once a second, so
  // the assertion is what waits for it.
  await expect(usernameWarning(page)).toContainText(`Username "${username}" already exists.`);
}

export async function enrolExistingUser(
  page: Page,
  courseId: string,
  username: string
): Promise<void> {
  await page.goto(`/users/add/${courseId}`);
  await typeExistingUsername(page, username);
  await usernameWarning(page).getByRole('link', { name: 'here' }).click();
}

async function findInClassList(page: Page, courseId: string, person: Person): Promise<Locator> {
  await page.goto(`/users/goToClassList/${courseId}`);
  await expect(page.locator('#table_id_info')).toBeVisible();
  // The table filters on keyup, which fill() does not produce. The student
  // number is unique, so filtering on it leaves the one row even where two
  // people share a full name.
  await page.locator('#table_id_filter input').pressSequentially(person.number);
  return page.locator('#table_id tbody tr').filter({ hasText: person.name });
}

export async function expectInClassList(
  page: Page,
  courseId: string,
  person: Person
): Promise<void> {
  await expect(
    await findInClassList(page, courseId, person),
    `${person.name} in course ${courseId}`
  ).toHaveCount(1);
}

export async function expectNotInClassList(
  page: Page,
  courseId: string,
  person: Person
): Promise<void> {
  await expect(
    await findInClassList(page, courseId, person),
    `${person.name} in course ${courseId}`
  ).toHaveCount(0);
}

async function openClassListRow(page: Page, courseId: string, person: Person): Promise<void> {
  const row = await findInClassList(page, courseId, person);
  await expect(row).toHaveCount(1);
  // The row actions live in a panel that a click on the row opens.
  await row.getByRole('cell').first().click();
}

export async function dropFromClassList(
  page: Page,
  courseId: string,
  person: Person
): Promise<void> {
  await openClassListRow(page, courseId, person);
  page.once('dialog', (dialog) => dialog.accept());
  await page.getByRole('link', { name: 'Drop', exact: true }).click();
  await expect(goodMessage(page)).toHaveText('Student is successfully unenrolled!');
}

export async function classListUserId(
  page: Page,
  courseId: string,
  person: Person
): Promise<string> {
  await openClassListRow(page, courseId, person);
  const href = await page.getByRole('link', { name: 'View', exact: true }).getAttribute('href');
  const id = href?.split('/').pop();
  if (!id) {
    throw new Error(`no user id for ${person.name} in the class list of course ${courseId}`);
  }
  return id;
}

export async function removeTutorFromCourse(
  page: Page,
  courseId: string,
  tutorName: string
): Promise<void> {
  await page.goto(`/courses/edit/${courseId}`);
  const row = page
    .locator('#tutors div[id^="tutorsList"]')
    .filter({ has: page.locator(`input[value="${tutorName}"]`) });
  await expect(row).toHaveCount(1);
  await row.getByRole('link', { name: 'X', exact: true }).click();
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('The course was updated successfully.');
}

// 'none' is only a course role: it is how the user edit form unenrols someone.
export type CourseRole = 'none' | SystemRole;
export type SystemRole = 'superadmin' | 'admin' | 'instructor' | 'tutor' | 'student';

export type NewUser = Person & {
  readonly username: string;
  readonly firstName: string;
  readonly lastName: string;
  readonly role: SystemRole;
  readonly courseIds: readonly string[];
};

export function newStudent(
  username: string,
  firstName: string,
  lastName: string,
  number: string,
  courseIds: readonly string[]
): NewUser {
  return {
    username,
    firstName,
    lastName,
    name: `${firstName} ${lastName}`,
    number,
    role: 'student',
    courseIds,
  };
}

export async function createUser(page: Page, user: NewUser): Promise<string> {
  await page.goto('/users/add');
  await page.getByLabel('Username', { exact: true }).fill(user.username);
  await page.getByLabel('First Name').fill(user.firstName);
  await page.getByLabel('Last Name').fill(user.lastName);
  await page.getByLabel('Student Number').fill(user.number);
  await page.getByLabel('Role', { exact: true }).selectOption({ label: user.role });
  for (const courseId of user.courseIds) {
    await page.locator(`#CoursesId${courseId}`).check();
  }
  await page.getByRole('button', { name: 'Save', exact: true }).click();

  // iPeer generates the password itself and shows it once, in the message that
  // confirms the account, so this is the only chance to capture it. The caller
  // needs it to sign in as the account it has just made.
  const message = (await goodMessage(page).innerText()).trim();
  const password = message.match(/Password:\s*(\S+)/)?.[1];
  if (!password) {
    throw new Error(`no account created for ${user.username}`);
  }
  return password;
}

// Creates a student account and finds its id through the class list of its
// first course.
export async function createStudent(
  page: Page,
  user: NewUser
): Promise<{ id: string; password: string }> {
  const password = await createUser(page, user);
  const id = await classListUserId(page, user.courseIds[0], user);
  return { id, password };
}

export async function setUserRoles(
  page: Page,
  userId: string,
  roles: {
    readonly primary: SystemRole;
    readonly faculty?: string;
    readonly courses?: Readonly<Record<string, CourseRole>>;
  }
): Promise<void> {
  await page.goto(`/users/edit/${userId}`);
  for (const [courseId, role] of Object.entries(roles.courses ?? {})) {
    await page.locator(`#course_${courseId}`).selectOption({ label: role });
  }
  // The primary role goes last: picking an instructor course raises it on its
  // own, and this must be the value that ends up submitted. It also decides
  // whether the faculty field is live, and iPeer refuses to save an instructor
  // or an admin that has no faculty.
  await page.getByLabel('Primary Role').selectOption({ label: roles.primary });
  if (roles.faculty) {
    await page.locator('#FacultyFaculty').selectOption({ label: roles.faculty });
  }
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('User successfully updated!');
}
