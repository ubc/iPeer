import { expect, test, type Page } from '../support/test';
import { expectAccessDenied, login, nav, pageTitle } from '../support/helpers';
import {
  changePermission,
  expectPermissionActions,
  openPermissionsEditor,
  PERMISSION_ACTIONS,
  permissionAccess,
  type PermissionAccess,
} from '../support/permissions';
import { INSTRUCTOR_PERMISSIONS, ROLES, USERS } from '../support/seed';

// Ported from app/tests/cases/system/permissions_editor.test.php.

const EDITOR = INSTRUCTOR_PERMISSIONS.permissionsEditor;
const MIXEVALS = INSTRUCTOR_PERMISSIONS.mixedEvaluations;

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

async function allowEditorForInstructors(page: Page): Promise<void> {
  await changePermission(page, ROLES.instructor, EDITOR.name, 'Allow All');
}

test('the Admin tab leads to the editor, which opens on the superadmin role', async ({ page }) => {
  await nav(page).getByRole('link', { name: 'Admin', exact: true }).click();
  await page.getByRole('link', { name: 'Permissions Editor', exact: true }).click();
  await expect(pageTitle(page)).toContainText('Permissions Editor > superadmin');

  await page.getByLabel('Role:').selectOption(ROLES.instructor);
  await expect(pageTitle(page)).toContainText('Permissions Editor > instructor');
});

test('allowing every action of a permission lets that role reach the page', async ({ page }) => {
  await allowEditorForInstructors(page);

  await login(page, USERS.instructor1);
  await openPermissionsEditor(page, ROLES.student);
  await expect(pageTitle(page)).toContainText('Permissions Editor > student');
});

test('denying every action of a permission stops that role from reaching the page', async ({
  page,
}) => {
  await allowEditorForInstructors(page);
  await changePermission(page, ROLES.instructor, EDITOR.name, 'Deny All');

  await login(page, USERS.instructor1);
  await page.goto(`/accesses/view/${ROLES.student}`);
  await expectAccessDenied(page);
});

test('denying one action changes only that action, and the role keeps the page', async ({
  page,
}) => {
  await changePermission(page, ROLES.instructor, MIXEVALS.name, 'Deny Create');

  expect(await permissionAccess(page, ROLES.instructor, MIXEVALS.name)).toEqual({
    ...MIXEVALS.seeded,
    Create: 'Deny',
  });
  // Only Create flipped, so only Create is now offered the other way round.
  await expectPermissionActions(page, ROLES.instructor, MIXEVALS.name, [
    'Allow Create',
    'Deny Read',
    'Deny Update',
    'Deny Delete',
  ]);

  // A page stays reachable while any one of its actions is still allowed, so
  // the role keeps the page it was only partly denied.
  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await expect(pageTitle(page)).toContainText('Mixed Evaluations');
});

test('denied actions can be allowed again one at a time, and the role gets the page back', async ({
  page,
}) => {
  // Each change must flip only its own action.
  let expected: PermissionAccess = MIXEVALS.seeded;
  for (const action of PERMISSION_ACTIONS) {
    await changePermission(page, ROLES.instructor, MIXEVALS.name, `Deny ${action}`);
    expected = { ...expected, [action]: 'Deny' };
    expect(
      await permissionAccess(page, ROLES.instructor, MIXEVALS.name),
      `after Deny ${action}`
    ).toEqual(expected);
  }

  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await expectAccessDenied(page);

  await login(page, USERS.superAdmin);
  for (const action of PERMISSION_ACTIONS) {
    await changePermission(page, ROLES.instructor, MIXEVALS.name, `Allow ${action}`);
    expected = { ...expected, [action]: 'Allow' };
    expect(
      await permissionAccess(page, ROLES.instructor, MIXEVALS.name),
      `after Allow ${action}`
    ).toEqual(expected);
  }

  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await expect(pageTitle(page)).toContainText('Mixed Evaluations');
});
