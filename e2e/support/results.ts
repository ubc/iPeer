import { expect, test, type Locator, type Page } from '@playwright/test';
import { waitForAjaxList } from './ajax-list';
import { labelledTable } from './helpers';

// The pages where an instructor reviews the results of an event, for every
// type of evaluation.

export const NOT_SUBMITTED = 'Have not submitted their evaluations';
export const LEFT_GROUP = 'Left the group, but had submitted or were evaluated';

// The table of one header such as 'Have not submitted their evaluations', on
// the results page of a group.
export function memberList(page: Page, header: string): Locator {
  return page
    .locator('table')
    .filter({ has: page.getByRole('columnheader', { name: header, exact: true }) });
}

// The list of the groups of an event, with their status and release.
export async function openEventResults(page: Page, eventId: string): Promise<void> {
  await page.goto(`/evaluations/view/${eventId}`);
  await waitForAjaxList(page);
}

// A cell of the group list on the event results page, by the group and the
// column header.
export async function groupListCell(page: Page, group: string, column: string): Promise<Locator> {
  await waitForAjaxList(page);
  const table = page.locator('#ajaxListDiv table').filter({ has: page.getByRole('columnheader') });
  const headers = (await table.getByRole('columnheader').allTextContents()).map((h) => h.trim());
  const index = headers.indexOf(column);
  expect(index, `column ${column} in ${headers.join(', ')}`).toBeGreaterThanOrEqual(0);
  return table
    .getByRole('row')
    .filter({ has: page.getByRole('cell', { name: group, exact: true }) })
    .getByRole('cell')
    .nth(index);
}

// The row of a table whose first cell is the name, such as an evaluated
// member or 'Group Average'.
export function namedRow(table: Locator, name: string): Locator {
  return table.locator(`xpath=./tbody/tr[td[1][normalize-space()='${name}']]`);
}

// The Detail page of a rubric or mixed evaluation has a panel for each
// evaluated member, with what each evaluator gave them. The accordion changes
// the class of a header when the panel opens, so the headers are found by
// their ids, such as panel6Header.
export function panelHeaders(page: Page): Locator {
  return page.locator('#accordion > div.panelName > div[id$="Header"]');
}

export function evaluateePanel(page: Page, member: string): Locator {
  return page
    .locator('#accordion > div.panelName')
    .filter({ has: page.locator('div[id$="Header"]', { hasText: `Evaluatee: ${member}` }) });
}

// Opens the panel of an evaluated member. Only the first panel is open when
// the page loads, and the content of a closed panel is hidden.
export async function openEvaluateePanel(page: Page, member: string): Promise<void> {
  await test.step(
    `Open the panel of ${member}`,
    async () => {
      // The inline Rico.Accordion script hides the closed panels. Before it
      // runs, a panel can look open, the click is skipped, and the script
      // then closes it.
      await page.waitForLoadState('load');
      const panel = evaluateePanel(page, member);
      const content = panel.locator('div.panelContent');
      if (!(await content.isVisible())) {
        await panel.locator('div[id$="Header"]').click();
      }
      await expect(content).toBeVisible();
      // The slide ends at the full height of the panel.
      await expect
        .poll(async () => (await content.boundingBox())?.height ?? 0)
        .toBeGreaterThan(400);
    },
    { box: true }
  );
}

// The release buttons of each evaluated member on the Detail page.
export function releaseButton(page: Page, member: string, name: string): Locator {
  const table = labelledTable(page, 'Evaluation Results Release Status');
  return namedRow(table, member).getByRole('button', { name, exact: true });
}
