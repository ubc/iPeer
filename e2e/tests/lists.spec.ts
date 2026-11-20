import { expect, test, type Page } from '../support/test';
import { login } from '../support/helpers';
import { waitForAjaxList } from '../support/ajax-list';
import { insertEvent, openDates } from '../support/events';
import { COURSES, ROLES, USERS } from '../support/seed';
import { NOW, insertRow, userId } from '../support/insert';

// The lists of the app (app/webroot/js/ajaxList.js and
// app/controllers/components/ajax_list.php): sorting, searching, filters and
// pages. The first tests use the controls of the list. The others send a list
// state that the controls do not make, as a changed request would, and check
// that the server keeps to the columns, filters and limits of the list.

// The texts of one column of the list, by its header, in the order shown.
async function columnTexts(page: Page, header: string): Promise<string[]> {
  await waitForAjaxList(page);
  return page
    .locator('#ajaxListDiv table')
    .filter({ has: page.getByRole('columnheader') })
    .evaluate((table, name) => {
      const rows = [...table.querySelectorAll('tr')];
      const headers = [...rows[0].querySelectorAll('th')].map((th) =>
        (th.textContent ?? '').replace(/[▲▼]/g, '').trim()
      );
      const index = headers.indexOf(name);
      if (index < 0) {
        throw new Error(`no column ${name} in ${headers.join(', ')}`);
      }
      return rows
        .slice(1)
        .filter((row) => row.children.length === headers.length)
        .map((row) => (row.children[index].textContent ?? '').trim());
    }, header);
}

function columnHeader(page: Page, header: string) {
  const escaped = header.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  return page.getByRole('columnheader', { name: new RegExp(`^${escaped}( [▲▼])?$`) });
}

// Waits for the list to load its data again after an action.
async function reloadsList(page: Page, controller: string, action: () => Promise<unknown>) {
  const response = page.waitForResponse(
    (r) => r.url().endsWith(`/${controller}/ajaxList`) && r.request().method() === 'POST'
  );
  await action();
  await response;
  // The list shows a busy image until it has drawn the new rows.
  await expect(page.locator('img[src$="big-rotation.gif"]')).toHaveCount(0);
  await waitForAjaxList(page);
}

// A list state as the page sends it. An empty sort or search column means
// the default of the list.
function listState(changes: Record<string, unknown> = {}): Record<string, unknown> {
  return {
    sortBy: '',
    sortAsc: true,
    pageSize: 20,
    pageShown: 1,
    searchBy: '',
    searchValue: '',
    mapFilterSelections: {},
    joinFilterSelections: {},
    ...changes,
  };
}

type ListReply = { url: string; json: boolean; body: unknown };

// Sends a list state to the list of the open page, as the page script does,
// and returns the reply. The state can be any JSON text.
async function sendListState(page: Page, controller: string, state: unknown): Promise<ListReply> {
  return page.evaluate(
    async ({ controller, json }) => {
      const body = new URLSearchParams({ json, fullPage: 'false' });
      const response = await fetch(`/${controller}/ajaxList`, { method: 'POST', body });
      const text = await response.text();
      const isJson = (response.headers.get('content-type') ?? '').includes('application/json');
      return { url: response.url, json: isJson, body: isJson ? JSON.parse(text) : text };
    },
    { controller, json: typeof state === 'string' ? state : JSON.stringify(state) }
  );
}

type ListData = {
  entries: Record<string, Record<string, unknown>>[];
  count: number;
  state: Record<string, unknown>;
};

function listData(reply: ListReply): ListData {
  expect(reply.json, `a list reply from ${reply.url}`).toBe(true);
  return reply.body as ListData;
}

// ---------------------------------------------------------------------------
// The controls of the list.

test('a list sorts by a column, then in the other direction', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/courses');

  // The list sorts by Course at first, so Title shows a change of column.
  await waitForAjaxList(page);
  await expect(columnHeader(page, 'Title')).toHaveAttribute('aria-sort', 'none');

  await reloadsList(page, 'courses', () => columnHeader(page, 'Title').click());
  await expect(columnHeader(page, 'Title')).toHaveAttribute('aria-sort', 'ascending');
  const ascending = await columnTexts(page, 'Title');
  expect(ascending.length).toBeGreaterThan(1);
  // The database sorts without regard to case.
  const byText = (a: string, b: string) => a.localeCompare(b, undefined, { sensitivity: 'base' });
  expect(ascending).toEqual([...ascending].sort(byText));

  await reloadsList(page, 'courses', () => columnHeader(page, 'Title').click());
  await expect(columnHeader(page, 'Title')).toHaveAttribute('aria-sort', 'descending');
  expect(await columnTexts(page, 'Title')).toEqual([...ascending].reverse());
});

test('a list searches the column that the user picks', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/courses');
  await waitForAjaxList(page);

  await page.getByLabel('Search column').selectOption({ label: 'Title' });
  await page.getByLabel('Search text').pressSequentially('Mechanical');
  await reloadsList(page, 'courses', () =>
    page.getByRole('button', { name: 'Search', exact: true }).click()
  );
  expect(await columnTexts(page, 'Course')).toEqual([COURSES.mech328.name]);
});

test('a map filter shows the rows with its value', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/courses');
  const all = await columnTexts(page, 'Course');

  await reloadsList(page, 'courses', () =>
    page.getByLabel('Status', { exact: true }).selectOption({ label: 'Active' })
  );
  const active = await columnTexts(page, 'Course');
  await reloadsList(page, 'courses', () =>
    page.getByLabel('Status', { exact: true }).selectOption({ label: 'Inactive' })
  );
  const inactive = await columnTexts(page, 'Course');
  // The seed has courses of both kinds, and each course has one status.
  expect(active.length).toBeGreaterThan(0);
  expect(inactive.length).toBeGreaterThan(0);
  expect([...active, ...inactive].sort()).toEqual([...all].sort());
});

test('a join filter shows the users of one role', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/users');
  await waitForAjaxList(page);

  await reloadsList(page, 'users', () =>
    page.getByLabel('Show role', { exact: true }).selectOption({ label: 'instructor' })
  );
  const roles = await columnTexts(page, 'Role');
  expect(roles.length).toBeGreaterThan(0);
  expect(new Set(roles.map((role) => role.toLowerCase()))).toEqual(new Set(['instructor']));
});

test('the page size and the page buttons move through a long list', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/users');
  await waitForAjaxList(page);

  // The list has a page bar above and below the rows. This is the one in
  // the row with the result count.
  const pages = page.getByRole('row', { name: /^Total Results/ });

  await reloadsList(page, 'users', () => page.getByLabel('15 per page').check());
  const first = await columnTexts(page, 'Username');
  expect(first).toHaveLength(15);
  await expect(pages.getByRole('button', { name: 'Page 1', exact: true })).toHaveAttribute(
    'aria-current',
    'page'
  );

  await reloadsList(page, 'users', () =>
    pages.getByRole('button', { name: 'Next page', exact: true }).click()
  );
  await expect(pages.getByRole('button', { name: 'Page 2', exact: true })).toHaveAttribute(
    'aria-current',
    'page'
  );
  const second = await columnTexts(page, 'Username');
  expect(second.length).toBeGreaterThan(0);
  expect(second.filter((username) => first.includes(username))).toEqual([]);
});

// ---------------------------------------------------------------------------
// List states that the controls do not make.

test('the group list stays in its course, whatever filter the browser sends', async ({ page }) => {
  await login(page, USERS.instructor1);
  // The group list takes its course from the course home that was opened.
  await page.goto(`/courses/home/${COURSES.mech328.id}`);
  await page.goto(`/groups/index/${COURSES.mech328.id}`);
  await waitForAjaxList(page);

  const reply = await sendListState(
    page,
    'groups',
    listState({ mapFilterSelections: { 'Group.course_id': COURSES.apsc201.id } })
  );
  const { entries } = listData(reply);
  expect(entries.length).toBeGreaterThan(0);
  for (const entry of entries) {
    expect(String(entry.Group.course_id)).toBe(COURSES.mech328.id);
  }
});

test('the user list shows only the roles that the user may see', async ({ page }) => {
  await login(page, USERS.admin);
  await page.goto('/users');
  await waitForAjaxList(page);

  // The role filter does not list the superadmin role for an admin.
  const reply = await sendListState(
    page,
    'users',
    listState({ joinFilterSelections: { 'Role.id': ROLES.superadmin }, pageSize: 270 })
  );
  const usernames = listData(reply).entries.map((entry) => String(entry.User.username));
  expect(usernames.length).toBeGreaterThan(0);
  expect(usernames).not.toContain(USERS.superAdmin);
});

test('the user list leaves out a user who also has a role that the user may not see', async ({
  page,
  testDatabase,
}) => {
  // The superadmin also gets the student role, which an admin may see.
  await insertRow(testDatabase, 'roles_users', {
    role_id: ROLES.student,
    user_id: await userId(testDatabase, USERS.superAdmin),
    created: NOW,
    modified: NOW,
  });
  await login(page, USERS.admin);
  await page.goto('/users');
  await waitForAjaxList(page);

  const reply = await sendListState(
    page,
    'users',
    listState({ joinFilterSelections: { 'Role.id': ROLES.student }, pageSize: 270 })
  );
  const usernames = listData(reply).entries.map((entry) => String(entry.User.username));
  expect(usernames).toContain(USERS.student);
  expect(usernames).not.toContain(USERS.superAdmin);
});

test('a user without the right to the user list gets no users from it', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto('/home');

  const reply = await sendListState(page, 'users', listState());
  expect(reply.json).toBe(false);
  expect(new URL(reply.url).pathname).toBe('/home');
});

test('the evaluation list lists only an event that the user can open', async ({
  page,
  testDatabase,
}) => {
  const other = await insertEvent(testDatabase, COURSES.apsc201.id, {
    title: 'Other Course Event',
    description: '',
    type: 'SIMPLE',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disabled',
    comments: 'Disabled',
    resultMode: 'Detailed',
    dates: openDates(),
    allGroups: true,
    penalties: [],
  });

  await login(page, USERS.instructor1);
  // The page of the event refuses the user, and its list must too.
  await page.goto(`/evaluations/view/${other}`);
  expect((await sendListState(page, 'evaluations', listState())).json).toBe(false);
});

test('the evaluation list stops when the user can no longer open its event', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.instructor1);
  // The page keeps its event for the list.
  await page.goto('/evaluations/view/1');
  await waitForAjaxList(page);
  expect((await sendListState(page, 'evaluations', listState())).json).toBe(true);

  await testDatabase.connection.query(
    'DELETE user_courses FROM user_courses JOIN users ON users.id = user_courses.user_id WHERE users.username = ? AND user_courses.course_id = ?',
    [USERS.instructor1, COURSES.mech328.id]
  );
  expect((await sendListState(page, 'evaluations', listState())).json).toBe(false);
});

test('a sort or search column that the list does not have gives its default', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/courses');
  await waitForAjaxList(page);

  const reply = await sendListState(
    page,
    'courses',
    listState({ sortBy: 'Course.no_such_column', searchBy: 'Course.no_such_column' })
  );
  const { state, entries } = listData(reply);
  expect(state.sortBy).toBe('Course.course');
  expect(state.searchBy).toBe('Course.course');
  expect(entries.length).toBeGreaterThan(0);
});

test('a list page has at most 270 rows, and a state that is not an object gets the defaults', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);
  await page.goto('/users');
  await waitForAjaxList(page);

  const large = listData(await sendListState(page, 'users', listState({ pageSize: 100000 })));
  expect(large.state.pageSize).toBe(270);

  const other = listData(await sendListState(page, 'users', '[1]'));
  expect(other.state.pageSize).toBe(20);
  expect(other.entries.length).toBeGreaterThan(0);
});

test('the search pages have no pages for their helper methods', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto('/searchs/formatSearchEvaluation');
  // web-test runs with debug on, so Cake reports the missing method in place
  // of a 404 page.
  await expect(page.getByRole('heading', { name: /^Missing Method in Searchs/ })).toBeVisible();
});
