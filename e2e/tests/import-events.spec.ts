import { expect, test, type Page } from '../support/test';
import { idFromAjaxList } from '../support/ajax-list';
import { searchEventList, viewField } from '../support/events';
import { fixture, flashMessage, goodMessage, login, notCsvFile } from '../support/helpers';
import { COURSES, MECH328_GROUPS, USERS, fullName } from '../support/seed';

// Ported from app/tests/cases/system/import_events.test.php. The old CSV files
// also imported a survey, which is out of scope, so these files use the other
// three evaluation types. See importEventsByCsv in app/models/event.php.

const COURSE = COURSES.mech328;
const { reapers, lazyEngineers } = MECH328_GROUPS;

// The columns of an import file, in order: the header that the export writes.
const COLUMNS = [
  'Title',
  'Description',
  'Type',
  'Template',
  'Opens',
  'Due',
  'Closes',
  'Results Open',
  'Results Close',
  'Self-Evaluation?',
  'Comments required?',
  'Auto-release results?',
  'Student result mode',
  'Groups',
] as const;
type Column = (typeof COLUMNS)[number];

// The dates in e2e/fixtures/events-*.csv, as the view page shows them.
const DATES = {
  opens: 'Thu, Jul 1, 2049 9:00 am',
  due: 'Thu, Jul 8, 2049 9:00 am',
  closes: 'Thu, Jul 15, 2049 9:00 am',
  resultsOpen: 'Fri, Jul 16, 2049 9:00 am',
  resultsClose: 'Sat, Jul 31, 2049 9:00 am',
};

type ImportedEvent = {
  title: string;
  description: string;
  // As the view page and the event list show the type.
  type: 'SIMPLE' | 'RUBRIC' | 'MIX EVALUATION';
  listType: 'Simple' | 'Rubric' | 'Mixed';
  template: string;
  selfEval: 'Enable' | 'Disable';
  comments: 'Yes' | 'No';
  autoRelease: 'Enable' | 'Disable';
  resultMode: 'Basic' | 'Detailed';
  groups: readonly { name: string }[];
};

// The events in e2e/fixtures/events-valid.csv. Each one has other settings, and
// each way to name the groups: one id, a list of ids, and * for all groups.
const IMPORTED: readonly ImportedEvent[] = [
  {
    title: 'Imported Simple Evaluation',
    description: 'Simple evaluation from a CSV file',
    type: 'SIMPLE',
    listType: 'Simple',
    template: 'Module 1 Project Evaluation',
    selfEval: 'Disable',
    comments: 'No',
    autoRelease: 'Disable',
    resultMode: 'Detailed',
    groups: [reapers],
  },
  {
    title: 'Imported Rubric Evaluation',
    description: 'Rubric evaluation from a CSV file',
    type: 'RUBRIC',
    listType: 'Rubric',
    template: 'Term Report Evaluation',
    selfEval: 'Enable',
    comments: 'Yes',
    autoRelease: 'Enable',
    resultMode: 'Basic',
    groups: [reapers, lazyEngineers],
  },
  {
    title: 'Imported Mixed Evaluation',
    description: 'Mixed evaluation from a CSV file',
    type: 'MIX EVALUATION',
    listType: 'Mixed',
    template: 'Default Mix Evaluation',
    selfEval: 'Enable',
    comments: 'No',
    autoRelease: 'Disable',
    resultMode: 'Detailed',
    groups: [reapers, lazyEngineers],
  },
];

// The titles of the imported events all contain this text, and no seeded
// event does.
const IMPORTED_TEXT = 'Imported';

const NO_COURSE = 'Error: Course does not exist or you do not have permission to view this course.';

type Upload = string | { name: string; mimeType: string; buffer: Buffer };

async function importFile(page: Page, file: Upload): Promise<void> {
  await page.goto(`/events/import/${COURSE.id}`);
  await page.locator('#ImportEventsFile').setInputFiles(file);
  await page.getByRole('button', { name: 'Import', exact: true }).click();
}

// A valid row for the error cases, with the changes that make it wrong.
function row(changes: Partial<Record<Column, string>> = {}): string[] {
  const valid: Record<Column, string> = {
    Title: 'Bad Event',
    Description: '',
    Type: '1',
    Template: '1',
    Opens: '2049-07-01 09:00:00',
    Due: '2049-07-08 09:00:00',
    Closes: '2049-07-15 09:00:00',
    'Results Open': '2049-07-16 09:00:00',
    'Results Close': '2049-07-31 09:00:00',
    'Self-Evaluation?': '0',
    'Comments required?': '0',
    'Auto-release results?': '0',
    'Student result mode': '1',
    Groups: '1',
  };
  return COLUMNS.map((column) => changes[column] ?? valid[column]);
}

function csvFile(rows: readonly (readonly string[])[]): Upload {
  return {
    name: 'events.csv',
    mimeType: 'text/csv',
    buffer: Buffer.from(rows.map((cells) => `${cells.join(',')}\n`).join('')),
  };
}

async function expectImportFailed(page: Page, message: string): Promise<void> {
  await test.step(
    'Expect the import to fail',
    async () => {
      // A failed import shows the form again, with the reason.
      await expect(flashMessage(page)).toHaveText(message);
      await expect(page).toHaveURL(new RegExp(`/events/import/${COURSE.id}$`));
      await expect(page.locator('#ImportEventsFile')).toBeVisible();
    },
    { box: true }
  );
}

async function expectNoImportedEvents(page: Page): Promise<void> {
  await page.goto(`/events/index/${COURSE.id}`);
  await searchEventList(page, IMPORTED_TEXT, 0);
}

async function expectImportedEvent(page: Page, id: string, event: ImportedEvent): Promise<void> {
  await test.step(
    `Expect the imported event ${event.title}`,
    async () => {
      await page.goto(`/events/view/${id}`);
      await expect(viewField(page, 'Course')).toHaveText(fullName(COURSE));
      await expect(viewField(page, 'Event Title')).toHaveText(event.title);
      await expect(viewField(page, 'Description')).toHaveText(event.description);
      await expect(viewField(page, 'Evaluation Format')).toHaveText(event.type);
      await expect(viewField(page, 'Evaluation')).toHaveText(event.template);
      await expect(viewField(page, 'Allow Self-Evaluation?')).toHaveText(event.selfEval);
      await expect(viewField(page, 'Require Student Comments?')).toHaveText(event.comments);
      await expect(viewField(page, 'Auto-Release Results?')).toHaveText(event.autoRelease);
      await expect(viewField(page, 'Student Result Mode')).toHaveText(event.resultMode);
      await expect(viewField(page, 'Due Date')).toHaveText(DATES.due);
      await expect(page.locator('#release_date_begin tr')).toHaveText([
        `FROM: ${DATES.opens}`,
        `TO: ${DATES.closes}`,
      ]);
      await expect(page.locator('#result_release_date_begin tr')).toHaveText([
        `FROM: ${DATES.resultsOpen}`,
        `TO: ${DATES.resultsClose}`,
      ]);
      const groups = viewField(page, 'Groups Assignment');
      for (const group of [reapers, lazyEngineers]) {
        await expect(
          groups.getByRole('cell', { name: group.name, exact: true }),
          group.name
        ).toHaveCount(event.groups.includes(group) ? 1 : 0);
      }
    },
    { box: true }
  );
}

test('an instructor imports events, with their settings, dates and groups', async ({ page }) => {
  await login(page, USERS.instructor1);

  // The file has the header row that the export writes, which the import skips.
  await importFile(page, fixture('events-valid.csv'));
  await expect(goodMessage(page)).toHaveText(
    `${IMPORTED.length} event(s) were imported successfully.`
  );
  await expect(page).toHaveURL(new RegExp(`/events/index/${COURSE.id}$`));

  await searchEventList(page, IMPORTED_TEXT, IMPORTED.length);
  const ids: string[] = [];
  for (const event of IMPORTED) {
    const listRow = page
      .locator('#ajaxListDiv tr')
      .filter({ has: page.getByRole('link', { name: event.title, exact: true }) });
    await expect(listRow.getByRole('cell', { name: event.listType, exact: true })).toBeVisible();
    await expect(
      listRow.getByRole('cell', { name: String(event.groups.length), exact: true })
    ).toBeVisible();
    ids.push(await idFromAjaxList(page, event.title, 'events'));
  }

  for (const [index, event] of IMPORTED.entries()) {
    await expectImportedEvent(page, ids[index], event);
  }
});

test('an import with an error saves none of the events', async ({ page }) => {
  await login(page, USERS.instructor1);

  // The first two rows are valid. The third has no evaluation type.
  await importFile(page, fixture('events-missing-type.csv'));
  await expectImportFailed(
    page,
    'Import Failed Event "Imported Missing Type" (on row 3), has an invalid field: "Type": ' +
      'Please select a template type.'
  );

  await expectNoImportedEvents(page);
});

// Each file has one row with one error, and the message names the row.
const ERRORS: readonly { name: string; rows: string[][]; message: string }[] = [
  {
    name: 'a template that does not exist',
    rows: [row({ Template: '99' })],
    message: 'Event "Bad Event" (on row 1), has a non-existent simple evaluation of id: 99',
  },
  {
    name: 'an unknown evaluation type',
    rows: [row({ Type: '7' })],
    message: 'Event "Bad Event" (on row 1), has an invalid template type "7"',
  },
  {
    name: 'a group that is not in the course',
    rows: [row({ Groups: '1;99' })],
    message: 'Event "Bad Event" (on row 1), has an invalid group of id: "99"',
  },
  {
    name: 'a date that is not a date',
    rows: [row({ Due: 'not a date' })],
    message:
      'Event "Bad Event" (on row 1), has an invalid field: "Due": ' +
      'Must be in Year-Month-Day Hour:Minute:Second format.',
  },
  {
    // An export of last term's events, with the dates not yet changed.
    name: 'a closing date in the past',
    rows: [
      row({
        Opens: '2011-01-01 09:00:00',
        Due: '2011-01-08 09:00:00',
        Closes: '2011-01-15 09:00:00',
      }),
    ],
    message:
      'Event "Bad Event" (on row 1), has a closing date in the past. ' +
      'Please check that you have updated the dates for the new session/term',
  },
  {
    name: 'too few columns',
    rows: [row().slice(0, COLUMNS.indexOf('Closes'))],
    message: 'Event on row 1 does not have an entry for column: Closes',
  },
  {
    name: 'too many columns',
    rows: [[...row(), 'extra']],
    message: 'Event on row 1 has too many columns',
  },
  {
    name: 'a header row and no events',
    rows: [[...COLUMNS]],
    message: 'Error: No events to import.',
  },
];

for (const error of ERRORS) {
  test(`an import of ${error.name} is refused`, async ({ page }) => {
    await login(page, USERS.instructor1);
    await importFile(page, csvFile(error.rows));
    await expectImportFailed(page, `Import Failed ${error.message}`);
  });
}

test('an empty file is refused', async ({ page }) => {
  await login(page, USERS.instructor1);
  await importFile(page, csvFile([]));
  await expectImportFailed(
    page,
    'Error: The file got uploaded, but could not be processed as a csv.'
  );
});

test('a file that is not a CSV is refused', async ({ page }) => {
  await login(page, USERS.instructor1);
  await importFile(page, notCsvFile('events'));
  await expectImportFailed(page, 'extension is not allowed.');
});

test('an instructor cannot import events into a course they do not teach', async ({ page }) => {
  // Instructor 2 does not teach MECH 328.
  await login(page, USERS.instructor2);
  await page.goto(`/events/import/${COURSE.id}`);
  await expect(flashMessage(page)).toHaveText(NO_COURSE);
  await expect(page).toHaveURL(/\/courses$/);

  // The upload itself, sent without the form.
  const response = await page.request.post(`/events/import/${COURSE.id}`, {
    multipart: {
      file: {
        name: 'events.csv',
        mimeType: 'text/csv',
        buffer: Buffer.from(row({ Title: `${IMPORTED_TEXT} By Instructor 2` }).join(',')),
      },
    },
    headers: { referer: new URL(`/events/import/${COURSE.id}`, page.url()).href },
  });
  expect(response.ok(), 'the refused import').toBe(true);
  expect(response.url()).toMatch(/\/courses$/);
  expect(await response.text()).toContain(NO_COURSE);

  await login(page, USERS.instructor1);
  await expectNoImportedEvents(page);
});
