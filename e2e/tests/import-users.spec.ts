import { expect, test, type Page } from '../support/test';
import { expectClassSize } from '../support/courses';
import {
  fixture,
  flashMessage,
  goodMessage,
  login,
  notCsvFile,
  resultsTable,
} from '../support/helpers';
import { COURSES, STUDENTS, USERS } from '../support/seed';
import {
  classListUserId,
  expectInClassList,
  expectNotInClassList,
  insertEnrolment,
} from '../support/users';

// Ported from app/tests/cases/system/import_users.test.php.
// The CSV files are the ones the old tests used.

// newClass_APSC201.csv lists the students APSC 201 starts with, Ed, who is a
// MECH 328 student, and this account, which the seed data does not have.
const NEW_STUDENT = { username: 'username11', name: 'New Student', number: '12345678' };

// oldClass_APSC201.csv lists exactly the students APSC 201 starts with.
const SEEDED_CLASS_FILE = 'oldClass_APSC201.csv';

type ImportFile = string | { name: string; mimeType: string; buffer: Buffer };

// Submits the import form of the page that is open.
async function submitImport(
  page: Page,
  file: ImportFile,
  removeOldStudents = false
): Promise<void> {
  await page.locator('input[type="file"]').setInputFiles(file);
  if (removeOldStudents) {
    await page.getByLabel('Remove old students').check();
  }
  await page.getByRole('button', { name: 'Import' }).click();
}

async function importUsers(
  page: Page,
  courseId: string,
  file: ImportFile,
  removeOldStudents = false
): Promise<void> {
  await page.goto(`/users/import/${courseId}`);
  await submitImport(page, file, removeOldStudents);
}

test('an import of a file type that is not allowed is refused and creates no user', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  await importUsers(page, COURSES.apsc201.id, notCsvFile('users'));
  await expect(flashMessage(page)).toHaveText('extension is not allowed.');

  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});

test('a CSV import creates new users, updates existing ones and enrols them all', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  // Rename Ed first, so that the import has a change to save: the file has
  // his seeded name. The class list matches names as case-insensitive
  // substrings, so the temporary name must not contain "ed Student".
  const edId = await classListUserId(page, COURSES.mech328.id, STUDENTS.ed);
  await page.goto(`/users/edit/${edId}`);
  await page.getByLabel('First Name').fill('Temporary');
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('User successfully updated!');
  await expectInClassList(page, COURSES.mech328.id, { ...STUDENTS.ed, name: 'Temporary Student' });

  await page.goto(`/users/import/${COURSES.apsc201.id}`);

  await submitImport(page, fixture('newClass_APSC201.csv'));
  await expect(goodMessage(page)).toContainText('Import successful!');

  // The summary separates the account the file introduced from the accounts
  // that already existed. Ed is a MECH 328 student the file names, so the
  // import updates him rather than creating him.
  await expect(
    resultsTable(page, 'User(s) created successfully:', 'h3').getByRole('cell', {
      name: NEW_STUDENT.username,
      exact: true,
    })
  ).toBeVisible();
  await expect(
    resultsTable(page, 'User(s) updated successfully:', 'h3').getByRole('cell', {
      name: STUDENTS.ed.username,
      exact: true,
    })
  ).toBeVisible();

  await expectInClassList(page, COURSES.apsc201.id, NEW_STUDENT);
  // Under his name from the file, so the import saved it.
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.ed);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount + 2);
});

test('an import that removes old students makes the class list match the file', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.superAdmin);

  await insertEnrolment(testDatabase, COURSES.apsc201.id, STUDENTS.chris.username);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.chris);

  await importUsers(page, COURSES.apsc201.id, fixture(SEEDED_CLASS_FILE), true);
  await expect(goodMessage(page)).toContainText('Import successful!');

  // Chris is not in the file, so the import drops him; the students the file
  // does list stay enrolled.
  await expectNotInClassList(page, COURSES.apsc201.id, STUDENTS.chris);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.geoff);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});
