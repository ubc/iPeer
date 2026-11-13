import { expect, test, type Locator, type Page } from '../support/test';
import {
  errorMessage,
  flashMessage,
  goodMessage,
  idFromUrl,
  login,
  pageTitle,
  withConfirmation,
} from '../support/helpers';
import { idFromAjaxList, runAjaxListAction, waitForAjaxList } from '../support/ajax-list';
import { COURSES, MECH328_GROUPS, USERS, fullName } from '../support/seed';
import type { RowDataPacket } from 'mysql2/promise';
import type { TestDatabase } from '../support/db';
import { NOW, findId, insertRow, userId } from '../support/insert';

// Ported from app/tests/cases/system/add_event.test.php. The Selenium test also
// ran the send_emails console command, to check which students get a reminder.
// A browser test cannot run that command, so that part is not ported.

// A seeded event of MECH 328. Students have submitted it, its release started
// in 2011, and it has four late penalties.
const SEEDED = {
  id: '1',
  title: 'Term 1 Evaluation',
  template: 'Module 1 Project Evaluation',
  templateId: '1',
  penalties: [
    { days: '1', percent: '20' },
    { days: '2', percent: '40' },
    { days: '3', percent: '60' },
    { days: '4', percent: '100' },
  ],
};

const GROUPS = [MECH328_GROUPS.reapers, MECH328_GROUPS.lazyEngineers];
const ALL_GROUP_IDS = GROUPS.map((group) => group.id);
// Each member once: Matt is in both groups.
const GROUP_MEMBERS = [...new Set(GROUPS.flatMap((group) => group.members))].sort();

type TemplateType = 'SIMPLE' | 'RUBRIC' | 'MIX EVALUATION';

// The template list that the form shows for each type.
const TEMPLATE_SELECT: Record<TemplateType, string> = {
  SIMPLE: '#EventSimpleEvaluation',
  RUBRIC: '#EventRubric',
  'MIX EVALUATION': '#EventMixeval',
};

type Penalty = { days: string; percent: string };

type Dates = {
  releaseBegin: string;
  due: string;
  releaseEnd: string;
  resultBegin: string;
  resultEnd: string;
};

type NewEvent = {
  title: string;
  description: string;
  type: TemplateType;
  template: string;
  selfEval: 'Enabled' | 'Disabled';
  comments: 'Enabled' | 'Disabled';
  resultMode: 'Basic' | 'Detailed';
  dates: Dates;
  // The reminder frequency label, such as '2 Days', and the email template.
  reminders?: { every: string; template: string };
  allGroups: boolean;
  penalties: readonly Penalty[];
};

// The events are in the future, so their release has not started: the edit
// form does not lock the groups, and the first reminder is on the release
// date. February has no change to or from daylight saving time, so each
// interval between reminders is a whole number of days.
const YEAR = new Date().getFullYear() + 1;
const feb = (day: number) => `${YEAR}-02-${String(day).padStart(2, '0')} 09:00:00`;

const DATES: Dates = {
  releaseBegin: feb(2),
  due: feb(9),
  releaseEnd: feb(16),
  resultBegin: feb(17),
  resultEnd: feb(28),
};

const REMINDER = {
  template: 'Evaluation Reminder Template',
  subject: 'iPeer Evaluation Reminder',
} as const;

const FINAL_PROJECT: NewEvent = {
  title: 'Final Project Peer Evaluation',
  description: 'Peer evaluation for the final project',
  type: 'RUBRIC',
  template: 'Term Report Evaluation',
  selfEval: 'Enabled',
  comments: 'Enabled',
  resultMode: 'Basic',
  dates: DATES,
  // From 2 to 9 February: the 2nd, 4th, 6th and 8th.
  reminders: { every: '2 Days', template: REMINDER.template },
  allGroups: true,
  penalties: [
    { days: '2', percent: '10' },
    { days: '5', percent: '50' },
  ],
};

const PLAIN: NewEvent = {
  title: 'Midterm Peer Evaluation',
  description: '',
  type: 'SIMPLE',
  template: SEEDED.template,
  selfEval: 'Disabled',
  comments: 'Disabled',
  resultMode: 'Detailed',
  dates: DATES,
  allGroups: true,
  penalties: [],
};

const SUBMIT = { name: 'Submit', exact: true } as const;
const ADDED = 'Add event successful!';
const EDITED = 'Edit event successful!';
const GROUPS_REMOVED =
  'You have removed one or more groups from the event. ' +
  'If there are submissions made within the groups, they would be permanently deleted. ' +
  'Are you sure you want to remove the groups?';

// The radio buttons of one setting, such as 'Self-Evaluation'. The settings
// share the labels Enabled and Disabled, so the legend tells them apart.
function setting(page: Page, legend: string): Locator {
  return page.getByRole('group', { name: legend, exact: true });
}

// The date fields open a date picker, which writes the current date into an
// empty field as it opens. Open it first, so the typed date replaces that
// date, then close it, so it does not cover the next field.
async function fillDate(page: Page, id: string, value: string): Promise<void> {
  const field = page.locator(id);
  await field.click();
  await expect(page.locator('#ui-datepicker-div')).toBeVisible();
  await field.fill(value);
  await field.press('Escape');
  await expect(field, id).toHaveValue(value);
}

async function fillDates(page: Page, dates: Dates): Promise<void> {
  await fillDate(page, '#EventDueDate', dates.due);
  await fillDate(page, '#EventReleaseDateBegin', dates.releaseBegin);
  await fillDate(page, '#EventReleaseDateEnd', dates.releaseEnd);
  await fillDate(page, '#EventResultReleaseDateBegin', dates.resultBegin);
  await fillDate(page, '#EventResultReleaseDateEnd', dates.resultEnd);
}

function penaltyRows(page: Page): Locator {
  return page.locator('#penaltyInputs .penaltyInput');
}

async function addPenalty(page: Page, penalty?: Penalty): Promise<void> {
  const rows = penaltyRows(page);
  const before = await rows.count();
  await page.getByRole('link', { name: 'Add Penalty', exact: true }).click();
  await expect(rows).toHaveCount(before + 1);
  if (penalty) {
    const added = rows.nth(before);
    await added.locator('input[id$="DaysLate"]').fill(penalty.days);
    await added.locator('select[id$="PercentPenalty"]').selectOption(penalty.percent);
  }
}

async function expectPenaltyInputs(page: Page, penalties: readonly Penalty[]): Promise<void> {
  await test.step(
    'Expect the late penalty inputs',
    async () => {
      const rows = penaltyRows(page);
      await expect(rows).toHaveCount(penalties.length);
      for (const [index, penalty] of penalties.entries()) {
        const row = rows.nth(index);
        await expect(row.locator('input[id$="DaysLate"]'), `penalty ${index + 1} days`).toHaveValue(
          penalty.days
        );
        await expect(
          row.locator('select[id$="PercentPenalty"]'),
          `penalty ${index + 1} percent`
        ).toHaveValue(penalty.percent);
      }
    },
    { box: true }
  );
}

async function fillForm(page: Page, event: NewEvent): Promise<void> {
  await test.step(
    `Fill in the event ${event.title}`,
    async () => {
      await page.locator('#EventTitle').fill(event.title);
      await page.locator('#EventDescription').fill(event.description);
      await page.locator('#EventEventTemplateTypeId').selectOption({ label: event.type });
      await page.locator(TEMPLATE_SELECT[event.type]).selectOption({ label: event.template });
      await setting(page, 'Self-Evaluation').getByLabel(event.selfEval, { exact: true }).check();
      await setting(page, 'Comments Required').getByLabel(event.comments, { exact: true }).check();
      await setting(page, 'Student Result Mode')
        .getByLabel(event.resultMode, { exact: true })
        .check();
      await fillDates(page, event.dates);
      if (event.reminders) {
        await page.locator('#EventEmailSchedule').selectOption({ label: event.reminders.every });
        await page.locator('#EventEmailTemplate').selectOption({ label: event.reminders.template });
      }
      if (event.allGroups) {
        await page.getByRole('button', { name: 'Select All', exact: true }).click();
      }
      for (const penalty of event.penalties) {
        await addPenalty(page, penalty);
      }
    },
    { box: true }
  );
}

// The id of each evaluation type in event_template_types, and the table of
// its templates.
const TEMPLATE_TYPES: Record<TemplateType, { id: number; table: string }> = {
  SIMPLE: { id: 1, table: 'simple_evaluations' },
  RUBRIC: { id: 2, table: 'rubrics' },
  'MIX EVALUATION': { id: 4, table: 'mixevals' },
};

// Adds whole days to a date such as '2027-02-02 09:00:00'. Like PHP's
// strtotime('+2 day'), it keeps the time of day.
function addDays(date: string, days: number): string {
  const time = new Date(`${date.replace(' ', 'T')}Z`);
  time.setUTCDate(time.getUTCDate() + days);
  return time.toISOString().slice(0, 19).replace('T', ' ');
}

// Writes the rows that a save of the Add Event form writes (see add and
// setSchedule in events_controller.php), and returns the id of the event.
async function insertEvent(
  testDatabase: TestDatabase,
  courseId: string,
  event: NewEvent,
  creator: string = USERS.instructor1
): Promise<string> {
  return test.step(
    `Insert the event ${event.title}`,
    async () => {
      const { connection } = testDatabase;
      // The first reminder is on the release date only when the release is in
      // the future. Otherwise the save starts the reminders at the time of the
      // save, which these rows do not copy.
      expect(
        Date.parse(event.dates.releaseBegin.replace(' ', 'T')),
        'release date'
      ).toBeGreaterThan(Date.now());
      const owner = await userId(testDatabase, creator);
      const type = TEMPLATE_TYPES[event.type];
      const id = await insertRow(testDatabase, 'events', {
        title: event.title,
        course_id: courseId,
        // The save stores an empty field as NULL.
        description: event.description || null,
        event_template_type_id: type.id,
        template_id: await findId(testDatabase, type.table, 'name', event.template),
        self_eval: event.selfEval === 'Enabled' ? '1' : '0',
        com_req: event.comments === 'Enabled' ? 1 : 0,
        auto_release: 0,
        enable_details: event.resultMode === 'Detailed' ? 1 : 0,
        due_date: event.dates.due,
        release_date_begin: event.dates.releaseBegin,
        release_date_end: event.dates.releaseEnd,
        result_release_date_begin: event.dates.resultBegin,
        result_release_date_end: event.dates.resultEnd,
        creator_id: owner,
        created: NOW,
        updater_id: owner,
        modified: NOW,
      });

      // Cake writes only the two keys of each group row. The other columns,
      // such as the release status 'None', keep their defaults.
      const groupIds: string[] = [];
      if (event.allGroups) {
        const [groups] = await connection.query<RowDataPacket[]>(
          'SELECT id FROM `groups` WHERE course_id = ? ORDER BY id',
          [courseId]
        );
        groupIds.push(...groups.map((group) => String(group.id)));
      }
      for (const groupId of groupIds) {
        await insertRow(testDatabase, 'group_events', { event_id: id, group_id: groupId });
      }

      for (const penalty of event.penalties) {
        await insertRow(testDatabase, 'penalties', {
          event_id: id,
          days_late: Number(penalty.days),
          percent_penalty: Number(penalty.percent),
        });
      }

      // One reminder every few days from the release date until the due date,
      // to every member of the groups. A student in two groups is listed
      // twice, as the save does.
      if (event.reminders && groupIds.length > 0) {
        const [members] = await connection.query<RowDataPacket[]>(
          'SELECT user_id FROM groups_members WHERE group_id IN (?)',
          [groupIds]
        );
        const [templates] = await connection.query<RowDataPacket[]>(
          'SELECT id, subject FROM email_templates WHERE name = ?',
          [event.reminders.template]
        );
        expect(templates, `the email template ${event.reminders.template}`).toHaveLength(1);
        const days = parseInt(event.reminders.every, 10);
        for (
          let date = event.dates.releaseBegin;
          date < event.dates.due;
          date = addDays(date, days)
        ) {
          await insertRow(testDatabase, 'email_schedules', {
            subject: String(templates[0].subject),
            // The id of the email template, not its text.
            content: String(templates[0].id),
            date,
            from: owner,
            to: ['save_reminder', ...members.map((member) => String(member.user_id))].join(';'),
            course_id: courseId,
            event_id: id,
            creator_id: owner,
            created: NOW,
          });
        }
      }
      return id;
    },
    { box: true }
  );
}

// The value that the event view page shows next to a heading, such as
// 'Due Date'. Only the heading cell is matched, so a value with the same text
// as a heading cannot select the wrong row.
function viewField(page: Page, heading: string): Locator {
  return page
    .locator('table.standardtable > tbody > tr')
    .filter({ has: page.locator('xpath=./th').getByText(heading, { exact: true }) })
    .locator('xpath=./td');
}

function penaltyLines(penalties: readonly Penalty[]): string[] {
  if (penalties.length === 0) {
    return ['No late penalties currently set'];
  }
  const days = (count: string) => `${count} ${count === '1' ? 'day' : 'days'}`;
  const lines = penalties.map((penalty, index) =>
    index === 0
      ? `From the due date to ${days(penalty.days)} late, ${penalty.percent}% will be deducted`
      : `Then until ${penalty.days} days after the due date, ${penalty.percent}% will be deducted`
  );
  const last = penalties[penalties.length - 1];
  return [...lines, `${last.percent}% is deducted after ${days(last.days)}`];
}

async function expectPenaltiesOnView(page: Page, penalties: readonly Penalty[]): Promise<void> {
  const field = viewField(page, 'Late Penalty');
  for (const line of penaltyLines(penalties)) {
    await expect(field).toContainText(line);
  }
}

// The reminders of the signed-in user on the Email page, with the subject of
// the event reminders.
async function openReminders(page: Page): Promise<Locator> {
  await page.goto('/emailer');
  // The list proves the page loaded, so a count of zero means no reminders.
  await waitForAjaxList(page);
  return page.locator('#ajaxListDiv').getByRole('link', { name: REMINDER.subject, exact: true });
}

async function expectReminderCount(page: Page, count: number): Promise<void> {
  await test.step(
    `Expect ${count} reminders`,
    async () => {
      await expect(await openReminders(page)).toHaveCount(count);
    },
    { box: true }
  );
}

async function openEdit(page: Page, id: string): Promise<void> {
  await page.goto(`/events/edit/${id}`);
  await expect(page.locator('#EventTitle')).toBeVisible();
}

async function saveEdit(page: Page): Promise<void> {
  await page.getByRole('button', SUBMIT).click();
  await expect(goodMessage(page)).toHaveText(EDITED);
}

// Checks the view page, the edit form and the reminders of FINAL_PROJECT,
// which must be the only event with reminders. Opens the view page first.
async function expectFinalProject(page: Page, id: string): Promise<void> {
  await test.step(
    `Expect the event ${FINAL_PROJECT.title}`,
    async () => {
      await page.goto(`/events/view/${id}`);
      await expect(viewField(page, 'Course')).toHaveText(fullName(COURSES.mech328));
      await expect(viewField(page, 'Event Title')).toHaveText(FINAL_PROJECT.title);
      await expect(viewField(page, 'Description')).toHaveText(FINAL_PROJECT.description);
      await expect(viewField(page, 'Evaluation Format')).toHaveText(FINAL_PROJECT.type);
      await expect(viewField(page, 'Evaluation')).toHaveText(FINAL_PROJECT.template);
      await expect(viewField(page, 'Allow Self-Evaluation?')).toHaveText('Enable');
      await expect(viewField(page, 'Require Student Comments?')).toHaveText('Yes');
      await expect(viewField(page, 'Auto-Release Results?')).toHaveText('Disable');
      await expect(viewField(page, 'Student Result Mode')).toHaveText('Basic');
      await expect(viewField(page, 'Due Date')).toContainText(`Feb 9, ${YEAR} 9:00 am`);
      await expectPenaltiesOnView(page, FINAL_PROJECT.penalties);
      for (const group of GROUPS) {
        await expect(
          viewField(page, 'Groups Assignment').getByRole('cell', { name: group.name, exact: true })
        ).toBeVisible();
      }

      // The edit form shows what was saved.
      await openEdit(page, id);
      await expect(page.locator('#EventCourseId')).toHaveCount(0);
      await expect(page.locator('label.course')).toHaveText(fullName(COURSES.mech328));
      await expect(page.locator('#EventDueDate')).toHaveValue(DATES.due);
      await expect(page.locator('#EventEmailSchedule')).toHaveValue('2');
      await expect(page.locator('#GroupGroup')).toHaveValues(ALL_GROUP_IDS);
      await expectPenaltyInputs(page, FINAL_PROJECT.penalties);

      // A reminder goes to every member of the event's groups.
      const reminders = await openReminders(page);
      await expect(reminders).toHaveCount(4);
      await reminders.first().click();
      await expect(page.locator('#emailContent')).toContainText(
        'A evaluation for {{{COURSENAME}}} is made available to you in iPeer'
      );
      const recipients = page
        .locator('table.standardtable tr')
        .filter({ has: page.getByRole('cell', { name: 'To', exact: true }) })
        .getByRole('link');
      await expect(recipients).toHaveCount(GROUP_MEMBERS.length);
      expect((await recipients.allTextContents()).sort()).toEqual(GROUP_MEMBERS);
    },
    { box: true }
  );
}

test('the Add Event form refuses a missing title and missing dates', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto(`/events/add/${COURSES.mech328.id}`);

  // An instructor can add events only to their own course.
  await expect(page.locator('#EventCourseId option')).toHaveCount(1);
  await expect(page.locator('#EventCourseId')).toHaveValue(COURSES.mech328.id);

  await expect(setting(page, 'Self-Evaluation').getByLabel('Disabled')).toBeChecked();
  await expect(setting(page, 'Comments Required').getByLabel('Disabled')).toBeChecked();
  await expect(setting(page, 'Auto-Release Results').getByLabel('Disabled')).toBeChecked();
  await expect(setting(page, 'Student Result Mode').getByLabel('Detailed')).toBeChecked();

  await page.getByRole('button', SUBMIT).click();
  await expect(flashMessage(page)).toHaveText('Add event failed.');
  const dateFormat = 'Must be in Year-Month-Day Hour:Minute:Second format.';
  await expect(errorMessage(page)).toHaveText([
    'Title is required.',
    dateFormat,
    dateFormat,
    dateFormat,
    dateFormat,
    dateFormat,
  ]);
});

test('the Add Event form shows the fields of the chosen evaluation type', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto(`/events/add/${COURSES.mech328.id}`);
  const type = page.locator('#EventEventTemplateTypeId');

  await type.selectOption({ label: 'RUBRIC' });
  await expect(page.locator('#RubricDiv')).toBeVisible();
  await expect(page.locator('#SimpleEvalDiv')).toBeHidden();
  await expect(page.locator('#prevR')).toHaveAttribute('href', /\/rubrics\/view\/1$/);

  // The preview of a mixed evaluation shows the self-evaluation questions
  // only when the event enables them.
  await type.selectOption({ label: 'MIX EVALUATION' });
  await expect(page.locator('#MixevalDiv')).toBeVisible();
  await expect(page.locator('#RubricDiv')).toBeHidden();
  await expect(page.locator('#prevM')).toHaveAttribute('href', /\/mixevals\/view\/1\/0$/);
  await setting(page, 'Self-Evaluation').getByLabel('Enabled', { exact: true }).check();
  await expect(page.locator('#prevM')).toHaveAttribute('href', /\/mixevals\/view\/1\/1$/);

  // A survey has no groups, late penalties or result release.
  await expect(page.locator('#ResultReleaseBeginDiv')).toBeVisible();
  await type.selectOption({ label: 'SURVEY' });
  await expect(page.locator('#SurveyDiv')).toBeVisible();
  await expect(page.locator('#prevV')).toHaveAttribute('href', /\/surveys\/view\/\d+$/);
  await expect(page.locator('#ResultReleaseBeginDiv')).toBeHidden();
  await expect(page.locator('#ResultReleaseEndDiv')).toBeHidden();
  await expect(page.locator('#GroupsDiv')).toBeHidden();
  await expect(page.locator('#penaltyInputs')).toBeHidden();
  await expect(page.locator('#EventDueDate')).toBeVisible();

  await type.selectOption({ label: 'SIMPLE' });
  await expect(page.locator('#SimpleEvalDiv')).toBeVisible();
  await expect(page.locator('#GroupsDiv')).toBeVisible();
  await expect(page.locator('#penaltyInputs')).toBeVisible();
});

test('a new late penalty starts one day after the last one added', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto(`/events/add/${COURSES.mech328.id}`);

  await addPenalty(page);
  await addPenalty(page);
  await addPenalty(page);
  await expectPenaltyInputs(page, [
    { days: '1', percent: '1' },
    { days: '2', percent: '1' },
    { days: '3', percent: '1' },
  ]);
  await expect(penaltyRows(page).nth(1).locator('select option')).toHaveCount(100);

  await penaltyRows(page).nth(0).getByRole('link', { name: 'X', exact: true }).click();
  await addPenalty(page);
  await expectPenaltyInputs(page, [
    { days: '2', percent: '1' },
    { days: '3', percent: '1' },
    { days: '4', percent: '1' },
  ]);
});

test('an instructor adds an event with reminders, groups and late penalties', async ({ page }) => {
  await login(page, USERS.instructor1);
  await page.goto(`/events/add/${COURSES.mech328.id}`);
  await expect(pageTitle(page)).toContainText('Add Event');

  await fillForm(page, FINAL_PROJECT);
  await expect(page.locator('#GroupGroup')).toHaveValues(ALL_GROUP_IDS);
  await page.getByRole('button', SUBMIT).click();
  await expect(goodMessage(page)).toHaveText(ADDED);

  await waitForAjaxList(page);
  await page
    .locator('#ajaxListDiv')
    .getByRole('link', { name: FINAL_PROJECT.title, exact: true })
    .click();
  await expect(page).toHaveURL(/\/events\/view\/\d+$/);
  const id = idFromUrl(page);
  await expectFinalProject(page, id);
});

// The insert must give what the form gives, or the tests that use it test
// something the app cannot make.
test('an inserted event shows like one added through the form', async ({ page, testDatabase }) => {
  await login(page, USERS.instructor1);
  const id = await insertEvent(testDatabase, COURSES.mech328.id, FINAL_PROJECT);
  await expectFinalProject(page, id);
});

test('editing an event can remove all of its groups', async ({ page, testDatabase }) => {
  await login(page, USERS.instructor1);
  const id = await insertEvent(testDatabase, COURSES.mech328.id, PLAIN);

  await openEdit(page, id);
  await expect(page.locator('#GroupGroup')).toHaveValues(ALL_GROUP_IDS);
  await page.getByRole('button', { name: 'Unselect All', exact: true }).click();
  await withConfirmation(page, GROUPS_REMOVED, () => page.getByRole('button', SUBMIT).click());
  await expect(goodMessage(page)).toHaveText(EDITED);

  await openEdit(page, id);
  await expect(page.locator('#GroupGroup')).toHaveValues([]);
  await page.goto(`/events/view/${id}`);
  await expect(viewField(page, 'Groups Assignment')).toHaveText('No Groups');
});

test('a new reminder frequency replaces the reminders, and Disable removes them', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.instructor1);
  const id = await insertEvent(testDatabase, COURSES.mech328.id, {
    ...PLAIN,
    reminders: { every: '2 Days', template: REMINDER.template },
  });
  await expectReminderCount(page, 4);

  await openEdit(page, id);
  await expect(page.locator('#EventEmailSchedule')).toHaveValue('2');
  await page.locator('#EventEmailSchedule').selectOption({ label: '4 Days' });
  await saveEdit(page);
  // From 2 to 9 February: the 2nd and the 6th.
  await expectReminderCount(page, 2);

  await openEdit(page, id);
  await expect(page.locator('#EventEmailSchedule')).toHaveValue('4');
  await page.locator('#EventEmailSchedule').selectOption({ label: 'Disable' });
  await saveEdit(page);
  await expectReminderCount(page, 0);

  await openEdit(page, id);
  await expect(page.locator('#EventEmailSchedule')).toHaveValue('0');
});

test('an event with a single reminder shows a frequency of 7 days', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.instructor1);
  // Three days from the release on 2 February is after the due date, so there
  // is one reminder. The form calculates the frequency from the interval
  // between the last two reminders, and with one reminder it shows 7 days.
  // This checks the current behaviour, not the wanted one: the form should
  // show the 3 days that were chosen. Update this test when that is fixed.
  const id = await insertEvent(testDatabase, COURSES.mech328.id, {
    ...PLAIN,
    dates: { ...DATES, due: feb(4) },
    reminders: { every: '3 Days', template: REMINDER.template },
  });
  await expectReminderCount(page, 1);

  await openEdit(page, id);
  await expect(page.locator('#EventEmailSchedule')).toHaveValue('7');
});

test('a sent reminder stays when the reminders change, and cannot be cancelled', async ({
  page,
  testDatabase,
}) => {
  await login(page, USERS.instructor1);
  const id = await insertEvent(testDatabase, COURSES.mech328.id, {
    ...PLAIN,
    reminders: { every: '2 Days', template: REMINDER.template },
  });
  await expectReminderCount(page, 4);

  // The send_emails console command marks a reminder as sent when it sends
  // it. A browser test cannot run the command, so the test marks the first
  // reminder in the database.
  await testDatabase.connection.query(
    'UPDATE email_schedules SET sent = 1 WHERE event_id = ? ORDER BY date LIMIT 1',
    [id]
  );

  await openEdit(page, id);
  await page.locator('#EventEmailSchedule').selectOption({ label: '4 Days' });
  await saveEdit(page);
  // The sent reminder, and the new reminders on the 2nd and the 6th.
  await expectReminderCount(page, 3);

  await openEdit(page, id);
  await page.locator('#EventEmailSchedule').selectOption({ label: 'Disable' });
  await saveEdit(page);
  const reminders = await openReminders(page);
  await expect(reminders).toHaveCount(1);
  const row = page
    .locator('#ajaxListDiv tr')
    .filter({ has: page.getByRole('link', { name: REMINDER.subject, exact: true }) });
  await expect(row.getByRole('cell', { name: 'Sent', exact: true })).toBeVisible();

  await runAjaxListAction(
    page,
    REMINDER.subject,
    'Cancel Email',
    'Are you sure you want to cancel this email?'
  );
  await expect(flashMessage(page)).toHaveText('Cannot cancel: Email is already sent.');
  await expectReminderCount(page, 1);
});

test('an event with submissions locks its evaluation type and groups, and other changes still save', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);
  await openEdit(page, SEEDED.id);

  await expect(
    page.getByText(
      'The evaluation type and template are locked because submissions have already been received.'
    )
  ).toBeVisible();
  await expect(page.locator('#EventEventTemplateTypeId')).toBeHidden();
  await expect(page.getByRole('link', { name: SEEDED.template, exact: true })).toHaveAttribute(
    'href',
    new RegExp(`/simpleevaluations/view/${SEEDED.templateId}$`)
  );

  // The release has started, so the groups are locked until the user unlocks
  // them.
  await expect(page.locator('#groupLockNotice')).toContainText('Group selection is locked.');
  await expect(page.locator('#GroupGroup')).toBeHidden();
  await page.getByRole('button', { name: 'Unlock group selection', exact: true }).click();
  await expect(page.locator('#GroupGroup')).toBeVisible();
  await expect(page.locator('#GroupGroup')).toHaveValues(ALL_GROUP_IDS);

  // Remove the last late penalty, and change the description.
  await expectPenaltyInputs(page, SEEDED.penalties);
  await penaltyRows(page).nth(3).getByRole('link', { name: 'X', exact: true }).click();
  const kept = SEEDED.penalties.slice(0, 3);
  await expectPenaltyInputs(page, kept);
  await page.locator('#EventDescription').fill('Evaluation of the first term');
  await saveEdit(page);

  await page.goto(`/events/view/${SEEDED.id}`);
  await expect(viewField(page, 'Description')).toHaveText('Evaluation of the first term');
  await expect(viewField(page, 'Evaluation Format')).toHaveText('SIMPLE');
  await expect(viewField(page, 'Evaluation')).toHaveText(SEEDED.template);
  await expectPenaltiesOnView(page, kept);
  await expect(viewField(page, 'Late Penalty')).not.toContainText('100%');
});

test('an instructor deletes an event after a confirmation page', async ({ page, testDatabase }) => {
  await login(page, USERS.instructor1);
  const id = await insertEvent(testDatabase, COURSES.mech328.id, PLAIN);

  await page.goto(`/events/view/${id}`);
  await page.getByRole('link', { name: 'Delete Event', exact: true }).click();
  await expect(page).toHaveURL(new RegExp(`/events/delete/${id}$`));
  await expect(page.locator('#EventDeleteForm code')).toHaveText(
    `${PLAIN.title} (${fullName(COURSES.mech328)})`
  );
  await page.getByRole('button', { name: 'Delete Event', exact: true }).click();
  await expect(goodMessage(page)).toHaveText('The event has been deleted successfully.');

  await expect(page).toHaveURL(new RegExp(`/events/index/${COURSES.mech328.id}$`));
  await waitForAjaxList(page);
  const list = page.locator('#ajaxListDiv');
  await expect(list.getByRole('link', { name: SEEDED.title, exact: true })).toBeVisible();
  await expect(list.getByRole('link', { name: PLAIN.title, exact: true })).toHaveCount(0);
});

test("a superadmin adds an event to any course, and only that course's instructors can reach it", async ({
  page,
}) => {
  const course = COURSES.apsc201;
  const event: NewEvent = { ...PLAIN, title: 'Report Peer Evaluation', allGroups: false };

  await login(page, USERS.superAdmin);
  await page.goto(`/events/add/${COURSES.mech328.id}`);
  // Every active course: CPSC 101 is inactive.
  await expect(page.locator('#EventCourseId option')).toHaveCount(3);
  await expect(page.locator(`#EventCourseId option[value="${COURSES.cpsc101.id}"]`)).toHaveCount(0);
  // Choosing another course opens the form of that course, with its groups.
  await page.locator('#EventCourseId').selectOption(course.id);
  await expect(page).toHaveURL(new RegExp(`/events/add/${course.id}$`));
  await expect(page.locator('#EventCourseId')).toHaveValue(course.id);
  await expect(page.locator('#GroupGroup option')).toHaveCount(0);
  await fillForm(page, event);
  await page.getByRole('button', SUBMIT).click();
  await expect(goodMessage(page)).toHaveText(ADDED);
  await expect(page).toHaveURL(new RegExp(`/events/index/${course.id}$`));
  const id = await idFromAjaxList(page, event.title, 'events');

  // Instructor 1 does not teach APSC 201.
  await login(page, USERS.instructor1);
  const noCourse =
    'Error: Course does not exist or you do not have permission to view this course.';
  await page.goto(`/events/index/${course.id}`);
  await expect(flashMessage(page)).toHaveText(noCourse);
  await expect(page).toHaveURL(/\/courses$/);
  await page.goto(`/events/add/${course.id}`);
  await expect(flashMessage(page)).toHaveText(noCourse);
  await expect(page).toHaveURL(/\/home$/);

  const noEvent = 'Error: That event does not exist or you dont have access to it';
  for (const action of ['view', 'edit', 'delete']) {
    await page.goto(`/events/${action}/${id}`);
    await expect(flashMessage(page), action).toHaveText(noEvent);
    await expect(page, action).toHaveURL(/\/home$/);
  }
  // The confirmed delete, sent without the confirmation page. The refusal
  // redirects to the home page, which shows the message.
  const response = await page.request.post(`/events/delete/${id}`, {
    form: { 'data[Event][confirm]': '1' },
    headers: { referer: new URL('/events', page.url()).href },
  });
  expect(response.ok(), 'the refused delete').toBe(true);
  expect(response.url()).toMatch(/\/home$/);
  expect(await response.text()).toContain(noEvent);

  // Instructor 2 teaches APSC 201, and the event is still there.
  await login(page, USERS.instructor2);
  await page.goto(`/events/index/${course.id}`);
  await waitForAjaxList(page);
  await page.locator('#ajaxListDiv').getByRole('link', { name: event.title, exact: true }).click();
  await expect(page).toHaveURL(new RegExp(`/events/view/${id}$`));
  await expect(viewField(page, 'Course')).toHaveText(fullName(course));
  await expect(viewField(page, 'Event Title')).toHaveText(event.title);
});
