import { expect, type Locator, type Page } from '@playwright/test';

// The Evaluation tab (/evaltools) lists the templates of the signed-in user,
// one table per template type.

export async function openEvaluationTab(page: Page): Promise<void> {
  await page.goto('/evaltools');
  // Proves the page is the tab, so a template that is absent from it is not
  // absent because some other page loaded.
  await expect(page.getByRole('heading', { name: 'My Simple Evaluations' })).toBeVisible();
}

// The table of one template type, under a heading such as 'My Rubrics
// Evaluations'. Fails when the section is missing, so a check that a template
// is absent from it cannot pass on a page without the section.
export async function evaluationTabSection(page: Page, heading: string): Promise<Locator> {
  const header = page
    .locator('div.evaltool-section-header')
    .filter({ has: page.getByRole('heading', { name: heading, exact: true }) });
  await expect(header, `section ${heading}`).toHaveCount(1);
  // The table follows the header div.
  const table = header.locator('xpath=following-sibling::table[1]');
  await expect(table, `table of ${heading}`).toBeVisible();
  return table;
}
