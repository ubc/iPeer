import { expect, test, type Page } from '@playwright/test';
import { expectAccessDenied, login, nav, pageTitle } from '../support/helpers';
import {
  changePermission,
  expectPermissionActions,
  openPermissionsEditor,
  PERMISSION_ACTIONS,
  permissionAccess,
  permissionId,
  setPermissionAccess,
  type PermissionAccess,
} from '../support/permissions';
import { INSTRUCTOR_PERMISSIONS, ROLES, USERS } from '../support/seed';

// Ported from app/tests/cases/system/permissions_editor.test.php.
// The editor writes the permissions of a whole role, which every later spec
// reads, so each test puts the seeded access back.

const EDITOR = INSTRUCTOR_PERMISSIONS.permissionsEditor;
const MIXEVALS = INSTRUCTOR_PERMISSIONS.mixedEvaluations;

let changed = new Set<{ id: string; access: PermissionAccess }>();

test.beforeEach(async ({ page }) => {
  changed = new Set();
  await login(page, USERS.superAdmin);
});

test.afterEach(async ({ page }) => {
  // A test may end signed in as the role it changed the permissions of, and
  // only an admin may change them back.
  await login(page, USERS.superAdmin);
  for (const permission of changed) {
    await setPermissionAccess(page, ROLES.instructor, permission.id, permission.access);
  }
});

async function allowEditorForInstructors(page: Page): Promise<void> {
  // Registered before the change, so a failed assertion still leaves the
  // seeded access to be put back.
  changed.add({
    id: await permissionId(page, ROLES.instructor, EDITOR.name),
    access: EDITOR.seeded,
  });
  await changePermission(page, ROLES.instructor, EDITOR.name, 'Allow All');
}

test('an admin reaches the editor through the Admin tab and it opens on their own role', async ({
  page,
}) => {
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

test('one action of a permission can be denied while the others stay allowed', async ({ page }) => {
  expect(await permissionAccess(page, ROLES.instructor, MIXEVALS.name)).toEqual(MIXEVALS.seeded);

  changed.add({
    id: await permissionId(page, ROLES.instructor, MIXEVALS.name),
    access: MIXEVALS.seeded,
  });
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

test('denied actions can be allowed again one at a time, which puts the seeded access back', async ({
  page,
}) => {
  changed.add({
    id: await permissionId(page, ROLES.instructor, MIXEVALS.name),
    access: MIXEVALS.seeded,
  });
  for (const action of PERMISSION_ACTIONS) {
    await changePermission(page, ROLES.instructor, MIXEVALS.name, `Deny ${action}`);
  }

  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await expectAccessDenied(page);

  await login(page, USERS.superAdmin);
  for (const action of PERMISSION_ACTIONS) {
    await changePermission(page, ROLES.instructor, MIXEVALS.name, `Allow ${action}`);
  }
  expect(await permissionAccess(page, ROLES.instructor, MIXEVALS.name)).toEqual(MIXEVALS.seeded);

  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await expect(pageTitle(page)).toContainText('Mixed Evaluations');
});
