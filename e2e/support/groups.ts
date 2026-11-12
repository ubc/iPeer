import { expect, test, type Page } from '@playwright/test';
import { waitForAjaxList } from './ajax-list';
import type { Person } from './seed';

async function optionValue(page: Page, listId: string, student: Person): Promise<string> {
  // Match on the student number, not the name: the seed data has people who
  // share a full name. A locator that resolves to more than one option throws,
  // which is what we want.
  const option = page.locator(`#${listId} option`).filter({ hasText: student.number });
  const value = await option.getAttribute('value');
  if (value === null) {
    throw new Error(`no option for ${student.name} (${student.number}) in ${listId}`);
  }
  return value;
}

export async function expectGroupMembers(page: Page, studentNumbers: string[]): Promise<void> {
  await test.step(
    `Expect group members ${studentNumbers.length === 0 ? 'none' : studentNumbers.join(', ')}`,
    async () => {
      const options = page.locator('#selected_groups option');
      await expect(options).toHaveCount(studentNumbers.length);
      for (const studentNo of studentNumbers) {
        // The student number is unique and is part of every label, so it identifies
        // the student. A count alone would pass if the wrong students were saved.
        await expect(options.filter({ hasText: studentNo }), `student ${studentNo}`).toHaveCount(1);
      }
    },
    { box: true }
  );
}

export async function assignToGroup(page: Page, students: readonly Person[]): Promise<void> {
  for (const student of students) {
    await page.locator('#all_groups').selectOption(await optionValue(page, 'all_groups', student));
    await page.getByRole('button', { name: 'Assign >>' }).click();
  }
}

export async function removeFromGroup(page: Page, students: readonly Person[]): Promise<void> {
  const values: string[] = [];
  for (const student of students) {
    values.push(await optionValue(page, 'selected_groups', student));
  }
  await page.locator('#selected_groups').selectOption(values);
  await page.getByRole('button', { name: '<< Remove' }).click();
}

export async function openGroupList(page: Page, courseId: string): Promise<void> {
  await test.step(
    `Open the group list of course ${courseId}`,
    async () => {
      // iPeer filters the group list by the course that was opened last, so the
      // course home page comes first.
      await page.goto(`/courses/home/${courseId}`);
      await page.getByRole('link', { name: 'List Groups', exact: true }).click();
      await expect(page).toHaveURL(new RegExp(`/groups/index/${courseId}$`));
      await waitForAjaxList(page);
    },
    { box: true }
  );
}
