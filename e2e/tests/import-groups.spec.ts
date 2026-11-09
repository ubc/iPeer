import { expect, test, type Page } from '@playwright/test';
import path from 'path';
import { expectGroupMembers, openGroupList } from '../support/groups';
import {
  USERS,
  flashMessage,
  goodMessage,
  idFromUrl,
  login,
  pageTitle,
  resultsTable,
} from '../support/helpers';

// Ported from app/tests/cases/system/import_groups.test.php.
// The CSV files are the ones the old tests used.

const fixture = (name: string) => path.join(__dirname, '..', 'fixtures', name);

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
  return idFromUrl(page);
}

test('an import with no file is refused', async ({ page }) => {
  await login(page, USERS.instructor2);

  await page.goto('/groups/import/2');
  await page.getByRole('button', { name: 'Import' }).click();
  await expect(flashMessage(page)).toHaveText('Please select a file to upload.');

  await openGroupList(page, '2');
  await expect(page.getByRole('link', { name: /^Team \(/ })).toHaveCount(0);
});

test('an import of a file type that is not allowed is refused', async ({ page }) => {
  await login(page, USERS.instructor2);

  await importCsv(page, '2', {
    name: 'groups.docx',
    mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    buffer: Buffer.from('not a CSV file'),
  });
  await expect(flashMessage(page)).toContainText('extension is not allowed');

  await openGroupList(page, '2');
  await expect(page.getByRole('link', { name: /^Team \(/ })).toHaveCount(0);
});

test('a valid CSV creates the listed groups with the listed members', async ({ page }) => {
  await login(page, USERS.instructor2);

  await importCsv(page, '2', fixture('importGroup.csv'));
  await expect(page.locator('#title')).toHaveText('The group CSV file was processed.');

  const groups = resultsTable(page, 'Groups Created');
  await expect(groups.getByRole('cell', { name: 'Team', exact: true })).toBeVisible();
  const placed = resultsTable(page, 'Students Placed');
  for (const username of ['redshirt0003', 'redshirt0005', 'redshirt0006']) {
    await expect(placed.getByRole('cell', { name: username, exact: true })).toBeVisible();
  }
  await expect(page.getByRole('heading', { name: 'Students Not Placed' })).toHaveCount(0);

  const groupId = await openGroup(page, '2', 'Team');
  await expect(page.getByRole('cell', { name: 'Team', exact: true })).toBeVisible();
  await page.goto(`/groups/edit/${groupId}`);
  await expect(page.locator('#GroupGroupName')).toHaveValue('Team');
  // The students of importGroup.csv: redshirt0003, redshirt0005 and
  // redshirt0006. The chooser labels them by student number.
  await expectGroupMembers(page, ['98985481', '81121651', '87800283']);

  await page.goto(`/groups/delete/${groupId}`);
  await expect(goodMessage(page)).toHaveText('The group was deleted successfully.');
});

test('an import reports the students it could not place', async ({ page }) => {
  await login(page, USERS.instructor1);

  // The CSV names accounts that do not exist, students of other courses and
  // accounts that are not students of this course.
  await importCsv(page, '1', fixture('invalidGroupMembers.csv'));
  await expect(page.locator('#title')).toHaveText('The group CSV file was processed.');

  await expect(page.getByRole('heading', { name: 'Students Placed' })).toHaveCount(0);
  const notPlaced = resultsTable(page, 'Students Not Placed');
  for (const username of [
    'instructor1',
    'redshirt0004',
    'redshirt0005',
    'root',
    'tutor3',
    'redshirt9998',
    'redshirt9999',
  ]) {
    await expect(notPlaced.getByRole('cell', { name: username, exact: true })).toBeVisible();
  }

  // The group is created, but with none of those students in it.
  const groupId = await openGroup(page, '1', 'Team Supreme');
  await expect(page.getByRole('cell', { name: 'Team Supreme', exact: true })).toBeVisible();
  await page.goto(`/groups/edit/${groupId}`);
  await expect(page.locator('#selected_groups option')).toHaveCount(0);

  await page.goto(`/groups/delete/${groupId}`);
  await expect(goodMessage(page)).toHaveText('The group was deleted successfully.');
});

test('an import can match students by student number', async ({ page }) => {
  await login(page, USERS.instructor1);

  await importCsv(page, '1', fixture('groupsStudentNo.csv'), 'Student No.');
  await expect(page.locator('#title')).toHaveText('The group CSV file was processed.');

  const placed = resultsTable(page, 'Students Placed');
  for (const studentNo of ['65498451', '65468188', '98985481', '84188465']) {
    await expect(placed.getByRole('cell', { name: studentNo, exact: true })).toBeVisible();
  }
  await expect(page.getByRole('heading', { name: 'Students Not Placed' })).toHaveCount(0);

  const groupId = await openGroup(page, '1', 'Best Team');
  await page.goto(`/groups/edit/${groupId}`);
  // The same student numbers the CSV named, so the import matched the right
  // students and not only the right number of them.
  await expectGroupMembers(page, ['65498451', '65468188', '98985481', '84188465']);

  await page.goto(`/groups/delete/${groupId}`);
  await expect(goodMessage(page)).toHaveText('The group was deleted successfully.');
});
