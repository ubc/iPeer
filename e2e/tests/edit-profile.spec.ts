import type { Page } from '@playwright/test';
import type { RowDataPacket } from 'mysql2/promise';
import { expect, test } from '../support/test';
import type { TestDatabase } from '../support/db';
import { flashMessage, goodMessage, login } from '../support/helpers';
import { PASSWORD, USERS } from '../support/seed';
import { PROFILE } from '../support/messages';

// Seed user 5 is the student redshirt0001 (Ed Student), with one role.
const STUDENT_ID = 5;

async function storedStudent({ connection }: TestDatabase): Promise<RowDataPacket> {
  const [rows] = await connection.query<RowDataPacket[]>(
    `SELECT u.username, u.first_name, u.record_status, u.updater_id,
        (SELECT GROUP_CONCAT(r.role_id) FROM roles_users r WHERE r.user_id = u.id) AS roles
      FROM users u WHERE u.id = ?`,
    [STUDENT_ID]
  );
  return rows[0];
}

test('a student changes their name and the header shows the new name', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.student);
  await page.goto('/users/editProfile');

  await page.getByLabel('First Name').fill('Edna');
  await page.getByRole('button', { name: 'Save' }).click();

  await expect(goodMessage(page)).toHaveText(PROFILE.updated);
  await expect(page.getByRole('link', { name: 'Edna Student' })).toBeVisible();
  expect((await storedStudent(testDatabase)).first_name).toBe('Edna');
});

test('a profile save ignores the roles and other fields that a student posts', async ({
  page,
  baseURL,
  testDatabase,
}) => {
  await login(page, USERS.student);

  const response = await page.request.post('/users/editProfile', {
    form: {
      'data[User][first_name]': 'Edna',
      'data[User][username]': 'root2',
      'data[User][record_status]': 'I',
      'data[Role][Role]': '1',
    },
    // The app refuses a POST without a same-origin referer.
    headers: { referer: new URL('/users/editProfile', baseURL).href },
  });

  expect(response.ok()).toBe(true);
  expect(await storedStudent(testDatabase)).toMatchObject({
    username: USERS.student,
    first_name: 'Edna',
    record_status: 'A',
    roles: '5',
    // The saved row names the user who changed it.
    updater_id: STUDENT_ID,
  });
});

async function changePassword(page: Page, oldPassword: string, newPassword: string) {
  await page.goto('/users/editProfile');
  await page.getByLabel('Old Password').fill(oldPassword);
  await page.getByLabel('New Password', { exact: true }).fill(newPassword);
  await page.getByLabel('Confirm New Password').fill(newPassword);
  await page.getByRole('button', { name: 'Save' }).click();
}

async function sessionId(page: Page): Promise<string | undefined> {
  const cookies = await page.context().cookies();
  return cookies.find((cookie) => cookie.name === 'IPEER')?.value;
}

test('a user changes their password and logs in with the new one', async ({ page }) => {
  await login(page, USERS.instructor1);
  const before = await sessionId(page);

  await changePassword(page, PASSWORD, 'newsecret1');

  await expect(goodMessage(page)).toHaveText(PROFILE.updated);
  // The session gets a new id and the user stays logged in.
  expect(before).toBeTruthy();
  expect(await sessionId(page)).not.toBe(before);
  await page.goto('/users/editProfile');
  await expect(page.getByRole('link', { name: 'Instructor 1' })).toBeVisible();

  await login(page, USERS.instructor1, 'newsecret1');
});

test('a wrong old password keeps the password', async ({ page }) => {
  await login(page, USERS.instructor1);

  await changePassword(page, 'wrongpassword', 'newsecret1');

  await expect(flashMessage(page)).toHaveText(PROFILE.oldPasswordIncorrect);
  await login(page, USERS.instructor1);
});
