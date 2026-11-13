import { expect, test, type Locator, type Page } from '../support/test';
import {
  errorMessage,
  flashMessage,
  goodMessage,
  login,
  nav,
  pageTitle,
  setPostedField,
} from '../support/helpers';
import { idFromAjaxList, runAjaxListAction, waitForAjaxList } from '../support/ajax-list';
import { evaluationTabSection, openEvaluationTab, requestDelete } from '../support/evaltools';
import { USERS } from '../support/seed';

// Ported from app/tests/cases/system/add_mixeval.test.php.

// The seeded template. Root created it, it is public, and an event with
// submissions uses it. Its first question is a Likert question, and the
// lowest level of that question is its first descriptor.
const SEEDED = {
  id: '1',
  name: 'Default Mix Evaluation',
  questionId: '1',
  questionTitle: 'Participated in Team Meetings',
  descriptorId: '1',
  descriptor: 'Lowest',
};

type Question = {
  type: 'Likert' | 'Sentence' | 'Paragraph' | 'ScoreDropdown';
  title: string;
  instructions: string;
  required: boolean;
  // Likert questions only: the marks of the highest level, whether students
  // see the marks, and one descriptor per level, lowest first.
  marks?: string;
  showMarks?: boolean;
  scale?: readonly string[];
};

type Section = 'Peer' | 'Self';

type Mixeval = {
  name: string;
  availability: 'Public' | 'Private';
  zeroMark: boolean;
  selfEval: boolean;
  peer: readonly Question[];
  self: readonly Question[];
};

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
const SAVED = 'The mixed evaluation was saved successfully.';
const SAVE = { name: 'Save', exact: true } as const;

function sectionQuestions(page: Page, section: Section): Locator {
  return page.locator(`#${section === 'Peer' ? 'questions' : 'selfQues'} .MixevalMakeQuestion`);
}

// Adds a question at the end of a section and fills it in.
async function addQuestion(page: Page, section: Section, question: Question): Promise<void> {
  await test.step(
    `Add the ${section.toLowerCase()} question ${question.title}`,
    async () => {
      const questions = sectionQuestions(page, section);
      const before = await questions.count();
      const type = page.locator(`#MixevalMixevalQuestionType${section}`);
      await type.selectOption({ label: question.type });
      await type.locator('..').getByRole('button', { name: 'Add', exact: true }).click();
      await expect(questions).toHaveCount(before + 1);

      const added = questions.nth(before);
      await added.locator('input[id$="Title"]').fill(question.title);
      await added.locator('[id$="Instructions"]').fill(question.instructions);
      await added.locator('input[type="checkbox"][id$="Required"]').setChecked(question.required);
      if (question.type === 'Likert') {
        await added.locator('input[id$="Multiplier"]').fill(question.marks ?? '');
        await added
          .locator('input[type="checkbox"][id$="ShowMarks"]')
          .setChecked(question.showMarks ?? false);
        const scale = added.locator('.DescsDiv input[type="text"]');
        for (const [level, descriptor] of (question.scale ?? []).entries()) {
          await added.getByRole('button', { name: 'Add', exact: true }).click();
          await expect(scale).toHaveCount(level + 1);
          await scale.nth(level).fill(descriptor);
        }
      }
    },
    { box: true }
  );
}

async function fillInfo(page: Page, mixeval: Mixeval): Promise<void> {
  await page.locator('#MixevalName').fill(mixeval.name);
  await page.getByLabel(mixeval.availability, { exact: true }).check();
  await page.locator('#MixevalZeroMark').setChecked(mixeval.zeroMark);
  await page.locator('#self_eval').setChecked(mixeval.selfEval);
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
      const questions = sectionQuestions(page, section);
      await expect(questions, `${section} questions`).toHaveCount(expected.length);
      for (const [index, question] of expected.entries()) {
        const shown = questions.nth(index);
        const label = `${section} question ${index + 1}`;
        await expect(shown.locator('span[id^="questionIndex"]'), label).toHaveText(`${index + 1}.`);
        await expect(shown.locator('h3'), label).toContainText(HEADERS[question.type]);
        await expect(shown.locator('input[id$="Title"]'), label).toHaveValue(question.title);
        await expect(shown.locator('[id$="Instructions"]'), label).toHaveValue(
          question.instructions
        );
        await expect(
          shown.locator('input[type="checkbox"][id$="Required"]'),
          `${label} required`
        ).toBeChecked({ checked: question.required });
        if (question.type === 'Likert') {
          await expect(shown.locator('input[id$="Multiplier"]'), `${label} marks`).toHaveValue(
            question.marks ?? ''
          );
          await expect(
            shown.locator('input[type="checkbox"][id$="ShowMarks"]'),
            `${label} show marks`
          ).toBeChecked({ checked: question.showMarks ?? false });
          const scale = shown.locator('.DescsDiv input[type="text"]');
          const descriptors = question.scale ?? [];
          await expect(scale, `${label} scale`).toHaveCount(descriptors.length);
          for (const [level, descriptor] of descriptors.entries()) {
            await expect(scale.nth(level), `${label} level ${level + 1}`).toHaveValue(descriptor);
          }
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
// only when Show Marks is on.
function previewMarks(page: Page, title: string): Locator {
  return page
    .locator('.MixevalQuestion')
    .filter({ has: page.locator('h3.question-title', { hasText: title }) })
    .first()
    .locator('tr')
    .nth(2)
    .getByRole('cell');
}

// Checks every field of the edit form, which shows what was saved.
async function expectForm(page: Page, mixeval: Mixeval): Promise<void> {
  await test.step(
    `Expect the form of ${mixeval.name}`,
    async () => {
      await expect(page.locator('#MixevalName')).toHaveValue(mixeval.name);
      await expect(page.getByLabel(mixeval.availability, { exact: true })).toBeChecked();
      await expect(page.locator('#MixevalZeroMark')).toBeChecked({ checked: mixeval.zeroMark });
      await expect(page.locator('#self_eval')).toBeChecked({ checked: mixeval.selfEval });
      await expectSection(page, 'Peer', mixeval.peer);
      await expectSection(page, 'Self', mixeval.self);
    },
    { box: true }
  );
}

async function addMixeval(page: Page, mixeval: Mixeval): Promise<string> {
  return test.step(
    `Add the mixed evaluation ${mixeval.name}`,
    async () => {
      await page.goto('/mixevals/add');
      await fillForm(page, mixeval);
      await page.getByRole('button', SAVE).click();
      await expect(goodMessage(page)).toHaveText(SAVED);
      return idFromAjaxList(page, mixeval.name, 'mixevals');
    },
    { box: true }
  );
}

// The value the view page shows for a field, such as 'Availability'.
function viewDetail(page: Page, field: string): Locator {
  return page
    .locator('.MixevalView dt')
    .filter({ hasText: new RegExp(`^${field}$`) })
    .locator('xpath=following-sibling::dd[1]');
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
  await expect(flashMessage(page)).toHaveText(
    'Unable to save, please check below for error messages.'
  );
  await expect(errorMessage(page)).toHaveText(['Please enter an evaluation name.']);

  await page.locator('#MixevalName').fill(SEEDED.name);
  await page.getByRole('button', SAVE).click();
  await expect(errorMessage(page)).toHaveText([
    'Another evaluation already exists with this name.',
  ]);
});

test('an admin adds a mixed evaluation with one question of each type', async ({ page }) => {
  await nav(page).getByRole('link', { name: 'Evaluation', exact: true }).click();
  await page.getByRole('link', { name: 'Mixed Evaluations', exact: true }).click();
  await expect(page).toHaveURL(/\/mixevals$/);
  await page.getByRole('link', { name: 'Add Mixed Evaluation', exact: true }).click();
  await expect(pageTitle(page)).toContainText('Mixed Evaluations > Add');

  await fillForm(page, FINAL);
  // Each question is numbered in the order it was added.
  await expectSection(page, 'Peer', FINAL.peer);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);

  const id = await idFromAjaxList(page, FINAL.name, 'mixevals');
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);

  await page.goto(`/mixevals/view/${id}`);
  await expect(viewDetail(page, 'Availability')).toHaveText('Public');
  await expect(viewDetail(page, 'Zero Mark')).toHaveText('On');
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('Off');
  await expect(previewMarks(page, WORK_ETHIC.title)).toHaveText(WORK_ETHIC_MARKS);
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
  await page.locator('#MixevalName').fill(FINAL.name);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);
  const id = await idFromAjaxList(page, FINAL.name, 'mixevals');
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);
});

test('editing a mixed evaluation can remove, move and add questions and scale levels', async ({
  page,
}) => {
  const id = await addMixeval(page, FINAL);
  await page.goto(`/mixevals/edit/${id}`);
  const questions = sectionQuestions(page, 'Peer');

  // Removing a question in the middle renumbers the questions after it.
  await questions.nth(1).locator('h3 a.removeQ').click();
  await expectSection(page, 'Peer', [WORK_ETHIC, COMMENTS, DISTRIBUTED]);

  // The first question cannot move up, and the last cannot move down.
  await questions.nth(0).locator('h3 a.upQ').click();
  await questions.nth(2).locator('h3 a.downQ').click();
  await waitForAnimations(page);
  await expectSection(page, 'Peer', [WORK_ETHIC, COMMENTS, DISTRIBUTED]);

  // Move the Likert question, with its scale, down one place, then the score
  // dropdown question up one place.
  await questions.nth(0).locator('h3 a.downQ').click();
  await expectSection(page, 'Peer', [COMMENTS, WORK_ETHIC, DISTRIBUTED]);
  await questions.nth(2).locator('h3 a.upQ').click();
  await expectSection(page, 'Peer', [COMMENTS, DISTRIBUTED, WORK_ETHIC]);

  // Remove the middle scale level. The levels above it move down one.
  await questions.nth(2).locator('.MixevalQuestionDesc').nth(2).locator('a.removeQ').click();
  const trimmed: Question = { ...WORK_ETHIC, scale: ['Poor', 'Fair', 'Very good', 'Excellent'] };
  await expectSection(page, 'Peer', [COMMENTS, DISTRIBUTED, trimmed]);

  const extra: Question = { ...sentence('Anything else?'), required: false };
  await addQuestion(page, 'Peer', extra);
  await expectSection(page, 'Peer', [COMMENTS, DISTRIBUTED, trimmed, extra]);

  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);

  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, { ...FINAL, peer: [COMMENTS, DISTRIBUTED, trimmed, extra] });

  // The four levels share the marks again.
  await page.goto(`/mixevals/view/${id}`);
  await expect(previewMarks(page, WORK_ETHIC.title)).toHaveText(['Mark: 0', '2.67', '5.33', '8']);
});

test('a copy of a mixed evaluation keeps its questions under a new name', async ({ page }) => {
  const id = await addMixeval(page, FINAL);

  await page.goto(`/mixevals/copy/${id}`);
  await expectForm(page, FINAL_COPY);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);

  const copyId = await idFromAjaxList(page, FINAL_COPY.name, 'mixevals');
  expect(copyId, 'the copy is a new template').not.toBe(id);
  await page.goto(`/mixevals/edit/${copyId}`);
  await expectForm(page, FINAL_COPY);
});

test('a self-evaluation section numbers its questions apart from the peer questions', async ({
  page,
}) => {
  await page.goto('/mixevals/add');
  await expect(page.locator('#self-eval-ques')).toBeHidden();
  await fillForm(page, WITH_SELF);
  await expect(page.locator('#self-eval-ques')).toBeVisible();
  await expectSection(page, 'Peer', WITH_SELF.peer);
  await expectSection(page, 'Self', WITH_SELF.self);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);

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
  await expect(page.locator('#peer-title')).toHaveText('Peer Evaluation Questions');
  await expect(page.locator('#self-title')).toHaveText('Self-Evaluation Questions');
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

test('questions move only within their own section', async ({ page }) => {
  const id = await addMixeval(page, WITH_SELF);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, WITH_SELF);
  const peer = sectionQuestions(page, 'Peer');
  const self = sectionQuestions(page, 'Self');
  const [optional, required] = WITH_SELF.peer;
  const [self1, self2, self3] = WITH_SELF.self;

  // The ends of each section do not move past it.
  await peer.nth(0).locator('h3 a.upQ').click();
  await peer.nth(1).locator('h3 a.downQ').click();
  await self.nth(0).locator('h3 a.upQ').click();
  await self.nth(2).locator('h3 a.downQ').click();
  await waitForAnimations(page);
  await expectForm(page, WITH_SELF);

  await peer.nth(0).locator('h3 a.downQ').click();
  await expectSection(page, 'Peer', [required, optional]);
  await self.nth(0).locator('h3 a.downQ').click();
  await expectSection(page, 'Self', [self2, self1, self3]);
  await self.nth(1).locator('h3 a.downQ').click();
  await expectSection(page, 'Self', [self2, self3, self1]);

  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, { ...WITH_SELF, peer: [required, optional], self: [self2, self3, self1] });
});

test('turning off Self-Evaluation removes the self-evaluation questions', async ({ page }) => {
  const id = await addMixeval(page, WITH_SELF);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, WITH_SELF);

  await page.locator('#self_eval').uncheck();
  await expect(page.locator('#self-eval-ques')).toBeHidden();
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);

  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, { ...WITH_SELF, selfEval: false, self: [] });
  await page.goto(`/mixevals/view/${id}`);
  await expect(viewDetail(page, 'Self-Evaluation')).toHaveText('Off');
});

test('a mixed evaluation that no event uses can be deleted', async ({ page }) => {
  const id = await addMixeval(page, PRIVATE);

  await deleteFromList(page, PRIVATE.name);
  await expect(goodMessage(page)).toHaveText('The Mixed Evaluation was removed successfully.');

  await openEvaluationTab(page);
  const own = await evaluationTabSection(page, 'My Mixed Evaluations');
  await expect(own.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(own.getByRole('link', { name: PRIVATE.name, exact: true })).toHaveCount(0);
  await page.goto(`/mixevals/view/${id}`);
  await expect(flashMessage(page)).toHaveText('Error: Invalid Mixeval ID.');
});

test('a mixed evaluation that events use cannot be deleted, and one with submissions cannot be edited', async ({
  page,
}) => {
  await page.goto('/mixevals');
  await deleteFromList(page, SEEDED.name);
  await expect(flashMessage(page)).toContainText(
    'This evaluation is now in use, and can NOT be deleted.'
  );

  await page.goto(`/mixevals/edit/${SEEDED.id}`);
  await expect(flashMessage(page)).toHaveText(
    `${SEEDED.name} cannot be edited now that submissions have been made. Please make a copy.`
  );

  await page.goto(`/mixevals/view/${SEEDED.id}`);
  await expect(viewDetail(page, 'Name')).toHaveText(SEEDED.name);
});

test("an instructor can view and copy an admin's public mixed evaluation but can neither edit nor delete it", async ({
  page,
}) => {
  const id = await addMixeval(page, FINAL);

  await login(page, USERS.instructor1);
  await openEvaluationTab(page);
  const own = await evaluationTabSection(page, 'My Mixed Evaluations');
  await expect(own.getByRole('link', { name: FINAL.name, exact: true })).toHaveCount(0);

  await page.goto('/mixevals');
  await waitForAjaxList(page);
  await page.locator('#ajaxListDiv').getByRole('link', { name: FINAL.name, exact: true }).click();
  await expect(viewDetail(page, 'Name')).toHaveText(FINAL.name);

  await requestDelete(page, 'mixevals', id);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to delete this evaluation'
  );
  await page.goto(`/mixevals/edit/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to edit this evaluation'
  );

  // A public template can be copied. The copy belongs to the instructor, who
  // can edit and delete it.
  await page.goto(`/mixevals/copy/${id}`);
  await expectForm(page, FINAL_COPY);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);
  const copyId = await idFromAjaxList(page, FINAL_COPY.name, 'mixevals');

  await page.goto(`/mixevals/edit/${copyId}`);
  const questions = sectionQuestions(page, 'Peer');
  await questions.nth(1).locator('h3 a.removeQ').click();
  // The question leaves the form only when its animation ends.
  await expect(questions).toHaveCount(3);
  await page.getByRole('button', SAVE).click();
  await expect(goodMessage(page)).toHaveText(SAVED);
  await page.goto(`/mixevals/edit/${copyId}`);
  await expectForm(page, { ...FINAL_COPY, peer: [WORK_ETHIC, COMMENTS, DISTRIBUTED] });

  await page.goto('/mixevals');
  await deleteFromList(page, FINAL_COPY.name);
  await expect(goodMessage(page)).toHaveText('The Mixed Evaluation was removed successfully.');

  // The refusals left the original as it was.
  await login(page, USERS.superAdmin);
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, FINAL);
});

test("an instructor can neither list, view nor copy an admin's private mixed evaluation", async ({
  page,
}) => {
  const id = await addMixeval(page, PRIVATE);

  await login(page, USERS.instructor1);
  await page.goto('/mixevals');
  await waitForAjaxList(page);
  const list = page.locator('#ajaxListDiv');
  // The public seeded template proves the list shows other users' templates.
  await expect(list.getByRole('link', { name: SEEDED.name, exact: true })).toBeVisible();
  await expect(list.getByRole('link', { name: PRIVATE.name, exact: true })).toHaveCount(0);

  await page.goto(`/mixevals/view/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to view this evaluation'
  );
  await page.goto(`/mixevals/copy/${id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: You do not have permission to copy this evaluation'
  );
});

// The forms post only the ids of the template's own rows. A save that posts
// any other id is refused.
const OUT_OF_DATE =
  'The evaluation was not saved because the form was out of date. Please open it again.';

test('a mixed evaluation save that posts the ids of another template is refused', async ({
  page,
}) => {
  const own: Mixeval = {
    name: 'Own Evaluation',
    availability: 'Private',
    zeroMark: false,
    selfEval: false,
    peer: [likert('Own rating', true, '4')],
    self: [],
  };
  const form = page.locator('form').filter({ has: page.locator('#MixevalName') });
  const listed = (name: string) =>
    page.locator('#ajaxListDiv').getByRole('link', { name, exact: true });

  await login(page, USERS.instructor1);
  const id = await addMixeval(page, own);

  await page.goto('/mixevals/add');
  await fillForm(page, { ...own, name: 'Second Evaluation' });
  await setPostedField(form, 'data[Mixeval][id]', SEEDED.id);
  await page.getByRole('button', SAVE).click();
  await expect(flashMessage(page)).toHaveText(OUT_OF_DATE);
  await waitForAjaxList(page);
  await expect(listed(own.name)).toBeVisible();
  await expect(listed('Second Evaluation')).toHaveCount(0);

  const forged = [
    ['data[Mixeval][id]', SEEDED.id],
    ['data[MixevalQuestion][0][id]', SEEDED.questionId],
    ['data[MixevalQuestionDesc][0][id]', SEEDED.descriptorId],
  ] as const;
  for (const [field, value] of forged) {
    await test.step(`Edit with ${field} set to ${value}`, async () => {
      await page.goto(`/mixevals/edit/${id}`);
      await sectionQuestions(page, 'Peer').nth(0).locator('input[id$="Title"]').fill('Changed');
      await setPostedField(form, field, value);
      await page.getByRole('button', SAVE).click();
      await expect(flashMessage(page)).toHaveText(OUT_OF_DATE);
    });
  }
  await page.goto(`/mixevals/edit/${id}`);
  await expectForm(page, own);

  await page.goto(`/mixevals/copy/${SEEDED.id}`);
  await expect(page.locator('#MixevalName')).toHaveValue(`Copy of ${SEEDED.name}`);
  await setPostedField(form, 'data[Mixeval][id]', SEEDED.id);
  await page.getByRole('button', SAVE).click();
  await expect(flashMessage(page)).toHaveText(OUT_OF_DATE);
  await waitForAjaxList(page);
  await expect(listed(own.name)).toBeVisible();
  await expect(listed(`Copy of ${SEEDED.name}`)).toHaveCount(0);

  await login(page, USERS.superAdmin);
  await page.goto(`/mixevals/view/${SEEDED.id}`);
  await expect(viewDetail(page, 'Name')).toHaveText(SEEDED.name);
  const first = page.locator('.MixevalQuestion').first();
  await expect(first.locator('h3.question-title')).toHaveText(
    new RegExp(`^1\\. ${SEEDED.questionTitle}`)
  );
  await expect(first.locator('tr').first().getByRole('cell').first()).toHaveText(SEEDED.descriptor);
});
