import { expect, test, type Page } from '../support/test';
import { login } from '../support/helpers';
import type { TestDatabase } from '../support/db';
import {
  endSubmissions,
  insertEvent,
  lateDates,
  openDates,
  type NewEvent,
} from '../support/events';
import { groupListCell, openEventResults } from '../support/results';
import { COURSES, MECH328_GROUPS, STUDENTS, USERS } from '../support/seed';
import {
  expectSimpleResult,
  fillMixevalForm,
  fillRubricPanel,
  insertSimpleSubmission,
  openEvaluation,
  openResults,
  rating,
  resultAnswers,
  resultsHeading,
  saveRubricSection,
  submitEvaluation,
  submitMixevalButton,
  submitRubricButton,
  type MixevalMark,
  type RubricMark,
} from '../support/student';

// With auto-release on, students see their grades and comments in the
// results period without a release by the instructor. The ports of the
// student specs left this out; the Selenium tests ran with it on.
//
// The simple results read auto_release from the event, so the simple tests
// insert the submissions. For rubric and mixed evaluations, the save copies
// auto_release to the release flags of each answer, and the results read
// those flags. So those tests submit through the form, which also checks
// that the save sets the flags.

const TITLE = 'Auto Release';
const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed;
const ALEX = STUDENTS.alex;
const MATT = STUDENTS.matt;
// Only Ed submits, except in the late penalty test, so Alex lists the event
// under his events without a submission.
const SECTION = 'Expired With No Submission';

async function insertAutoReleaseEvent(
  testDatabase: TestDatabase,
  changes: Partial<NewEvent>
): Promise<string> {
  return insertEvent(testDatabase, COURSES.mech328.id, {
    title: TITLE,
    description: '',
    type: 'SIMPLE',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disabled',
    comments: 'Disabled',
    resultMode: 'Detailed',
    autoRelease: 'Enabled',
    dates: openDates(),
    allGroups: true,
    // Alex does not submit, so a penalty would also take the final penalty
    // off his rating. Only the late penalty test has one.
    penalties: [],
    ...changes,
  });
}

// Ed opens the evaluation in the submission period and submits it.
async function openAsEd(page: Page): Promise<void> {
  await login(page, ED.username);
  await openEvaluation(page, TITLE, GROUP.name);
}

test('a simple evaluation with auto-release shows the grades and comments at once', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertAutoReleaseEvent(testDatabase, {});
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'A very great group member' },
    { member: MATT.name, points: 80, comment: 'Very responsible.' },
  ]);
  await endSubmissions(testDatabase, eventId);

  // The event form sets the release of each group to Auto-Release, and so
  // does the insert.
  await login(page, USERS.instructor1);
  await openEventResults(page, eventId);
  await expect(await groupListCell(page, GROUP.name, 'Grade')).toHaveText('Auto-Release');
  await expect(await groupListCell(page, GROUP.name, 'Comment')).toHaveText('Auto-Release');

  await login(page, ALEX.username);
  await openResults(page, TITLE, GROUP.name, SECTION);
  await expectSimpleResult(page, {
    rating: '120.00',
    // The mean of the average mark that each member received: Alex 120 and
    // Matt 80, both from Ed.
    groupAverage: '100.00',
    comments: ['A very great group member'],
  });
});

test('a late submitter sees their late penalty in the results', async ({ page, testDatabase }) => {
  const eventId = await insertAutoReleaseEvent(testDatabase, {
    dates: lateDates(),
    penalties: [{ days: '1', percent: '10' }],
  });
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'A very great group member' },
    { member: MATT.name, points: 80, comment: 'Very responsible.' },
  ]);
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ALEX.username, [
    { member: ED.name, points: 100, comment: 'Hardworking' },
    { member: MATT.name, points: 100, comment: 'A great leader' },
  ]);
  await endSubmissions(testDatabase, eventId);

  // Ed and Alex submitted after the due date, so the marks each receives lose
  // 10%. Matt did not submit, so his lose 10% as the final penalty:
  //   Ed 100 - 10 = 90, Alex 120 - 12 = 108, Matt (80 + 100) / 2 - 9 = 81.
  // The group average is (90 + 108 + 81) / 3 = 93.
  await login(page, ED.username);
  await openResults(page, TITLE, GROUP.name);
  await expectSimpleResult(page, {
    rating: '100.00 - (10.00)* = 90.00 ( )* : 10% late penalty.',
    groupAverage: '93.00',
    comments: ['Hardworking'],
  });
});

test('a rubric evaluation with auto-release shows the grades and comments at once', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertAutoReleaseEvent(testDatabase, {
    type: 'RUBRIC',
    template: 'Term Report Evaluation',
  });
  const marks: RubricMark[] = [
    {
      member: ALEX.name,
      levels: [5, 4, 4],
      comments: ['active', 'helpful', 'on time'],
      general: 'good job',
    },
    {
      member: MATT.name,
      levels: [3, 4, 5],
      comments: ['some', 'kind', 'early'],
      general: 'amazing',
    },
  ];
  await openAsEd(page);
  for (const mark of marks) {
    await fillRubricPanel(page, mark);
    await saveRubricSection(
      page,
      mark.member,
      'Your evaluation has been saved, but you still have to submit the evaluation with the Submit button below.'
    );
  }
  await submitEvaluation(page, submitRubricButton(page));
  await endSubmissions(testDatabase, eventId);

  await login(page, ALEX.username);
  await openResults(page, TITLE, GROUP.name, SECTION);
  await expect(rating(page)).toHaveText('13.00');
  // Nothing is held back: the header has no "Not Released" note, and each
  // criterion shows the grade and the comment.
  const received = page.locator('#panelResults');
  await expect(received.locator('#panelResultsHeader')).toHaveText(
    'Evaluation Results From Your Teammates. (Randomly Ordered)'
  );
  // Ed's evaluation is a row of criteria, then a row with the general
  // comment. The first cell of the criteria row is not a criterion.
  const rows = received.locator('table').locator('xpath=./tbody/tr[td]');
  await expect(rows).toHaveCount(2);
  await expect(rows.nth(0).locator('xpath=./td[position() > 1]')).toHaveText(
    [
      'Points: Grade: 5.00 Comment: active',
      'Points: Grade: 4.00 Comment: helpful',
      'Points: Grade: 4.00 Comment: on time',
    ],
    { useInnerText: true }
  );
  await expect(rows.nth(1)).toHaveText('General Comment: good job', { useInnerText: true });
});

test('a mixed evaluation with auto-release shows the grades and comments at once', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertAutoReleaseEvent(testDatabase, {
    type: 'MIX EVALUATION',
    template: 'Default Mix Evaluation',
  });
  const marks: MixevalMark[] = [
    { member: ALEX.name, answers: { 1: 4, 2: 3, 3: 5, 4: 'absolutely', 5: 'definitely' } },
    { member: MATT.name, answers: { 1: 5, 2: 5, 3: 4, 4: 'yes', 5: 'always' } },
  ];
  await openAsEd(page);
  await fillMixevalForm(page, marks);
  await submitEvaluation(page, submitMixevalButton(page));
  await endSubmissions(testDatabase, eventId);

  await login(page, ALEX.username);
  await openResults(page, TITLE, GROUP.name, SECTION);
  await expect(rating(page)).toHaveText('2.40 (80%)');
  // Nothing is held back: the heading has no "Not Released" note. A level
  // is a fifth of a mark.
  await expect(resultsHeading(page)).toHaveText('Questions');
  await expect(resultAnswers(page, 1).locator('label.grade')).toHaveText(['Grade: 0.80 / 1']);
  await expect(resultAnswers(page, 2).locator('label.grade')).toHaveText(['Grade: 0.60 / 1']);
  await expect(resultAnswers(page, 3).locator('label.grade')).toHaveText(['Grade: 1.00 / 1']);
  await expect(resultAnswers(page, 4)).toHaveText(['absolutely']);
  await expect(resultAnswers(page, 5)).toHaveText(['definitely']);
});
