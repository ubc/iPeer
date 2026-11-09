import { expect, type Page } from '@playwright/test';

/**
 * Steps and checks for course groups: the group list, the group member chooser
 * and the group forms.
 */

/** Deletes a group. Used for cleanup, so a missing group is not an error. */
export async function deleteGroup(page: Page, groupId: string): Promise<void> {
  await page.goto(`/groups/delete/${groupId}`);
}

/**
 * Picks an option of a group member list by name. The lists label a student
 * with their student number as well as their name, so the match is partial.
 */
async function optionValue(page: Page, listId: string, student: string): Promise<string> {
  const option = page.locator(`#${listId} option`).filter({ hasText: student }).first();
  const value = await option.getAttribute('value');
  if (value === null) {
    throw new Error(`no option for ${student} in ${listId}`);
  }
  return value;
}

/**
 * Checks the selected list of the group member chooser holds these students and
 * no others. The list labels a student with their student number, which is
 * unique, so the student number identifies the student.
 */
export async function expectGroupMembers(page: Page, studentNumbers: string[]): Promise<void> {
  const options = page.locator('#selected_groups option');
  await expect(options).toHaveCount(studentNumbers.length);
  for (const studentNo of studentNumbers) {
    await expect(options.filter({ hasText: studentNo }), `student ${studentNo}`).toHaveCount(1);
  }
}

/** Moves students into the selected list of the group member chooser. */
export async function assignToGroup(page: Page, students: string[]): Promise<void> {
  for (const student of students) {
    await page.locator('#all_groups').selectOption(await optionValue(page, 'all_groups', student));
    await page.getByRole('button', { name: 'Assign >>' }).click();
  }
}

/** Removes students from the selected list of the group member chooser. */
export async function removeFromGroup(page: Page, students: string[]): Promise<void> {
  const values: string[] = [];
  for (const student of students) {
    values.push(await optionValue(page, 'selected_groups', student));
  }
  await page.locator('#selected_groups').selectOption(values);
  await page.getByRole('button', { name: '<< Remove' }).click();
}

/**
 * Opens the group list of a course. iPeer filters the list by the course that
 * was opened last, so the course home page comes first.
 */
export async function openGroupList(page: Page, courseId: string): Promise<void> {
  await page.goto(`/courses/home/${courseId}`);
  await page.getByRole('link', { name: 'List Groups', exact: true }).click();
  await expect(page).toHaveURL(new RegExp(`/groups/index/${courseId}$`));
  // The list comes in through AJAX. Its result count appears when the rows are
  // in place, so a check for a group that must be absent is not premature.
  await expect(page.locator('#ajaxListDiv .total-result').first()).toBeVisible();
}
