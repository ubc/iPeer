import { expect, test } from '../support/test';
import { login, logout, nav, pageTitle } from '../support/helpers';
import { COURSES, FULL_NAMES, STUDENTS, USERS, fullName } from '../support/seed';

// Checks that support/seed.ts still describes the seeded database. The other
// specs build on these facts, so a change to ipeer_samples_data.sql must fail
// here, with a message that names what moved, instead of failing scattered
// assertions that each look like an application bug.

test('every seed account logs in as the expected person', async ({ page }) => {
  for (const [role, username] of Object.entries(USERS)) {
    await login(page, username);
    // The navigation bar links to the profile of whoever is signed in, so it
    // proves which account the session belongs to.
    await expect(
      nav(page).getByRole('link', { name: FULL_NAMES[role as keyof typeof USERS], exact: true }),
      `${role} (${username})`
    ).toBeVisible();
  }
  await logout(page);
});

test('the seeded courses exist, and only CPSC 101 is inactive', async ({ page }) => {
  await login(page, USERS.superAdmin);

  for (const [key, course] of Object.entries(COURSES)) {
    await page.goto(`/courses/home/${course.id}`);
    await expect(pageTitle(page), `${key} id ${course.id}`).toContainText(fullName(course));
  }

  await page.goto('/home');
  const inactive = page.locator(
    "xpath=//h2[normalize-space()='Inactive Courses']/following-sibling::div[@class='course']"
  );
  await expect(inactive.filter({ hasText: COURSES.cpsc101.name })).toHaveCount(1);
  for (const active of [COURSES.mech328, COURSES.apsc201, COURSES.cpsc404]) {
    await expect(inactive.filter({ hasText: active.name }), active.name).toHaveCount(0);
  }
});

test('the seeded students are enrolled where expected, with their student numbers', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  for (const [courseKey, course] of Object.entries(COURSES)) {
    const expected = Object.entries(STUDENTS).filter(([, s]) =>
      (s.courses as readonly string[]).includes(courseKey)
    );
    if (expected.length === 0) {
      continue;
    }

    // The Add Group chooser lists the students of the course, labelled by
    // student number and full name, so one page proves both facts.
    await page.goto(`/courses/home/${course.id}`);
    await page.getByRole('link', { name: 'Add Group', exact: true }).click();
    const options = page.locator('#all_groups option');

    for (const [key, student] of expected) {
      await expect(
        options.filter({ hasText: student.number }).filter({ hasText: student.name }),
        `${key} in ${courseKey}`
      ).toHaveCount(1);
    }
  }
});
