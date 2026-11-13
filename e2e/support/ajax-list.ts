import { expect, test, type Page } from '@playwright/test';
import { withConfirmation } from './helpers';

// The AJAX list (app/webroot/js/ajaxList.js) is the table on pages such as
// /simpleevaluations. A script on the page draws it from data in the page, and
// fetches the data again over AJAX when it is stale.

export async function waitForAjaxList(page: Page): Promise<void> {
  // The result count appears when the rows are in place, so a check for a row
  // that must be absent is not premature.
  await expect(page.locator('#ajaxListDiv .total-result').first()).toBeVisible();
}

// The id of the row whose name links to /<controller>/view/<id>. A template
// save ends on its list, so this reads the new id without another page load.
export async function idFromAjaxList(
  page: Page,
  name: string,
  controller: string
): Promise<string> {
  await waitForAjaxList(page);
  const href = await page
    .locator('#ajaxListDiv')
    .getByRole('link', { name, exact: true })
    .getAttribute('href');
  const escaped = controller.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const id = href?.match(new RegExp(`/${escaped}/view/(\\d+)$`))?.[1];
  if (!id) {
    throw new Error(`no ${controller} view link for ${name} in the list: ${href}`);
  }
  return id;
}

// Runs an action from the menu that a right-click on a row opens. The row is
// the one with a link named `rowName`. When the action asks for confirmation,
// pass the question. Fails when the action does not open a new page, for
// example because the menu greys it out for this row.
export async function runAjaxListAction(
  page: Page,
  rowName: string,
  action: string,
  confirmation?: string
): Promise<void> {
  await test.step(
    `${action} on ${rowName}`,
    async () => {
      await waitForAjaxList(page);
      await page
        .locator('#ajaxListDiv')
        .getByRole('link', { name: rowName, exact: true })
        .click({ button: 'right' });

      // The script adds the menu to the end of the body, positioned on its own.
      const menu = page.locator('body > div[style*="position: absolute"]');
      const item = menu.getByText(action, { exact: true });
      const opened = page.waitForRequest(
        (request) => request.isNavigationRequest() && request.frame() === page.mainFrame()
      );
      // A failed check below must not also report this wait as unhandled.
      opened.catch(() => undefined);

      // The menu items react to mouse up, which a click sends.
      if (confirmation !== undefined) {
        await withConfirmation(page, confirmation, () => item.click());
      } else {
        await item.click();
      }
      await opened;
    },
    { box: true }
  );
}
