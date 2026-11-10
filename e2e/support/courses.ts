import { expect, type Locator, type Page } from '@playwright/test';

export async function expectInstructorChoice(
  page: Page,
  courseId: string,
  instructorName: string,
  offered: boolean
): Promise<void> {
  await page.goto(`/courses/edit/${courseId}`);
  const chooser = page.locator('#CourseInstructors');
  // iPeer answers a page it refuses with a redirect and a 200, so without this
  // the "not offered" case would also pass on a page that has no chooser at
  // all. The chooser holds a prompt option, then the instructors who do not
  // teach the course yet; the page script removes those who already do. So a
  // second option shows that the list was built.
  await expect(chooser).toBeVisible();
  await expect(chooser.locator('option').nth(1)).toBeAttached();
  await expect(
    chooser.getByRole('option', { name: instructorName, exact: true }),
    `${instructorName} offered as an instructor of course ${courseId}`
  ).toHaveCount(offered ? 1 : 0);
}

export async function expectClassSize(
  page: Page,
  courseId: string,
  students: number
): Promise<void> {
  await page.goto(`/courses/home/${courseId}`);
  // An exact cell match, not a substring: "13 students" is part of
  // "113 students", so a containText check would pass on a wrong count.
  await expect(
    page.getByRole('cell', { name: `${students} students`, exact: true }),
    `class size of course ${courseId}`
  ).toHaveCount(1);
}

function courseTutorCell(page: Page): Locator {
  // The course summary is one header row and one data row, with Tutors third.
  return page.locator('#CourseHome table.standardtable tr:nth-child(2) td:nth-child(3)');
}

export async function expectCourseTutors(
  page: Page,
  courseId: string,
  tutorNames: readonly string[]
): Promise<void> {
  await page.goto(`/courses/home/${courseId}`);
  const cell = courseTutorCell(page);
  if (tutorNames.length === 0) {
    await expect(cell).toHaveText('None');
    return;
  }
  // The count rules out a tutor who got attached to the course twice, which a
  // check for each expected name on its own would not notice.
  await expect(cell.locator('li')).toHaveCount(tutorNames.length);
  for (const name of tutorNames) {
    await expect(cell.getByRole('link', { name, exact: true }), `tutor ${name}`).toHaveCount(1);
  }
}
