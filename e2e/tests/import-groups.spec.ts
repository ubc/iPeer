import { expect, test, type Page } from '../support/test';
import { expectGroupMembers, openGroupList } from '../support/groups';
import {
  fixture,
  flashMessage,
  idFromUrl,
  login,
  notCsvFile,
  pageTitle,
  resultsTable,
} from '../support/helpers';
import { COURSES, STUDENTS, USERS } from '../support/seed';

// Ported from app/tests/cases/system/import_groups.test.php.
// The CSV files are the ones the old tests used.

// Who each CSV in e2e/fixtures names.
const BY_USERNAME = [STUDENTS.matt, STUDENTS.johnny, STUDENTS.travis];
const BY_NUMBER = [STUDENTS.ed, STUDENTS.alex, STUDENTS.matt, STUDENTS.damien];

async function importCsv(
  page: Page,
  courseId: string,
  file: string | { name: string; mimeType: string; buffer: Buffer },
  identifier: 'Username' | 'Student No.' = 'Username'
): Promise<void> {
  await page.goto(`/groups/import/${courseId}`);
  await page.locator('input[type="file"]').setInputFiles(file);
  await page.getByLabel(identifier, { exact: true }).check();
  await page.getByRole('button', { name: 'Import' }).click();
}

async function openGroup(page: Page, courseId: string, name: string): Promise<string> {
  await openGroupList(page, courseId);
  await page.getByRole('link', { name: new RegExp(`^${name} \\(`) }).click();
  await expect(pageTitle(page)).toContainText('Groups > View');
  await expect(page).toHaveURL(/\/groups\/view\/\d+$/);
  return idFromUrl(page);
}

test('an import with no file is refused', async ({ page }) => {
  await login(page, USERS.instructor2);

  await page.goto(`/groups/import/${COURSES.apsc201.id}`);
  await page.getByRole('button', { name: 'Import' }).click();
  await expect(flashMessage(page)).toHaveText('Please select a file to upload.');

  await openGroupList(page, COURSES.apsc201.id);
  await expect(page.getByRole('link', { name: /^Team \(/ })).toHaveCount(0);
});

test('an import of a file type that is not allowed is refused', async ({ page }) => {
  await login(page, USERS.instructor2);

  await importCsv(page, COURSES.apsc201.id, notCsvFile('groups'));
  await expect(flashMessage(page)).toHaveText('extension is not allowed.');

  await openGroupList(page, COURSES.apsc201.id);
  await expect(page.getByRole('link', { name: /^Team \(/ })).toHaveCount(0);
});

test('a valid CSV creates the listed groups with the listed members', async ({ page }) => {
  await login(page, USERS.instructor2);

  await importCsv(page, COURSES.apsc201.id, fixture('importGroup.csv'));
  await expect(page.locator('#title')).toHaveText('The group CSV file was processed.');

  const groups = resultsTable(page, 'Groups Created');
  await expect(groups.getByRole('cell', { name: 'Team', exact: true })).toBeVisible();
  const placed = resultsTable(page, 'Students Placed');
  for (const student of BY_USERNAME) {
    await expect(placed.getByRole('cell', { name: student.username, exact: true })).toBeVisible();
  }
  await expect(page.getByRole('heading', { name: 'Students Not Placed' })).toHaveCount(0);

  const groupId = await openGroup(page, COURSES.apsc201.id, 'Team');
  await expect(page.getByRole('cell', { name: 'Team', exact: true })).toBeVisible();
  await page.goto(`/groups/edit/${groupId}`);
  await expect(page.locator('#GroupGroupName')).toHaveValue('Team');
  await expectGroupMembers(
    page,
    BY_USERNAME.map((s) => s.number)
  );
});

test('an import reports the students it could not place', async ({ page }) => {
  await login(page, USERS.instructor1);

  // The CSV names accounts that do not exist, students of other courses and
  // accounts that are not students of this course.
  await importCsv(page, COURSES.mech328.id, fixture('invalidGroupMembers.csv'));
  await expect(page.locator('#title')).toHaveText('The group CSV file was processed.');

  await expect(page.getByRole('heading', { name: 'Students Placed' })).toHaveCount(0);
  const notPlaced = resultsTable(page, 'Students Not Placed');
  for (const username of [
    USERS.instructor1,
    STUDENTS.chris.username,
    STUDENTS.johnny.username,
    USERS.superAdmin,
    'tutor3',
    // These two accounts do not exist at all.
    'redshirt9998',
    'redshirt9999',
  ]) {
    await expect(notPlaced.getByRole('cell', { name: username, exact: true })).toBeVisible();
  }

  // The group is created, but with none of those students in it.
  const groupId = await openGroup(page, COURSES.mech328.id, 'Team Supreme');
  await expect(page.getByRole('cell', { name: 'Team Supreme', exact: true })).toBeVisible();
  await page.goto(`/groups/edit/${groupId}`);
  await expect(page.locator('#selected_groups option')).toHaveCount(0);
});

test('an import can match students by student number', async ({ page }) => {
  await login(page, USERS.instructor1);

  await importCsv(page, COURSES.mech328.id, fixture('groupsStudentNo.csv'), 'Student No.');
  await expect(page.locator('#title')).toHaveText('The group CSV file was processed.');

  const placed = resultsTable(page, 'Students Placed');
  for (const student of BY_NUMBER) {
    await expect(placed.getByRole('cell', { name: student.number, exact: true })).toBeVisible();
  }
  await expect(page.getByRole('heading', { name: 'Students Not Placed' })).toHaveCount(0);

  const groupId = await openGroup(page, COURSES.mech328.id, 'Best Team');
  await page.goto(`/groups/edit/${groupId}`);
  await expectGroupMembers(
    page,
    BY_NUMBER.map((s) => s.number)
  );
});
