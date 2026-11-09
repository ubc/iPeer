import { expect, test, type Page } from '@playwright/test';
import {
  USERS,
  deleteCourse,
  errorMessage,
  flashMessage,
  goodMessage,
  idFromUrl,
  login,
  nav,
  pageTitle,
} from '../support/helpers';

// Ported from app/tests/cases/system/add_course.test.php.

/** Opens the course home page from the course list and returns the course id. */
async function openCourse(page: Page, name: string, title: string): Promise<string> {
  await page.goto('/courses');
  const row = page
    .getByRole('row')
    .filter({ has: page.getByRole('cell', { name, exact: true }) });
  await row.getByRole('link', { name: title, exact: true }).click();
  await expect(pageTitle(page)).toContainText(name);
  return idFromUrl(page);
}

test('an admin adds a course through the Courses tab', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await nav(page).getByRole('link', { name: 'Courses', exact: true }).click();
  await expect(pageTitle(page)).toHaveText('Courses');

  await page.getByRole('link', { name: 'Add Course', exact: true }).click();
  await expect(pageTitle(page)).toHaveText('Add Course');

  await page.locator('#CourseCourse').fill('EECE 474 101');
  await page.locator('#CourseTitle').fill('Project Course');

  await page.locator('#CourseInstructors').selectOption({ label: 'Instructor 1' });
  await page.getByRole('link', { name: 'Add Instructor' }).click();
  await page.locator('#CourseInstructors').selectOption({ label: 'Instructor 3' });
  await page.getByRole('link', { name: 'Add Instructor' }).click();

  await page.locator('#CourseTutors').selectOption({ label: 'Tutor 1' });
  await page.getByRole('link', { name: 'Add Tutor' }).click();
  await page.locator('#CourseTutors').selectOption({ label: 'Tutor 3' });
  await page.getByRole('link', { name: 'Add Tutor' }).click();

  await page.locator('#DepartmentDepartment').selectOption(['2', '3']);
  await page.locator('#CourseHomepage').fill('http://www.ece.ubc.ca/course/eece-474');

  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('Course created!');

  // The course exists with the instructors and tutors that were chosen.
  const courseId = await openCourse(page, 'EECE 474 101', 'Project Course');
  await expect(page.getByRole('link', { name: 'Instructor 1' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'Instructor 3' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'Tutor 1' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'Tutor 3' })).toBeVisible();

  await deleteCourse(page, courseId);
});

test('the Add Course form refuses an empty name, a duplicate name and an empty person', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);
  await page.goto('/courses/add');

  await page.getByRole('button', { name: 'Save' }).click();
  await expect(flashMessage(page)).toHaveText('Add course failed.');
  await expect(errorMessage(page)).toHaveText('The course name is required.');

  // Add Instructor and Add Tutor with nothing selected must add no row. The
  // person that is chosen next must become the first row, which is only true
  // if the click with nothing selected was ignored.
  await page.getByRole('link', { name: 'Add Instructor' }).click();
  await page.locator('#CourseInstructors').selectOption({ label: 'Instructor 1' });
  await page.getByRole('link', { name: 'Add Instructor' }).click();
  await expect(page.locator('#Instructor0FullName')).toHaveValue('Instructor 1');
  await expect(page.locator('#instructors div[id^="instructorsList"]')).toHaveCount(1);

  await page.getByRole('link', { name: 'Add Tutor' }).click();
  await page.locator('#CourseTutors').selectOption({ label: 'Tutor 1' });
  await page.getByRole('link', { name: 'Add Tutor' }).click();
  await expect(page.locator('#Tutor0FullName')).toHaveValue('Tutor 1');
  await expect(page.locator('#tutors div[id^="tutorsList"]')).toHaveCount(1);

  // APSC 201 is a seeded course.
  await page.locator('#CourseCourse').fill('APSC 201');
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(errorMessage(page)).toHaveText('A course with this name already exists');
});

test('an instructor who creates a course becomes its instructor by default', async ({ page }) => {
  await login(page, USERS.instructor1);

  await page.goto('/courses/add');
  await page.locator('#CourseCourse').fill('CPSC 101 101');
  await page.locator('#CourseTitle').fill('Introductory Course to Programming');
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('Course created!');

  const courseId = await openCourse(page, 'CPSC 101 101', 'Introductory Course to Programming');
  await page.goto(`/courses/edit/${courseId}`);

  await expect(page.locator('#Instructor0FullName')).toHaveValue('Instructor 1');
  await expect(page.locator('#Tutor0FullName')).toHaveCount(0);

  // instructor1 is in the Applied Science faculty, so its departments are
  // selected for them.
  await expect(page.locator('#DepartmentDepartment')).toHaveValues(['1', '2']);

  await deleteCourse(page, courseId);
});

test('an inactive course gets limited access and is listed apart', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await page.goto('/courses/add');
  await page.locator('#CourseCourse').fill('EECE 450 101');
  await page.locator('#CourseTitle').fill('Course To Deactivate');
  await page.locator('#CourseInstructors').selectOption({ label: 'Instructor 1' });
  await page.getByRole('link', { name: 'Add Instructor' }).click();
  await page.locator('#CourseTutors').selectOption({ label: 'Tutor 1' });
  await page.getByRole('link', { name: 'Add Tutor' }).click();
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('Course created!');

  const courseId = await openCourse(page, 'EECE 450 101', 'Course To Deactivate');
  await page.goto(`/courses/edit/${courseId}`);

  // Removing a person puts them back in the list that can be chosen from.
  await expect(page.locator('#CourseInstructors option', { hasText: 'Instructor 1' })).toHaveCount(
    0
  );
  await page.locator('#instructorsList0').getByRole('link', { name: 'X' }).click();
  await expect(page.locator('#CourseInstructors option', { hasText: 'Instructor 1' })).toHaveCount(
    1
  );

  await expect(page.locator('#CourseTutors option', { hasText: 'Tutor 1' })).toHaveCount(0);
  await page.locator('#tutorsList0').getByRole('link', { name: 'X' }).click();
  await expect(page.locator('#CourseTutors option', { hasText: 'Tutor 1' })).toHaveCount(1);

  // The status is set by hand: iPeer keeps a course active until someone
  // changes the status, also when the course has no instructor left.
  await page.locator('#status').selectOption('I');
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('The course was updated successfully.');

  // The course home page says the course is inactive and drops the links that
  // change the course.
  await page.goto(`/courses/home/${courseId}`);
  await expect(page.locator('#CourseHome')).toContainText('Inactive Course (Limited Access)');
  await expect(page.getByRole('link', { name: 'Add Student' })).toHaveCount(0);
  await expect(page.getByRole('link', { name: 'Import Students from CSV' })).toHaveCount(0);
  await expect(page.getByRole('link', { name: 'Import Groups from CSV' })).toHaveCount(0);
  await expect(page.getByRole('link', { name: 'Add Event' })).toHaveCount(0);
  // The read-only links stay.
  await expect(page.getByRole('link', { name: 'List Students' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'List Groups' })).toBeVisible();
  await expect(page.getByRole('link', { name: 'List Evaluation Events' })).toBeVisible();

  // The home page lists the course under Inactive Courses.
  await page.goto('/home');
  const inactive = page.locator(
    "xpath=//h2[normalize-space()='Inactive Courses']/following-sibling::div[@class='course']"
  );
  await expect(inactive.filter({ hasText: 'EECE 450 101 - Course To Deactivate' })).toHaveCount(1);

  await deleteCourse(page, courseId);
});

test('an admin can delete a course', async ({ page }) => {
  await login(page, USERS.superAdmin);

  await page.goto('/courses/add');
  await page.locator('#CourseCourse').fill('EECE 480 101');
  await page.locator('#CourseTitle').fill('Course To Delete');
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('Course created!');

  const courseId = await openCourse(page, 'EECE 480 101', 'Course To Delete');

  await page.goto(`/courses/delete/${courseId}`);
  await expect(page.locator('#CourseDeleteForm')).toContainText('EECE 480 101 - Course To Delete');
  await page.getByRole('button', { name: 'Delete Course' }).click();
  await expect(goodMessage(page)).toHaveText('The course was deleted successfully.');

  await page.goto('/courses');
  await expect(page.getByRole('cell', { name: 'EECE 480 101', exact: true })).toHaveCount(0);
});
