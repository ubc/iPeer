import { expect, test, type Locator, type Page } from '../support/test';
import { login, nav, pageTitle } from '../support/helpers';
import { COURSES, USERS, fullName } from '../support/seed';

// The other specs open the page they test by its URL. This spec checks, once,
// that the tab bar and the pages under it link to those URLs.
// course-home-links.spec.ts does the same for the links of a course home page.

type Link = {
  // The page that shows the link, and the part of it that holds the link.
  from: string;
  area: (page: Page) => Locator;
  name: string;
  to: string;
  // iPeer answers an error page with HTTP 200, so the title is the proof that
  // the link reached the page it names.
  title: string;
};

const tabBar = nav;
// The links below the tab bar have names that the tab bar does not use.
const content = (page: Page) => page.locator('body');

async function expectLinks(page: Page, links: readonly Link[]): Promise<void> {
  for (const link of links) {
    await test.step(
      `The link ${link.name} on ${link.from}`,
      async () => {
        await page.goto(link.from);
        await link.area(page).getByRole('link', { name: link.name, exact: true }).click();
        await expect(page).toHaveURL(new RegExp(`${link.to}$`));
        await expect(pageTitle(page)).toContainText(link.title);
      },
      { box: true }
    );
  }
}

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

test('the tab bar leads to the main page of each tab', async ({ page }) => {
  await expectLinks(page, [
    { from: '/', area: tabBar, name: 'Home', to: '/home', title: 'Home' },
    { from: '/', area: tabBar, name: 'Courses', to: '/courses', title: 'Courses' },
    { from: '/', area: tabBar, name: 'Users', to: '/users', title: 'Users' },
    { from: '/', area: tabBar, name: 'Evaluation', to: '/evaltools', title: 'Evaluation Tools' },
    { from: '/', area: tabBar, name: 'Admin', to: '/pages/admin', title: 'Admin' },
  ]);
});

test('the Courses tab links to Add Course and to the home page of each course', async ({
  page,
}) => {
  await expectLinks(page, [
    {
      from: '/courses',
      area: content,
      name: 'Add Course',
      to: '/courses/add',
      title: 'Add Course',
    },
  ]);

  const course = COURSES.apsc201;
  await page.goto('/courses');
  const row = page
    .getByRole('row')
    .filter({ has: page.getByRole('cell', { name: course.name, exact: true }) });
  await row.getByRole('link', { name: course.title, exact: true }).click();
  await expect(page).toHaveURL(new RegExp(`/courses/home/${course.id}$`));
  await expect(pageTitle(page)).toContainText(fullName(course));
});

test('the Evaluation tab links to the Add page of each template type', async ({ page }) => {
  await expectLinks(page, [
    {
      from: '/evaltools',
      area: content,
      name: 'Add Simple Evaluation',
      to: '/simpleevaluations/add',
      title: 'Simple Evaluations > Add',
    },
    {
      from: '/evaltools',
      area: content,
      name: 'Add Rubric',
      to: '/rubrics/add',
      title: 'Rubrics',
    },
    {
      from: '/evaltools',
      area: content,
      name: 'Add Mixed Evaluation',
      to: '/mixevals/add',
      title: 'Mixed Evaluations > Add',
    },
  ]);
});

test('the Admin tab links to the permissions editor', async ({ page }) => {
  await expectLinks(page, [
    {
      from: '/pages/admin',
      area: content,
      name: 'Permissions Editor',
      to: '/accesses/view',
      title: 'Permissions Editor > superadmin',
    },
  ]);
});
