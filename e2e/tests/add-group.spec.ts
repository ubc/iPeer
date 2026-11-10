import { expect, test, type Page } from '../support/test';
import { assignToGroup, openGroupList, removeFromGroup } from '../support/groups';
import { errorMessage, goodMessage, idFromUrl, login, pageTitle } from '../support/helpers';
import { COURSES, STUDENTS as SEED_STUDENTS, USERS, fullName, type Person } from '../support/seed';

// Ported from app/tests/cases/system/add_group.test.php.

// APSC 201 has no seeded group, so the group numbers start at 1.
const COURSE_ID = COURSES.apsc201.id;
const COURSE = fullName(COURSES.apsc201);
const STUDENTS = [
  SEED_STUDENTS.geoff,
  SEED_STUDENTS.vanHong,
  SEED_STUDENTS.denny,
  SEED_STUDENTS.trevor,
];

async function openGroup(page: Page, name: string): Promise<string> {
  await openGroupList(page, COURSE_ID);
  await page.getByRole('link', { name: new RegExp(`^${name} \\(`) }).click();
  const groupId = idFromUrl(page);
  await expect(pageTitle(page)).toContainText(`${COURSE} > Groups > View`);
  return groupId;
}

async function createGroup(page: Page, name: string, students: readonly Person[]): Promise<string> {
  await page.goto(`/courses/home/${COURSE_ID}`);
  await page.getByRole('link', { name: 'Add Group', exact: true }).click();
  await page.locator('#GroupGroupName').fill(name);
  await assignToGroup(page, students);
  await page.getByRole('button', { name: 'Add Group' }).click();
  await expect(goodMessage(page)).toHaveText('The group was added successfully.');
  return openGroup(page, name);
}

test('the Add Group form pre-fills a read-only number and needs a name', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await page.goto(`/courses/home/${COURSE_ID}`);
  await page.getByRole('link', { name: 'Add Group', exact: true }).click();
  await expect(pageTitle(page)).toContainText(`${COURSE} > Add Group`);

  const groupNum = page.locator('#GroupGroupNum');
  await expect(groupNum).toHaveValue('1');
  await expect(groupNum).toHaveAttribute('readonly', /.*/);

  await page.getByRole('button', { name: 'Add Group' }).click();
  await expect(errorMessage(page)).toHaveText('Please insert group name');
});

test('an admin creates a group with the students that were assigned', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await page.goto(`/groups/add/${COURSE_ID}`);
  // The name is trimmed when it is saved.
  await page.locator('#GroupGroupName').fill(' Amazing Group ');
  await assignToGroup(page, STUDENTS);
  await expect(page.locator('#selected_groups option')).toHaveCount(STUDENTS.length);

  await page.getByRole('button', { name: 'Add Group' }).click();
  await expect(goodMessage(page)).toHaveText('The group was added successfully.');

  await openGroup(page, 'Amazing Group');
  await expect(page.getByRole('cell', { name: 'Amazing Group', exact: true })).toBeVisible();
  for (const student of STUDENTS) {
    await expect(page.getByRole('cell', { name: student.name, exact: true })).toBeVisible();
  }
  await expect(page.getByRole('link', { name: 'Back to Group Listing' })).toHaveAttribute(
    'href',
    `/groups/index/${COURSE_ID}`
  );

  // A second group with the same name is refused.
  await page.goto(`/groups/add/${COURSE_ID}`);
  await page.locator('#GroupGroupName').fill(' Amazing Group ');
  await assignToGroup(page, [SEED_STUDENTS.geoff]);
  await page.getByRole('button', { name: 'Add Group' }).click();
  await expect(errorMessage(page)).toHaveText('A group with the name already exists.');
});

test('an admin renames a group and changes its members', async ({ page }) => {
  await login(page, USERS.superAdmin);
  const groupId = await createGroup(page, 'Group To Edit', STUDENTS);

  await page.getByRole('link', { name: 'Edit this Group' }).click();
  await expect(page.locator('#GroupGroupName')).toHaveValue('Group To Edit');

  // Saving without a change must not report the name as a duplicate of itself.
  await page.getByRole('button', { name: 'Edit Group' }).click();
  await expect(goodMessage(page)).toHaveText('The group was updated successfully.');

  await page.goto(`/groups/edit/${groupId}`);
  await page.locator('#GroupGroupName').fill('Group To Edit Two');
  await removeFromGroup(page, [SEED_STUDENTS.denny, SEED_STUDENTS.trevor]);
  await assignToGroup(page, [SEED_STUDENTS.michael]);
  await expect(page.locator('#selected_groups option')).toHaveCount(3);
  await page.getByRole('button', { name: 'Edit Group' }).click();
  await expect(goodMessage(page)).toHaveText('The group was updated successfully.');

  await page.goto(`/groups/view/${groupId}`);
  await expect(page.getByRole('cell', { name: 'Group To Edit Two', exact: true })).toBeVisible();
  await expect(page.getByRole('cell', { name: 'Geoff Student', exact: true })).toBeVisible();
  await expect(page.getByRole('cell', { name: 'Van Hong Student', exact: true })).toBeVisible();
  await expect(page.getByRole('cell', { name: 'Michael Student', exact: true })).toBeVisible();
  await expect(page.getByRole('cell', { name: 'Denny Student', exact: true })).toHaveCount(0);
  await expect(page.getByRole('cell', { name: 'Trevor Student', exact: true })).toHaveCount(0);
});

test('an admin deletes a group and only that group leaves the course group list', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);
  // A second group stays, so a list that shows no group at all is a failure.
  const doomedId = await createGroup(page, 'Group To Delete', STUDENTS.slice(0, 2));
  await createGroup(page, 'Group To Keep', STUDENTS.slice(2));

  await page.goto(`/groups/delete/${doomedId}`);
  await expect(goodMessage(page)).toHaveText('The group was deleted successfully.');

  await openGroupList(page, COURSE_ID);
  await expect(page.getByRole('link', { name: /^Group To Keep \(/ })).toBeVisible();
  await expect(page.getByRole('link', { name: /^Group To Delete \(/ })).toHaveCount(0);
});
