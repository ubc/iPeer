import { expect, type Locator, type Page } from '@playwright/test';
import { waitForAjaxList } from './ajax-list';

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
