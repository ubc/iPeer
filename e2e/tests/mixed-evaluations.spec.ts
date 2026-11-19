import { expect, test, type Locator, type Page } from '../support/test';
import { errorMessage, flashMessage, goodMessage, login, pageTitle } from '../support/helpers';
import { idFromAjaxList, runAjaxListAction, waitForAjaxList } from '../support/ajax-list';
import { evaluationTabSection, openEvaluationTab, requestDelete } from '../support/evaltools';
import { insertMixeval, type Mixeval, type Question, type Section } from '../support/mixevals';
import { USERS } from '../support/seed';
import { MIXEVAL, noTemplatePermission } from '../support/messages';
import { expectTextIgnoringSpace } from '../support/text';

// Ported from app/tests/cases/system/add_mixeval.test.php.

// The seeded template. Root created it, it is public, and an event with
// submissions uses it.
const SEEDED = { id: '1', name: 'Default Mix Evaluation' };

const WORK_ETHIC: Question = {
  type: 'Likert',
  title: 'In your opinion, how is their work ethics?',
  instructions: 'Please be honest.',
  required: true,
  marks: '8',
  showMarks: true,
  scale: ['Poor', 'Fair', 'Good', 'Very good', 'Excellent'],
};

// With Zero Mark on, the lowest level gives 0 and the highest gives all the
// marks of the question.
const WORK_ETHIC_MARKS = ['Mark: 0', '2', '4', '6', '8'];

const CONTRIBUTION: Question = {
  type: 'Sentence',
  title: 'Which part of the project was their greatest contribution?',
  instructions: 'Choose one of the following: Research, Report, Presentation.',
  required: false,
};

const COMMENTS: Question = {
  type: 'Paragraph',
  title: 'What have they done well? How can they improve?',
  instructions: 'Please give constructive comments.',
  required: true,
};

const DISTRIBUTED: Question = {
  type: 'ScoreDropdown',
  title: 'Distributed Marks',
  instructions: 'Distribute the marks among your members.',
  required: true,
};

// One question of each type, with the Likert question first, so the questions
// added after it renumber the page while it already has its scale.
const FINAL: Mixeval = {
  name: 'Final Project Evaluation',
  availability: 'Public',
  zeroMark: true,
  selfEval: false,
  peer: [WORK_ETHIC, CONTRIBUTION, COMMENTS, DISTRIBUTED],
  self: [],
};

const FINAL_COPY: Mixeval = { ...FINAL, name: `Copy of ${FINAL.name}` };

const PRIVATE: Mixeval = {
  name: 'Private Evaluation',
  availability: 'Private',
  zeroMark: false,
  selfEval: false,
  peer: [COMMENTS],
  self: [],
};

const likert = (title: string, required: boolean, marks: string): Question => ({
  type: 'Likert',
  title,
  instructions: '',
  required,
  marks,
  scale: ['Low', 'High'],
});

const sentence = (title: string): Question => ({
  type: 'Sentence',
  title,
  instructions: '',
  required: true,
});

const WITH_SELF: Mixeval = {
  name: 'With Self-Evaluation',
  availability: 'Private',
  zeroMark: false,
  selfEval: true,
  peer: [likert('Peer optional', false, '6'), likert('Peer required', true, '4')],
  self: [likert('Self 1', true, '8'), sentence('Self 2'), sentence('Self 3')],
};

// The heading the form shows above each type of question.
const HEADERS: Record<Question['type'], string> = {
  Likert: 'Likert Answer Question',
  Sentence: 'Sentence Answer Question',
  Paragraph: 'Paragraph Answer Question',
  ScoreDropdown: 'Score Dropdown Answer Question',
};

const DELETE_QUESTION = 'Are you sure you want to delete this evaluation permanently?';
const SAVE = { name: 'Save', exact: true } as const;

const SECTION_HEADINGS: Record<Section, string> = {
  Peer: 'Peer Evaluation Questions',
  Self: 'Self-Evaluation Questions',
};

// The questions of a section of the form, with the type list and the button
// that adds a question.
function formSection(page: Page, section: Section): Locator {
  return page.getByRole('region', { name: SECTION_HEADINGS[section], exact: true });
}

// The same section, also when it is hidden, which a role lookup otherwise
// leaves out. Without it, toBeHidden() passes for a section that is not there.
function hiddenSection(page: Page, section: Section): Locator {
  return page.getByRole('region', {
    name: SECTION_HEADINGS[section],
    exact: true,
    includeHidden: true,
  });
}

// All the questions of a section, also when the section is hidden. With
// Self-Evaluation off, the form hides the self-evaluation questions but would
// still save any that are left in it.
function sectionQuestions(page: Page, section: Section): Locator {
  return hiddenSection(page, section).getByRole('group', { includeHidden: true });
}

// A question by the number that the form shows for it. Its name is the number
// and the type, such as '2. Sentence Answer Question'.
function sectionQuestion(page: Page, section: Section, number: number): Locator {
  return formSection(page, section).getByRole('group', { name: new RegExp(`^${number}\\. `) });
}

function scaleLevels(question: Locator): Locator {
  return question.getByRole('textbox', { name: 'Scale level', exact: true });
}

// The values of text boxes in the order of the page, such as the scale levels
// of a question from the lowest to the highest.
function valuesInOrder(boxes: Locator): Promise<string[]> {
  return boxes.evaluateAll((elements) =>
    elements.map((element) => (element as HTMLInputElement).value)
  );
}

// Clicks one of the controls in the heading of a question: 'Remove question',
// 'Move question up' or 'Move question down'.
async function questionControl(
  page: Page,
  section: Section,
  number: number,
  control: 'Remove question' | 'Move question up' | 'Move question down'
): Promise<void> {
  const questions = sectionQuestions(page, section);
  const before = await questions.count();
  await sectionQuestion(page, section, number)
    .getByRole('link', { name: control, exact: true })
    .click();
  if (control === 'Remove question') {
    // A removed question keeps its number until its animation ends, so the
    // next step could find it instead of the question that takes its number.
    await expect(questions).toHaveCount(before - 1);
  }
}

// Adds a question at the end of a section and fills it in.
async function addQuestion(page: Page, section: Section, question: Question): Promise<void> {
  await test.step(
    `Add the ${section.toLowerCase()} question ${question.title}`,
    async () => {
      const questions = sectionQuestions(page, section);
      const before = await questions.count();
      const form = formSection(page, section);
      await form.getByRole('combobox').selectOption({ label: question.type });
      await form.getByRole('button', { name: 'Add question', exact: true }).click();
      await expect(questions).toHaveCount(before + 1);

      const added = sectionQuestion(page, section, before + 1);
      await added.getByLabel('Question', { exact: true }).fill(question.title);
      await added.getByLabel('Instructions', { exact: true }).fill(question.instructions);
      await added.getByLabel('Required', { exact: true }).setChecked(question.required);
      if (question.type === 'Likert') {
        await added.getByLabel('Marks', { exact: true }).fill(question.marks ?? '');
        await added
          .getByLabel('Show Marks', { exact: true })
          .setChecked(question.showMarks ?? false);
        for (const [level, descriptor] of (question.scale ?? []).entries()) {
          await added.getByRole('button', { name: 'Add scale level', exact: true }).click();
          await expect(scaleLevels(added)).toHaveCount(level + 1);
          // A new level gets the focus.
          await scaleLevels(added).and(page.locator(':focus')).fill(descriptor);
        }
      }
    },
    { box: true }
  );
}

async function fillInfo(page: Page, mixeval: Mixeval): Promise<void> {
  await page.getByLabel('Name', { exact: true }).fill(mixeval.name);
  await page.getByLabel(mixeval.availability, { exact: true }).check();
  await page.getByLabel('Zero Mark', { exact: true }).setChecked(mixeval.zeroMark);
  await page.getByLabel('Self-Evaluation', { exact: true }).setChecked(mixeval.selfEval);
}

async function fillForm(page: Page, mixeval: Mixeval): Promise<void> {
  await fillInfo(page, mixeval);
  for (const question of mixeval.peer) {
    await addQuestion(page, 'Peer', question);
  }
  for (const question of mixeval.self) {
    await addQuestion(page, 'Self', question);
  }
}

async function expectSection(
  page: Page,
  section: Section,
  expected: readonly Question[]
): Promise<void> {
  await test.step(
    `Expect the ${section.toLowerCase()} questions`,
    async () => {
      // A removed question leaves the form only when its animation ends, and
      // keeps its old number until then.
      await expect(sectionQuestions(page, section), `${section} questions`).toHaveCount(
        expected.length
      );
      // The order on the page. The numbers come from the editor's own list,
      // so a move that changed the list but not the page would still number
      // the questions as expected. The titles in the tests are unique.
      await expect
        .poll(
          () =>
            valuesInOrder(sectionQuestions(page, section).getByLabel('Question', { exact: true })),
          { message: `${section} question order` }
        )
        .toEqual(expected.map((question) => question.title));
      for (const [index, question] of expected.entries()) {
        const number = index + 1;
        const shown = sectionQuestion(page, section, number);
        const label = `${section} question ${number}`;
        await expect(shown, label).toHaveAccessibleName(`${number}. ${HEADERS[question.type]}`);
        await expect(shown.getByLabel('Question', { exact: true }), label).toHaveValue(
          question.title
        );
        await expect(shown.getByLabel('Instructions', { exact: true }), label).toHaveValue(
          question.instructions
        );
        await expect(
          shown.getByLabel('Required', { exact: true }),
          `${label} required`
        ).toBeChecked({ checked: question.required });
        if (question.type === 'Likert') {
          await expect(shown.getByLabel('Marks', { exact: true }), `${label} marks`).toHaveValue(
            question.marks ?? ''
          );
          await expect(
            shown.getByLabel('Show Marks', { exact: true }),
            `${label} show marks`
          ).toBeChecked({ checked: question.showMarks ?? false });
          await expect
            .poll(() => valuesInOrder(scaleLevels(shown)), { message: `${label} scale` })
            .toEqual(question.scale ?? []);
        }
      }
    },
    { box: true }
  );
}

// Moving a question runs a 300 ms animation before the page changes. Wait for
// it, so a check that a click moved nothing cannot pass before a wrong move
// lands.
async function waitForAnimations(page: Page): Promise<void> {
  await page.waitForFunction(
    () =>
      (window as unknown as { jQuery: (selector: string) => { length: number } }).jQuery(
        ':animated'
      ).length === 0
  );
}

// The marks row under a Likert question in the view page preview. It shows
// only when Show Marks is on. The preview repeats the questions for three
// sample members, so the first one stands for all.
function previewMarks(page: Page, title: string): Locator {
  return page
    .getByRole('region', { name: 'Demo Student1', exact: true })
    .getByRole('group', { name: title })
    .getByRole('row')
    .filter({ hasText: /^\s*Mark:/ })
    .getByRole('cell');
}

// Checks every field of the edit form, which shows what was saved.
async function expectForm(page: Page, mixeval: Mixeval): Promise<void> {
  await test.step(
    `Expect the form of ${mixeval.name}`,
    async () => {
      await expect(page.getByLabel('Name', { exact: true })).toHaveValue(mixeval.name);
      await expect(page.getByLabel(mixeval.availability, { exact: true })).toBeChecked();
      await expect(page.getByLabel('Zero Mark', { exact: true })).toBeChecked({
        checked: mixeval.zeroMark,
      });
      await expect(page.getByLabel('Self-Evaluation', { exact: true })).toBeChecked({
        checked: mixeval.selfEval,
      });
      await expectSection(page, 'Peer', mixeval.peer);
      await expectSection(page, 'Self', mixeval.self);
    },
    { box: true }
  );
}

// The value the view page shows for a field, such as 'Availability'. In the
// list of details, each value is the <dd> right after the <dt> of its field.
// ARIA gives a <dd> no name, so the HTML pairing is the link to the field.
function viewDetail(page: Page, field: string): Locator {
  return page.locator(`dt:text-is("${field}") + dd`);
}

// Runs Delete from the template list, which must be the open page.
async function deleteFromList(page: Page, name: string): Promise<void> {
  await runAjaxListAction(page, name, 'Delete Evaluation', DELETE_QUESTION);
}

test.beforeEach(async ({ page }) => {
  await login(page, USERS.superAdmin);
});

test('the Add Mixed Evaluation form refuses a missing name and a duplicate name', async ({
  page,
}) => {
  await page.goto('/mixevals/add');
  await page.getByRole('button', SAVE).click();
  await expect(flashMessage(page)).toHaveText(MIXEVAL.notSaved);
  await expect(errorMessage(page)).toHaveText(['Please enter an evaluation name.']);

  await page.getByLabel('Name', { exact: true }).fill(SEEDED.name);
  await page.getByRole('button', SAVE).click();
  await expect(errorMessage(page)).toHaveText([
    'Another evaluation already exists with this name.',
  ]);
});

test('an admin adds a mixed evaluation with one question of each type', async ({ page }) => {
  await page.goto('/mixevals/add');
  await expect(pageTitle(page)).toContainText('Mixed Evaluations > Add');

  await fillForm(page, FINAL);
  // Each question is numbered in the order it was added.
  await expectSection(page, 'Peer', FINAL.peer);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);

  const id = await idFromAjaxList(page, FINAL.name, 'mixevals');
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);

  await page.goto(`/mixevals/view/${id}`);
  await expect(viewDetail(page, 'Availability')).toHaveText('Public');
  await expect(viewDetail(page, 'Zero Mark')).toHaveText('On');
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('Off');
  await expect(previewMarks(page, WORK_ETHIC.title)).toHaveText(WORK_ETHIC_MARKS);
});

// The insert must give what the form gives, or the tests that use it test
// something the app cannot make.
test('an inserted mixed evaluation shows like one added through the form', async ({
  page,
  testDatabase,
}) => {
  const id = await insertMixeval(testDatabase, FINAL);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);
  await page.goto(`/mixevals/view/${id}`);
  await expect(viewDetail(page, 'Availability')).toHaveText('Public');
  await expect(viewDetail(page, 'Zero Mark')).toHaveText('On');
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('Off');
  await expect(previewMarks(page, WORK_ETHIC.title)).toHaveText(WORK_ETHIC_MARKS);

  const withSelfId = await insertMixeval(testDatabase, WITH_SELF);
  await page.goto(`/mixevals/edit/${withSelfId}`);
  await expectForm(page, WITH_SELF);
  await page.goto(`/mixevals/view/${withSelfId}`);
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('On');
});

test('a failed save shows the questions again with their scales', async ({ page }) => {
  await page.goto('/mixevals/add');
  await fillForm(page, { ...FINAL, name: SEEDED.name });
  await page.getByRole('button', SAVE).click();
  await expect(errorMessage(page)).toHaveText([
    'Another evaluation already exists with this name.',
  ]);
  await expectSection(page, 'Peer', FINAL.peer);

  // The form that came back saves as it is.
  await page.getByLabel('Name', { exact: true }).fill(FINAL.name);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);
  const id = await idFromAjaxList(page, FINAL.name, 'mixevals');
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);
});

test('editing a mixed evaluation can remove, move and add questions and scale levels', async ({
  page,
  testDatabase,
}) => {
  const id = await insertMixeval(testDatabase, FINAL);
  await page.goto(`/mixevals/edit/${id}`);
  // Removing a question in the middle renumbers the questions after it.
  await questionControl(page, 'Peer', 2, 'Remove question');
  await expectSection(page, 'Peer', [WORK_ETHIC, COMMENTS, DISTRIBUTED]);

  // The first question cannot move up, and the last cannot move down.
  await questionControl(page, 'Peer', 1, 'Move question up');
  await questionControl(page, 'Peer', 3, 'Move question down');
  await waitForAnimations(page);
  await expectSection(page, 'Peer', [WORK_ETHIC, COMMENTS, DISTRIBUTED]);

  // Move the Likert question, with its scale, down one place, then the score
  // dropdown question up one place.
  await questionControl(page, 'Peer', 1, 'Move question down');
  await expectSection(page, 'Peer', [COMMENTS, WORK_ETHIC, DISTRIBUTED]);
  await questionControl(page, 'Peer', 3, 'Move question up');
  await expectSection(page, 'Peer', [COMMENTS, DISTRIBUTED, WORK_ETHIC]);

  // Remove the middle scale level. The levels above it move down one.
  await sectionQuestion(page, 'Peer', 3)
    .getByRole('link', { name: 'Remove Good', exact: true })
    .click();
  const trimmed: Question = { ...WORK_ETHIC, scale: ['Poor', 'Fair', 'Very good', 'Excellent'] };
  await expectSection(page, 'Peer', [COMMENTS, DISTRIBUTED, trimmed]);

  const extra: Question = { ...sentence('Anything else?'), required: false };
  await addQuestion(page, 'Peer', extra);
  await expectSection(page, 'Peer', [COMMENTS, DISTRIBUTED, trimmed, extra]);

  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);

  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, { ...FINAL, peer: [COMMENTS, DISTRIBUTED, trimmed, extra] });

  // The four levels share the marks again.
  await page.goto(`/mixevals/view/${id}`);
  await expect(previewMarks(page, WORK_ETHIC.title)).toHaveText(['Mark: 0', '2.67', '5.33', '8']);
});

test('a copy of a mixed evaluation keeps its questions under a new name', async ({
  page,
  testDatabase,
}) => {
  const id = await insertMixeval(testDatabase, FINAL);

  await page.goto(`/mixevals/copy/${id}`);
  await expectForm(page, FINAL_COPY);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);

  const copyId = await idFromAjaxList(page, FINAL_COPY.name, 'mixevals');
  expect(copyId, 'the copy is a new template').not.toBe(id);
  await page.goto(`/mixevals/edit/${copyId}`);
  await expectForm(page, FINAL_COPY);
});

test('a self-evaluation section numbers its questions apart from the peer questions', async ({
  page,
}) => {
  await page.goto('/mixevals/add');
  await expect(hiddenSection(page, 'Self')).toBeHidden();
  await fillForm(page, WITH_SELF);
  await expect(formSection(page, 'Self')).toBeVisible();
  await expectSection(page, 'Peer', WITH_SELF.peer);
  await expectSection(page, 'Self', WITH_SELF.self);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);

  // The list counts the questions of each section, and the total marks count
  // only the required peer Likert question.
  await waitForAjaxList(page);
  const row = page
    .locator('#ajaxListDiv tr')
    .filter({ has: page.getByRole('link', { name: WITH_SELF.name, exact: true }) });
  // Name, In Use, Availability, peer and self question counts, Total Marks.
  await expect(row.locator('td:nth-child(-n+6)')).toHaveText([
    WITH_SELF.name,
    'No',
    'private',
    '2',
    '3',
    '4',
  ]);

  const id = await idFromAjaxList(page, WITH_SELF.name, 'mixevals');
  await page.goto(`/mixevals/view/${id}`);
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('On');
  await expect(
    page.getByRole('heading', { name: SECTION_HEADINGS.Peer, exact: true })
  ).toBeVisible();
  await expect(
    page.getByRole('heading', { name: SECTION_HEADINGS.Self, exact: true })
  ).toBeVisible();
  // The preview asks the peer questions once for each of its three sample
  // group members, then asks the self-evaluation questions once.
  const peerTitles = [/^1\. Peer optional\s*$/, /^2\. Peer required\s*\*$/];
  await expect(page.locator('h3.question-title')).toHaveText([
    ...peerTitles,
    ...peerTitles,
    ...peerTitles,
    /^1\. Self 1\s*\*$/,
    /^2\. Self 2\s*\*$/,
    /^3\. Self 3\s*\*$/,
  ]);
});

test('questions move only within their own section', async ({ page, testDatabase }) => {
  const id = await insertMixeval(testDatabase, WITH_SELF);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, WITH_SELF);
  const [optional, required] = WITH_SELF.peer;
  const [self1, self2, self3] = WITH_SELF.self;

  // The ends of each section do not move past it.
  await questionControl(page, 'Peer', 1, 'Move question up');
  await questionControl(page, 'Peer', 2, 'Move question down');
  await questionControl(page, 'Self', 1, 'Move question up');
  await questionControl(page, 'Self', 3, 'Move question down');
  await waitForAnimations(page);
  await expectForm(page, WITH_SELF);

  await questionControl(page, 'Peer', 1, 'Move question down');
  await expectSection(page, 'Peer', [required, optional]);
  await questionControl(page, 'Self', 1, 'Move question down');
  await expectSection(page, 'Self', [self2, self1, self3]);
  await questionControl(page, 'Self', 2, 'Move question down');
  await expectSection(page, 'Self', [self2, self3, self1]);

  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, { ...WITH_SELF, peer: [required, optional], self: [self2, self3, self1] });
});

test('turning off Self-Evaluation removes the self-evaluation questions', async ({
  page,
  testDatabase,
}) => {
  const id = await insertMixeval(testDatabase, WITH_SELF);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, WITH_SELF);

  await page.getByLabel('Self-Evaluation', { exact: true }).uncheck();
  await expect(hiddenSection(page, 'Self')).toBeHidden();
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);

  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, { ...WITH_SELF, selfEval: false, self: [] });
  await page.goto(`/mixevals/view/${id}`);
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('Off');
});

test('a mixed evaluation that no event uses can be deleted', async ({ page }) => {
  // Added through the form, not inserted: PRIVATE has no scale levels, and no
  // other test saves such a template through the form.
  await page.goto('/mixevals/add');
  await fillForm(page, PRIVATE);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);
  const id = await idFromAjaxList(page, PRIVATE.name, 'mixevals');

  await deleteFromList(page, PRIVATE.name);
  await expect(goodMessage(page)).toHaveText(MIXEVAL.removed);

  await openEvaluationTab(page);
  const own = await evaluationTabSection(page, 'My Mixed Evaluations');
  await expect(own.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(own.getByRole('link', { name: PRIVATE.name, exact: true })).toHaveCount(0);
  await page.goto(`/mixevals/view/${id}`);
  await expect(flashMessage(page)).toHaveText(MIXEVAL.invalidId);
});

test('a mixed evaluation that events use cannot be deleted, and one with submissions cannot be edited', async ({
  page,
}) => {
  await page.goto('/mixevals');
  await deleteFromList(page, SEEDED.name);
  await expectTextIgnoringSpace(flashMessage(page), MIXEVAL.inUseNoDelete);

  await page.goto(`/mixevals/edit/${SEEDED.id}`);
  await expect(flashMessage(page)).toHaveText(MIXEVAL.inUse(SEEDED.name));

  await page.goto(`/mixevals/view/${SEEDED.id}`);
  await expect(viewDetail(page, 'Name')).toHaveText(SEEDED.name);
});

test("an instructor can view and copy an admin's public mixed evaluation but can neither edit nor delete it", async ({
  page,
  testDatabase,
}) => {
  const id = await insertMixeval(testDatabase, FINAL);

  await login(page, USERS.instructor1);
  await openEvaluationTab(page);
  const own = await evaluationTabSection(page, 'My Mixed Evaluations');
  await expect(own.getByRole('link', { name: FINAL.name, exact: true })).toHaveCount(0);

  await page.goto('/mixevals');
  await waitForAjaxList(page);
  await page.locator('#ajaxListDiv').getByRole('link', { name: FINAL.name, exact: true }).click();
  await expect(viewDetail(page, 'Name')).toHaveText(FINAL.name);

  await requestDelete(page, 'mixevals', id);
  await expect(flashMessage(page)).toHaveText(noTemplatePermission('delete', 'evaluation'));
  await page.goto(`/mixevals/edit/${id}`);
  await expect(flashMessage(page)).toHaveText(noTemplatePermission('edit', 'evaluation'));

  // A public template can be copied. The copy belongs to the instructor, who
  // can edit and delete it.
  await page.goto(`/mixevals/copy/${id}`);
  await expectForm(page, FINAL_COPY);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);
  const copyId = await idFromAjaxList(page, FINAL_COPY.name, 'mixevals');

  await page.goto(`/mixevals/edit/${copyId}`);
  await questionControl(page, 'Peer', 2, 'Remove question');
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(MIXEVAL.saved);
  await page.goto(`/mixevals/edit/${copyId}`);
  await expectForm(page, { ...FINAL_COPY, peer: [WORK_ETHIC, COMMENTS, DISTRIBUTED] });

  await page.goto('/mixevals');
  await deleteFromList(page, FINAL_COPY.name);
  await expect(goodMessage(page)).toHaveText(MIXEVAL.removed);

  // The refusals left the original as it was.
  await login(page, USERS.superAdmin);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);
});

test("an instructor can neither list, view nor copy an admin's private mixed evaluation", async ({
  page,
  testDatabase,
}) => {
  const id = await insertMixeval(testDatabase, PRIVATE);

  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await waitForAjaxList(page);
  const list = page.locator('#ajaxListDiv');
  // The public seeded template proves the list shows other users' templates.
  await expect(list.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(list.getByRole('link', { name: PRIVATE.name, exact: true })).toHaveCount(0);

  await page.goto(`/mixevals/view/${id}`);
  await expect(flashMessage(page)).toHaveText(noTemplatePermission('view', 'evaluation'));
  await page.goto(`/mixevals/copy/${id}`);
  await expect(flashMessage(page)).toHaveText(noTemplatePermission('copy', 'evaluation'));
});
