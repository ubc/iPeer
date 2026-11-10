import { expect, test, type Locator, type Page } from '@playwright/test';
import { goodMessage } from './helpers';
import type { Person } from './seed';

export function usernameWarning(page: Page): Locator {
  return page.locator('#usernameErr');
}

export async function typeExistingUsername(page: Page, username: string): Promise<void> {
  await test.step(
    `Type existing username ${username}`,
    async () => {
      await page.getByLabel('Username', { exact: true }).fill(username);
      // The warning arrives through a field observer that polls once a second, so
      // the assertion is what waits for it.
      await expect(usernameWarning(page)).toContainText(`Username "${username}" already exists.`);
    },
    { box: true }
  );
}

export async function enrolExistingUser(
  page: Page,
  courseId: string,
  username: string
): Promise<void> {
  await test.step(
    `Enrol ${username} in course ${courseId}`,
    async () => {
      await page.goto(`/users/add/${courseId}`);
      await typeExistingUsername(page, username);
      await usernameWarning(page).getByRole('link', { name: 'here' }).click();
    },
    { box: true }
  );
}

async function findInClassList(page: Page, courseId: string, person: Person): Promise<Locator> {
  await page.goto(`/users/goToClassList/${courseId}`);
  await expect(page.locator('#table_id_info')).toBeVisible();
  // The table filters on keyup, which fill() does not produce. The student
  // number is unique, so filtering on it leaves the one row even where two
  // people share a full name.
  await page.locator('#table_id_filter input').pressSequentially(person.number);
  // The table shows 10 rows a page. Until the filter has run, a person on a
  // later page has no row, which would pass a check that they are not there.
  // A student number matches at most one row, so wait until the table shows
  // at most one entry: then no row can be on a later page.
  await expect(page.locator('#table_id_info')).toContainText(
    /Showing [01] to [01] of [01] entries/
  );
  return page.locator('#table_id tbody tr').filter({ hasText: person.name });
}

export async function expectInClassList(
  page: Page,
  courseId: string,
  person: Person
): Promise<void> {
  await test.step(
    `Expect ${person.name} in the class list of course ${courseId}`,
    async () => {
      await expect(
        await findInClassList(page, courseId, person),
        `${person.name} in course ${courseId}`
      ).toHaveCount(1);
    },
    { box: true }
  );
}

export async function expectNotInClassList(
  page: Page,
  courseId: string,
  person: Person
): Promise<void> {
  await test.step(
    `Expect ${person.name} not in the class list of course ${courseId}`,
    async () => {
      await expect(
        await findInClassList(page, courseId, person),
        `${person.name} not in course ${courseId}`
      ).toHaveCount(0);
    },
    { box: true }
  );
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
  await test.step(
    `Drop ${person.name} from course ${courseId}`,
    async () => {
      await openClassListRow(page, courseId, person);
      page.once('dialog', (dialog) => dialog.accept());
      await page.getByRole('link', { name: 'Drop', exact: true }).click();
      await expect(goodMessage(page)).toHaveText('Student is successfully unenrolled!');
    },
    { box: true }
  );
}

export async function classListUserId(
  page: Page,
  courseId: string,
  person: Person
): Promise<string> {
  return test.step(
    `Get the user id of ${person.name} from the class list of course ${courseId}`,
    async () => {
      await openClassListRow(page, courseId, person);
      const href = await page.getByRole('link', { name: 'View', exact: true }).getAttribute('href');
      const id = href?.split('/').pop();
      if (!id) {
        throw new Error(`no user id for ${person.name} in the class list of course ${courseId}`);
      }
      return id;
    },
    { box: true }
  );
}

export async function removeTutorFromCourse(
  page: Page,
  courseId: string,
  tutorName: string
): Promise<void> {
  await test.step(
    `Remove tutor ${tutorName} from course ${courseId}`,
    async () => {
      await page.goto(`/courses/edit/${courseId}`);
      const row = page
        .locator('#tutors div[id^="tutorsList"]')
        .filter({ has: page.locator(`input[value="${tutorName}"]`) });
      await expect(row).toHaveCount(1);
      await row.getByRole('link', { name: 'X', exact: true }).click();
      await page.getByRole('button', { name: 'Save' }).click();
      await expect(goodMessage(page)).toHaveText('The course was updated successfully.');
    },
    { box: true }
  );
}

// The roles the course selects of the user edit form offer. 'none' unenrols.
export type CourseRole = 'none' | 'instructor' | 'tutor' | 'student';
export type SystemRole = 'superadmin' | 'admin' | 'instructor' | 'tutor' | 'student';

export async function setUserRoles(
  page: Page,
  userId: string,
  roles: {
    readonly primary: SystemRole;
    readonly faculty?: string;
    readonly courses?: Readonly<Record<string, CourseRole>>;
  }
): Promise<void> {
  const courseRoles = Object.entries(roles.courses ?? {}).map(
    ([courseId, role]) => `, ${role} in course ${courseId}`
  );
  const faculty = roles.faculty ? ` in ${roles.faculty}` : '';
  await test.step(
    `Set the roles of user ${userId}: ${roles.primary}${faculty}${courseRoles.join('')}`,
    async () => {
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
    },
    { box: true }
  );
}
