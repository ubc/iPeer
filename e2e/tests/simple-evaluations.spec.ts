import { expect, test, type Page } from '../support/test';
import {
  errorMessage,
  flashMessage,
  goodMessage,
  idFromUrl,
  login,
  pageTitle,
} from '../support/helpers';
import { idFromAjaxList, runAjaxListAction, waitForAjaxList } from '../support/ajax-list';
import { evaluationTabSection, openEvaluationTab, requestDelete } from '../support/evaltools';
import { USERS } from '../support/seed';
import type { TestDatabase } from '../support/db';
import { NOW, insertRow, userId } from '../support/insert';

// Ported from app/tests/cases/system/add_simple.test.php.

// The seeded template. Root created it, and events with submissions use it.
const SEEDED = { id: '1', name: 'Module 1 Project Evaluation' };

type SimpleEvaluation = {
  name: string;
  description: string;
  points: string;
  availability: 'Public' | 'Private';
};

const PROJECT: SimpleEvaluation = {
  name: 'Project Eval Part One',
  description: 'Please evaluate your group members for the first part of the project.',
  points: '50',
  availability: 'Public',
};

const SAVE = { name: 'Save', exact: true } as const;

const DELETE_QUESTION = 'Are you sure you want to delete this evaluation permanently?';

// Runs Delete from the template list, which must be the open page.
async function deleteFromList(page: Page, name: string): Promise<void> {
  await runAjaxListAction(page, name, 'Delete Evaluation', DELETE_QUESTION);
}

async function fillForm(page: Page, evaluation: SimpleEvaluation): Promise<void> {
  await page.locator('#SimpleEvaluationName').fill(evaluation.name);
  await page.locator('#SimpleEvaluationDescription').fill(evaluation.description);
  await page.locator('#SimpleEvaluationPointPerMember').fill(evaluation.points);
  await page.getByLabel(evaluation.availability, { exact: true }).check();
}

// Opens a template of the signed-in user from the Evaluation tab, and returns
// its id.
async function openFromEvaluationTab(page: Page, name: string): Promise<string> {
  await openEvaluationTab(page);
  await (await ownSimpleEvaluations(page)).getByRole('link', { name, exact: true }).click();
  // Read the id only once the view page has loaded.
  await expect(page).toHaveURL(/\/simpleevaluations\/view\/\d+$/);
  return idFromUrl(page);
}

// The table of the signed-in user's templates on the Evaluation tab.
function ownSimpleEvaluations(page: Page) {
  return evaluationTabSection(page, 'My Simple Evaluations');
}

// Writes the row that a save of the Add Simple Evaluation form writes (see
// __processForm in simpleevaluations_controller.php), and returns the id.
async function insertSimpleEvaluation(
  testDatabase: TestDatabase,
  evaluation: SimpleEvaluation,
  creator: string = USERS.superAdmin
): Promise<string> {
  return test.step(
    `Insert the simple evaluation ${evaluation.name}`,
    async () => {
      const owner = await userId(testDatabase, creator);
      return insertRow(testDatabase, 'simple_evaluations', {
        name: evaluation.name,
        description: evaluation.description,
        point_per_member: Number(evaluation.points),
        availability: evaluation.availability.toLowerCase(),
        creator_id: owner,
        created: NOW,
        updater_id: owner,
        modified: NOW,
      });
    },
    { box: true }
  );
}

// The value the view page shows next to a label.
function viewField(page: Page, label: string) {
  return page
    .locator('#frm tr')
    .filter({ has: page.getByRole('cell', { name: `${label}:`, exact: true }) })
    .getByRole('cell')
    .nth(1);
}

async function expectView(page: Page, evaluation: SimpleEvaluation): Promise<void> {
  await test.step(
    `Expect the view of ${evaluation.name}`,
    async () => {
      await expect(viewField(page, 'Evaluation Name')).toHaveText(evaluation.name);
      await expect(viewField(page, 'Description')).toHaveText(evaluation.description);
      await expect(viewField(page, 'Base Point Per Member')).toHaveText(evaluation.points);
      await expect(viewField(page, 'Availability')).toHaveText(
        evaluation.availability.toLowerCase()
      );
      // The preview is a form for three group members, each worth the points
      // per member, and nothing is allocated yet.
      await expect(page.locator('#statusMsg')).toContainText(
        `Please allocate ${Number(evaluation.points) * 3} more points.`
      );
    },
    { box: true }
  );
}

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

test('the Add Simple Evaluation form refuses missing fields, a duplicate name and points that are not positive', async ({
  page,
}) => {
  await page.goto('/simpleevaluations/add');
  await page.getByRole('button', SAVE).click();
  await expect(flashMessage(page)).toHaveText('The evaluation was not added successfully.');
  await expect(errorMessage(page)).toHaveText([
    'Please give the evaluation template a name.',
    'Please enter a positive integer.',
    'Please select an availability option.',
  ]);

  await page.locator('#SimpleEvaluationName').fill(SEEDED.name);
  for (const points of ['-100', '0']) {
    await page.locator('#SimpleEvaluationPointPerMember').fill(points);
    await page.getByRole('button', SAVE).click();
    await expect(errorMessage(page), `points ${points}`).toHaveText([
      'Duplicate name found. Please change the name.',
      'Please enter a positive integer.',
      'Please select an availability option.',
    ]);
  }
});

test('an admin adds a simple evaluation', async ({ page }) => {
  await page.goto('/simpleevaluations/add');
  await expect(pageTitle(page)).toContainText('Simple Evaluations > Add');

  await fillForm(page, PROJECT);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText('The evaluation was added successfully.');

  await openFromEvaluationTab(page, PROJECT.name);
  await expectView(page, PROJECT);
});

// The insert must give what the form gives, or the tests that use it test
// something the app cannot make.
test('an inserted simple evaluation shows like one added through the form', async ({
  page,
  testDatabase,
}) => {
  await insertSimpleEvaluation(testDatabase, PROJECT);
  await openFromEvaluationTab(page, PROJECT.name);
  await expectView(page, PROJECT);
});

test('editing a simple evaluation saves the new points', async ({ page, testDatabase }) => {
  const id = await insertSimpleEvaluation(testDatabase, PROJECT);

  await page.goto(`/simpleevaluations/edit/${id}`);
  const points = page.locator('#SimpleEvaluationPointPerMember');
  await expect(points).toHaveValue(PROJECT.points);
  await points.fill('72');
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText('The simple evaluation was updated successfully.');

  await page.goto(`/simpleevaluations/view/${id}`);
  await expectView(page, { ...PROJECT, points: '72' });
});

test('a copy of a simple evaluation keeps its fields under a new name', async ({
  page,
  testDatabase,
}) => {
  const id = await insertSimpleEvaluation(testDatabase, PROJECT);

  await page.goto(`/simpleevaluations/copy/${id}`);
  const copy = { ...PROJECT, name: `Copy of ${PROJECT.name}` };
  await expect(page.locator('#SimpleEvaluationName')).toHaveValue(copy.name);
  await expect(page.locator('#SimpleEvaluationDescription')).toHaveValue(copy.description);
  await expect(page.locator('#SimpleEvaluationPointPerMember')).toHaveValue(copy.points);
  await expect(page.getByLabel(copy.availability, { exact: true })).toBeChecked();
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText('The evaluation was added successfully.');

  const copyId = await idFromAjaxList(page, copy.name, 'simpleevaluations');
  expect(copyId, 'the copy is a new template').not.toBe(id);
  await page.goto(`/simpleevaluations/view/${copyId}`);
  await expectView(page, copy);
});

test('a simple evaluation that no event uses can be deleted', async ({ page, testDatabase }) => {
  const id = await insertSimpleEvaluation(testDatabase, PROJECT);

  await page.goto('/simpleevaluations');
  await deleteFromList(page, PROJECT.name);
  await expect(goodMessage(page)).toHaveText('The evaluation was deleted successfully.');

  await openEvaluationTab(page);
  const own = await ownSimpleEvaluations(page);
  await expect(own.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(own.getByRole('link', { name: PROJECT.name, exact: true })).toHaveCount(0);
  await page.goto(`/simpleevaluations/view/${id}`);
  await expect(flashMessage(page)).toHaveText('Error: Invalid ID.');
});

test('a simple evaluation that events use cannot be deleted, and one with submissions cannot be edited', async ({
  page,
}) => {
  await page.goto('/simpleevaluations');
  await deleteFromList(page, SEEDED.name);
  await expect(flashMessage(page)).toContainText(
    'This evaluation is now in use, and can NOT be deleted.'
  );

  await page.goto(`/simpleevaluations/edit/${SEEDED.id}`);
  await expect(flashMessage(page)).toHaveText(
    `Submissions had been made. ${SEEDED.name} cannot be edited. Please make a copy.`
  );

  await page.goto(`/simpleevaluations/view/${SEEDED.id}`);
  await expect(viewField(page, 'Evaluation Name')).toHaveText(SEEDED.name);
});

test("an instructor can view an admin's public simple evaluation but can neither edit nor delete it", async ({
  page,
  testDatabase,
}) => {
  const id = await insertSimpleEvaluation(testDatabase, PROJECT);

  await login(page, USERS.instructor1);
  // The Evaluation tab lists only the templates of the signed-in user.
  await openEvaluationTab(page);
  const own = await ownSimpleEvaluations(page);
  await expect(own.getByRole('link', { name: PROJECT.name, exact: true })).toHaveCount(0);

  // The list shows the public templates of other users.
  await page.goto('/simpleevaluations');
  await waitForAjaxList(page);
  await page.locator('#ajaxListDiv').getByRole('link', { name: SEEDED.name, exact: true }).click();
  await expect(viewField(page, 'Evaluation Name')).toHaveText(SEEDED.name);

  await requestDelete(page, 'simpleevaluations', id);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to delete this evaluation'
  );
  await page.goto(`/simpleevaluations/edit/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to edit this evaluation'
  );

  // The refusals left the template as it was.
  await page.goto(`/simpleevaluations/view/${id}`);
  await expectView(page, PROJECT);
});
