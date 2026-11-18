import { readFile, writeFile } from 'fs/promises';
import { expect, test, type Locator, type Page } from '../support/test';
import { flashMessage, login, pageTitle } from '../support/helpers';
import type { TestDatabase } from '../support/db';
import {
  endSubmissions,
  insertEvent,
  lateDates,
  openDates,
  type NewEvent,
} from '../support/events';
import { userId } from '../support/insert';
import { pdfPages } from '../support/pdf';
import { readInOrder } from '../support/text';
import { COURSES, MECH328_GROUPS, STUDENTS, TUTORS, USERS, fullName } from '../support/seed';
import {
  insertMixevalSubmission,
  insertRubricSubmission,
  insertSimpleSubmission,
} from '../support/student';
import { ACCESS } from '../support/messages';

// The export of the results of an event, as a CSV or PDF file. There was no
// Selenium test for it. The events and the submissions are inserted.
//
// The late penalty of a row depends on the submission of the evaluatee, not
// of the evaluator. In most tests only Ed submits, on time, and the
// submissions have ended, so the marks Alex and Matt received carry the
// penalty for no submission: the top tier, 10%. The simple CSV test covers
// an on-time, a late and a missing submission.

const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed;
const ALEX = STUDENTS.alex;
const MATT = STUDENTS.matt;
const TUTOR = TUTORS.tutor1.name;

const TITLE = 'Simple Export';

function exportEvent(changes: Partial<NewEvent>): NewEvent {
  return {
    title: TITLE,
    description: '',
    type: 'SIMPLE',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disabled',
    comments: 'Disabled',
    resultMode: 'Detailed',
    dates: openDates(),
    allGroups: true,
    penalties: [{ days: '1', percent: '10' }],
    ...changes,
  };
}

async function insertExportEvent(
  testDatabase: TestDatabase,
  changes: Partial<NewEvent>
): Promise<string> {
  return insertEvent(testDatabase, COURSES.mech328.id, exportEvent(changes));
}

// A small CSV reader: quoted fields may hold commas and doubled quotes. A
// line break at the end of the file does not start a row. A blank line
// inside the file is a row with one empty cell, so the checks see it.
function parseCsv(text: string): string[][] {
  const rows: string[][] = [];
  let row: string[] = [];
  let field = '';
  let quoted = false;
  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (quoted) {
      if (c === '"' && text[i + 1] === '"') {
        field += '"';
        i++;
      } else if (c === '"') {
        quoted = false;
      } else {
        field += c;
      }
    } else if (c === '"') {
      quoted = true;
    } else if (c === ',') {
      row.push(field);
      field = '';
    } else if (c === '\n') {
      row.push(field);
      rows.push(row);
      row = [];
      field = '';
    } else if (c !== '\r') {
      field += c;
    }
  }
  if (field !== '' || row.length > 0) {
    row.push(field);
    rows.push(row);
  }
  return rows;
}

async function openExport(page: Page, eventId: string): Promise<void> {
  await page.goto(`/evaluations/export/event/${eventId}`);
  await expect(pageTitle(page)).toContainText('Export Evaluation Results');
}

function exportButton(page: Page): Locator {
  return page.getByRole('button', { name: 'Export', exact: true });
}

// Presses Export and returns the CSV file that the browser saves. The file
// is attached to the test, so it can be opened after the run: in the report,
// in UI mode, and under test-results.
async function exportFile(page: Page): Promise<{ name: string; content: Buffer }> {
  return test.step(
    'Export',
    async () => {
      const download = page.waitForEvent('download');
      await exportButton(page).click();
      const file = await download;
      const name = file.suggestedFilename();
      const path = await file.path();
      await test.info().attach(name, { path });
      return { name, content: await readFile(path) };
    },
    { box: true }
  );
}

// The header and all the rows of the CSV export. The export lists every pair
// of evaluatee and evaluator in the group, answered or not.
async function exportCsvRows(page: Page): Promise<{ header: string[]; rows: string[][] }> {
  const { name, content } = await exportFile(page);
  expect(name).toMatch(/\.csv$/);
  const [header, ...rows] = parseCsv(content.toString('utf8'));
  return { header, rows };
}

// Sends the export form as the Export button does, and returns the answer.
// The PDF export sends its file inline: a browser with a PDF viewer, such as
// a headed Chromium, shows it instead of downloading it. So the PDF test
// posts the form itself, with the cookies of the page.
async function postExport(page: Page): Promise<Buffer> {
  return test.step(
    'Export',
    async () => {
      const form = page.locator('form').filter({ has: exportButton(page) });
      const action = await form.getAttribute('action');
      expect(action, 'the action of the export form').not.toBeNull();
      // A checkbox and its hidden field share a name. PHP keeps the last
      // value, and so does an object.
      const fields = await form.evaluate((element) =>
        Object.fromEntries(
          [...new FormData(element as HTMLFormElement).entries()].map(([key, value]) => [
            key,
            String(value),
          ])
        )
      );
      const response = await page.request.post(action!, { form: fields });
      expect(response.status()).toBe(200);
      const content = await response.body();
      const path = test.info().outputPath('export.pdf');
      await writeFile(path, content);
      await test.info().attach('export.pdf', { path });
      return content;
    },
    { box: true }
  );
}

// The columns the export always starts with.
const LEAD = ['Course Name', 'Event', 'Evaluation Type', 'Group Name'];
const PEOPLE = ['Evaluatee', 'Evaluatee S#', 'Evaluator', 'Evaluator S#'];
const TOTALS = ['Raw Score', 'Late Penalty', 'Final Score'];
const EVALUATOR = LEAD.length + PEOPLE.indexOf('Evaluator');

function lead(event: string, type: string): string[] {
  return [COURSES.mech328.name, event, type, GROUP.name];
}

function pair(
  evaluatee: { name: string; number: string },
  evaluator: { name: string; number: string }
) {
  return [evaluatee.name, evaluatee.number, evaluator.name, evaluator.number];
}

// The rows that have an answer. The Raw Score is set only for those; an
// answer can have empty comments.
function answered(header: readonly string[], rows: string[][]): string[][] {
  const score = header.indexOf('Raw Score');
  expect(score, 'the Raw Score column').toBeGreaterThanOrEqual(0);
  return rows.filter((row) => (row[score] ?? '') !== '');
}

// ---------------------------------------------------------------------------

// Moves the submission of a student to an hour before the due date, as if
// it was made on time. The insert stamps it with the present time.
async function submitOnTime(
  testDatabase: TestDatabase,
  eventId: string,
  username: string
): Promise<void> {
  const submitter = await userId(testDatabase, username);
  await testDatabase.connection.query(
    `UPDATE evaluation_submissions s JOIN events e ON e.id = s.event_id
        SET s.date_submitted = e.due_date - INTERVAL 1 HOUR
      WHERE s.event_id = ? AND s.submitter_id = ?;
     UPDATE evaluation_simples s JOIN events e ON e.id = s.event_id
        SET s.date_submitted = e.due_date - INTERVAL 1 HOUR
      WHERE s.event_id = ? AND s.evaluator = ?`,
    [eventId, submitter, eventId, submitter]
  );
}

test('the CSV export of a simple evaluation lists each mark with the late penalty', async ({
  page,
  testDatabase,
}) => {
  // Two tiers, so a late submission and no submission give different
  // penalties.
  const eventId = await insertExportEvent(testDatabase, {
    dates: lateDates(),
    penalties: [
      { days: '1', percent: '10' },
      { days: '2', percent: '20' },
    ],
  });
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'Did the research, "really" well' },
    { member: MATT.name, points: 80, comment: 'Wrote the report' },
  ]);
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ALEX.username, [
    { member: ED.name, points: 100, comment: '' },
    { member: MATT.name, points: 100, comment: 'Kept us on track' },
  ]);
  await submitOnTime(testDatabase, eventId, ALEX.username);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  const { header, rows } = await exportCsvRows(page);
  expect(header).toEqual([...LEAD, ...PEOPLE, 'Comment', ...TOTALS]);
  // Ed submitted less than a day late: 10%. Alex submitted on time: none.
  // Matt did not submit: the top tier, 20%. Alex's answer to Ed has no
  // comment.
  expect(answered(header, rows)).toEqual([
    [...lead(TITLE, 'Simple Evaluation'), ...pair(ED, ALEX), '', '100', '10%', '90'],
    [
      ...lead(TITLE, 'Simple Evaluation'),
      ...pair(ALEX, ED),
      'Did the research, "really" well',
      '120',
      '0%',
      '120',
    ],
    [...lead(TITLE, 'Simple Evaluation'), ...pair(MATT, ED), 'Wrote the report', '80', '20%', '64'],
    [
      ...lead(TITLE, 'Simple Evaluation'),
      ...pair(MATT, ALEX),
      'Kept us on track',
      '100',
      '20%',
      '80',
    ],
  ]);
  // Every evaluator of the group has a row for each member they evaluate,
  // also without an answer. The tutor evaluates but is not evaluated.
  expect(rows).toHaveLength(9);
  expect(rows.filter((row) => row[EVALUATOR] === TUTOR)).toHaveLength(3);
  for (const row of rows) {
    expect(row, 'a row as wide as the header').toHaveLength(header.length);
  }
});

test('the CSV export of a rubric evaluation has a column for each criterion', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertExportEvent(testDatabase, {
    title: 'Rubric Export',
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

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  const { header, rows } = await exportCsvRows(page);
  expect(header).toEqual([
    ...LEAD,
    ...PEOPLE,
    'Q1 Comment',
    'Q2 Comment',
    'Q3 Comment',
    'Comment',
    'Q1 ( /5)',
    'Q2 ( /5)',
    'Q3 ( /5)',
    ...TOTALS,
  ]);
  expect(answered(header, rows)).toEqual([
    [
      ...lead('Rubric Export', 'Rubrics Evaluation'),
      ...pair(ALEX, ED),
      'active',
      'helpful',
      'on time',
      'good job',
      '5.00',
      '4.00',
      '4.00',
      '13.00',
      '10%',
      '11.7',
    ],
    [
      ...lead('Rubric Export', 'Rubrics Evaluation'),
      ...pair(MATT, ED),
      'some',
      'kind',
      'early',
      'amazing',
      '3.00',
      '4.00',
      '5.00',
      '12.00',
      '10%',
      '10.8',
    ],
  ]);
});

test('the CSV export of a mixed evaluation has a column for each question', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertExportEvent(testDatabase, {
    title: 'Mixed Export',
    type: 'MIX EVALUATION',
    template: 'Default Mix Evaluation',
  });
  await insertMixevalSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, answers: { 1: 4, 2: 3, 3: 5, 4: 'absolutely', 5: 'definitely' } },
    { member: MATT.name, answers: { 1: 5, 2: 5, 3: 4, 4: 'yes', 5: 'always', 6: 'very' } },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  const { header, rows } = await exportCsvRows(page);
  expect(header).toEqual([
    ...LEAD,
    ...PEOPLE,
    'Q1 ( /1)',
    'Q2 ( /1)',
    'Q3 ( /1)',
    'Q4',
    'Q5',
    'Q6',
    ...TOTALS,
  ]);
  expect(answered(header, rows)).toEqual([
    [
      ...lead('Mixed Export', 'Mixed Evaluation'),
      ...pair(ALEX, ED),
      '0.80',
      '0.60',
      '1.00',
      'absolutely',
      'definitely',
      '',
      '2.40',
      '10%',
      '2.16',
    ],
    [
      ...lead('Mixed Export', 'Mixed Evaluation'),
      ...pair(MATT, ED),
      '1.00',
      '1.00',
      '0.80',
      'yes',
      'always',
      'very',
      '2.80',
      '10%',
      '2.52',
    ],
  ]);
});

test('the export leaves out the columns that are not checked', async ({ page, testDatabase }) => {
  const eventId = await insertExportEvent(testDatabase, {});
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'good' },
    { member: MATT.name, points: 80, comment: 'ok' },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  await page.getByLabel('Include Course Name').uncheck();
  await page.getByLabel('Include Student Id').uncheck();
  await page.getByLabel('Include Comments').uncheck();
  const { header, rows } = await exportCsvRows(page);
  expect(header).toEqual([
    'Event',
    'Evaluation Type',
    'Group Name',
    'Evaluatee',
    'Evaluator',
    ...TOTALS,
  ]);
  // The answered rows lose the same cells. Alex and Matt did not submit, so
  // their marks carry the penalty for no submission.
  expect(answered(header, rows)).toEqual([
    [TITLE, 'Simple Evaluation', GROUP.name, ALEX.name, ED.name, '120', '10%', '108'],
    [TITLE, 'Simple Evaluation', GROUP.name, MATT.name, ED.name, '80', '10%', '72'],
  ]);
});

test('the export needs one field of each coloured group', async ({ page, testDatabase }) => {
  const eventId = await insertExportEvent(testDatabase, {});

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  const errors = {
    red: page.getByText('Please include at least one of the red fields'),
    green: page.getByText('Please include at least one of the green fields'),
    orange: page.getByText('Please include at least one of the orange fields'),
  };
  for (const label of [
    'Include Course Name',
    'Include Event Name',
    'Include Student Name',
    'Include Student Id',
    'Include Comments',
    'Include Grades',
  ]) {
    await page.getByLabel(label).uncheck();
  }
  let downloads = 0;
  page.on('download', () => downloads++);
  await exportButton(page).click();
  for (const error of Object.values(errors)) {
    await expect(error).toBeVisible();
  }
  expect(downloads, 'downloads after the refused export').toBe(0);

  await page.getByLabel('Include Event Name').check();
  await page.getByLabel('Include Student Name').check();
  await page.getByLabel('Include Grades').check();
  await exportFile(page);
  for (const error of Object.values(errors)) {
    await expect(error).toBeHidden();
  }
  expect(downloads).toBe(1);
});

// Exports the event as a PDF, and returns the text of each page.
async function exportPdf(page: Page, eventId: string): Promise<string[]> {
  await openExport(page, eventId);
  await page.getByLabel('Export File Type').selectOption('pdf');
  // The PDF has no columns per student, so the form hides those choices.
  await expect(page.getByLabel('Include Student Name')).toBeHidden();
  const content = await postExport(page);
  expect(content.subarray(0, 5).toString('latin1')).toBe('%PDF-');
  const pages = await pdfPages(content);
  await test.info().attach('export.pdf.txt', { body: pages.join('\n\n') });
  return pages;
}

// The text of one group. Each group starts a page with its 'Group:' label,
// and can go on over the next pages. The title of the export is above the
// first group only.
function groupText(pages: readonly string[], group: string): string {
  const starts = (text: string) => /^\s*Group:/.test(text);
  const first = pages.findIndex((text) => text.includes(`Group: ${group}`));
  expect(first, `the page of ${group}`).toBeGreaterThanOrEqual(0);
  const next = pages.findIndex((text, index) => index > first && starts(text));
  return pages.slice(first, next < 0 ? undefined : next).join('\n');
}

// The other group of the course has no answers.
const OTHER = MECH328_GROUPS.lazyEngineers.name;

test('the PDF export of a simple evaluation shows the marks and comments', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertExportEvent(testDatabase, {});
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'good' },
    { member: MATT.name, points: 80, comment: 'ok' },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  const pages = await exportPdf(page, eventId);
  readInOrder(pages[0]).expect(['Evaluation Event Detail:', `${COURSES.mech328.name} - ${TITLE}`]);
  const text = readInOrder(groupText(pages, GROUP.name));
  text.expect(['Self-Evaluation:', 'No', 'Event Name:', TITLE, 'Evaluation Type:', 'Simple']);
  text.expect(['Members who have not submitted their evaluations', ALEX.name, MATT.name, TUTOR]);
  // The marks Ed gave, then the totals of each member evaluated.
  text.expect(['Members Evaluated', ALEX.name, MATT.name, ED.name, '120.00', '80.00']);
  text.expect(['Totals', '120.00', '80.00', 'Penalty', '12.00 (10%)', '8.00 (10%)']);
  text.expect(['Final Mark', '108.00', '72.00', '# of Evaluator(s)', '1', '1']);
  text.expect(['Average Received', '108.00', '72.00']);
  text.expect(['Comments', 'Evaluator:', ED.name]);
  text.expect([`${ALEX.name}:`, 'good', `${MATT.name}:`, 'ok']);
  readInOrder(groupText(pages, OTHER)).expect(['No Submissions']);
});

test('the PDF export of a rubric evaluation shows each criterion', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertExportEvent(testDatabase, {
    title: 'Rubric Export',
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

  await login(page, USERS.instructor1);
  const pages = await exportPdf(page, eventId);
  const text = readInOrder(groupText(pages, GROUP.name));
  text.expect(['Event Name:', 'Rubric Export', 'Evaluation Type:', 'Rubric']);
  // The average grade of each criterion, of 5, then the total of 15 with
  // the penalty.
  text.expect(['Evaluatee', '1 (/5.0)', '2 (/5.0)', '3 (/5.0)', 'Total (/15.00)']);
  text.expect([ALEX.name, '5.00', '4.00', '4.00', '13.00 - 1.30 = 11.70 (78.00%)']);
  text.expect([MATT.name, '3.00', '4.00', '5.00', '12.00 - 1.20 = 10.80 (72.00%)']);
  text.expect(['Group Average', '4.00', '4.00', '4.50', '11.25']);
  // Then what each member received, criterion by criterion.
  for (const [member, total, place, grades, comments, general] of [
    [
      ALEX.name,
      '13.00 - 1.30 = 11.70 (78.00%)',
      'Above',
      ['5.00', '4.00', '4.00'],
      ['active', 'helpful', 'on time'],
      'good job',
    ],
    [
      MATT.name,
      '12.00 - 1.20 = 10.80 (72.00%)',
      'Below',
      ['3.00', '4.00', '5.00'],
      ['some', 'kind', 'early'],
      'amazing',
    ],
  ] as const) {
    text.expect(['Evaluatee:', member, 'Number of Evaluators:', '1', 'Final Total:', total]);
    text.expect([`${place} Group Average`, 'Note: 10% Late Penalty']);
    text.expect([
      ED.name,
      ...grades.flatMap((grade, index) => ['Grade:', `${grade} / 5`, 'Comment:', comments[index]]),
      'General Comment:',
      general,
    ]);
  }
  readInOrder(groupText(pages, OTHER)).expect(['Group Average', '0.00', '0.00', '0.00', '0.00']);
});

test('the PDF export of a mixed evaluation shows each question', async ({ page, testDatabase }) => {
  const eventId = await insertExportEvent(testDatabase, {
    title: 'Mixed Export',
    type: 'MIX EVALUATION',
    template: 'Default Mix Evaluation',
  });
  await insertMixevalSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, answers: { 1: 4, 2: 3, 3: 5, 4: 'absolutely', 5: 'definitely' } },
    { member: MATT.name, answers: { 1: 5, 2: 5, 3: 4, 4: 'yes', 5: 'always', 6: 'very' } },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  const pages = await exportPdf(page, eventId);
  const text = readInOrder(groupText(pages, GROUP.name));
  text.expect(['Event Name:', 'Mixed Export', 'Evaluation Type:', 'Mix Evaluation']);
  // The grade of each Likert question, of 1, then the total of 3 with the
  // penalty.
  text.expect(['Evaluatee', '1 (/1.0)', '2 (/1.0)', '3 (/1.0)', 'Total (/3.00)']);
  text.expect([ALEX.name, '0.80', '0.60', '1.00', '2.40 - 0.24 = 2.16 (72.00%)']);
  text.expect([MATT.name, '1.00', '1.00', '0.80', '2.80 - 0.28 = 2.52 (84.00%)']);
  text.expect(['Group Average', '0.90', '0.80', '0.90', '2.34']);
  // Then the answers each member received, question by question. Alex has
  // no answer to the optional question 6.
  const questions = [
    '1. Participated in Team Meetings',
    '2. Was Helpful and co-operative',
    '3. Submitted work on time',
    '4. Produced efficient work?',
    '5. Contributed?',
    '6. Easy to work with?',
  ];
  for (const [member, total, place, answers] of [
    [
      ALEX.name,
      '2.40 - 0.24 = 2.16 (72.00%)',
      'Below',
      ['0.80 / 1', '0.60 / 1', '1.00 / 1', 'absolutely', 'definitely', null],
    ],
    [
      MATT.name,
      '2.80 - 0.28 = 2.52 (84.00%)',
      'Above',
      ['1.00 / 1', '1.00 / 1', '0.80 / 1', 'yes', 'always', 'very'],
    ],
  ] as const) {
    text.expect(['Evaluatee:', member, 'Final Total:', total, `${place} Average`]);
    text.expect(['Note: 10% Late Penalty']);
    for (const [index, answer] of answers.entries()) {
      text.expect(
        answer === null ? [questions[index], 'N/A'] : [questions[index], `${ED.name}:`, answer]
      );
    }
  }
  readInOrder(groupText(pages, OTHER)).expect(['Group Average', '0.00', '0.00', '0.00', '0.00']);
});

test('the course export lists the evaluation events of the course', async ({
  page,
  testDatabase,
}) => {
  await insertExportEvent(testDatabase, {});
  await insertEvent(
    testDatabase,
    COURSES.apsc201.id,
    exportEvent({ title: 'Other Course Export' })
  );

  await login(page, USERS.instructor1);
  await page.goto(`/courses/home/${COURSES.mech328.id}`);
  await page.getByRole('link', { name: 'Export Evaluation Results', exact: true }).click();
  await expect(pageTitle(page)).toHaveText(
    `${fullName(COURSES.mech328)} > Export Evaluation Results`
  );
  const events = page.getByLabel('Event Name', { exact: true });
  await expect(events.locator('option', { hasText: TITLE })).toHaveCount(1);
  await expect(events.locator('option', { hasText: 'Other Course Export' })).toHaveCount(0);
  // Surveys have no results to export.
  await expect(events.locator('option', { hasText: 'Team Creation Survey' })).toHaveCount(0);
});

test('an instructor cannot export the results of a course they do not teach', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto(`/evaluations/export/course/${COURSES.apsc201.id}`);
  await expect(flashMessage(page)).toHaveText(ACCESS.noCourse);
  await expect(page).toHaveURL(/\/courses$/);
});

// The form lists only the events of the course, but the export checks the
// posted event again.
test('an export of an event of another course is refused', async ({ page, testDatabase }) => {
  const own = await insertExportEvent(testDatabase, {});
  const other = await insertEvent(
    testDatabase,
    COURSES.apsc201.id,
    exportEvent({ title: 'Other Course Export' })
  );

  await login(page, USERS.instructor1);
  await openExport(page, own);
  // Point the event field at the other event, as a forged form would.
  await page
    .locator('#ExportEvalEventId')
    .evaluate((select, id) => ((select as HTMLSelectElement).options[0].value = id), other);
  let downloads = 0;
  page.on('download', () => downloads++);
  await exportButton(page).click();
  // The export redirects to the evaluations list, which sets its own error
  // in place of the export's, so only the start of the message is checked.
  await expect(flashMessage(page)).toHaveText(/^Error: /);
  expect(downloads).toBe(0);
});
