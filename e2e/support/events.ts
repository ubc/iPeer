import { expect, test, type Locator, type Page } from '@playwright/test';
import type { RowDataPacket } from 'mysql2/promise';
import { waitForAjaxList } from './ajax-list';
import type { TestDatabase } from './db';
import { NOW, appTime, findId, insertRow, userId } from './insert';
import { USERS } from './seed';

// Events for the specs that need one but do not test the event form.
// events.spec.ts tests the form, and checks that insertEvent() writes what the
// form writes.

export type TemplateType = 'SIMPLE' | 'RUBRIC' | 'MIX EVALUATION';

export type Penalty = { days: string; percent: string };

export type Dates = {
  releaseBegin: string;
  due: string;
  releaseEnd: string;
  resultBegin: string;
  resultEnd: string;
};

export type NewEvent = {
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
export async function insertEvent(
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
      // Both are app times as text, which sort in date order.
      if (event.reminders) {
        expect(
          event.dates.releaseBegin > appTime(),
          `release date ${event.dates.releaseBegin} after now`
        ).toBe(true);
      }
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
      // twice, as the save does. Like the save, no members means no reminders.
      const [members] =
        groupIds.length > 0
          ? await connection.query<RowDataPacket[]>(
              'SELECT user_id FROM groups_members WHERE group_id IN (?)',
              [groupIds]
            )
          : [[] as RowDataPacket[]];
      if (event.reminders && members.length > 0) {
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

const MINUTE = 60_000;
const HOUR = 60 * MINUTE;
const DAY = 24 * HOUR;

// The dates of an event that students can submit now, in the time of the app.
// The due date is in the future, so a submission is on time.
export function openDates(): Dates {
  return {
    releaseBegin: appTime(-DAY),
    due: appTime(DAY),
    releaseEnd: appTime(2 * DAY),
    resultBegin: appTime(3 * DAY),
    resultEnd: appTime(7 * DAY),
  };
}

// Like openDates(), but the due date was an hour ago, so a submission is late
// by less than one day.
export function lateDates(): Dates {
  return { ...openDates(), due: appTime(-HOUR) };
}

// A test cannot wait for the end of the submission period, so this moves the
// event back in time: all its dates, and all the dates of its submissions,
// move back by the same span, so that the submissions ended a minute ago. A
// submission that was on time or late stays so, by the same margin, and was
// made before the end, as in the app. The results period starts at the end of
// the submissions. Instructors can release grades and comments only after the
// end, and students see their results only in the results period.
//
// Only simple evaluation answers move. A spec for another type of evaluation
// adds the update of its answer table here.
export async function endSubmissions({ connection }: TestDatabase, eventId: string): Promise<void> {
  await test.step(
    `End the submissions of event ${eventId}`,
    async () => {
      const ended = appTime(-MINUTE);
      await connection.query(
        `SELECT TIMESTAMPDIFF(SECOND, ?, release_date_end) INTO @span FROM events WHERE id = ?;
         UPDATE evaluation_submissions
            SET date_submitted = date_submitted - INTERVAL @span SECOND,
                created = created - INTERVAL @span SECOND,
                modified = modified - INTERVAL @span SECOND
          WHERE event_id = ?;
         UPDATE evaluation_simples
            SET date_submitted = date_submitted - INTERVAL @span SECOND,
                created = created - INTERVAL @span SECOND,
                modified = modified - INTERVAL @span SECOND
          WHERE event_id = ?;
         UPDATE events
            SET due_date = due_date - INTERVAL @span SECOND,
                release_date_begin = release_date_begin - INTERVAL @span SECOND,
                release_date_end = ?,
                result_release_date_begin = ?,
                result_release_date_end = result_release_date_end - INTERVAL @span SECOND
          WHERE id = ?`,
        [ended, eventId, eventId, eventId, ended, ended, eventId]
      );
    },
    { box: true }
  );
}

// The value that the event view page shows next to a heading, such as
// 'Due Date'. Only the heading cell is matched, so a value with the same text
// as a heading cannot select the wrong row.
export function viewField(page: Page, heading: string): Locator {
  return page
    .locator('table.standardtable > tbody > tr')
    .filter({ has: page.locator('xpath=./th').getByText(heading, { exact: true }) })
    .locator('xpath=./td');
}

// Filters the event list of the open course page by title. The list shows 20
// events on a page, and the seeded MECH 328 already has 17, so a check for a
// new event searches for it instead of relying on the first page.
export async function searchEventList(page: Page, title: string, count: number): Promise<void> {
  await test.step(
    `Search the event list for ${title}`,
    async () => {
      await waitForAjaxList(page);
      const field = page.locator('#searchInputField');
      await field.fill(title);
      // The list reads the search text on key up, not on input.
      await field.press('End');
      // The click cancels the search-after-typing timer and sends one request,
      // so the rows after this response are those of the search.
      const searched = page.waitForResponse(
        (response) =>
          new URL(response.url()).pathname === '/events/ajaxList' &&
          response.request().method() === 'POST'
      );
      await page
        .locator('#ajaxListDiv')
        .getByRole('button', { name: 'Search', exact: true })
        .click();
      await searched;
      await expect(page.locator('#ajaxListDiv .total-result').first()).toHaveText(
        count === 0 ? 'No results' : `Total Results: ${count}`
      );
    },
    { box: true }
  );
}
