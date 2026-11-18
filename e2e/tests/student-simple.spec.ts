import { expect, test, type Locator, type Page } from '../support/test';
import { flashMessage, goodMessage, login, pageTitle, resultsTable } from '../support/helpers';
import { waitForAjaxList } from '../support/ajax-list';
import type { TestDatabase } from '../support/db';
import {
  endSubmissions,
  insertEvent,
  lateDates,
  openDates,
  type NewEvent,
} from '../support/events';
import { userId } from '../support/insert';
import {
  LEFT_GROUP,
  NOT_SUBMITTED,
  groupListCell,
  memberList,
  openEventResults,
} from '../support/results';
import { COURSES, MECH328_GROUPS, STUDENTS, TUTORS, USERS, fullName } from '../support/seed';
import {
  expectOnHome,
  expectSimpleForm,
  expectSimpleResult,
  expectSimpleStatus,
  fillAndLeave,
  fillSimpleEvaluation,
  insertSimpleSubmission,
  openEvaluation,
  openResults,
  setRelativeWeight,
  simplePoints,
  submitButton,
  submitEvaluation,
  type SimpleMark,
} from '../support/student';
import { SUBMISSION } from '../support/messages';
import { expectTextIgnoringSpace } from '../support/text';

// Ported from app/tests/cases/system/student_simple.test.php. The event is
// inserted, because the event form is not the subject here (events.spec.ts
// tests it). The Selenium test changed the dates of the event through the
// form to end the submissions; endSubmissions() moves them in the database.
// The submissions are inserted too, except in the tests of the form.

const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed.name;
const ALEX = STUDENTS.alex.name;
const MATT = STUDENTS.matt.name;
const TUTOR = TUTORS.tutor1.name;
// The members that a student or tutor of Reapers evaluates, in the order of
// the result columns. Tutors are not evaluated.
const EVALUATEES = [ED, ALEX, MATT];

// The seeded template gives 100 points for each member that a student
// evaluates. Self-evaluation is off, so a student of Reapers evaluates two
// members and distributes 200 points.
function simpleEvent(changes: Partial<NewEvent> = {}): NewEvent {
  return {
    title: 'Simple Evaluation',
    description: 'Description for the Eval.',
    type: 'SIMPLE',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disabled',
    comments: 'Enabled',
    resultMode: 'Detailed',
    dates: openDates(),
    allGroups: true,
    penalties: [{ days: '1', percent: '10' }],
    ...changes,
  };
}

const TITLE = simpleEvent().title;

const ED_MARKS: SimpleMark[] = [
  { member: ALEX, points: 120, comment: 'A very great group member' },
  { member: MATT, points: 80, comment: 'Very responsible.' },
];
const ALEX_MARKS: SimpleMark[] = [
  { member: ED, points: 95, comment: 'Very hardworking' },
  { member: MATT, points: 105, comment: 'A great leader' },
];
const TUTOR_MARKS: SimpleMark[] = [
  { member: ED, points: 100, comment: 'Does his fair share of work' },
  { member: ALEX, points: 100, comment: 'Knows what he is doing' },
  { member: MATT, points: 100, comment: 'Does his work efficiently' },
];

const ALL_FILLED = 'All points are allocated. All comments are filled.';

async function insertSimpleEvent(
  testDatabase: TestDatabase,
  changes: Partial<NewEvent> = {}
): Promise<string> {
  return insertEvent(testDatabase, COURSES.mech328.id, simpleEvent(changes));
}

// A submission for the tests whose subject is not the form. The tests of the
// form fill it in.
async function insertMarks(
  testDatabase: TestDatabase,
  eventId: string,
  username: string,
  marks: readonly SimpleMark[]
): Promise<void> {
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, username, marks);
}

// Ed and Alex submit; Matt does not. After the end of the submissions, Matt
// gets the final late penalty on the marks he received.
async function submitEdAndAlex(testDatabase: TestDatabase, eventId: string) {
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);
  await insertMarks(testDatabase, eventId, STUDENTS.alex.username, ALEX_MARKS);
  await endSubmissions(testDatabase, eventId);
}

// ---------------------------------------------------------------------------
// The results page of the instructor.

async function openGroupResults(page: Page, eventId: string): Promise<void> {
  await page.goto(`/evaluations/viewEvaluationResults/${eventId}/${GROUP.id}`);
  await expect(pageTitle(page)).toHaveText(`${fullName(COURSES.mech328)} > ${TITLE} > Results`);
}

// The table of marks: one row for each evaluator, one column for each
// evaluated member, then rows such as 'Total'.
function marksTable(page: Page): Locator {
  return resultsTable(page, 'Evaluation Results', 'h3');
}

// The second header row names the evaluated members.
function memberColumns(page: Page): Locator {
  return marksTable(page).locator('xpath=./tbody/tr[2]/th');
}

// The header cell of a row, such as an evaluator or 'Total'. The header row
// of the members has no data cells, so it never matches.
function rowHeader(page: Page, header: string): Locator {
  return marksTable(page).locator(`xpath=./tbody/tr[td]/th[1][normalize-space()='${header}']`);
}

function rowCells(page: Page, header: string): Locator {
  return rowHeader(page, header).locator('xpath=./following-sibling::td');
}

async function expectRow(page: Page, header: string, cells: string[]): Promise<void> {
  await expect(rowCells(page, header), header).toHaveText(cells);
}

// The buttons that release or unrelease the grade of each member. They show
// only after the end of the submissions.
function gradeReleaseButtons(page: Page): Locator {
  return marksTable(page)
    .locator("xpath=./tbody/tr[td[1][normalize-space()='Grade Release']]")
    .getByRole('button');
}

function evaluatorHeading(page: Page, evaluator: string): Locator {
  return page.getByRole('heading', { level: 3, name: `Evaluator: ${evaluator}`, exact: true });
}

// The row of one evaluated member in the comments of an evaluator.
function commentRow(page: Page, evaluator: string, evaluatee: string): Locator {
  return evaluatorHeading(page, evaluator)
    .locator('xpath=following-sibling::table[1]')
    .getByRole('row')
    .filter({ has: page.getByRole('cell', { name: evaluatee, exact: true }) });
}

async function expectComments(page: Page, evaluator: string, marks: readonly SimpleMark[]) {
  await test.step(
    `Expect the comments of ${evaluator}`,
    async () => {
      for (const mark of marks) {
        await expect(commentRow(page, evaluator, mark.member).getByRole('cell').nth(1)).toHaveText(
          mark.comment
        );
      }
    },
    { box: true }
  );
}

// ---------------------------------------------------------------------------

test('a student weighs the members, distributes the points and submits', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase);
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

  await expect(submitButton(page)).toBeDisabled();
  await expectSimpleStatus(
    page,
    'Please allocate 200 more points. There are still 2 comments to be filled.'
  );

  // Distribute shares the points by the weights: 7 and 3 of 10.
  await setRelativeWeight(page, ALEX, 7);
  await setRelativeWeight(page, MATT, 3);
  await page.getByRole('button', { name: 'Distribute', exact: true }).click();
  await expect(simplePoints(page, ALEX)).toHaveValue('140');
  await expect(simplePoints(page, MATT)).toHaveValue('60');
  await expect(page.locator('#total')).toHaveText('200');
  await expectSimpleStatus(
    page,
    'All points are allocated. There are still 2 comments to be filled.'
  );
  await expect(submitButton(page)).toBeDisabled();

  const marks: SimpleMark[] = [
    { member: ALEX, points: 140, comment: 'A very great group member' },
    { member: MATT, points: 60, comment: 'Very responsible.' },
  ];
  await fillSimpleEvaluation(page, marks);
  await expectSimpleStatus(page, ALL_FILLED);
  await submitEvaluation(page);
  await expectOnHome(page, 'Submitted', TITLE, GROUP.name);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await expectRow(page, ED, ['-', '140.00', '60.00']);
  await expectComments(page, ED, marks);
});

test('a student can change a submitted evaluation before the due date', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase);
  const first: SimpleMark[] = [
    { ...ED_MARKS[0], points: 140 },
    { ...ED_MARKS[1], points: 60 },
  ];
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, first);

  // The form opens with the saved values.
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  await expectSimpleForm(page, first);

  await fillAndLeave(simplePoints(page, ALEX), '120');
  await expectSimpleStatus(page, 'Please allocate 20 more points. All comments are filled.');
  await expect(submitButton(page)).toBeDisabled();
  await fillAndLeave(simplePoints(page, MATT), '80');
  await expectSimpleStatus(page, ALL_FILLED);
  await submitEvaluation(page);

  // The new marks replace the old ones: each member has one evaluator.
  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await expectRow(page, ED, ['-', '120.00', '80.00']);
  await expectRow(page, '# of Evaluator(s)', ['-', '1', '1']);
  await expectComments(page, ED, ED_MARKS);
});

// The insert must give what the form gives, or the tests that use it test
// something the app cannot make. The checks are those of the form tests.
test('an inserted submission shows like one made through the form', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase);
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);

  await login(page, STUDENTS.ed.username);
  await expectOnHome(page, 'Submitted', TITLE, GROUP.name);
  await openEvaluation(page, TITLE, GROUP.name, 'Submitted');
  await expectSimpleForm(page, ED_MARKS);
  await expectSimpleStatus(page, ALL_FILLED);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await expectRow(page, ED, ['-', '120.00', '80.00']);
  await expectRow(page, '# of Evaluator(s)', ['-', '1', '1']);
  await expectComments(page, ED, ED_MARKS);
});

test('the points must add up to the required total', async ({ page, testDatabase }) => {
  await insertSimpleEvent(testDatabase);
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);

  await simplePoints(page, ALEX).fill('150');
  await simplePoints(page, MATT).fill('100');
  await simplePoints(page, MATT).blur();
  await expectSimpleStatus(
    page,
    'Too many points, need to unallocate 50 points. There are still 2 comments to be filled.'
  );
  await expect(submitButton(page)).toBeDisabled();

  await fillSimpleEvaluation(page, [
    { ...ED_MARKS[0], points: 150 },
    { ...ED_MARKS[1], points: 20 },
  ]);
  await expectSimpleStatus(page, 'Please allocate 30 more points. All comments are filled.');
  await expect(submitButton(page)).toBeDisabled();

  // The server checks the total too: a form sent with a wrong total is not
  // saved, and the form opens again.
  await submitButton(page).evaluate((button) => button.removeAttribute('disabled'));
  await submitButton(page).click();
  // Only the form that opens again is empty: the page before the submit had
  // the points.
  await expect(simplePoints(page, ALEX)).toHaveValue('0');
  await expectSimpleStatus(
    page,
    'Please allocate 200 more points. There are still 2 comments to be filled.'
  );
  await expect(page).toHaveURL(/\/evaluations\/makeEvaluation\/\d+\/\d+$/);
  await expect(goodMessage(page)).toHaveCount(0);
  await expectOnHome(page, 'Due', TITLE, GROUP.name);
});

test('negative points are refused', async ({ page, testDatabase }) => {
  await insertSimpleEvent(testDatabase);
  await login(page, STUDENTS.ed.username);
  await openEvaluation(page, TITLE, GROUP.name);

  // The total is right, so the form lets the student submit.
  await fillSimpleEvaluation(page, [
    { ...ED_MARKS[0], points: -100 },
    { ...ED_MARKS[1], points: 300 },
  ]);
  await expectSimpleStatus(page, ALL_FILLED);
  await submitButton(page).click();

  await expect(flashMessage(page)).toHaveText(SUBMISSION.negativePoints);
  await expect(page).toHaveURL(/\/evaluations\/makeEvaluation\/\d+\/\d+$/);
  await expectOnHome(page, 'Due', TITLE, GROUP.name);
});

test('the results show the late penalties, who did not submit, and who left the group', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase, { dates: lateDates() });
  await insertMarks(testDatabase, eventId, STUDENTS.ed.username, ED_MARKS);
  await insertMarks(testDatabase, eventId, STUDENTS.alex.username, ALEX_MARKS);
  await insertMarks(testDatabase, eventId, TUTORS.tutor1.username, TUTOR_MARKS);
  // The group form is not the subject here, so the tutor leaves the group
  // in the database.
  await testDatabase.connection.query(
    'DELETE FROM groups_members WHERE group_id = ? AND user_id = ?',
    [GROUP.id, await userId(testDatabase, TUTORS.tutor1.username)]
  );
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await expect(memberList(page, NOT_SUBMITTED).getByRole('cell')).toHaveText([`${MATT} (student)`]);
  await expect(memberList(page, LEFT_GROUP).getByRole('cell')).toHaveText([`${TUTOR} (TA)`]);

  // Red marks a member who did not submit, blue a member who left.
  await expect(memberColumns(page)).toHaveText(EVALUATEES);
  await expect(memberColumns(page).nth(2)).toHaveClass('red');
  await expectRow(page, ED, ['-', '120.00', '80.00']);
  await expectRow(page, ALEX, ['95.00', '-', '105.00']);
  await expectRow(page, MATT, ['-', '-', '-']);
  await expect(rowHeader(page, MATT)).toHaveClass('red');
  await expectRow(page, TUTOR, ['100.00', '100.00', '100.00']);
  await expect(rowHeader(page, TUTOR)).toHaveClass('blue');

  // Ed and Alex submitted after the due date, and Matt did not submit by the
  // end. The penalty is on the marks that each of them received.
  await expectRow(page, 'Total', ['195.00', '220.00', '285.00']);
  await expectRow(page, 'Penalty', ['19.50 (10%)', '22.00 (10%)', '28.50 (10%)']);
  await expectRow(page, 'Final Mark', ['175.50', '198.00', '256.50']);
  await expectRow(page, '# of Evaluator(s)', ['2', '2', '3']);
  await expectRow(page, 'Average Received', ['87.75', '99.00', '85.50']);

  await expectComments(page, ED, ED_MARKS);
  await expectComments(page, ALEX, ALEX_MARKS);
  await expectComments(page, TUTOR, TUTOR_MARKS);
  await expect(evaluatorHeading(page, TUTOR)).toHaveClass('blue');
  await expect(commentRow(page, ED, MATT).getByRole('cell').first()).toHaveClass('red');

  // The submission details of the group give the days late.
  await page.goto(`/evaluations/viewGroupSubmissionDetails/${eventId}/${GROUP.id}`);
  const members = resultsTable(page, 'Group Members', 'h3');
  const lateBy = (name: string) =>
    members
      .getByRole('row')
      .filter({ has: page.getByRole('cell', { name, exact: true }) })
      .getByRole('cell')
      .nth(3);
  await expect(lateBy(ED)).toHaveText('1 day(s)');
  await expect(lateBy(ALEX)).toHaveText('1 day(s)');
  await expect(lateBy(MATT)).toHaveText('---');
});

test('a tutor who is still in the group is an evaluator, not a member to evaluate', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase);
  await insertMarks(testDatabase, eventId, TUTORS.tutor1.username, TUTOR_MARKS);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await expect(memberColumns(page)).toHaveText(EVALUATEES);
  await expectRow(page, TUTOR, ['100.00', '100.00', '100.00']);
  await expect(rowHeader(page, TUTOR)).not.toHaveAttribute('class');
  await expect(evaluatorHeading(page, TUTOR)).not.toHaveAttribute('class');
  await expect(memberList(page, LEFT_GROUP)).toHaveCount(0);
});

test('the instructor releases one grade and one comment, and each student sees only those', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase);
  await submitEdAndAlex(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await expect(gradeReleaseButtons(page)).toHaveText(['Release', 'Release', 'Release']);
  await gradeReleaseButtons(page).first().click();
  await expect(gradeReleaseButtons(page)).toHaveText(['Unrelease', 'Release', 'Release']);

  await page.getByRole('button', { name: 'Mark Peer Evaluations as Reviewed' }).click();
  await expect(
    page.getByRole('button', { name: 'Mark Peer Evaluations as Not Reviewed' })
  ).toBeVisible();

  // Release the comment of Ed to Alex.
  const edToAlex = commentRow(page, ED, ALEX).getByRole('checkbox');
  await edToAlex.check();
  await page.getByRole('button', { name: 'Save Changes', exact: true }).click();
  await expect(edToAlex).toBeChecked();
  await expect(commentRow(page, ED, MATT).getByRole('checkbox')).not.toBeChecked();
  await expect(commentRow(page, ALEX, ED).getByRole('checkbox')).not.toBeChecked();
  await expect(commentRow(page, ALEX, MATT).getByRole('checkbox')).not.toBeChecked();

  await openEventResults(page, eventId);
  await expect(await groupListCell(page, GROUP.name, 'Status')).toHaveText('Reviewed');
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Some Released');
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Some Released');

  // The group average counts Matt with the final penalty: 95, 120 and
  // (80 + 105) / 2 less 10%.
  await login(page, STUDENTS.ed.username);
  await openResults(page, TITLE, GROUP.name);
  await expectSimpleResult(page, { rating: '95.00', groupAverage: '99.42' });

  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expectSimpleResult(page, {
    rating: 'Not Released',
    groupAverage: 'Not Released',
    comments: ['A very great group member'],
  });

  await login(page, STUDENTS.matt.username);
  await openResults(page, TITLE, GROUP.name, 'Expired With No Submission');
  await expectSimpleResult(page, { rating: 'Not Released', groupAverage: 'Not Released' });
});

test('the instructor releases and unreleases all comments, then releases all grades', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertSimpleEvent(testDatabase);
  await submitEdAndAlex(testDatabase, eventId);
  const toMatt = [ED_MARKS[1].comment, ALEX_MARKS[1].comment];

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await page.getByRole('button', { name: 'Release All', exact: true }).click();
  await expect(commentRow(page, ED, MATT).getByRole('checkbox')).toBeChecked();
  await login(page, STUDENTS.matt.username);
  await openResults(page, TITLE, GROUP.name, 'Expired With No Submission');
  await expectSimpleResult(page, {
    rating: 'Not Released',
    groupAverage: 'Not Released',
    comments: toMatt,
  });

  await login(page, USERS.instructor1);
  await openGroupResults(page, eventId);
  await page.getByRole('button', { name: 'Unrelease All', exact: true }).click();
  await expect(commentRow(page, ED, MATT).getByRole('checkbox')).not.toBeChecked();
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await waitForAjaxList(page);
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');

  // Matt did not submit by the end, so his rating loses the final penalty.
  await login(page, STUDENTS.matt.username);
  await openResults(page, TITLE, GROUP.name, 'Expired With No Submission');
  await expectSimpleResult(page, {
    rating: '92.50 - (9.25)* = 83.25 ( )* : 10% late penalty.',
    groupAverage: '99.42',
  });
});

test('an event without details shows the rating only', async ({ page, testDatabase }) => {
  const eventId = await insertSimpleEvent(testDatabase, { resultMode: 'Basic' });
  await submitEdAndAlex(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await page.getByRole('link', { name: 'Release All Grades', exact: true }).click();
  await waitForAjaxList(page);
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Released');
  await page.getByRole('link', { name: 'Release All Comments', exact: true }).click();
  await waitForAjaxList(page);
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Released');

  // The comments are released, but the event does not show them.
  await login(page, STUDENTS.alex.username);
  await openResults(page, TITLE, GROUP.name);
  await expectSimpleResult(page, { rating: '120.00' });
});
