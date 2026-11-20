import { expect, test, type Locator, type Page } from '../support/test';
import {
  flashMessage,
  labelledTable,
  login,
  pageTitle,
  withConfirmation,
} from '../support/helpers';
import type { TestDatabase } from '../support/db';
import {
  endSubmissions,
  insertEvent,
  lateDates,
  openDates,
  type NewEvent,
} from '../support/events';
import { findId, userId } from '../support/insert';
import {
  LEFT_GROUP,
  NOT_SUBMITTED,
  evaluateePanel,
  groupListCell,
  memberList,
  namedRow,
  openEvaluateePanel,
  openEventResults,
  panelHeaders,
  releaseButton,
} from '../support/results';
import { COURSES, MECH328_GROUPS, STUDENTS, TUTORS, USERS, fullName } from '../support/seed';
import {
  expectOnHome,
  expectRubricPanel,
  fillAndLeave,
  fillRubricPanel,
  insertRubricSubmission,
  openEvaluation,
  openResults,
  openRubricPanel,
  rating,
  rubricComment,
  rubricCriterion,
  rubricLevel,
  rubricPanel,
  rubricPanelHeader,
  saveRubricSection,
  saveSectionButton,
  submitEvaluation,
  submitRubricButton,
  type RubricMark,
} from '../support/student';
import { rubricSectionSaved } from '../support/messages';
import { collapse, expectTextIgnoringSpace, expectTextInOrder } from '../support/text';

// Ported from app/tests/cases/system/student_rubric.test.php. As in
// student-simple.spec.ts, the event and the submissions are inserted, except
// in the tests of the form, and endSubmissions() ends the submission period.

const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed.name;
const ALEX = STUDENTS.alex.name;
const MATT = STUDENTS.matt.name;
const TUTOR = TUTORS.tutor1.name;

// The seeded rubric has three criteria of 5 marks and five levels, so the
// level is also the grade of a criterion.
const CRITERIA = [
  'Participated in Team Meetings',
  'Was Helpful and Co-operative',
  'Submitted Work on Time',
];

function rubricEvent(changes: Partial<NewEvent> = {}): NewEvent {
  return {
    title: 'Rubric Evaluation',
    description: 'Description for the rubric eval.',
    type: 'RUBRIC',
    template: 'Term Report Evaluation',
    selfEval: 'Disabled',
    comments: 'Enabled',
    resultMode: 'Detailed',
    dates: openDates(),
    allGroups: true,
    penalties: [{ days: '1', percent: '10' }],
    ...changes,
  };
}

const TITLE = rubricEvent().title;

const ED_MARKS: RubricMark[] = [
  {
    member: ALEX,
    levels: [5, 4, 4],
    comments: ['very active in the group', 'easy to work with', 'hands in their work on time'],
    general: 'good job',
  },
  {
    member: MATT,
    levels: [3, 4, 5],
    comments: ['average participation', 'pretty co-operative', 'very hardworking'],
    general: 'amazing',
  },
];
const ALEX_MARKS: RubricMark[] = [
  { member: ED, levels: [5, 5, 5], comments: ['abc', 'def', 'ghi'], general: 'excellent' },
  {
    member: MATT,
    levels: [5, 4, 3],
    comments: ['participated', 'co-operated', 'on time'],
    general: 'super',
  },
];
const TUTOR_MARKS: RubricMark[] = [
  { member: ED, levels: [4, 3, 4], comments: ['bop', 'pop', 'cop'], general: 'good work' },
  {
    member: ALEX,
    levels: [3, 3, 3],
    comments: ['giraffe', 'lion', 'monkey'],
    general: 'acceptable',
  },
  { member: MATT, levels: [4, 3, 5], comments: ['gerbil', 'dog', 'cat'], general: 'pets' },
];

async function insertRubricEvent(
  testDatabase: TestDatabase,
  changes: Partial<NewEvent> = {}
): Promise<string> {
  return insertEvent(testDatabase, COURSES.mech328.id, rubricEvent(changes));
}

async function insertMarks(
  testDatabase: TestDatabase,
  eventId: string,
  username: string,
  marks: readonly RubricMark[],
  options: { submit?: boolean } = {}
): Promise<void> {
  await insertRubricSubmission(testDatabase, eventId, GROUP.id, username, marks, options);
}

// Ed, Alex and the tutor submit after the due date; Matt does not submit. So
// each member gets a 10% late penalty on the marks received: Ed and Alex for
// the late submission, Matt as the final penalty.
//
//   Ed receives 15 from Alex and 11 from the tutor: 13.00 - 1.30 = 11.70
//   Alex receives 13 from Ed and 9 from the tutor: 11.00 - 1.10 = 9.90
//   Matt receives 12 from each of the others: 12.00 - 1.20 = 10.80
async function submitAllButMatt(testDatabase: TestDatabase): Promise<string> {
  const eventId = await insertRubricEvent(testDatabase, { dates: lateDates() });
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);
  await insertMarks(testDatabase, eventId, STUDENTS.alex.username, ALEX_MARKS);
  await insertMarks(testDatabase, eventId, TUTORS.tutor1.username, TUTOR_MARKS);
  return eventId;
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

// The total of each evaluated member on the Basic page.
function basicResults(page: Page): Locator {
  return labelledTable(page, 'Evaluation Results');
}

// The Detail page: one row for each evaluated member, with the average of
// each criterion and the total.
function detailSummary(page: Page): Locator {
  return page.locator('#evalForm > table.standardtable');
}

// The row of one evaluator in the panel of an evaluated member: the evaluator,
// then a cell for each criterion. The general comment is in the next row.
function evaluatorRow(page: Page, evaluatee: string, evaluator: string): Locator {
  return namedRow(evaluateePanel(page, evaluatee).locator('table'), evaluator);
}

// The cells of an evaluator's row for the given marks: the evaluator, then
// the grade and the comment of each criterion. The grade of each criterion is
// out of 5.
function detailCells(evaluator: string, mark: RubricMark): (string | RegExp)[] {
  return [
    evaluator,
    ...mark.levels.map(
      (level, index) =>
        new RegExp(`^Points: Grade: ${level}\\.00 / 5\\s*Comment: ${mark.comments[index]}$`)
    ),
  ];
}

// ---------------------------------------------------------------------------
// The results page of a student.

function penaltyRating(total: string, deduction: string, final: string): string {
  return `${total} - (${deduction})* = ${final} ( )* : 10% late penalty.`;
}

// The panel with the evaluations that the teammates gave the student. Its
// header says what is not released yet.
function receivedPanel(page: Page): Locator {
  return page.locator('#panelResults');
}

// What one teammate gave the student, as the panel shows it: the text of each
// criterion cell, and the general comment. Without a release, the panel
// shows n/a for comments and no grades.
type Received = { grades?: readonly number[]; comments?: readonly string[]; general?: string };

function receivedText(received: Received): string {
  const cells = CRITERIA.map((_, index) => {
    const grade =
      received.grades !== undefined ? `Points: Grade: ${received.grades[index]}.00 ` : '';
    return `${grade}Comment: ${received.comments?.[index] ?? 'n/a'}`;
  });
  return [...cells, `General Comment: ${received.general ?? 'n/a'}`].join(' | ');
}

// The page shows the evaluations in a random order, so they are compared as
// sorted lists. The line breaks of a cell depend on its markup, so each run of
// whitespace counts as one space.
async function expectReceived(page: Page, expected: readonly Received[]): Promise<void> {
  await test.step(
    'Expect the evaluations from the teammates',
    async () => {
      const table = receivedPanel(page).locator('table');
      // Each evaluation has a row of criteria, then a row with the general
      // comment. The first row is the header.
      const rows = table.locator('xpath=./tbody/tr[td]');
      await expect(rows).toHaveCount(expected.length * 2);
      const shown: string[] = [];
      for (let index = 0; index < expected.length; index++) {
        const cells = await rows
          .nth(index * 2)
          .getByRole('cell')
          .evaluateAll((all) => all.slice(1).map((cell) => (cell as HTMLElement).innerText));
        const general = await rows.nth(index * 2 + 1).innerText();
        shown.push(collapse([...cells, general].join(' | ')));
      }
      expect(shown.sort()).toEqual(expected.map((one) => collapse(receivedText(one))).sort());
    },
    { box: true }
  );
}

function marksFor(member: string, marks: readonly RubricMark[]): RubricMark {
  const mark = marks.find((one) => one.member === member);
  if (!mark) {
    throw new Error(`no mark for ${member}`);
  }
  return mark;
}

// ---------------------------------------------------------------------------

test('a student rates each member, saves each section and submits', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertRubricEvent(testDatabase);
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);

  await expect(page.locator('ul.instructions')).toContainText('Enter Comments (Required)');
  const penalty = page.locator('#penalty');
  await expect(penalty).toBeHidden();
  await page.getByRole('link', { name: '( Show/Hide late penalty policy )' }).click();
  await expectTextIgnoringSpace(
    penalty,
    'From the due date to 1 days(s) late, 10% will be deducted. 10% is deducted afterwards.'
  );
  await expect(submitRubricButton(page)).toBeDisabled();
  await expect(rubricPanelHeader(page, ALEX)).toHaveText('Alex Student - (click to expand)');

  // A section without a level for each criterion is not sent: the form marks
  // the criterion without a level in red.
  await fillRubricPanel(page, {
    member: ALEX,
    levels: [4, 0, 5],
    comments: ['', 'very co-operative', 'very punctual'],
    general: 'awesome',
  });
  for (const criterion of [1, 2, 3]) {
    await expect(rubricCriterion(page, ALEX, criterion)).not.toHaveAttribute('color');
  }
  await saveSectionButton(page, ALEX).click();
  await expect(rubricCriterion(page, ALEX, 2)).toHaveAttribute('color', 'red');
  await expect(rubricCriterion(page, ALEX, 1)).not.toHaveAttribute('color');
  await expect(rubricCriterion(page, ALEX, 3)).not.toHaveAttribute('color');
  await expect(
    rubricPanel(page, ALEX).getByText('Please complete all the questions marked red before saving.')
  ).toBeVisible();
  await expect(flashMessage(page)).toHaveCount(0);

  // With all the levels, the section is saved. The message says what is left
  // to do.
  await rubricLevel(page, ALEX, 2, 5).check();
  await saveRubricSection(page, ALEX, rubricSectionSaved({ comments: true, submit: true }));
  await expect(submitRubricButton(page)).toBeDisabled();

  await openRubricPanel(page, ALEX);
  await fillAndLeave(rubricComment(page, ALEX, 1), 'participated lots');
  await saveRubricSection(page, ALEX, rubricSectionSaved({ submit: true }));
  await expect(rubricPanelHeader(page, ALEX)).toHaveText('Alex Student ( Saved )');
  await expectRubricPanel(page, {
    member: ALEX,
    levels: [4, 5, 5],
    comments: ['participated lots', 'very co-operative', 'very punctual'],
    general: 'awesome',
  });

  // The student can submit once every member has a level and a comment for
  // each criterion.
  await expect(submitRubricButton(page)).toBeDisabled();
  await fillRubricPanel(page, ED_MARKS[1]);
  await saveRubricSection(page, MATT, rubricSectionSaved({ submit: true }));
  await submitEvaluation(page, submitRubricButton(page));
  await expectOnHome(page, 'Submitted', TITLE, GROUP.name);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Basic');
  await expect(namedRow(basicResults(page), ALEX).getByRole('cell')).toHaveText([ALEX, '14.00']);
  await expect(namedRow(basicResults(page), MATT).getByRole('cell')).toHaveText([MATT, '12.00']);
});

// The rubric editor offers 2 to 10 levels, but the model accepts any number.
// With Zero Mark on, the one level of a one-level rubric is the lowest, so it
// gives 0.
test('the form of a rubric with one level and Zero Mark shows every member', async ({
  page,
  testDatabase,
}) => {
  const rubricId = await findId(testDatabase, 'rubrics', 'name', rubricEvent().template);
  await testDatabase.connection.query(
    `DELETE FROM rubrics_loms WHERE rubric_id = ? AND lom_num > 1;
     UPDATE rubrics SET lom_max = 1, zero_mark = 1 WHERE id = ?`,
    [rubricId, rubricId]
  );
  await insertRubricEvent(testDatabase);
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);

  // The submit button comes after the sections of all the members.
  await expect(rubricPanelHeader(page, MATT)).toBeVisible();
  await expect(submitRubricButton(page)).toBeVisible();
});

test('a student can change a submitted evaluation before the due date', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertRubricEvent(testDatabase);
  const first: RubricMark = {
    member: ALEX,
    levels: [4, 5, 5],
    comments: ['participated lots', 'very co-operative', 'very punctual'],
    general: 'awesome',
  };
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, [first, ED_MARKS[1]]);

  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  await expect(rubricPanelHeader(page, ALEX)).toHaveText('Alex Student ( Saved )');
  await expectRubricPanel(page, first);

  // A save of a submitted evaluation does not ask for a submit again.
  await fillRubricPanel(page, {
    ...ED_MARKS[0],
    comments: [ED_MARKS[0].comments[0], '', ED_MARKS[0].comments[2]],
  });
  await saveRubricSection(page, ALEX, rubricSectionSaved({ comments: true }));
  await openRubricPanel(page, ALEX);
  await fillAndLeave(rubricComment(page, ALEX, 2), ED_MARKS[0].comments[1]);
  await saveRubricSection(page, ALEX, rubricSectionSaved());
  await expectRubricPanel(page, ED_MARKS[0]);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await openEvaluateePanel(page, ALEX);
  await expect(evaluatorRow(page, ALEX, ED).getByRole('cell')).toHaveText(
    detailCells(ED, marksFor(ALEX, ED_MARKS))
  );
});

test('Submit also saves a changed section, after it asks about an incomplete one', async ({
  page,
  testDatabase,
}) => {
  // Without required comments, the student can submit before every section is
  // saved.
  const eventId = await insertRubricEvent(testDatabase, { comments: 'Disabled' });
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, [ED_MARKS[0]], { submit: false });

  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await fillRubricPanel(page, { ...ED_MARKS[1], levels: [3, 4, 0] });
  const question =
    'You have not completed all the questions for Matt Student. Would you like to discard changes?';
  // A no keeps the student on the form, with the changes.
  const asked: string[] = [];
  page.once('dialog', (dialog) => {
    asked.push(dialog.message());
    void dialog.dismiss();
  });
  await submitRubricButton(page).click();
  await expect.poll(() => asked).toEqual([question]);
  await expect(rubricCriterion(page, MATT, 3)).toHaveAttribute('color', 'red');
  await expect(rubricLevel(page, MATT, 2, 4)).toBeChecked();
  await expectOnHome(page, 'Due', TITLE, GROUP.name);

  await openEvaluation(page, TITLE, GROUP.name);
  await fillRubricPanel(page, ED_MARKS[1]);
  await submitEvaluation(page, submitRubricButton(page));

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Basic');
  await expect(namedRow(basicResults(page), MATT).getByRole('cell')).toHaveText([MATT, '12.00']);
});

test('Submit discards an incomplete section when the student agrees', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertRubricEvent(testDatabase, { comments: 'Disabled' });
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, [ED_MARKS[0]], { submit: false });

  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);
  await fillRubricPanel(page, { ...ED_MARKS[1], levels: [3, 4, 0] });
  await withConfirmation(
    page,
    'You have not completed all the questions for Matt Student. Would you like to discard changes?',
    () => submitEvaluation(page, submitRubricButton(page))
  );

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Basic');
  await expect(namedRow(basicResults(page), ALEX).getByRole('cell')).toHaveText([ALEX, '13.00']);
  await expect(namedRow(basicResults(page), MATT)).toHaveCount(0);
});

// The insert must give what the form gives. The checks are those of the form
// tests.
test('an inserted submission shows like one made through the form', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertRubricEvent(testDatabase);
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);

  await login(page, STUDENTS.ed.username);
  await expectOnHome(page, 'Submitted', TITLE, GROUP.name);
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  for (const mark of ED_MARKS) {
    await expect(rubricPanelHeader(page, mark.member)).toHaveText(`${mark.member} ( Saved )`);
    await expectRubricPanel(page, mark);
  }
  await expect(submitRubricButton(page)).toBeEnabled();

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(namedRow(detailSummary(page), ALEX).getByRole('cell')).toHaveText([
    ALEX,
    '5.00',
    '4.00',
    '4.00',
    '13.00 (86.67%)',
  ]);
  await openEvaluateePanel(page, MATT);
  await expect(evaluatorRow(page, MATT, ED).getByRole('cell')).toHaveText(
    detailCells(ED, marksFor(MATT, ED_MARKS))
  );
});

test('the Detail page of a group where nobody submitted has nothing to release', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertRubricEvent(testDatabase);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toContainText([
    `${MATT} (student)`,
  ]);
  // The heading after the release status shows that the whole page loaded.
  await expect(
    page.getByRole('heading', { name: 'Evaluation Results', exact: true })
  ).toBeVisible();
  await expect(
    page.getByRole('heading', { name: 'Evaluation Results Release Status' })
  ).toHaveCount(0);
});

test('the results show the late penalties, who did not submit, and who left the group', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await testDatabase.connection.query(
    'DELETE FROM groups_members WHERE group_id = ? AND user_id = ?',
    [GROUP.id, await userId(testDatabase, TUTORS.tutor1.username)]
  );
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Basic');
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(memberList(page, LEFT_GROUP).getByRole('cell')).toHaveText([`${TUTOR} (TA)`]);
  await expect(basicResults(page).getByRole('columnheader')).toHaveText([
    'Student Name',
    'Total : (/15.00)',
  ]);
  const basic = basicResults(page);
  await expect(namedRow(basic, ALEX).getByRole('cell')).toHaveText([ALEX, '11.00 - 1.10 = 9.90']);
  await expect(namedRow(basic, MATT).getByRole('cell')).toHaveText([MATT, '12.00 - 1.20 = 10.80']);
  await expect(namedRow(basic, ED).getByRole('cell')).toHaveText([ED, '13.00 - 1.30 = 11.70']);
  await expect(namedRow(basic, 'Group Average:').getByRole('cell')).toHaveText([
    'Group Average:',
    '10.80',
  ]);

  await page.getByRole('link', { name: 'Detail', exact: true }).click();
  await expect(page).toHaveURL(new RegExp(`/viewEvaluationResults/${eventId}/${GROUP.id}/Detail$`));
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(memberList(page, LEFT_GROUP).getByRole('cell')).toHaveText([`${TUTOR} (TA)`]);
  const summary = detailSummary(page);
  await expect(summary.getByRole('columnheader')).toHaveText([
    'Evaluatee',
    '1 (/5.0)',
    '2 (/5.0)',
    '3 (/5.0)',
    'Total (/15.00)',
  ]);
  await expect(namedRow(summary, ALEX).getByRole('cell')).toHaveText([
    ALEX,
    '4.00',
    '3.50',
    '3.50',
    '11.00 - 1.10 = 9.90 (66.00%)',
  ]);
  await expect(namedRow(summary, MATT).getByRole('cell')).toHaveText([
    MATT,
    '4.00',
    '3.67',
    '4.33',
    '12.00 - 1.20 = 10.80 (72.00%)',
  ]);
  await expect(namedRow(summary, ED).getByRole('cell')).toHaveText([
    ED,
    '4.50',
    '4.00',
    '4.50',
    '13.00 - 1.30 = 11.70 (78.00%)',
  ]);
  await expect(namedRow(summary, 'Group Average').getByRole('cell')).toHaveText([
    'Group Average',
    '4.17',
    '3.72',
    '4.11',
    '10.80',
  ]);

  // The panel of each evaluated member. The tutor is not evaluated, so has no
  // panel, and shows in blue as an evaluator who left the group.
  await expect(panelHeaders(page)).toHaveText([
    `Evaluatee: ${ALEX}`,
    `Evaluatee: ${MATT}`,
    `Evaluatee: ${ED}`,
  ]);
  const alex = evaluateePanel(page, ALEX);
  await expectTextInOrder(alex.locator('div.panelContent'), [
    '(Number of Evaluator(s): 2)',
    'Final Total:',
    '11.00 - 1.10 = 9.90 (66%)',
    'Below Group Average',
    'NOTE: 10% Late Penalty',
  ]);
  await expect(alex.locator('table').getByRole('columnheader')).toHaveText([
    'Evaluator',
    ...CRITERIA.map((criterion, index) => `(${index + 1}) ${criterion}`),
  ]);
  await openEvaluateePanel(page, ALEX);
  await expect(evaluatorRow(page, ALEX, ED).getByRole('cell')).toHaveText(
    detailCells(ED, marksFor(ALEX, ED_MARKS))
  );
  await expect(
    evaluatorRow(page, ALEX, ED).locator('xpath=following-sibling::tr[1]')
  ).toContainText('General Comment: good job');
  await expect(evaluatorRow(page, ALEX, TUTOR).getByRole('cell')).toHaveText(
    detailCells(TUTOR, marksFor(ALEX, TUTOR_MARKS))
  );
  await expect(
    evaluatorRow(page, ALEX, TUTOR).locator('xpath=following-sibling::tr[1]')
  ).toContainText('General Comment: acceptable');
  await expect(evaluatorRow(page, ALEX, TUTOR).getByRole('cell').first()).toHaveClass('blue');
  await expect(evaluatorRow(page, ALEX, ED).getByRole('cell').first()).not.toHaveClass('blue');
  // A full circle for each level of the grade, and an empty one for each
  // level above it: Ed gave 5, 4 and 4, the tutor 3, 3 and 3, of 5 each.
  await expect(alex.getByRole('img', { name: 'circle', exact: true })).toHaveCount(22);
  await expect(alex.getByRole('img', { name: 'circle_empty', exact: true })).toHaveCount(8);

  // The submission details of the group give the days late. Ed, Alex and the
  // tutor submitted late.
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: '3 Late', exact: true }).click();
  const members = labelledTable(page, 'Group Members');
  const row = (name: string) =>
    members.getByRole('row').filter({ has: page.getByRole('cell', { name, exact: true }) });
  await expect(row(ED).getByRole('cell').nth(3)).toHaveText('1 day(s)');
  await expect(row(ALEX).getByRole('cell').nth(3)).toHaveText('1 day(s)');
  await expect(row(MATT).getByRole('cell').nth(2)).toHaveText('(not submitted)');
  await expect(row(MATT).getByRole('cell').nth(3)).toHaveText('---');
});

test('a tutor who is still in the group is an evaluator, not a member to evaluate', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(memberList(page, LEFT_GROUP)).toHaveCount(0);
  await expect(panelHeaders(page)).toHaveCount(3);
  await expect(evaluateePanel(page, TUTOR)).toHaveCount(0);
  await openEvaluateePanel(page, ALEX);
  await expect(evaluatorRow(page, ALEX, TUTOR).getByRole('cell')).toHaveText(
    detailCells(TUTOR, marksFor(ALEX, TUTOR_MARKS))
  );
  await expect(evaluatorRow(page, ALEX, TUTOR).getByRole('cell').first()).not.toHaveAttribute(
    'class'
  );
});

test('the instructor releases the grades, then the comments, of one member', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await endSubmissions(testDatabase, eventId);
  const toAlex = [marksFor(ALEX, ED_MARKS), marksFor(ALEX, TUTOR_MARKS)];

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await page.getByRole('button', { name: 'Mark Peer Evaluations as Reviewed' }).click();
  await expect(
    page.getByRole('button', { name: 'Mark Peer Evaluations as Not Reviewed' })
  ).toBeVisible();
  await page.getByRole('link', { name: 'Detail', exact: true }).click();
  await releaseButton(page, ALEX, 'Release Grades').click();
  await expect(releaseButton(page, ALEX, 'Unrelease Grades')).toBeVisible();
  await expect(releaseButton(page, ED, 'Release Grades')).toBeVisible();

  // Alex sees the grades, and only the grades.
  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText(penaltyRating('11.00', '1.10', '9.90'));
  await expect(receivedPanel(page).locator('#panelResultsHeader font')).toHaveText(
    'Comments Not Released Yet.'
  );
  await expectReceived(
    page,
    toAlex.map((mark) => ({ grades: mark.levels }))
  );

  // Ed sees nothing yet.
  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText('Not Released');
  await expect(receivedPanel(page).locator('#panelResultsHeader font')).toHaveText(
    'Comments/Grades Not Released Yet.'
  );
  await expect(receivedPanel(page).locator('table td')).toHaveText(
    'Comments/Grades Not Released Yet.'
  );

  // The other way around: the comments to Alex, without the grades.
  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await releaseButton(page, ALEX, 'Unrelease Grades').click();
  await expect(releaseButton(page, ALEX, 'Release Grades')).toBeVisible();
  await releaseButton(page, ALEX, 'Release Comments').click();
  await expect(releaseButton(page, ALEX, 'Unrelease Comments')).toBeVisible();

  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText('Not Released');
  await expect(receivedPanel(page).locator('#panelResultsHeader font')).toHaveText(
    'Grades Not Released Yet.'
  );
  await expectReceived(
    page,
    toAlex.map((mark) => ({ comments: mark.comments, general: mark.general }))
  );
});

test('the instructor releases all grades, then all comments, of the event', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await endSubmissions(testDatabase, eventId);
  const toAlex = [marksFor(ALEX, ED_MARKS), marksFor(ALEX, TUTOR_MARKS)];

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');

  // Matt did not submit, so his rating has the final penalty.
  const ratings = [
    { student: STUDENTS.alex.username, rating: penaltyRating('11.00', '1.10', '9.90') },
    { student: STUDENTS.ed.username, rating: penaltyRating('13.00', '1.30', '11.70') },
    { student: STUDENTS.matt.username, rating: penaltyRating('12.00', '1.20', '10.80') },
  ];
  for (const { student, rating: shown } of ratings) {
    await login(page, student);
    await openResults(
      page,
      TITLE,
      GROUP.name,
      student === STUDENTS.matt.username ? 'Expired With No Submission' : 'Submitted'
    );
    await expect(rating(page), student).toHaveText(shown);
    await expect(receivedPanel(page).locator('#panelResultsHeader font'), student).toHaveText(
      'Comments Not Released Yet.'
    );
  }

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Unrelease All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Not Released');
  await page.getByRole('link', { name: 'Release All Comments', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Released');

  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText('Not Released');
  await expect(receivedPanel(page).locator('#panelResultsHeader font')).toHaveText(
    'Grades Not Released Yet.'
  );
  await expectReceived(
    page,
    toAlex.map((mark) => ({ comments: mark.comments, general: mark.general }))
  );
  for (const [student, section] of [
    [STUDENTS.ed.username, 'Submitted'],
    [STUDENTS.matt.username, 'Expired With No Submission'],
  ] as const) {
    await login(page, student);
    await openResults(page, TITLE, GROUP.name, section);
    await expect(receivedPanel(page).locator('#panelResultsHeader font'), student).toHaveText(
      'Grades Not Released Yet.'
    );
  }

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Unrelease All Comments', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Not Released');
});

test('an event without details shows the rating and the average of each criterion', async ({
  page,
  testDatabase,
}) => {
  const eventId = await submitAllButMatt(testDatabase);
  await testDatabase.connection.query('UPDATE events SET enable_details = 0 WHERE id = ?', [
    eventId,
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');

  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText(penaltyRating('11.00', '1.10', '9.90'));
  await expect(receivedPanel(page)).toHaveCount(0);
  const criteria = page.locator('table').filter({
    has: page.getByRole('columnheader', { name: 'Criteria', exact: true }),
  });
  const averages = ['4.00', '3.50', '3.50'];
  await expect(criteria.locator('xpath=./tbody/tr[td]')).toHaveCount(CRITERIA.length);
  for (const [index, criterion] of CRITERIA.entries()) {
    await expect(namedRow(criteria, criterion).getByRole('cell')).toHaveText([
      criterion,
      averages[index],
    ]);
  }
});

test('answers that are saved but not submitted do not count', async ({ page, testDatabase }) => {
  const eventId = await submitAllButMatt(testDatabase);
  // Matt saves a section for Ed, but does not submit.
  await insertMarks(
    testDatabase,
    eventId,
    STUDENTS.matt.username,
    [
      {
        member: ED,
        levels: [1, 1, 1],
        comments: ['math', 'English', 'history'],
        general: 'subjects',
      },
    ],
    { submit: false }
  );
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId, 'Detail');
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(namedRow(detailSummary(page), ED).getByRole('cell').last()).toHaveText(
    '13.00 - 1.30 = 11.70 (78.00%)'
  );
  await openEvaluateePanel(page, ED);
  await expect(evaluatorRow(page, ED, ALEX)).toHaveCount(1);
  await expect(evaluatorRow(page, ED, MATT)).toHaveCount(0);

  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');
  await page.getByRole('link', { name: 'Release All Comments', exact: true }).click();
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Released');

  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expect(rating(page)).toHaveText(penaltyRating('13.00', '1.30', '11.70'));
  await expectReceived(
    page,
    [marksFor(ED, ALEX_MARKS), marksFor(ED, TUTOR_MARKS)].map((mark) => ({
      grades: mark.levels,
      comments: mark.comments,
      general: mark.general,
    }))
  );
});
