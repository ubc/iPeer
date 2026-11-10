import { expect, test, type Locator, type Page } from '../support/test';
import { expectClassSize } from '../support/courses';
import { fixture, flashMessage, goodMessage, login, nav, pageTitle } from '../support/helpers';
import { COURSES, STUDENTS, USERS, fullName } from '../support/seed';
import { enrolExistingUser, expectInClassList, expectNotInClassList } from '../support/users';

// Ported from app/tests/cases/system/import_users.test.php.
// The CSV files are the ones the old tests used.

// newClass_APSC201.csv lists the students APSC 201 starts with, Ed, who is a
// MECH 328 student, and this account, which the seed data does not have.
const NEW_STUDENT = { username: 'username11', name: 'New Student', number: '12345678' };

// oldClass_APSC201.csv lists exactly the students APSC 201 starts with.
const SEEDED_CLASS_FILE = 'oldClass_APSC201.csv';

function userSummaryTable(page: Page, heading: string): Locator {
  return page.locator(`xpath=//h3[normalize-space()='${heading}']/following-sibling::table[1]`);
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
  await page.getByRole('button', { name: 'Import' }).click();
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
  await page.getByRole('button', { name: 'Import' }).click();
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

  await expectInClassList(page, COURSES.apsc201.id, NEW_STUDENT);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.ed);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount + 2);
});

test('an import that removes old students makes the class list match the file', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  await enrolExistingUser(page, COURSES.apsc201.id, STUDENTS.chris.username);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.chris);

  await importUsers(page, COURSES.apsc201.id, fixture(SEEDED_CLASS_FILE), true);
  await expect(goodMessage(page)).toContainText('Import successful!');

  // Chris is not in the file, so the import drops him; the students the file
  // does list stay enrolled.
  await expectNotInClassList(page, COURSES.apsc201.id, STUDENTS.chris);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.geoff);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});
