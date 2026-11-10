import { expect, test, type Page } from '../support/test';
import { expectAccessDenied, login, nav, pageTitle } from '../support/helpers';
import {
  changePermission,
  expectPermissionActions,
  INSTRUCTOR_PERMISSIONS,
  openPermissionsEditor,
  PERMISSION_ACTIONS,
  permissionAccess,
  ROLES,
} from '../support/permissions';
import { USERS } from '../support/seed';

// Ported from app/tests/cases/system/permissions_editor.test.php.

const EDITOR = INSTRUCTOR_PERMISSIONS.permissionsEditor;
const MIXEVALS = INSTRUCTOR_PERMISSIONS.mixedEvaluations;

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

test('the seed gives the roles their expected ids and the instructor its expected access', async ({
  page,
}) => {
  // The editor names the role it has opened, so one page proves each role id.
  for (const [name, id] of Object.entries(ROLES)) {
    await page.goto(`/accesses/view/${id}`);
    await expect(pageTitle(page), `role id ${id}`).toContainText(`Permissions Editor > ${name}`);
  }
  for (const [key, permission] of Object.entries(INSTRUCTOR_PERMISSIONS)) {
    expect(await permissionAccess(page, ROLES.instructor, permission.name), key).toEqual(
      permission.seeded
    );
  }
});

async function allowEditorForInstructors(page: Page): Promise<void> {
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
