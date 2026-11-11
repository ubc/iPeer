import { expect, test, type Page, type Route } from '../support/test';
import { flashMessage, goodMessage, login } from '../support/helpers';
import {
  expectNoMergeCandidates,
  mergeButton,
  mergeCandidates,
  mergeDetail,
  openMergeUsers,
  pickMergeAccount,
  searchMergeAccount,
  startMergeSearch,
  submitMerge,
} from '../support/merge';
import { COURSES, STUDENTS, USERS } from '../support/seed';
import { expectInClassList, expectNotInClassList } from '../support/users';

// Ported from app/tests/cases/system/merge_users.test.php.

// Two students who share no course, so a merge moves an enrolment.
const KEPT = STUDENTS.johnny; // APSC 201
const GONE = STUDENTS.ed; // MECH 328

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

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
  await mergeByUsername(page, KEPT.username, GONE.username);
  await expect(goodMessage(page)).toHaveText('The two accounts have successfully merged.');

  await expectInClassList(page, COURSES.apsc201.id, KEPT);
  await expectInClassList(page, COURSES.mech328.id, KEPT);
  await expectNotInClassList(page, COURSES.mech328.id, GONE);

  // The account that lost the merge is gone, not only unenrolled.
  await openMergeUsers(page);
  await searchMergeAccount(page, 'Primary', 'Username', GONE.username);
  await expectNoMergeCandidates(page, 'Primary');
});

test('the merge form refuses to submit until both accounts are picked', async ({ page }) => {
  await openMergeUsers(page);
  await expect(mergeCandidates(page, 'Primary')).toBeDisabled();
  await expect(mergeCandidates(page, 'Secondary')).toBeDisabled();

  await searchMergeAccount(page, 'Primary', 'Username', KEPT.username);
  await pickMergeAccount(page, 'Primary', KEPT.username);
  await expect(mergeButton(page)).toBeDisabled();

  await searchMergeAccount(page, 'Secondary', 'Username', GONE.username);
  await pickMergeAccount(page, 'Secondary', GONE.username);
  await expect(mergeButton(page)).toBeEnabled();
});

test('picking an account shows its details, and a new search clears them', async ({ page }) => {
  await openMergeUsers(page);
  await searchMergeAccount(page, 'Primary', 'Username', KEPT.username);
  await pickMergeAccount(page, 'Primary', KEPT.username);
  await expect(mergeDetail(page, 'Primary', 'Username')).toHaveText(KEPT.username);
  await expect(mergeDetail(page, 'Primary', 'FirstName')).toHaveText('Johnny');

  await searchMergeAccount(page, 'Primary', 'Username', GONE.username);
  await expect(mergeDetail(page, 'Primary', 'Username')).toHaveText('');
  await expect(mergeDetail(page, 'Primary', 'FirstName')).toHaveText('');
});

test('merging two accounts with different roles is refused', async ({ page }) => {
  await mergeByUsername(page, USERS.instructor1, GONE.username);
  await expect(flashMessage(page)).toHaveText('Error: The users do not have the same role.');

  await expectInClassList(page, COURSES.mech328.id, GONE);
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

  // The signed-in account is still there.
  await openMergeUsers(page);
  await searchMergeAccount(page, 'Secondary', 'Username', USERS.admin);
  await pickMergeAccount(page, 'Secondary', USERS.admin);
});

test('picking the same account twice is refused', async ({ page }) => {
  await mergeByUsername(page, KEPT.username, KEPT.username);
  await expect(flashMessage(page)).toHaveText(
    'Error: No merger needed. The primary and secondary accounts are the same.'
  );

  await expectInClassList(page, COURSES.apsc201.id, KEPT);
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

// The page's AJAX calls of one kind: the account search or the details of
// one account.
function mergeRequest(action: 'account' | 'data') {
  return (url: URL) =>
    url.pathname.endsWith('/users/ajax_merge') && url.searchParams.get('action') === action;
}

// Holds back the answer to the first request of one kind and lets the later
// ones through. The returned function releases the held answer and waits
// until the page has handled it.
async function holdFirstAnswer(
  page: Page,
  action: 'account' | 'data'
): Promise<() => Promise<void>> {
  let held: Route | undefined;
  await page.route(mergeRequest(action), async (route) => {
    if (held) {
      await route.continue();
    } else {
      held = route;
    }
  });
  return async () => {
    await expect.poll(() => held !== undefined, { message: `a held ${action} request` }).toBe(true);
    await held!.continue();
    // jQuery counts a request as open until its callbacks have run.
    await page.waitForFunction(
      () => (window as { jQuery?: { active: number } }).jQuery?.active === 0
    );
  };
}

test('a failed account search says so instead of searching forever', async ({ page }) => {
  await openMergeUsers(page);
  await page.route(mergeRequest('account'), (route) => route.fulfill({ status: 500 }));

  await startMergeSearch(page, 'Primary', 'Username', KEPT.username);
  await expect(mergeCandidates(page, 'Primary').locator('option')).toHaveText([
    '-- Search failed --',
  ]);
  await expect(mergeCandidates(page, 'Primary')).toBeDisabled();
});

test('a late answer to an older search does not replace the newer one', async ({ page }) => {
  await openMergeUsers(page);
  const releaseOlder = await holdFirstAnswer(page, 'account');

  await startMergeSearch(page, 'Primary', 'Username', KEPT.username);
  await searchMergeAccount(page, 'Primary', 'Username', GONE.username);
  const newer = ['-- Pick the primary account --', GONE.username];
  await expect(mergeCandidates(page, 'Primary').locator('option')).toHaveText(newer);

  await releaseOlder();
  await expect(mergeCandidates(page, 'Primary').locator('option')).toHaveText(newer);
});

test('a late details answer does not replace the details of the account picked after it', async ({
  page,
}) => {
  await openMergeUsers(page);
  // Finds both students, so both can be picked from one list.
  await searchMergeAccount(page, 'Primary', 'Username', 'redshirt000');
  const releaseOlder = await holdFirstAnswer(page, 'data');

  await pickMergeAccount(page, 'Primary', KEPT.username);
  await pickMergeAccount(page, 'Primary', GONE.username);
  await expect(mergeDetail(page, 'Primary', 'Username')).toHaveText(GONE.username);

  await releaseOlder();
  await expect(mergeDetail(page, 'Primary', 'Username')).toHaveText(GONE.username);
});
