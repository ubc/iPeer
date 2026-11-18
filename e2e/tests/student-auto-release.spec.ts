import { expect, test, type Page } from '../support/test';
import { login, resultsTable } from '../support/helpers';
import type { TestDatabase } from '../support/db';
import {
  endSubmissions,
  insertEvent,
  lateDates,
  openDates,
  type NewEvent,
} from '../support/events';
import { COURSES, MECH328_GROUPS, STUDENTS } from '../support/seed';
import {
  expectSimpleResult,
  insertMixevalSubmission,
  insertRubricSubmission,
  insertSimpleSubmission,
  openResults,
} from '../support/student';

// With auto-release on, students see their grades and comments in the
// results period without a release by the instructor. The ports of the
// student specs left this out; the Selenium tests ran with it on. The
// events and submissions are inserted.

const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed;
const ALEX = STUDENTS.alex;
const MATT = STUDENTS.matt;
// Only Ed submits, except in the late penalty test, so Alex lists the event
// under his events without a submission.
const SECTION = 'Expired With No Submission';

// insertEvent() writes auto_release 0, as the event form does by default, so
// the event turns it on before the answers are inserted: an answer saved
// under auto-release is released from the start.
async function insertAutoReleaseEvent(
  testDatabase: TestDatabase,
  event: Partial<NewEvent>
): Promise<string> {
  const eventId = await insertEvent(testDatabase, COURSES.mech328.id, {
    title: 'Auto Release',
    description: '',
    type: 'SIMPLE',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disabled',
    comments: 'Disabled',
    resultMode: 'Detailed',
    dates: openDates(),
    allGroups: true,
    // Alex does not submit, so a penalty would also take the final penalty
    // off his rating. Only the late penalty test has one.
    penalties: [],
    ...event,
  });
  await testDatabase.connection.query('UPDATE events SET auto_release = 1 WHERE id = ?', [eventId]);
  return eventId;
}

function rating(page: Page) {
  return resultsTable(page, 'Summary').getByRole('cell');
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

  await login(page, ALEX.username);
  await openResults(page, 'Auto Release', GROUP.name, SECTION);
  await expectSimpleResult(page, {
    rating: '120.00',
    // The average of the marks Ed gave: (120 + 80) / 2.
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

  // Ed submitted after the due date, so his rating loses 10%.
  await login(page, ED.username);
  await openResults(page, 'Auto Release', GROUP.name);
  await expect(rating(page).first()).toHaveText(
    '100.00 - (10.00)* = 90.00 ( )* : 10% late penalty.'
  );
});

test('a rubric evaluation with auto-release shows the grades and comments at once', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertAutoReleaseEvent(testDatabase, {
    type: 'RUBRIC',
    template: 'Term Report Evaluation',
  });
  await insertRubricSubmission(testDatabase, eventId, GROUP.id, ED.username, [
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
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, ALEX.username);
  await openResults(page, 'Auto Release', GROUP.name, SECTION);
  await expect(rating(page)).toHaveText('13.00');
  // Nothing is held back: the header has no "Not Released" note.
  const received = page.locator('#panelResults');
  await expect(received.locator('#panelResultsHeader')).toHaveText(
    'Evaluation Results From Your Teammates. (Randomly Ordered)'
  );
  await expect(received.locator('table')).toContainText('Grade: 5.00');
  await expect(received.locator('table')).toContainText('Comment: active');
  await expect(received.locator('table')).toContainText('good job');
});

test('a mixed evaluation with auto-release shows the grades and comments at once', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertAutoReleaseEvent(testDatabase, {
    type: 'MIX EVALUATION',
    template: 'Default Mix Evaluation',
  });
  await insertMixevalSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, answers: { 1: 4, 2: 3, 3: 5, 4: 'absolutely', 5: 'definitely' } },
    { member: MATT.name, answers: { 1: 5, 2: 5, 3: 4, 4: 'yes', 5: 'always' } },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, ALEX.username);
  await openResults(page, 'Auto Release', GROUP.name, SECTION);
  await expect(rating(page)).toHaveText('2.40 (80%)');
  const results = page.locator('#mixeval_result').last();
  await expect(results.locator('h2').first()).toHaveText('Questions');
  await expect(results.locator('label.grade').first()).toHaveText('Grade: 0.80 / 1');
  await expect(results).toContainText('absolutely');
  await expect(results).toContainText('definitely');
});
