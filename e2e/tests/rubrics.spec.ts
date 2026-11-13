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

// With Zero Mark, the lowest level is worth 0 and the highest the full weight.
const MIDTERM: Rubric = {
  name: 'Midterm Evaluation',
  availability: 'Private',
  viewMode: 'Student',
  zeroMark: true,
  levels: ['Bad', 'Average', 'Good'],
  criteria: [
    {
      name: 'Effort',
      comments: ['Does a sloppy job.', 'Does the minimum work', 'Exceeds my expectation'],
      weight: '4',
      marks: ['0', '2', '4'],
    },
    {
      name: 'Participation',
      comments: [
        'Does not attend meetings regularly',
        'Attends all meetings',
        'Very active during discussions',
      ],
      weight: '3',
      marks: ['0', '1.5', '3'],
    },
  ],
  total: '7',
};

// MIDTERM after the edit: a fourth level, a third criterion and new weights.
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
// again, which does not change them.
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
      await expectMarks(page, rubric);
    },
    { box: true }
  );
}

function idFromList(page: Page, name: string): Promise<string> {
  return idFromAjaxList(page, name, 'rubrics');
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
      return idFromList(page, rubric.name);
    },
    { box: true }
  );
}

async function deleteFromList(page: Page, name: string): Promise<void> {
  await page.goto('/rubrics');
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
  await expect(errorMessage(page).first()).toHaveText(
    'Please give the evaluation template a name.'
  );

  await page.locator('#name').fill(SEEDED.name);
  await saveButton(page).click();
  await expect(errorMessage(page).first()).toHaveText(
    'Duplicate name found. Please change the name.'
  );
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

  const id = await idFromList(page, MIDTERM.name);
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

  await fillTexts(page, FINAL);
  await expectMarks(page, FINAL);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric evaluation was updated successfully');

  await page.goto(`/rubrics/edit/${id}`);
  await expectForm(page, FINAL);
});

test('a copy of a rubric keeps its format, texts and weights under a new name', async ({
  page,
}) => {
  const id = await addRubric(page, FINAL);
  const copy = { ...FINAL, name: `Copy of ${FINAL.name}` };

  await page.goto(`/rubrics/copy/${id}`);
  await expectForm(page, copy);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric was added successfully.');

  const copyId = await idFromList(page, copy.name);
  expect(copyId, 'the copy is a new rubric').not.toBe(id);
  await page.goto(`/rubrics/edit/${copyId}`);
  await expectForm(page, copy);
});

test('a rubric that no event uses can be deleted', async ({ page }) => {
  const id = await addRubric(page, MIDTERM);

  await deleteFromList(page, MIDTERM.name);
  await expect(goodMessage(page)).toHaveText('The rubric was deleted successfully.');

  await openEvaluationTab(page);
  const rubrics = evaluationTabSection(page, 'My Rubrics Evaluations');
  await expect(rubrics.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(rubrics.getByRole('link', { name: MIDTERM.name, exact: true })).toHaveCount(0);
  await page.goto(`/rubrics/view/${id}`);
  await expect(flashMessage(page)).toHaveText('Error: Invalid ID.');
});

test('a rubric that events use cannot be deleted, and one with submissions cannot be edited', async ({
  page,
}) => {
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
  await expect(
    evaluationTabSection(page, 'My Rubrics Evaluations').getByRole('link', {
      name: FINAL.name,
      exact: true,
    })
  ).toHaveCount(0);

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

  // A public rubric can be copied, and the copy belongs to the instructor.
  const copy = { ...FINAL, name: `Copy of ${FINAL.name}` };
  await page.goto(`/rubrics/copy/${id}`);
  await expectForm(page, copy);
  await saveButton(page).click();
  await expect(goodMessage(page)).toHaveText('The rubric was added successfully.');
  await openEvaluationTab(page);
  await expect(
    evaluationTabSection(page, 'My Rubrics Evaluations').getByRole('link', {
      name: copy.name,
      exact: true,
    })
  ).toBeVisible();

  // The refusals left the original as it was.
  await login(page, USERS.superAdmin);
  await page.goto(`/rubrics/edit/${id}`);
  await expectForm(page, FINAL);
});
