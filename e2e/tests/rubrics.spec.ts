import { expect, test, type Page } from '../support/test';
import { errorMessage, flashMessage, goodMessage, login, nav } from '../support/helpers';
import { idFromAjaxList, runAjaxListAction, waitForAjaxList } from '../support/ajax-list';
import { evaluationTabSection, openEvaluationTab } from '../support/evaltools';
import { USERS } from '../support/seed';

// Ported from app/tests/cases/system/add_rubric.test.php.

// The seeded rubric. Root created it, and an event with submissions uses it.
const SEEDED = { id: '1', name: 'Term Report Evaluation' };

type Criterion = {
  name: string;
  // One comment per level of mastery.
  comments: readonly string[];
  weight: string;
  // The mark of each level, as the page calculates it from the weight.
  marks: readonly string[];
};

type Rubric = {
  name: string;
  availability: 'Public' | 'Private';
  viewMode: 'Criteria' | 'Student';
  zeroMark: boolean;
  // One comment per level of mastery.
  levels: readonly string[];
  criteria: readonly Criterion[];
  total: string;
};

// Without Zero Mark, each level is worth an equal share of the weight, so the
// lowest level is worth weight / levels.
const MIDTERM: Rubric = {
  name: 'Midterm Evaluation',
  availability: 'Private',
  viewMode: 'Student',
  zeroMark: false,
  levels: ['Bad', 'Average', 'Good'],
  criteria: [
    {
      name: 'Effort',
      comments: ['Does a sloppy job.', 'Does the minimum work', 'Exceeds my expectation'],
      weight: '4',
      marks: ['1.33', '2.67', '4'],
    },
    {
      name: 'Participation',
      comments: [
        'Does not attend meetings regularly',
        'Attends all meetings',
        'Very active during discussions',
      ],
      weight: '3',
      marks: ['1', '2', '3'],
    },
  ],
  total: '7',
};

// MIDTERM after the edit: a fourth level, a third criterion, new weights, and
// Zero Mark, which makes the lowest level worth 0 and the highest the weight.
const FINAL: Rubric = {
  name: 'Final Project Evaluation',
  availability: 'Public',
  viewMode: 'Criteria',
  zeroMark: true,
  levels: [...MIDTERM.levels, 'Excellent'],
  criteria: [
    {
      ...MIDTERM.criteria[0],
      comments: [...MIDTERM.criteria[0].comments, 'Willing to help others as well'],
      weight: '5',
      marks: ['0', '1.67', '3.33', '5'],
    },
    {
      ...MIDTERM.criteria[1],
      comments: [
        ...MIDTERM.criteria[1].comments,
        'Continually led the group in the right direction',
      ],
      weight: '4',
      marks: ['0', '1.33', '2.67', '4'],
    },
    {
      name: 'Punctuality',
      comments: [
        'Always late',
        'On time most of the time',
        'On time all of the time',
        'Always early',
      ],
      weight: '7',
      marks: ['0', '2.33', '4.67', '7'],
    },
  ],
  total: '16',
};

const FINAL_COPY: Rubric = { ...FINAL, name: `Copy of ${FINAL.name}` };

// FINAL after an edit that removes its last level and its last criterion.
const REDUCED: Rubric = {
  ...FINAL,
  levels: FINAL.levels.slice(0, 3),
  criteria: [
    {
      ...FINAL.criteria[0],
      comments: FINAL.criteria[0].comments.slice(0, 3),
      marks: ['0', '2.5', '5'],
    },
    {
      ...FINAL.criteria[1],
      comments: FINAL.criteria[1].comments.slice(0, 3),
      marks: ['0', '2', '4'],
    },
  ],
  total: '9',
};

const DELETE_QUESTION = 'Are you sure you want to delete this Rubric permanently?';

const NEXT = { name: 'Next', exact: true } as const;

function saveButton(page: Page) {
  return page.locator('#submit-rubric');
}

// Fills the first page of the form: everything except the texts and weights.
async function fillFormat(page: Page, rubric: Rubric): Promise<void> {
  await page.locator('#name').fill(rubric.name);
  await page.locator('#LOM').selectOption(String(rubric.levels.length));
  await page.locator('#criteria').selectOption(String(rubric.criteria.length));
  await page.getByLabel(rubric.availability, { exact: true }).check();
  await page.getByLabel(rubric.viewMode, { exact: true }).check();
  await page.locator('#zero_mark').setChecked(rubric.zeroMark);
}

function criterionComment(page: Page, criterion: number, level: number) {
  return page.locator(`#RubricsCriteria${criterion}RubricsCriteriaComment${level}CriteriaComment`);
}

// Fills the texts and weights. Fields that already hold the text are filled
// again, which does not change them, so check what a page kept before calling
// this.
async function fillTexts(page: Page, rubric: Rubric): Promise<void> {
  for (const [level, comment] of rubric.levels.entries()) {
    await page.locator(`#RubricsLom${level}LomComment`).fill(comment);
  }
  for (const [index, criterion] of rubric.criteria.entries()) {
    await page.locator(`#RubricsCriteria${index}Criteria`).fill(criterion.name);
    for (const [level, comment] of criterion.comments.entries()) {
      await criterionComment(page, index, level).fill(comment);
    }
    await page.locator(`#RubricsCriteria${index}Multiplier`).selectOption(criterion.weight);
  }
}

async function expectMarks(page: Page, rubric: Rubric): Promise<void> {
  await test.step(
    `Expect the marks of ${rubric.name}`,
    async () => {
      for (const [index, criterion] of rubric.criteria.entries()) {
        for (const [level, mark] of criterion.marks.entries()) {
          await expect(
            page.locator(`#RubricsCriteriaMark${index}${level}`),
            `${criterion.name}, level ${level + 1}`
          ).toHaveValue(mark);
        }
      }
      await expect(page.locator('#total')).toHaveValue(rubric.total);
    },
    { box: true }
  );
}

// Checks every field of the edit form, which shows what was saved.
// Checks the texts and weights the form holds for each level and criterion.
async function expectTexts(page: Page, rubric: Rubric): Promise<void> {
  await test.step(
    `Expect the texts of ${rubric.name}`,
    async () => {
      for (const [level, comment] of rubric.levels.entries()) {
        await expect(page.locator(`#RubricsLom${level}LomComment`)).toHaveValue(comment);
      }
      for (const [index, criterion] of rubric.criteria.entries()) {
        await expect(page.locator(`#RubricsCriteria${index}Criteria`)).toHaveValue(criterion.name);
        for (const [level, comment] of criterion.comments.entries()) {
          await expect(criterionComment(page, index, level)).toHaveValue(comment);
        }
        await expect(page.locator(`#RubricsCriteria${index}Multiplier`)).toHaveValue(
          criterion.weight
        );
      }
    },
    { box: true }
  );
}

async function expectForm(page: Page, rubric: Rubric): Promise<void> {
  await test.step(
    `Expect the form of ${rubric.name}`,
    async () => {
      await expect(page.locator('#name')).toHaveValue(rubric.name);
      await expect(page.locator('#LOM')).toHaveValue(String(rubric.levels.length));
      await expect(page.locator('#criteria')).toHaveValue(String(rubric.criteria.length));
      await expect(page.getByLabel(rubric.availability, { exact: true })).toBeChecked();
      await expect(page.getByLabel(rubric.viewMode, { exact: true })).toBeChecked();
      await expect(page.locator('#zero_mark')).toBeChecked({ checked: rubric.zeroMark });
      await expectTexts(page, rubric);
      await expectMarks(page, rubric);
    },
    { box: true }
  );
}

async function addRubric(page: Page, rubric: Rubric): Promise<string> {
  return test.step(
    `Add the rubric ${rubric.name}`,
    async () => {
      await page.goto('/rubrics/add');
      await fillFormat(page, rubric);
      await page.getByRole('button', NEXT).click();
      await fillTexts(page, rubric);
      await saveButton(page).click();
      await expect(goodMessage(page)).toHaveText('The rubric was added successfully.');
      return idFromAjaxList(page, rubric.name, 'rubrics');
    },
    { box: true }
  );
}

// Runs Delete from the rubric list, which must be the open page.
async function deleteFromList(page: Page, name: string): Promise<void> {
  await runAjaxListAction(page, name, 'Delete Rubric', DELETE_QUESTION);
}

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

test('the Add Rubric form refuses a missing name and a duplicate name', async ({ page }) => {
  await page.goto('/rubrics/add');
  await page.getByRole('button', NEXT).click();
  await saveButton(page).click();
  await expect(flashMessage(page)).toHaveText('The evaluation was not added successfully.');
  await expect(errorMessage(page)).toHaveText(['Please give the evaluation template a name.']);

  await page.locator('#name').fill(SEEDED.name);
  await saveButton(page).click();
  await expect(errorMessage(page)).toHaveText(['Duplicate name found. Please change the name.']);
});

test('an admin adds a rubric in two steps, and the page calculates its marks', async ({ page }) => {
  await nav(page).getByRole('link', { name: 'Evaluation', exact: true }).click();
  await page.getByRole('link', { name: 'Add Rubric', exact: true }).click();

  await fillFormat(page, MIDTERM);
  await page.getByRole('button', NEXT).click();
  await fillTexts(page, MIDTERM);
  await expectMarks(page, MIDTERM);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric was added successfully.');

  const id = await idFromAjaxList(page, MIDTERM.name, 'rubrics');
  await page.goto(`/rubrics/edit/${id}`);
  await expectForm(page, MIDTERM);
});

test('editing a rubric can add levels and criteria, and recalculates the marks', async ({
  page,
}) => {
  const id = await addRubric(page, MIDTERM);

  await page.goto(`/rubrics/edit/${id}`);
  await fillFormat(page, FINAL);
  // A new format needs a preview before it can be saved.
  await expect(saveButton(page)).toBeDisabled();
  await page.getByRole('button', { name: 'Preview (Update Format)' }).click();
  await expect(page.locator(`#RubricsLom${FINAL.levels.length - 1}LomComment`)).toBeVisible();
  // The preview keeps the saved texts and weights where they were, so only the
  // new fields and the new weights need filling.
  await expectTexts(page, MIDTERM);

  await fillTexts(page, FINAL);
  await expectMarks(page, FINAL);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric evaluation was updated successfully');

  await page.goto(`/rubrics/edit/${id}`);
  await expectForm(page, FINAL);
});

test('editing a rubric can remove levels and criteria, and keeps the texts of the rest', async ({
  page,
}) => {
  const id = await addRubric(page, FINAL);

  await page.goto(`/rubrics/edit/${id}`);
  await fillFormat(page, REDUCED);
  await page.getByRole('button', { name: 'Preview (Update Format)' }).click();
  await expect(page.locator(`#RubricsLom${REDUCED.levels.length}LomComment`)).toHaveCount(0);
  // The preview keeps the texts and weights of the remaining levels and
  // criteria, so the rubric is saved without filling anything again.
  await expectTexts(page, REDUCED);
  await expectMarks(page, REDUCED);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric evaluation was updated successfully');

  // The edit form shows the saved rubric: the removed level and criterion are
  // gone, and each remaining text is still at its level.
  await page.goto(`/rubrics/edit/${id}`);
  await expectForm(page, REDUCED);
  await expect(page.locator(`#RubricsLom${REDUCED.levels.length}LomComment`)).toHaveCount(0);
  await expect(page.locator(`#RubricsCriteria${REDUCED.criteria.length}Criteria`)).toHaveCount(0);
});

test('a copy of a rubric keeps its format, texts and weights under a new name', async ({
  page,
}) => {
  const id = await addRubric(page, FINAL);

  await page.goto(`/rubrics/copy/${id}`);
  await expectForm(page, FINAL_COPY);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric was added successfully.');

  const copyId = await idFromAjaxList(page, FINAL_COPY.name, 'rubrics');
  expect(copyId, 'the copy is a new rubric').not.toBe(id);
  await page.goto(`/rubrics/edit/${copyId}`);
  await expectForm(page, FINAL_COPY);
});

test('a rubric that no event uses can be deleted', async ({ page }) => {
  const id = await addRubric(page, MIDTERM);

  await deleteFromList(page, MIDTERM.name);
  await expect(goodMessage(page)).toHaveText('The rubric was deleted successfully.');

  await openEvaluationTab(page);
  const rubrics = await evaluationTabSection(page, 'My Rubrics Evaluations');
  await expect(rubrics.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(rubrics.getByRole('link', { name: MIDTERM.name, exact: true })).toHaveCount(0);
  await page.goto(`/rubrics/view/${id}`);
  await expect(flashMessage(page)).toHaveText('Error: Invalid ID.');
});

test('a rubric that events use cannot be deleted, and one with submissions cannot be edited', async ({
  page,
}) => {
  await page.goto('/rubrics');
  await deleteFromList(page, SEEDED.name);
  await expect(errorMessage(page)).toHaveText(
    'This evaluation is in use. Please remove all the events assosiated with this evaluation first.'
  );

  await page.goto(`/rubrics/edit/${SEEDED.id}`);
  await expect(flashMessage(page)).toHaveText(
    `Submissions had been made. ${SEEDED.name} cannot be edited. Please make a copy.`
  );

  await page.goto(`/rubrics/view/${SEEDED.id}`);
  await expect(page.locator('#name')).toHaveValue(SEEDED.name);
});

test("an instructor can view and copy an admin's public rubric but can neither edit nor delete it", async ({
  page,
}) => {
  const id = await addRubric(page, FINAL);

  await login(page, USERS.instructor1);
  // The Evaluation tab lists only the templates of the signed-in user.
  await openEvaluationTab(page);
  const ownRubrics = await evaluationTabSection(page, 'My Rubrics Evaluations');
  await expect(ownRubrics.getByRole('link', { name: FINAL.name, exact: true })).toHaveCount(0);

  // The list shows the public rubrics of other users.
  await page.goto('/rubrics');
  await waitForAjaxList(page);
  await page.locator('#ajaxListDiv').getByRole('link', { name: FINAL.name, exact: true }).click();
  await expect(page.locator('#name')).toHaveValue(FINAL.name);

  // The list greys out Delete for rubrics the user may not change, so send the
  // request the menu would send, from the list page. Production can refuse a
  // delete without a same-origin referer (IPEER_ENFORCE_REFERRER); the test
  // stack does not, but the request should not depend on that.
  await page.goto(`/rubrics/delete/${id}`, {
    referer: new URL('/rubrics', page.url()).href,
  });
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to delete this rubric'
  );
  await page.goto(`/rubrics/edit/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to edit this rubric'
  );

  // A public rubric can be copied. The copy belongs to the instructor, who can
  // delete it.
  await page.goto(`/rubrics/copy/${id}`);
  await expectForm(page, FINAL_COPY);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric was added successfully.');
  await deleteFromList(page, FINAL_COPY.name);
  await expect(goodMessage(page)).toHaveText('The rubric was deleted successfully.');

  // The refusals left the original as it was.
  await login(page, USERS.superAdmin);
  await page.goto(`/rubrics/edit/${id}`);
  await expectForm(page, FINAL);
});

test("an instructor can neither list, view nor copy an admin's private rubric", async ({
  page,
}) => {
  const id = await addRubric(page, MIDTERM);

  await login(page, USERS.instructor1);
  await page.goto('/rubrics');
  await waitForAjaxList(page);
  const list = page.locator('#ajaxListDiv');
  // The public seeded rubric proves the list shows other users' rubrics at all.
  await expect(list.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(list.getByRole('link', { name: MIDTERM.name, exact: true })).toHaveCount(0);

  await page.goto(`/rubrics/view/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to view this rubric'
  );
  await page.goto(`/rubrics/copy/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to copy this rubric'
  );
});
