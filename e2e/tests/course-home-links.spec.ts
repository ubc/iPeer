import { expect, test, type Page } from '@playwright/test';
import { USERS, login, pageTitle } from '../support/helpers';

// Ported from the link tests of app/tests/cases/system/add_course.test.php.

// MECH 328 is a seeded course with students, groups and events.
const COURSE_ID = '1';
const COURSE = 'MECH 328 - Mechanical Engineering Design Project';

/**
 * Follows each link of the course home page and checks the page it opens.
 * iPeer answers an error page with HTTP 200, so the title of the page is the
 * proof that the link reached the feature it names.
 */
async function expectLinks(page: Page, links: [string, string, string][]): Promise<void> {
  for (const [name, path, title] of links) {
    await page.goto(`/courses/home/${COURSE_ID}`);
    await page.getByRole('link', { name, exact: true }).click();
    await expect(page).toHaveURL(new RegExp(`${path}$`));
    await expect(pageTitle(page)).toHaveText(new RegExp(title));
  }
}

test('the course home page shows the course web page, instructors and tutors', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await page.goto(`/courses/home/${COURSE_ID}`);

  await expect(page.locator('#CourseHome a[target="_blank"]')).toHaveAttribute(
    'href',
    /^http:\/\/www\.mech\.ubc\.ca/
  );

  for (const [person, userId] of [
    ['Instructor 1', '2'],
    ['Tutor 1', '35'],
    ['Tutor 2', '36'],
  ]) {
    await page.goto(`/courses/home/${COURSE_ID}`);
    await page.locator('#CourseHome').getByRole('link', { name: person, exact: true }).click();
    await expect(page).toHaveURL(new RegExp(`/users/view/${userId}$`));
    await expect(pageTitle(page)).toHaveText(/View User/);
  }
});

test('the course home page links to the student pages of that course', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await expectLinks(page, [
    ['Add Student', `/users/add/${COURSE_ID}`, `${COURSE} > Add User`],
    ['List Students', `/users/goToClassList/${COURSE_ID}`, `${COURSE} > Students`],
    ['Email to All Students', `/emailer/write/C/${COURSE_ID}`, `${COURSE} > Write Email`],
    ['Import Students from CSV', `/users/import/${COURSE_ID}`, `${COURSE} > Import Students`],
  ]);
});

test('the course home page links to the group pages of that course', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await expectLinks(page, [
    ['Add Group', `/groups/add/${COURSE_ID}`, `${COURSE} > Add Group`],
    ['List Groups', `/groups/index/${COURSE_ID}`, `${COURSE} > Groups`],
    ['Import Groups from CSV', `/groups/import/${COURSE_ID}`, `${COURSE} > Import Groups`],
    ['Export Groups to CSV', `/groups/export/${COURSE_ID}`, `${COURSE} > Export Groups`],
  ]);
});

test('the course home page links to the evaluation event pages of that course', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await expectLinks(page, [
    ['Add Event', `/events/add/${COURSE_ID}`, `${COURSE} > Add Event`],
    ['List Evaluation Events', `/events/index/${COURSE_ID}`, `${COURSE} > Events`],
    [
      'Export Evaluation Results',
      `/evaluations/export/course/${COURSE_ID}`,
      `${COURSE} > Export Evaluation Results`,
    ],
    ['Move Students', '/courses/move', 'Move Students'],
    ['Move Group of Students', '/courses/import', 'Courses'],
  ]);
});

test('the course home page links to the team maker pages of that course', async ({ page }) => {
  await login(page, USERS.superAdmin);
  await expectLinks(page, [
    ['Create Groups (Auto)', `/surveygroups/makegroups/${COURSE_ID}`, `${COURSE} > Create Group Set`],
    ['List Survey Group Sets', `/surveygroups/index/${COURSE_ID}`, `${COURSE} > Survey Groups`],
    [
      'Export Survey Group Sets',
      `/surveygroups/export/${COURSE_ID}`,
      `${COURSE} > Export Survey Groups`,
    ],
  ]);
});
