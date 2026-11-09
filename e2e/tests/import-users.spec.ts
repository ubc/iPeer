import { expect, test, type Page } from '@playwright/test';
import path from 'path';
import { expectClassSize } from '../support/courses';
import { flashMessage, goodMessage, login, nav, pageTitle } from '../support/helpers';
import { COURSES, STUDENTS, USERS, fullName } from '../support/seed';
import {
  classListUserId,
  deleteUser,
  deleteUserIfPresent,
  dropIfEnrolled,
  enrolExistingUser,
  expectInClassList,
  expectNotInClassList,
  userSummaryTable,
} from '../support/users';

// Ported from app/tests/cases/system/import_users.test.php.
// The CSV files are the ones the old tests used.

const fixture = (name: string) => path.join(__dirname, '..', 'fixtures', name);

// The one account newClass_APSC201.csv names that the seed data does not have.
// Everyone else in that file is a seeded user the import updates.
const NEW_STUDENT = { username: 'username11', name: 'New Student', number: '12345678' };

// oldClass_APSC201.csv lists exactly the students APSC 201 starts with, so an
// import of it with "Remove old students" puts the class back as it was.
const SEEDED_CLASS_FILE = 'oldClass_APSC201.csv';

// What a test changed in APSC 201. Undone after the test, also when it failed.
let enrolments = new Set<{ name: string; number: string }>();
let createdUserIds = new Set<string>();

test.beforeEach(() => {
  enrolments = new Set();
  createdUserIds = new Set();
});

test.afterEach(async ({ page }) => {
  for (const userId of createdUserIds) {
    await deleteUserIfPresent(page, userId);
  }
  for (const enrolment of enrolments) {
    await dropIfEnrolled(page, COURSES.apsc201.id, enrolment);
  }
});

async function submitImportForm(page: Page): Promise<void> {
  // The view rewrites the action of this form to https so that an upload
  // always leaves the browser encrypted. The test web server serves plain http
  // only, so the form would post to a port that nothing listens on. Put the
  // scheme back to the one this environment answers on; everything else about
  // the request is what the browser would send.
  await page.locator('#UserImport form').evaluate((form) => {
    const upload = form as HTMLFormElement;
    upload.action = upload.action.replace(/^https:/, 'http:');
  });
  await page.getByRole('button', { name: 'Import' }).click();
}

async function importUsers(
  page: Page,
  courseId: string,
  file: string | { name: string; mimeType: string; buffer: Buffer },
  removeOldStudents = false
): Promise<void> {
  await page.goto(`/users/import/${courseId}`);
  await page.locator('input[type="file"]').setInputFiles(file);
  if (removeOldStudents) {
    await page.getByLabel('Remove old students').check();
  }
  await submitImportForm(page);
}

test('an import of a file type that is not allowed is refused and creates no user', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  await importUsers(page, COURSES.apsc201.id, {
    name: 'users.docx',
    mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    buffer: Buffer.from('not a CSV file'),
  });
  await expect(flashMessage(page)).toContainText('extension is not allowed');

  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});

test('a CSV import creates new users, updates existing ones and enrols them all', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  await nav(page).getByRole('link', { name: 'Courses', exact: true }).click();
  const row = page
    .getByRole('row')
    .filter({ has: page.getByRole('cell', { name: COURSES.apsc201.name, exact: true }) });
  await row.getByRole('link', { name: COURSES.apsc201.title, exact: true }).click();
  await expect(pageTitle(page)).toContainText(fullName(COURSES.apsc201));
  await page.getByRole('link', { name: 'Import Students from CSV', exact: true }).click();

  await page.locator('input[type="file"]').setInputFiles(fixture('newClass_APSC201.csv'));
  await submitImportForm(page);
  await expect(goodMessage(page)).toContainText('Import successful!');

  // The summary separates the account the file introduced from the accounts
  // that already existed. Ed is a MECH 328 student the file names, so the
  // import updates him rather than creating him.
  await expect(
    userSummaryTable(page, 'User(s) created successfully:').getByRole('cell', {
      name: NEW_STUDENT.username,
      exact: true,
    })
  ).toBeVisible();
  await expect(
    userSummaryTable(page, 'User(s) updated successfully:').getByRole('cell', {
      name: STUDENTS.ed.username,
      exact: true,
    })
  ).toBeVisible();

  const newUserId = await classListUserId(page, COURSES.apsc201.id, NEW_STUDENT);
  createdUserIds.add(newUserId);
  enrolments.add(STUDENTS.ed);

  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.ed);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount + 2);

  await deleteUser(page, newUserId);
  createdUserIds.delete(newUserId);
  await expectNotInClassList(page, COURSES.apsc201.id, NEW_STUDENT);
});

test('an import that removes old students makes the class list match the file', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  enrolments.add(STUDENTS.chris);
  await enrolExistingUser(page, COURSES.apsc201.id, STUDENTS.chris.username);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.chris);

  await importUsers(page, COURSES.apsc201.id, fixture(SEEDED_CLASS_FILE), true);
  await expect(goodMessage(page)).toContainText('Import successful!');

  // Chris is not in the file, so the import drops him; the students the file
  // does list stay enrolled.
  await expectNotInClassList(page, COURSES.apsc201.id, STUDENTS.chris);
  enrolments.clear();
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.geoff);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});
