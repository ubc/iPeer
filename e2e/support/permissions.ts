import { expect, test, type Locator, type Page } from '@playwright/test';
import { goodMessage } from './helpers';

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
  // The table filters on keyup, which fill() does not produce.
  await page.locator('#table_id_filter input').pressSequentially(permission);
  const rows = page.locator('#table_id tbody tr');
  // When nothing matches, the table shows one "No matching records" row, so
  // the row must also hold the permission.
  const row = rows.filter({ has: page.getByRole('cell', { name: permission, exact: true }) });
  await expect(rows, `rows matching ${permission}`).toHaveCount(1);
  await expect(row, `row of ${permission}`).toHaveCount(1);
  return row;
}

export async function permissionAccess(
  page: Page,
  roleId: string,
  permission: string
): Promise<PermissionAccess> {
  return test.step(
    `Read the access of ${permission} for role ${roleId}`,
    async () => {
      await openPermissionsEditor(page, roleId);
      const row = await filterToPermission(page, permission);
      const cells = await row.getByRole('cell').allInnerTexts();
      // The name column comes first and the four action columns follow it in the
      // order of PERMISSION_ACTIONS. The id column is in the markup but the table
      // script hides it, so count the action columns from the end of the row.
      const access = cells.slice(-PERMISSION_ACTIONS.length).map((text) => text.trim());
      return Object.fromEntries(
        PERMISSION_ACTIONS.map((action, index) => [action, access[index]])
      ) as PermissionAccess;
    },
    { box: true }
  );
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

export async function changePermission(
  page: Page,
  roleId: string,
  permission: string,
  label: string
): Promise<void> {
  await test.step(
    `${label} on ${permission} for role ${roleId}`,
    async () => {
      const panel = await openActionPanel(page, roleId, permission);
      // The editor asks for confirmation. Accept only that question, and fail
      // when it does not come.
      let question: string | undefined;
      page.once('dialog', (dialog) => {
        question = dialog.message();
        void dialog.accept();
      });
      await panel.getByRole('link', { name: label, exact: true }).click();
      await expect(goodMessage(page)).toHaveText('Permissions have been updated');
      expect(question, 'change confirmation').toContain(
        'Are you sure you want to change the access to'
      );
    },
    { box: true }
  );
}
