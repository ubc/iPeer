import { expect, test, type Locator, type Page } from '../support/test';
import { flashMessage, login, pageTitle, resultsTable } from '../support/helpers';
import type { TestDatabase } from '../support/db';
import {
  endSubmissions,
  insertEvent,
  lateDates,
  openDates,
  type NewEvent,
} from '../support/events';
import { userId } from '../support/insert';
import { insertMixeval, type Mixeval } from '../support/mixevals';
import {
  LEFT_GROUP,
  NOT_SUBMITTED,
  evaluateePanel,
  groupListCell,
  memberList,
  namedRow,
  openEvaluateePanel,
  openEventResults,
  releaseButton,
} from '../support/results';
import { COURSES, MECH328_GROUPS, STUDENTS, TUTORS, USERS, fullName } from '../support/seed';
import {
  expectMixevalForm,
  expectOnHome,
  fillMixevalForm,
  insertMixevalSubmission,
  mixevalQuestion,
  openEvaluation,
  openResults,
  rating,
  resultAnswers,
  resultsHeading,
  submitEvaluation,
  submitMixevalButton,
  type MixevalMark,
} from '../support/student';
import { SUBMISSION } from '../support/messages';
import { expectTextInOrder } from '../support/text';

// Ported from app/tests/cases/system/student_mixeval.test.php. As in
// student-simple.spec.ts, the event and the submissions are inserted, except
// in the tests of the form, and endSubmissions() ends the submission period.

const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed.name;
const ALEX = STUDENTS.alex.name;
const MATT = STUDENTS.matt.name;
const TUTOR = TUTORS.tutor1.name;

// The seeded template has three required Likert questions of 1 mark with five
// levels, so a level is a fifth of a mark. Then a required sentence question
// (4), a required paragraph question (5) and an optional sentence question
// (6).
const QUESTIONS = [
  'Participated in Team Meetings',
  'Was Helpful and co-operative',
  'Submitted work on time',
  'Produced efficient work?',
  'Contributed?',
  'Easy to work with?',
];

function mixedEvent(changes: Partial<NewEvent> = {}): NewEvent {
  return {
    title: 'Mixed Evaluation',
    description: 'description for the evaluation',
    type: 'MIX EVALUATION',
    template: 'Default Mix Evaluation',
    selfEval: 'Disabled',
    comments: 'Disabled',
    resultMode: 'Detailed',
    dates: openDates(),
    allGroups: true,
    penalties: [{ days: '1', percent: '10' }],
    ...changes,
  };
}

const TITLE = mixedEvent().title;

const ED_MARKS: MixevalMark[] = [
  {
    member: ALEX,
    answers: { 1: 4, 2: 3, 3: 5, 4: 'absolutely', 5: 'definitely', 6: 'very easy' },
  },
  { member: MATT, answers: { 1: 5, 2: 5, 3: 4, 4: 'absolutely', 5: 'all the time' } },
];
const ALEX_MARKS: MixevalMark[] = [
  { member: ED, answers: { 1: 4, 2: 5, 3: 4, 4: 'cat', 5: 'dog', 6: 'gerbil' } },
  { member: MATT, answers: { 1: 3, 2: 3, 3: 2, 4: 'abc', 5: 'def' } },
];
const TUTOR_MARKS: MixevalMark[] = [
  { member: ED, answers: { 1: 5, 2: 5, 3: 5, 4: 'red', 5: 'blue', 6: 'green' } },
  { member: ALEX, answers: { 1: 4, 2: 4, 3: 4, 4: 'one', 5: 'two' } },
  { member: MATT, answers: { 1: 2, 2: 2, 3: 2, 4: 'right', 5: 'left', 6: 'forward' } },
];

const ANSWER_ALL = 'Please fill in all required questions before resubmitting the evaluation.';

async function insertMixedEvent(
  testDatabase: TestDatabase,
  changes: Partial<NewEvent> = {}
): Promise<string> {
  return insertEvent(testDatabase, COURSES.mech328.id, mixedEvent(changes));
}

async function insertMarks(
  testDatabase: TestDatabase,
  eventId: string,
  username: string,
  marks: readonly MixevalMark[],
  options: { submit?: boolean } = {}
): Promise<void> {
  await insertMixevalSubmission(testDatabase, eventId, GROUP.id, username, marks, options);
}

// Ed, Alex and the tutor submit after the due date; Matt does not submit. So
// each member gets a 10% late penalty on the marks received: Ed and Alex for
// the late submission, Matt as the final penalty. Only the answers of the
// members still in the group count, so in the tests where the tutor leaves:
//   Ed receives 2.60 from Alex: 2.60 - 0.26 = 2.34
//   Alex receives 2.40 from Ed: 2.40 - 0.24 = 2.16
//   Matt receives 2.80 from Ed and 1.60 from Alex: 2.20 - 0.22 = 1.98
async function submitAllButMatt(testDatabase: TestDatabase): Promise<string> {
  const eventId = await insertMixedEvent(testDatabase, { dates: lateDates() });
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);
  await insertMarks(testDatabase, eventId, STUDENTS.alex.username, ALEX_MARKS);
  await insertMarks(testDatabase, eventId, TUTORS.tutor1.username, TUTOR_MARKS);
  return eventId;
}

async function tutorLeaves(testDatabase: TestDatabase): Promise<void> {
  await testDatabase.connection.query(
    'DELETE FROM groups_members WHERE group_id = ? AND user_id = ?',
    [GROUP.id, await userId(testDatabase, TUTORS.tutor1.username)]
  );
}

// ---------------------------------------------------------------------------
// The results pages of the instructor.

async function openGroupResults(
  page: Page,
  eventId: string,
  format: 'Basic' | 'Detail' = 'Detail'
): Promise<void> {
  await page.goto(`/evaluations/viewEvaluationResults/${eventId}/${GROUP.id}/${format}`);
  await expect(pageTitle(page)).toHaveText(`${fullName(COURSES.mech328)} > ${TITLE} > Results`);
}

function basicResults(page: Page): Locator {
  return resultsTable(page, 'Evaluation Results', 'h3');
}

// The table on the Detail page with a row for each evaluated member: the
// average of each required Likert question, and the total.
function detailSummary(page: Page): Locator {
  return page
    .locator('table.standardtable')
    .filter({ has: page.getByRole('columnheader', { name: 'Total (/3.00)' }) });
}

// The answers to one question in the panel of an evaluated member, one item
// for each evaluator.
function panelAnswers(page: Page, member: string, question: number): Locator {
  return evaluateePanel(page, member).locator(
    `xpath=.//h3[starts-with(normalize-space(), '${question}. ')]/following-sibling::ul[1]//li`
  );
}

// ---------------------------------------------------------------------------
// The results page of a student.

function penaltyRating(total: string, deduction: string, final: string, percent: string): string {
  return `${total} - (${deduction})* = ${final} ( )* : 10% late penalty. (${percent}%)`;
}

// ---------------------------------------------------------------------------

test('a student answers the questions for each member and submits', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertMixedEvent(testDatabase);
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);

  const penalty = page.locator('#penalty');
  await expect(penalty).toBeHidden();
  await page.getByRole('link', { name: 'Show/Hide Late Penalty Policy' }).click();
  await expectTextInOrder(penalty, [
    'From the due date to 1 days(s) late, 10% will be deducted.',
    '10% is deducted afterwards.',
  ]);

  // Each member has the questions, with their instructions, and a star on
  // each required question.
  for (const member of [ALEX, MATT]) {
    const mark = { member, answers: {} };
    const first = await mixevalQuestion(page, mark, 1);
    await expect(first.locator('h3')).toHaveText(`1. ${QUESTIONS[0]} *`);
    await expect(first.locator('p.help')).toHaveText('Please rate performance.');
    const fifth = await mixevalQuestion(page, mark, 5);
    await expect(fifth.locator('p.help')).toHaveText('Please give a paragraph answer.');
    const sixth = await mixevalQuestion(page, mark, 6);
    await expect(sixth.locator('h3')).toHaveText(`6. ${QUESTIONS[5]}`);
  }
  await expect(page.locator('form span.required')).toHaveCount(10);

  // Without an answer to a required question, the submit saves the answers
  // but does not count as a submission.
  const [toAlex] = ED_MARKS;
  const withoutParagraph: MixevalMark = {
    member: MATT,
    answers: { 1: 5, 2: 5, 3: 4, 4: 'absolutely', 6: 'very easy' },
  };
  await fillMixevalForm(page, [toAlex, withoutParagraph]);
  await submitMixevalButton(page).click();
  await expect(flashMessage(page)).toHaveText(SUBMISSION.mixevalSavedNotSubmitted);
  await expect(page).toHaveURL(/\/evaluations\/makeEvaluation\/\d+\/\d+$/);
  await expectMixevalForm(page, [toAlex, withoutParagraph]);
  await expectOnHome(page, 'Due', TITLE, GROUP.name);

  await openEvaluation(page, TITLE, GROUP.name);
  await fillMixevalForm(page, [{ member: MATT, answers: { 5: 'definitely' } }]);
  await submitEvaluation(page, submitMixevalButton(page));
  await expectOnHome(page, 'Submitted', TITLE, GROUP.name);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(namedRow(detailSummary(page), ALEX).getByRole('cell')).toHaveText([
    ALEX,
    '0.80',
    '0.60',
    '1.00',
    '2.40 (80.00%)',
  ]);
  await expect(namedRow(detailSummary(page), MATT).getByRole('cell')).toHaveText([
    MATT,
    '1.00',
    '1.00',
    '0.80',
    '2.80 (93.33%)',
  ]);
});

test('a change to a submitted evaluation needs an answer to every required question', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertMixedEvent(testDatabase);
  const first: MixevalMark = {
    member: MATT,
    answers: { 1: 5, 2: 5, 3: 4, 4: 'absolutely', 5: 'definitely', 6: 'very easy' },
  };
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, [ED_MARKS[0], first]);

  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  await expectMixevalForm(page, [ED_MARKS[0], first]);

  // The form refuses to send a submitted evaluation with a required question
  // left empty.
  await fillMixevalForm(page, [{ member: MATT, answers: { 5: '' } }]);
  const asked: string[] = [];
  page.once('dialog', (dialog) => {
    asked.push(dialog.message());
    void dialog.accept();
  });
  await submitMixevalButton(page).click();
  await expect.poll(() => asked).toEqual([ANSWER_ALL]);
  // Nothing was sent: the saved answer is still there.
  await page.reload();
  await expectMixevalForm(page, [first]);

  // An optional question can be emptied.
  await fillMixevalForm(page, [{ member: MATT, answers: { 5: 'all the time', 6: '' } }]);
  await submitEvaluation(page, submitMixevalButton(page));
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  await expectMixevalForm(page, [{ member: MATT, answers: { ...ED_MARKS[1].answers, 6: '' } }]);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await openEvaluateePanel(page, MATT);
  await expect(panelAnswers(page, MATT, 5)).toHaveText([/all the time$/]);
  await expect(panelAnswers(page, MATT, 6)).toHaveText(['N/A']);
});

// The insert must give what the form gives. The checks are those of the form
// tests.
test('an inserted submission shows like one made through the form', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertMixedEvent(testDatabase);
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);

  await login(page, STUDENTS.ed.username);
  await expectOnHome(page, 'Submitted', TITLE, GROUP.name);
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  await expectMixevalForm(page, ED_MARKS);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(namedRow(detailSummary(page), ALEX).getByRole('cell')).toHaveText([
    ALEX,
    '0.80',
    '0.60',
    '1.00',
    '2.40 (80.00%)',
  ]);
  await expect(namedRow(detailSummary(page), MATT).getByRole('cell')).toHaveText([
    MATT,
    '1.00',
    '1.00',
    '0.80',
    '2.80 (93.33%)',
  ]);
});

test('the results show the late penalties, who did not submit, and who left the group', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await tutorLeaves(testDatabase);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Basic');
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(memberList(page, LEFT_GROUP).getByRole('cell')).toHaveText([`${TUTOR} (TA)`]);
  const basic = basicResults(page);
  await expect(basic.getByRole('columnheader')).toHaveText(['Student Name:', 'Total:( /3.00)']);
  await expect(namedRow(basic, ED).getByRole('cell')).toHaveText([
    ED,
    '2.60 - 0.26 = 2.34 (78.00%)',
  ]);
  await expect(namedRow(basic, ALEX).getByRole('cell')).toHaveText([
    ALEX,
    '2.40 - 0.24 = 2.16 (72.00%)',
  ]);
  await expect(namedRow(basic, MATT).getByRole('cell')).toHaveText([
    MATT,
    '2.20 - 0.22 = 1.98 (66.00%)',
  ]);
  await expect(namedRow(basic, 'Group Average').getByRole('cell')).toHaveText([
    'Group Average',
    '2.16',
  ]);

  await page.getByRole('link', { name: 'Detail', exact: true }).click();
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(memberList(page, LEFT_GROUP).getByRole('cell')).toHaveText([`${TUTOR} (TA)`]);
  const summary = detailSummary(page);
  await expect(summary.getByRole('columnheader')).toHaveText([
    'Evaluatee',
    '1 (/1.0)',
    '2 (/1.0)',
    '3 (/1.0)',
    'Total (/3.00)',
  ]);
  await expect(namedRow(summary, ED).getByRole('cell')).toHaveText([
    ED,
    '0.80',
    '1.00',
    '0.80',
    '2.60 - 0.26 = 2.34 (78.00%)',
  ]);
  await expect(namedRow(summary, ALEX).getByRole('cell')).toHaveText([
    ALEX,
    '0.80',
    '0.60',
    '1.00',
    '2.40 - 0.24 = 2.16 (72.00%)',
  ]);
  await expect(namedRow(summary, MATT).getByRole('cell')).toHaveText([
    MATT,
    '0.80',
    '0.80',
    '0.60',
    '2.20 - 0.22 = 1.98 (66.00%)',
  ]);
  await expect(namedRow(summary, 'Group Average').getByRole('cell')).toHaveText([
    'Group Average',
    '0.80',
    '0.80',
    '0.80',
    '2.16',
  ]);

  // The panel of Ed: the total, then the answers of each evaluator.
  await openEvaluateePanel(page, ED);
  const ed = evaluateePanel(page, ED);
  await expect(ed.locator('div.panelContent > b')).toHaveText(
    'Final Total: 2.60 - 0.26 = 2.34 (78%) << Above Group Average >>'
  );
  await expect(ed.locator('div.panelContent > font')).toHaveText('10%');
  // A Likert answer shows the scale with the level of the evaluator, the
  // grade, and the descriptor of the level when it has one.
  await expect(panelAnswers(page, ED, 1).locator('label.name')).toHaveText([`${ALEX}:`]);
  await expect(panelAnswers(page, ED, 1).locator('label.grade')).toHaveText(['Grade: 0.80 / 1']);
  await expect(panelAnswers(page, ED, 2).locator('label.grade, label.desc')).toHaveText([
    'Grade: 1.00 / 1',
    '(Highest)',
  ]);
  await expect(panelAnswers(page, ED, 1).getByRole('radio', { checked: true })).toHaveAttribute(
    'value',
    '0.80'
  );
  await expect(panelAnswers(page, ED, 1).getByRole('radio', { checked: true })).toBeDisabled();
  await expect(panelAnswers(page, ED, 4)).toHaveText([`${ALEX}:cat`]);
  await expect(panelAnswers(page, ED, 5)).toHaveText([`${ALEX}:dog`]);
  await expect(panelAnswers(page, ED, 6)).toHaveText([`${ALEX}:gerbil`]);

  // The submission details of the group give the days late. Ed, Alex and the
  // tutor submitted late.
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: '3 Late', exact: true }).click();
  const members = resultsTable(page, 'Group Members', 'h3');
  const row = (name: string) =>
    members.getByRole('row').filter({ has: page.getByRole('cell', { name, exact: true }) });
  await expect(row(ED).getByRole('cell').nth(3)).toHaveText('1 day(s)');
  await expect(row(ALEX).getByRole('cell').nth(3)).toHaveText('1 day(s)');
  await expect(row(MATT).getByRole('cell').nth(2)).toHaveText('(not submitted)');
  await expect(row(MATT).getByRole('cell').nth(3)).toHaveText('---');
});

test('the instructor releases the grades, then the comments, of one member', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await tutorLeaves(testDatabase);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await page.getByRole('button', { name: 'Mark Peer Evaluations as Reviewed' }).click();
  await expect(
    page.getByRole('button', { name: 'Mark Peer Evaluations as Not Reviewed' })
  ).toBeVisible();
  await openEventResults(page, eventId);
  await expect(await groupListCell(page, GROUP.name, 'Status')).toHaveText('Reviewed');

  await openGroupResults(page, eventId, 'Detail');
  await releaseButton(page, ED, 'Release Grades').click();
  await expect(releaseButton(page, ED, 'Unrelease Grades')).toBeVisible();

  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText(penaltyRating('2.60', '0.26', '2.34', '78'));
  await expect(resultsHeading(page)).toHaveText('Questions - Comments Not Released Yet');
  await expect(resultAnswers(page, 1).locator('label.grade')).toHaveText(['Grade: 0.80 / 1']);
  await expect(resultAnswers(page, 4)).toHaveText(['Comments Not Released Yet']);

  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText('Not Released');
  await expect(resultsHeading(page)).toHaveText('Questions - Comments/Grades Not Released Yet');
  await expect(resultAnswers(page, 1)).toHaveText(['Grades Not Released Yet']);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await releaseButton(page, ED, 'Unrelease Grades').click();
  await expect(releaseButton(page, ED, 'Release Grades')).toBeVisible();
  await releaseButton(page, ED, 'Release Comments').click();
  await expect(releaseButton(page, ED, 'Unrelease Comments')).toBeVisible();

  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText('Not Released');
  await expect(resultsHeading(page)).toHaveText('Questions - Grades Not Released Yet');
  await expect(resultAnswers(page, 1)).toHaveText(['Grades Not Released Yet']);
  await expect(resultAnswers(page, 4)).toHaveText(['cat']);
  await expect(resultAnswers(page, 5)).toHaveText(['dog']);
  await expect(resultAnswers(page, 6)).toHaveText(['gerbil']);

  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(resultsHeading(page)).toHaveText('Questions - Comments/Grades Not Released Yet');
});

test('the instructor releases all comments, then all grades, of the event', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await tutorLeaves(testDatabase);
  await endSubmissions(testDatabase, eventId);
  const students = [
    { username: STUDENTS.ed.username, section: 'Submitted' },
    { username: STUDENTS.alex.username, section: 'Submitted' },
    { username: STUDENTS.matt.username, section: 'Expired With No Submission' },
  ] as const;

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Comments', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Released');
  for (const { username, section } of students) {
    await login(page, username);
    await openResults(page, TITLE, GROUP.name, section);
    await expect(resultsHeading(page), username).toHaveText('Questions - Grades Not Released Yet');
  }

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Unrelease All Comments', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Not Released');
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');

  // Matt did not submit, so his rating has the final penalty.
  const ratings = [
    penaltyRating('2.60', '0.26', '2.34', '78'),
    penaltyRating('2.40', '0.24', '2.16', '72'),
    penaltyRating('2.20', '0.22', '1.98', '66'),
  ];
  for (const [index, { username, section }] of students.entries()) {
    await login(page, username);
    await openResults(page, TITLE, GROUP.name, section);
    await expect(rating(page), username).toHaveText(ratings[index]);
    await expect(resultsHeading(page), username).toHaveText(
      'Questions - Comments Not Released Yet'
    );
  }

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Unrelease All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Not Released');
});

test('an event without details shows the rating and the average of each question', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await tutorLeaves(testDatabase);
  await testDatabase.connection.query('UPDATE events SET enable_details = 0 WHERE id = ?', [
    eventId,
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');

  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText(penaltyRating('2.60', '0.26', '2.34', '78'));
  await expect(page.locator('#mixeval_result h3')).toHaveText([
    `1. ${QUESTIONS[0]} *`,
    `2. ${QUESTIONS[1]} *`,
    `3. ${QUESTIONS[2]} *`,
  ]);
  await expect(resultAnswers(page, 1)).toHaveText(['Average: 0.80 / 1']);
  await expect(resultAnswers(page, 2)).toHaveText(['Average: 1.00 / 1']);
  await expect(resultAnswers(page, 3)).toHaveText(['Average: 0.80 / 1']);
});

// The results count the answers of every member of the group, also answers
// that were saved but not submitted. Rubric results count only submitted
// answers (see student-rubric.spec.ts). This test keeps the current
// behaviour, as the Selenium test did.
test('answers that are saved but not submitted count in the results', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await tutorLeaves(testDatabase);
  // Matt answers for Ed, but not for Alex, so the answers are only saved.
  await insertMarks(
    testDatabase,
    eventId,
    STUDENTS.matt.username,
    [{ member: ED, answers: { 1: 2, 2: 2, 3: 2, 4: 'right', 5: 'acute', 6: 'obtuse' } }],
    { submit: false }
  );
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(namedRow(detailSummary(page), ED).getByRole('cell').last()).toHaveText(
    '1.90 - 0.19 = 1.71 (57.00%)'
  );
});

// ---------------------------------------------------------------------------
// Templates with other kinds of questions.

const DISTRIBUTED: Mixeval = {
  name: 'Final Project Evaluation',
  availability: 'Public',
  zeroMark: false,
  selfEval: false,
  peer: [
    {
      type: 'ScoreDropdown',
      title: 'Distributed Marks',
      instructions: 'Distribute the marks among your members.',
      required: true,
    },
  ],
  self: [],
};

test('the points of a score dropdown question must add up to 10 for each member', async ({
  page,
  testDatabase,
}) => {
  await insertMixeval(testDatabase, DISTRIBUTED, USERS.instructor1);
  const eventId = await insertMixedEvent(testDatabase, { template: DISTRIBUTED.name });

  // The tutor evaluates the three students: 0 to 30 points each.
  await login(page, TUTORS.tutor1.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await expect(page.locator('form select')).toHaveCount(3);
  await expect(page.locator('form select').first().locator('option')).toHaveCount(31);

  // Ed evaluates two members: 20 points, 0 to 20 each.
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await expect(page.locator('form select')).toHaveCount(2);
  await expect(page.locator('form select').first().locator('option')).toHaveCount(21);
  const question =
    'Please make sure that the total of the grades in the drop-downs equals 20 and then press "Submit" again.';
  for (const points of [0, 11]) {
    await fillMixevalForm(page, [
      { member: ALEX, answers: { 1: points } },
      { member: MATT, answers: { 1: points } },
    ]);
    const asked: string[] = [];
    page.once('dialog', (dialog) => {
      asked.push(dialog.message());
      void dialog.accept();
    });
    await submitMixevalButton(page).click();
    await expect.poll(() => asked, `${points} points each`).toEqual([question]);
    await expect(submitMixevalButton(page)).toBeVisible();
  }
  // Nothing was sent.
  await expectOnHome(page, 'Due', TITLE, GROUP.name);
  await openEvaluation(page, TITLE, GROUP.name);
  await fillMixevalForm(page, [
    { member: ALEX, answers: { 1: 12 } },
    { member: MATT, answers: { 1: 8 } },
  ]);
  await submitEvaluation(page, submitMixevalButton(page));

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await openEvaluateePanel(page, ALEX);
  await expect(panelAnswers(page, ALEX, 1)).toHaveText([`${ED}:12.00`]);
});

const WITH_SELF: Mixeval = {
  name: 'With Self-Evaluation',
  availability: 'Private',
  zeroMark: false,
  selfEval: true,
  peer: [
    {
      type: 'Likert',
      title: 'peer likert question',
      instructions: '',
      required: true,
      marks: '5',
      scale: ['Low', 'Middle', 'High'],
    },
  ],
  self: [
    {
      type: 'Likert',
      title: 'self likert question',
      instructions: '',
      required: true,
      marks: '7',
      scale: ['Low', 'High'],
    },
    { type: 'Sentence', title: 'self sentence question', instructions: '', required: true },
  ],
};

test('students answer the self-evaluation questions, and tutors do not get them', async ({
  page,
  testDatabase,
}) => {
  await insertMixeval(testDatabase, WITH_SELF, USERS.instructor1);
  const eventId = await insertMixedEvent(testDatabase, {
    template: WITH_SELF.name,
    selfEval: 'Enabled',
  });

  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await expect(page.getByRole('heading', { name: 'Self-Evaluation Questions' })).toBeVisible();
  // With self-evaluation on, the student also rates themself in the peer
  // questions.
  await fillMixevalForm(page, [
    { member: ED, answers: { 1: 2 } },
    { member: ALEX, answers: { 1: 3 } },
    { member: MATT, answers: { 1: 1 } },
    { member: ED, self: true, answers: { 2: 2, 3: 'I did great.' } },
  ]);
  await submitEvaluation(page, submitMixevalButton(page));

  await login(page, STUDENTS.alex.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await fillMixevalForm(page, [
    { member: ED, answers: { 1: 3 } },
    { member: ALEX, answers: { 1: 3 } },
    { member: MATT, answers: { 1: 2 } },
    { member: ALEX, self: true, answers: { 2: 2, 3: 'It was amazing.' } },
  ]);
  await submitEvaluation(page, submitMixevalButton(page));

  await login(page, TUTORS.tutor1.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await expect(page.getByRole('heading', { name: 'Peer Evaluation Questions' })).toBeVisible();
  await expect(page.getByRole('heading', { name: 'Self-Evaluation Questions' })).toHaveCount(0);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Basic');
  const self = resultsTable(page, 'Self-Evaluation', 'h3');
  await expect(namedRow(self, 'self likert question').getByRole('cell')).toHaveText([
    'self likert question',
    '7.00 / 7',
  ]);
  // The peer question is worth 5, a third for each level. The students rated
  // themselves too, and the tutor is not evaluated.
  const basic = basicResults(page);
  await expect(basic.getByRole('columnheader')).toHaveText(['Student Name:', 'Total:( /5.00)']);
  await expect(namedRow(basic, ED).getByRole('cell')).toHaveText([ED, '4.17 (83.30%)']);
  await expect(namedRow(basic, ALEX).getByRole('cell')).toHaveText([ALEX, '5.00 (100.00%)']);
  await expect(namedRow(basic, MATT).getByRole('cell')).toHaveText([MATT, '2.50 (50.00%)']);
  await expect(namedRow(basic, TUTOR).getByRole('cell')).toHaveText([TUTOR, 'N/A']);

  await page.getByRole('link', { name: 'Detail', exact: true }).click();
  const selfPanel = page.locator('#panelSelf');
  await expect(selfPanel.locator('#panelSelfHeader')).toHaveText('Self-Evaluation');
  await expect(
    selfPanel.locator(
      "xpath=.//h3[starts-with(normalize-space(), '2. ')]/following-sibling::ul[1]//li"
    )
  ).toHaveText([`${ED}:I did great.`, `${ALEX}:It was amazing.`]);

  // The student sees their own self-evaluation in the results.
  await endSubmissions(testDatabase, eventId);
  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(page.locator('#mixeval_result > h2').last()).toHaveText('Self-Evaluation');
  // The own answers show, but the sentence answer only after a release of
  // the comments.
  const ownAnswer = (question: number) =>
    page
      .locator('#mixeval_result')
      .last()
      .locator(
        `xpath=./h3[starts-with(normalize-space(), '${question}. ')]/following-sibling::ul[1]//li`
      );
  await expect(ownAnswer(1).getByRole('radio', { checked: true })).toHaveAttribute('value', '7.00');
  await expect(ownAnswer(2)).toHaveText(['n/a']);
});
