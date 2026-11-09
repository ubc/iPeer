import { expect, type Page } from '@playwright/test';
import { goodMessage } from './helpers';

/** Steps and checks for courses. */

/** Deletes a course through the confirmation form. */
export async function deleteCourse(page: Page, courseId: string): Promise<void> {
  await page.goto(`/courses/delete/${courseId}`);
  await page.getByRole('button', { name: 'Delete Course' }).click();
  await expect(goodMessage(page)).toContainText('The course was deleted successfully.');
}
