import { expect, type Locator, type Page } from '@playwright/test';
import { goodMessage } from './helpers';

// The permissions editor addresses a role by its id, so the id is part of
// the seed data a spec depends on, not only the name.
export const ROLES = {
  superadmin: '1',
  admin: '2',
  instructor: '3',
  tutor: '4',
  student: '5',
} as const;

// Two permissions of the instructor role, with the access the seed data gives
// them. The permissions-editor spec checks these first.
export const INSTRUCTOR_PERMISSIONS = {
  permissionsEditor: {
    name: 'controllers/accesses/view',
    seeded: { Create: 'Deny', Read: 'Deny', Update: 'Deny', Delete: 'Deny' },
  },
  mixedEvaluations: {
    name: 'controllers/mixevals/index',
    seeded: { Create: 'Allow', Read: 'Allow', Update: 'Allow', Delete: 'Allow' },
  },
} as const;

export const PERMISSION_ACTIONS = ['Create', 'Read', 'Update', 'Delete'] as const;

export type PermissionAction = (typeof PERMISSION_ACTIONS)[number];
export type Access = 'Allow' | 'Deny';
export type PermissionAccess = Readonly<Record<PermissionAction, Access>>;

export async function openPermissionsEditor(page: Page, roleId: string): Promise<void> {
  await page.goto(`/accesses/view/${roleId}`);
  await expect(page.locator('#table_id_filter input')).toBeVisible();
}

async function filterToPermission(page: Page, permission: string): Promise<Locator> {
  // The table filters on keyup, which fill() does not produce.
  await page.locator('#table_id_filter input').pressSequentially(permission);
  const rows = page.locator('#table_id tbody tr');
  await expect(rows, `rows matching ${permission}`).toHaveCount(1);
  return rows.first();
}

export async function permissionAccess(
  page: Page,
  roleId: string,
  permission: string
): Promise<PermissionAccess> {
  await openPermissionsEditor(page, roleId);
  const row = await filterToPermission(page, permission);
  const cells = await row.getByRole('cell').allInnerTexts();
  // The name column comes first and the four action columns follow it in the
  // order of PERMISSION_ACTIONS. The id column is in the markup but the table
  // script hides it, so count the action columns from the end of the row.
  expect(cells, `row of ${permission}`).toContain(permission);
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
  const panel = await openActionPanel(page, roleId, permission);
  // Each action is offered as its opposite, so the labels name the access the
  // permission has now as much as what a click would do.
  for (const label of labels) {
    await expect(panel.getByRole('link', { name: label, exact: true }), label).toHaveCount(1);
  }
}

export async function changePermission(
  page: Page,
  roleId: string,
  permission: string,
  label: string
): Promise<void> {
  const panel = await openActionPanel(page, roleId, permission);
  page.once('dialog', (dialog) => dialog.accept());
  await panel.getByRole('link', { name: label, exact: true }).click();
  await expect(goodMessage(page)).toHaveText('Permissions have been updated');
}
