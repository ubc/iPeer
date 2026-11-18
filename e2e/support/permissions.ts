import { expect, test, type Locator, type Page } from '@playwright/test';
import { goodMessage, withConfirmation } from './helpers';
import { PERMISSIONS } from './messages';

export const PERMISSION_ACTIONS = ['Create', 'Read', 'Update', 'Delete'] as const;

export type PermissionAction = (typeof PERMISSION_ACTIONS)[number];
export type Access = 'Allow' | 'Deny';
export type PermissionAccess = Readonly<Record<PermissionAction, Access>>;

export async function openPermissionsEditor(page: Page, roleId: string): Promise<void> {
  await test.step(
    `Open the permissions editor for role ${roleId}`,
    async () => {
      await page.goto(`/accesses/view/${roleId}`);
      await expect(page.locator('#table_id_filter input')).toBeVisible();
    },
    { box: true }
  );
}

async function filterToPermission(page: Page, permission: string): Promise<Locator> {
  // The table filters on keyup, which fill() does not produce, so a key
  // press follows. Typing each character takes about a second per filter.
  const filter = page.locator('#table_id_filter input');
  await filter.fill(permission);
  await filter.press('End');
  const rows = page.locator('#table_id tbody tr');
  // When nothing matches, the table shows one "No matching records" row, so
  // the row must also hold the permission.
  const row = rows.filter({ has: page.getByRole('cell', { name: permission, exact: true }) });
  await expect(rows, `rows matching ${permission}`).toHaveCount(1);
  await expect(row, `row of ${permission}`).toHaveCount(1);
  return row;
}

// The access of a permission, on the open editor page.
async function readAccess(page: Page, permission: string): Promise<PermissionAccess> {
  const row = await filterToPermission(page, permission);
  const cells = await row.getByRole('cell').allInnerTexts();
  // The name column comes first and the four action columns follow it in the
  // order of PERMISSION_ACTIONS. The id column is in the markup but the table
  // script hides it, so count the action columns from the end of the row.
  const access = cells.slice(-PERMISSION_ACTIONS.length).map((text) => text.trim());
  return Object.fromEntries(
    PERMISSION_ACTIONS.map((action, index) => [action, access[index]])
  ) as PermissionAccess;
}

async function openActionPanel(page: Page, roleId: string, permission: string): Promise<Locator> {
  await openPermissionsEditor(page, roleId);
  const row = await filterToPermission(page, permission);
  // The actions live in a panel that a click on the row opens.
  await row.getByRole('cell').first().click();
  const panel = page.locator('#table_id div.userActionPanel');
  await expect(panel).toBeVisible();
  return panel;
}

export async function expectPermissionActions(
  page: Page,
  roleId: string,
  permission: string,
  labels: readonly string[]
): Promise<void> {
  await test.step(
    `Expect the actions ${labels.join(', ')} on ${permission} for role ${roleId}`,
    async () => {
      const panel = await openActionPanel(page, roleId, permission);
      // Each action is offered as its opposite, so the labels name the access the
      // permission has now as much as what a click would do. The panel always
      // starts with the two links that change every action.
      await expect(panel.getByRole('link')).toHaveText(['Allow All', 'Deny All', ...labels]);
    },
    { box: true }
  );
}

// Changes a permission, and returns its access as the editor shows it after
// the save: the app saves the change, then loads the editor of the role again.
export async function changePermission(
  page: Page,
  roleId: string,
  permission: string,
  label: string
): Promise<PermissionAccess> {
  return test.step(
    `${label} on ${permission} for role ${roleId}`,
    async () => {
      const panel = await openActionPanel(page, roleId, permission);
      await withConfirmation(
        page,
        `Are you sure you want to change the access to ${permission}?`,
        () => panel.getByRole('link', { name: label, exact: true }).click()
      );
      await expect(goodMessage(page)).toHaveText(PERMISSIONS.updated);
      await expect(page).toHaveURL(new RegExp(`/accesses/view/${roleId}$`));
      return readAccess(page, permission);
    },
    { box: true }
  );
}
