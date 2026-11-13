import { expect, type Locator, type Page } from '@playwright/test';

// The Evaluation tab (/evaltools) lists the templates of the signed-in user,
// one table per template type.

export async function openEvaluationTab(page: Page): Promise<void> {
  await page.goto('/evaltools');
  // Proves the page is the tab, so a template that is absent from it is not
  // absent because some other page loaded.
  await expect(page.getByRole('heading', { name: 'My Simple Evaluations' })).toBeVisible();
}

// The table under a section heading such as 'My Rubrics Evaluations'. Each
// heading sits in its own header div, and the table follows that div.
export function evaluationTabSection(page: Page, heading: string): Locator {
  return page.locator(
    `xpath=//div[h2[normalize-space()='${heading}']]/following-sibling::table[1]`
  );
}
