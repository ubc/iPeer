import type { RowDataPacket } from 'mysql2/promise';
import { expect, test } from '../support/test';
import type { TestDatabase } from '../support/db';
import { flashMessage, login } from '../support/helpers';
import { USERS } from '../support/seed';
import { CSRF } from '../support/messages';

// The app refuses a request that changes data when it does not come from one
// of its own pages (the Referer check, IPEER_ENFORCE_REFERRER). Seed event 2
// is a rubric evaluation. Its group event 3 has two evaluations, and the seed
// releases neither their grades nor their comments.
const EVENT_ID = 2;
const GROUP_EVENT_ID = 3;
const RELEASE_ALL_GRADES = `/evaluations/changeAllGradeRelease/${EVENT_ID};1`;

async function rubricRelease(
  { connection }: TestDatabase,
  field: 'grade_release' | 'comment_release'
): Promise<number[]> {
  const [rows] = await connection.query<RowDataPacket[]>(
    'SELECT ?? AS value FROM evaluation_rubrics WHERE grp_event_id = ? ORDER BY id',
    [field, GROUP_EVENT_ID]
  );
  return rows.map((row) => Number(row.value));
}

test('a release link opened from another site is refused and changes nothing', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.superAdmin);

  await page.goto(RELEASE_ALL_GRADES, { referer: 'https://other.example/page' });

  await expect(page).toHaveURL(/\/home$/);
  await expect(flashMessage(page)).toHaveText(CSRF.outsideApp);
  expect(await rubricRelease(testDatabase, 'grade_release')).toEqual([0, 0]);
});

test('the same release link opened from the app releases the grades', async ({
  page,
  baseURL,
  testDatabase,
}) => {
  await login(page, USERS.superAdmin);

  await page.goto(RELEASE_ALL_GRADES, {
    referer: new URL(`/evaluations/view/${EVENT_ID}`, baseURL).href,
  });

  expect(await rubricRelease(testDatabase, 'grade_release')).toEqual([1, 1]);
});

test('a form post without a referer is refused and changes nothing', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.superAdmin);

  const response = await page.request.post('/evaluations/markCommentRelease', {
    form: { group_event_id: String(GROUP_EVENT_ID), submit: 'Release All Comments' },
    maxRedirects: 0,
  });

  expect(response.status()).toBe(302);
  expect(response.headers()['location']).toMatch(/\/home$/);
  expect(response.headers()['x-ipeer-csrf-blocked']).toBeDefined();
  expect(await rubricRelease(testDatabase, 'comment_release')).toEqual([0, 0]);
});

test('a HEAD request to a release link gets 405 and changes nothing', async ({
  page,
  baseURL,
  testDatabase,
}) => {
  await login(page, USERS.superAdmin);

  const response = await page.request.fetch(RELEASE_ALL_GRADES, {
    method: 'HEAD',
    headers: { referer: new URL(`/evaluations/view/${EVENT_ID}`, baseURL).href },
    maxRedirects: 0,
  });

  expect(response.status()).toBe(405);
  expect(response.headers()['allow']).toBe('GET');
  expect(await rubricRelease(testDatabase, 'grade_release')).toEqual([0, 0]);
});
