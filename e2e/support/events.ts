import { expect, test, type Locator, type Page } from '@playwright/test';
import { waitForAjaxList } from './ajax-list';

// The value that the event view page shows next to a heading, such as
// 'Due Date'. Only the heading cell is matched, so a value with the same text
// as a heading cannot select the wrong row.
export function viewField(page: Page, heading: string): Locator {
  return page
    .locator('table.standardtable > tbody > tr')
    .filter({ has: page.locator('xpath=./th').getByText(heading, { exact: true }) })
    .locator('xpath=./td');
}

// Filters the event list of the open course page by title. The list shows 20
// events on a page, and the seeded MECH 328 already has 17, so a check for a
// new event searches for it instead of relying on the first page.
export async function searchEventList(page: Page, title: string, count: number): Promise<void> {
  await test.step(
    `Search the event list for ${title}`,
    async () => {
      await waitForAjaxList(page);
      const field = page.locator('#searchInputField');
      await field.fill(title);
      // The list reads the search text on key up, not on input.
      await field.press('End');
      // The click cancels the search-after-typing timer and sends one request,
      // so the rows after this response are those of the search.
      const searched = page.waitForResponse(
        (response) =>
          new URL(response.url()).pathname === '/events/ajaxList' &&
          response.request().method() === 'POST'
      );
      await page
        .locator('#ajaxListDiv')
        .getByRole('button', { name: 'Search', exact: true })
        .click();
      await searched;
      await expect(page.locator('#ajaxListDiv .total-result').first()).toHaveText(
        count === 0 ? 'No results' : `Total Results: ${count}`
      );
    },
    { box: true }
  );
}
