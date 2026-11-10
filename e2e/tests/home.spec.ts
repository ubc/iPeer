import { expect, test } from '../support/test';
import { expectTabs, login, pageTitle, sectionHeadings } from '../support/helpers';
import { COURSES, USERS, fullName } from '../support/seed';

// Ported from app/tests/cases/system/home.test.php.
// Surveys are out of scope, so these tests assert only the peer-evaluation
// section of the student and tutor home page.

test('a super admin sees every course and the full tab bar', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await expect(pageTitle(page)).toHaveText('Home');
  await expect(page.getByRole('heading', { level: 2, name: 'My Courses' })).toBeVisible();
  await expect(page.getByRole('heading', { level: 2, name: 'Inactive Courses' })).toBeVisible();

  // cpsc101 is the seeded inactive course, and a super admin sees it too.
  for (const course of Object.values(COURSES).map(fullName)) {
    await expect(page.getByRole('heading', { level: 3, name: course })).toBeVisible();
  }

  await expectTabs(page, ['Home', 'Courses', 'Users', 'Evaluation', 'Admin']);
});

test('an admin sees only their own courses and gets no Admin tab', async ({ page }) => {
  await login(page, USERS.admin);

  await expect(page.getByRole('heading', { level: 2, name: 'My Courses' })).toBeVisible();
  await expect(page.getByRole('heading', { level: 2, name: 'Inactive Courses' })).toBeVisible();

  await expect(
    page.getByRole('heading', { level: 3, name: 'CPSC 404 - Advanced Software Engineering' })
  ).toBeVisible();
  await expect(
    page.getByRole('heading', { level: 3, name: 'CPSC 101 - Connecting with Computer Science' })
  ).toBeVisible();
  await expect(
    page.getByRole('heading', {
      level: 3,
      name: 'MECH 328 - Mechanical Engineering Design Project',
    })
  ).toHaveCount(0);
  await expect(
    page.getByRole('heading', { level: 3, name: 'APSC 201 - Technical Communication' })
  ).toHaveCount(0);

  await expectTabs(page, ['Home', 'Courses', 'Users', 'Evaluation']);
});

test('an instructor sees only their own course and gets no Users or Admin tab', async ({
  page,
}) => {
  await login(page, USERS.instructor2);

  await expect(page.getByRole('heading', { level: 2, name: 'My Courses' })).toBeVisible();
  await expect(page.getByRole('heading', { level: 2, name: 'Inactive Courses' })).toHaveCount(0);

  await expect(
    page.getByRole('heading', { level: 3, name: 'APSC 201 - Technical Communication' })
  ).toBeVisible();
  await expect(
    page.getByRole('heading', {
      level: 3,
      name: 'MECH 328 - Mechanical Engineering Design Project',
    })
  ).toHaveCount(0);

  await expectTabs(page, ['Home', 'Courses', 'Evaluation']);
});

test('a tutor lands on the evaluation home and gets no course or admin tabs', async ({ page }) => {
  await login(page, USERS.tutor);

  await expect(page.getByRole('heading', { level: 2, name: 'Peer Evaluations' })).toBeVisible();
  await expect(sectionHeadings(page, 'Peer Evaluations')).toHaveText(['Due', 'Submitted']);

  await expectTabs(page, ['Home']);
});

test('a student lands on the evaluation home and gets no course or admin tabs', async ({
  page,
}) => {
  await login(page, USERS.student);

  await expect(page.getByRole('heading', { level: 2, name: 'Peer Evaluations' })).toBeVisible();
  await expect(sectionHeadings(page, 'Peer Evaluations')).toHaveText([
    'Due',
    'Submitted',
    'Expired With No Submission',
  ]);

  await expectTabs(page, ['Home']);
});
