import { expect, test, type Page } from '@playwright/test';
import { flashMessage, goodMessage, login } from '../support/helpers';
import { COURSES, FACULTIES, STUDENTS, USERS } from '../support/seed';
import {
  createTestUser,
  deleteCreatedAccount,
  expectInClassList,
  expectNoMergeCandidates,
  expectNotInClassList,
  mergeButton,
  mergeCandidates,
  nextTestStudent,
  openMergeUsers,
  pickMergeAccount,
  searchMergeAccount,
  setUserRoles,
  submitMerge,
  type CreatedAccount,
  type NewUser,
} from '../support/users';

// Ported from app/tests/cases/system/merge_users.test.php.
// A merge deletes the secondary account for good, so these specs merge
// accounts they made themselves. The cases that expect a refusal use a seeded
// account only as the primary, which a merge never removes.

let createdUsers = new Set<CreatedAccount>();

test.beforeEach(async ({ page }) => {
  createdUsers = new Set();
  await login(page, USERS.superAdmin);
});

test.afterEach(async ({ page }) => {
  // A test may end signed in as another account, and only an admin may remove
  // what it made.
  await login(page, USERS.superAdmin);
  for (const account of createdUsers) {
    await deleteCreatedAccount(page, account);
  }
});

async function createTwins(page: Page): Promise<{ kept: NewUser; gone: NewUser }> {
  const kept = nextTestStudent('Kept', 'Twin', [COURSES.apsc201.id]);
  const gone = nextTestStudent('Gone', 'Twin', [COURSES.mech328.id]);
  for (const user of [kept, gone]) {
    await createTestUser(page, user, createdUsers);
  }
  return { kept, gone };
}

async function createAdmin(page: Page): Promise<{ user: NewUser; password: string }> {
  // Made as a student of a course, which is the handle the class list gives on
  // a brand new account, and raised to admin afterwards. The merge page is only
  // open to an admin, and a merge needs both accounts to hold the same role.
  const user = nextTestStudent('Spare', 'Admin', [COURSES.mech328.id]);
  const { id, password } = await createTestUser(page, user, createdUsers);
  await setUserRoles(page, id, { primary: 'admin', faculty: FACULTIES[0] });
  return { user, password };
}

async function mergeByUsername(page: Page, primary: string, secondary: string): Promise<void> {
  await openMergeUsers(page);
  await searchMergeAccount(page, 'Primary', 'Username', primary);
  await searchMergeAccount(page, 'Secondary', 'Username', secondary);
  await pickMergeAccount(page, 'Primary', primary);
  await pickMergeAccount(page, 'Secondary', secondary);
  await submitMerge(page);
}

test('merging two accounts of the same role keeps one and moves the enrolments onto it', async ({
  page,
}) => {
  const { kept, gone } = await createTwins(page);

  await mergeByUsername(page, kept.username, gone.username);
  await expect(goodMessage(page)).toHaveText('The two accounts have successfully merged.');

  await expectInClassList(page, COURSES.apsc201.id, kept);
  await expectInClassList(page, COURSES.mech328.id, kept);
  await expectNotInClassList(page, COURSES.mech328.id, gone);

  // The account that lost the merge is gone, not only unenrolled.
  await openMergeUsers(page);
  await searchMergeAccount(page, 'Primary', 'Username', gone.username);
  await expectNoMergeCandidates(page, 'Primary');
});

test('the merge form refuses to submit until both accounts are picked', async ({ page }) => {
  const { kept, gone } = await createTwins(page);

  await openMergeUsers(page);
  await expect(mergeCandidates(page, 'Primary')).toBeDisabled();
  await expect(mergeCandidates(page, 'Secondary')).toBeDisabled();

  await searchMergeAccount(page, 'Primary', 'Username', kept.username);
  await pickMergeAccount(page, 'Primary', kept.username);
  await expect(mergeButton(page)).toBeDisabled();

  await searchMergeAccount(page, 'Secondary', 'Username', gone.username);
  await pickMergeAccount(page, 'Secondary', gone.username);
  await expect(mergeButton(page)).toBeEnabled();
});

test('merging two accounts with different roles is refused', async ({ page }) => {
  const { gone } = await createTwins(page);

  await mergeByUsername(page, USERS.instructor1, gone.username);
  await expect(flashMessage(page)).toHaveText('Error: The users do not have the same role.');

  await expectInClassList(page, COURSES.mech328.id, gone);
});

test('a merge search that matches nothing reports that no users were found', async ({ page }) => {
  await openMergeUsers(page);

  await searchMergeAccount(page, 'Primary', 'Username', 'redshirt9999');
  await expectNoMergeCandidates(page, 'Primary');
});

test('merging the account you are signed in as is refused', async ({ page }) => {
  await login(page, USERS.admin);

  // Both seeded admin accounts, so the role check cannot be what refuses it.
  await mergeByUsername(page, USERS.adminAlt, USERS.admin);
  await expect(flashMessage(page)).toHaveText(
    'Error: The secondary account is the currently logged in user.'
  );

  await openMergeUsers(page);
  await searchMergeAccount(page, 'Secondary', 'Username', USERS.admin);
  await pickMergeAccount(page, 'Secondary', USERS.admin);
});

test('picking the same account twice is refused', async ({ page }) => {
  const { kept } = await createTwins(page);

  await mergeByUsername(page, kept.username, kept.username);
  await expect(flashMessage(page)).toHaveText(
    'Error: No merger needed. The primary and secondary accounts are the same.'
  );

  await expectInClassList(page, COURSES.apsc201.id, kept);
});

test('the merge page offers only the roles the signed-in user may merge', async ({ page }) => {
  await login(page, USERS.admin);
  await openMergeUsers(page);

  // An admin may not touch a super admin, so the one account of that role is
  // not a candidate, while an account of a role they may merge is.
  await searchMergeAccount(page, 'Primary', 'Username', USERS.superAdmin);
  await expectNoMergeCandidates(page, 'Primary');

  await searchMergeAccount(page, 'Primary', 'Username', STUDENTS.ed.username);
  await pickMergeAccount(page, 'Primary', STUDENTS.ed.username);
});
