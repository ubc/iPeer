import { readFileSync } from 'fs';
import { expect, test, type Page } from '../support/test';
import { flashMessage, login, pageTitle } from '../support/helpers';
import type { TestDatabase } from '../support/db';
import { endSubmissions, insertEvent, lateDates, type NewEvent } from '../support/events';
import { COURSES, MECH328_GROUPS, STUDENTS, TUTORS, USERS, fullName } from '../support/seed';
import {
  insertMixevalSubmission,
  insertRubricSubmission,
  insertSimpleSubmission,
} from '../support/student';

// The export of the results of an event, as a CSV or PDF file. There was no
// Selenium test for it. The events and the submissions are inserted: Ed
// submitted after the due date, and the submissions have ended, so Ed's marks
// to Alex and Matt carry the late penalty of 10%.

const GROUP = MECH328_GROUPS.reapers;
const ED = STUDENTS.ed;
const ALEX = STUDENTS.alex;
const MATT = STUDENTS.matt;
const TUTOR = TUTORS.tutor1.name;

function exportEvent(changes: Partial<NewEvent>): NewEvent {
  return {
    title: 'Export Evaluation',
    description: '',
    type: 'SIMPLE',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disabled',
    comments: 'Disabled',
    resultMode: 'Detailed',
    dates: lateDates(),
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

// A small CSV reader: quoted fields may hold commas and doubled quotes. The
// export ends with an empty line, which is dropped.
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
  return rows.filter((cells) => cells.some((cell) => cell !== ''));
}

async function openExport(page: Page, eventId: string): Promise<void> {
  await page.goto(`/evaluations/export/event/${eventId}`);
  await expect(pageTitle(page)).toContainText('Export Evaluation Results');
}

// Presses Export and returns the file that the browser saves.
async function exportFile(page: Page): Promise<{ name: string; content: Buffer }> {
  return test.step(
    'Export',
    async () => {
      const download = page.waitForEvent('download');
      await page.getByRole('button', { name: 'Export', exact: true }).click();
      const file = await download;
      const path = await file.path();
      return { name: file.suggestedFilename(), content: readFileSync(path) };
    },
    { box: true }
  );
}

// The rows of the CSV export, keyed by evaluatee and evaluator, with the
// columns after them. Rows without an answer are left out: the export lists
// every pair of members of the group, answered or not.
async function exportCsvRows(page: Page): Promise<{ header: string[]; rows: string[][] }> {
  const { name, content } = await exportFile(page);
  expect(name).toMatch(/\.csv$/);
  const [header, ...rows] = parseCsv(content.toString('utf8'));
  return { header, rows };
}

// The columns the export always starts with.
const LEAD = ['Course Name', 'Event', 'Evaluation Type', 'Group Name'];
const PEOPLE = ['Evaluatee', 'Evaluatee S#', 'Evaluator', 'Evaluator S#'];
const TOTALS = ['Raw Score', 'Late Penalty', 'Final Score'];

function lead(event: string, type: string): string[] {
  return [COURSES.mech328.name, event, type, GROUP.name];
}

function pair(
  evaluatee: { name: string; number: string },
  evaluator: { name: string; number: string }
) {
  return [evaluatee.name, evaluatee.number, evaluator.name, evaluator.number];
}

// The rows that have an answer: the export also lists every pair of members
// without one, with empty cells.
function answered(rows: string[][], firstAnswerColumn: number): string[][] {
  return rows.filter((row) => (row[firstAnswerColumn] ?? '') !== '');
}

// ---------------------------------------------------------------------------

test('the CSV export of a simple evaluation lists each mark with the late penalty', async ({
  page,
  testDatabase,
}) => {
  const eventId = await insertExportEvent(testDatabase, { title: 'Simple Export' });
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'Did the research, "really" well' },
    { member: MATT.name, points: 80, comment: 'Wrote the report' },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  const { header, rows } = await exportCsvRows(page);
  expect(header).toEqual([...LEAD, ...PEOPLE, 'Comment', ...TOTALS]);
  const lead8 = LEAD.length + PEOPLE.length;
  expect(answered(rows, lead8)).toEqual([
    [
      ...lead('Simple Export', 'Simple Evaluation'),
      ...pair(ALEX, ED),
      'Did the research, "really" well',
      '120',
      '10%',
      '108',
    ],
    [
      ...lead('Simple Export', 'Simple Evaluation'),
      ...pair(MATT, ED),
      'Wrote the report',
      '80',
      '10%',
      '72',
    ],
  ]);
  // Every evaluator of the group has a row for each member they evaluate,
  // also without an answer. The tutor evaluates but is not evaluated.
  expect(rows).toHaveLength(9);
  expect(rows.filter((row) => row[6] === TUTOR)).toHaveLength(3);
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
  const lead8 = LEAD.length + PEOPLE.length;
  expect(answered(rows, lead8)).toEqual([
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
  const lead8 = LEAD.length + PEOPLE.length;
  expect(answered(rows, lead8)).toEqual([
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
  const eventId = await insertExportEvent(testDatabase, { title: 'Simple Export' });
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
  const { header } = await exportCsvRows(page);
  expect(header).toEqual([
    'Event',
    'Evaluation Type',
    'Group Name',
    'Evaluatee',
    'Evaluator',
    ...TOTALS,
  ]);
});

test('the export needs one field of each coloured group', async ({ page, testDatabase }) => {
  const eventId = await insertExportEvent(testDatabase, { title: 'Simple Export' });

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
  await page.getByRole('button', { name: 'Export', exact: true }).click();
  for (const error of Object.values(errors)) {
    await expect(error).toBeVisible();
  }

  await page.getByLabel('Include Event Name').check();
  await page.getByLabel('Include Student Name').check();
  await page.getByLabel('Include Grades').check();
  await exportFile(page);
  for (const error of Object.values(errors)) {
    await expect(error).toBeHidden();
  }
  expect(downloads).toBe(1);
});

test('the PDF export gives a PDF file', async ({ page, testDatabase }) => {
  const eventId = await insertExportEvent(testDatabase, { title: 'Simple Export' });
  await insertSimpleSubmission(testDatabase, eventId, GROUP.id, ED.username, [
    { member: ALEX.name, points: 120, comment: 'good' },
    { member: MATT.name, points: 80, comment: 'ok' },
  ]);
  await endSubmissions(testDatabase, eventId);

  await login(page, USERS.instructor1);
  await openExport(page, eventId);
  await page.getByLabel('Export File Type').selectOption('pdf');
  // The PDF has no columns per student, so the form hides those choices.
  await expect(page.getByLabel('Include Student Name')).toBeHidden();
  const { name, content } = await exportFile(page);
  expect(name).toMatch(/\.pdf$/);
  expect(content.subarray(0, 5).toString('latin1')).toBe('%PDF-');
  expect(content.length).toBeGreaterThan(1000);
});

test('the course export lists the evaluation events of the course', async ({
  page,
  testDatabase,
}) => {
  await insertExportEvent(testDatabase, { title: 'Simple Export' });

  await login(page, USERS.instructor1);
  await page.goto(`/courses/home/${COURSES.mech328.id}`);
  await page.getByRole('link', { name: 'Export Evaluation Results', exact: true }).click();
  await expect(pageTitle(page)).toHaveText(
    `${fullName(COURSES.mech328)} > Export Evaluation Results`
  );
  const events = page.getByLabel('Event Name');
  await expect(events.locator('option', { hasText: 'Simple Export' })).toHaveCount(1);
  // Surveys have no results to export.
  await expect(events.locator('option', { hasText: 'Team Creation Survey' })).toHaveCount(0);
});

test('an instructor cannot export the results of a course they do not teach', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto(`/evaluations/export/course/${COURSES.apsc201.id}`);
  await expect(flashMessage(page)).toHaveText(
    'Error: Course does not exist or you do not have permission to view this course.'
  );
  await expect(page).toHaveURL(/\/courses$/);
});
