import { expect, test, type Locator, type Page } from '@playwright/test';
import type { RowDataPacket } from 'mysql2/promise';
import type { TestDatabase } from './db';
import { goodMessage, resultsTable, sectionHeadings } from './helpers';
import { appTime, insertRow, userId } from './insert';

// The student side of a peer evaluation: the evaluation list on the home
// page, the evaluation form, and the results page. Tutors use the same pages.
// The parts that do not depend on the type of evaluation come first. The
// parts for each type (simple, rubric, mixed) follow, so that a spec for
// another type can add its own form and results helpers next to them.

// The tables of the Peer Evaluations section of the home page.
export type HomeSection = 'Due' | 'Submitted' | 'Expired With No Submission';

export const SUBMITTED = 'Your Evaluation was submitted successfully.';

// The table under one heading of the Peer Evaluations section. The Surveys
// section uses the same headings.
export function peerEvaluationTable(page: Page, section: HomeSection): Locator {
  return sectionHeadings(page, 'Peer Evaluations')
    .and(page.getByRole('heading', { level: 3, name: section, exact: true }))
    .locator('xpath=following-sibling::table[1]');
}

// The row of an event for one group. A student in two groups of a course has
// one row for each group, with the same event title. The first cell is the
// title, a link only when the page has something to open, so the cell text
// is matched and not the link.
export function homeRow(page: Page, section: HomeSection, title: string, group: string): Locator {
  return peerEvaluationTable(page, section)
    .getByRole('row')
    .filter({ has: page.locator(`xpath=./td[1][normalize-space()='${title}']`) })
    .filter({ has: page.getByRole('cell', { name: group, exact: true }) });
}

export async function openHome(page: Page): Promise<void> {
  await page.goto('/home');
  // The section is there for every student and tutor, so a row that is absent
  // from it is not absent because some other page loaded.
  await expect(page.getByRole('heading', { level: 2, name: 'Peer Evaluations' })).toBeVisible();
}

// Checks the table of the home page that lists the event for the group, and
// that no other table of the section lists it.
export async function expectOnHome(
  page: Page,
  section: HomeSection,
  title: string,
  group: string
): Promise<void> {
  await test.step(
    `Expect ${title} (${group}) under ${section}`,
    async () => {
      await openHome(page);
      const sections: HomeSection[] = ['Due', 'Submitted', 'Expired With No Submission'];
      for (const other of sections) {
        await expect(homeRow(page, other, title, group), other).toHaveCount(
          other === section ? 1 : 0
        );
      }
    },
    { box: true }
  );
}

// Opens the evaluation form from the home page. In the submission period, an
// event is under Due until the first submission, also after the due date
// (then a submission is late). After the first submission it is under
// Submitted until the submissions end.
export async function openEvaluation(
  page: Page,
  title: string,
  group: string,
  section: HomeSection = 'Due'
): Promise<void> {
  await test.step(
    `Open the evaluation ${title} (${group})`,
    async () => {
      await openHome(page);
      await homeRow(page, section, title, group).getByRole('link', { name: title }).click();
      await expect(page).toHaveURL(/\/evaluations\/makeEvaluation\/\d+\/\d+$/);
      await expect(page.locator('form#evalForm')).toBeVisible();
    },
    { box: true }
  );
}

// Opens the results from the home page. The link goes to the results only in
// the results period.
export async function openResults(
  page: Page,
  title: string,
  group: string,
  section: HomeSection = 'Submitted'
): Promise<void> {
  await test.step(
    `Open the results of ${title} (${group})`,
    async () => {
      await openHome(page);
      await homeRow(page, section, title, group).getByRole('link', { name: title }).click();
      await expect(page).toHaveURL(/\/evaluations\/studentViewEvaluationResult\/\d+\/\d+$/);
      await expect(page.getByRole('heading', { level: 2, name: 'Summary' })).toBeVisible();
    },
    { box: true }
  );
}

export function submitButton(page: Page): Locator {
  return page.getByRole('button', { name: 'Submit Evaluation', exact: true });
}

// Submits the open form, and expects the save to succeed.
export async function submitEvaluation(page: Page): Promise<void> {
  await test.step(
    'Submit the evaluation',
    async () => {
      await submitButton(page).click();
      await expect(goodMessage(page)).toHaveText(SUBMITTED);
      await expect(page).toHaveURL(/\/home(\/index)?\/?$/);
    },
    { box: true }
  );
}

// Fills in a text field and leaves it. The forms update their totals on the
// change event, which a browser sends when the field loses the focus.
export async function fillAndLeave(field: Locator, value: string): Promise<void> {
  await field.fill(value);
  await field.blur();
}

// ---------------------------------------------------------------------------
// Simple evaluations: points to distribute, and a comment for each member.

export type SimpleMark = { member: string; points: number; comment: string };

// The row of one member in the simple evaluation form. Each row holds a small
// table for the slider, so only the rows of the outer table count.
function simpleMemberRow(page: Page, member: string): Locator {
  return page
    .locator('#evalForm > table > tbody > tr')
    .filter({ has: page.locator(`xpath=./td[1][normalize-space()='${member}']`) });
}

export function simplePoints(page: Page, member: string): Locator {
  return simpleMemberRow(page, member).locator('input[name="points[]"]');
}

export function simpleComment(page: Page, member: string): Locator {
  return simpleMemberRow(page, member).locator('input[name="comments[]"]');
}

// The text under the members, such as 'Please allocate 200 more points.' The
// form writes it with line breaks, which the check turns into spaces.
export async function expectSimpleStatus(page: Page, status: string): Promise<void> {
  await test.step(
    `Expect the status ${status}`,
    async () => {
      await expect(page.locator('#statusMsg')).toHaveText(status, { useInnerText: true });
    },
    { box: true }
  );
}

// Drags the slider of a member to a relative weight from 1 to 10. The slider
// maps the track, less the width of the handle, to the values 0 to 9, and
// shows the value plus one.
export async function setRelativeWeight(page: Page, member: string, weight: number): Promise<void> {
  await test.step(
    `Set the weight of ${member} to ${weight}`,
    async () => {
      const row = simpleMemberRow(page, member);
      // The mouse uses window coordinates and does not scroll, so the slider
      // must be in the window before it is measured.
      await row.locator('[id^="track"]').scrollIntoViewIfNeeded();
      const track = await row.locator('[id^="track"]').boundingBox();
      const handle = await row.locator('[id^="handle"]').boundingBox();
      if (!track || !handle) {
        throw new Error(`no slider for ${member}`);
      }
      const y = handle.y + handle.height / 2;
      const target = track.x + ((weight - 1) / 9) * (track.width - handle.width) + handle.width / 2;
      await page.mouse.move(handle.x + handle.width / 2, y);
      await page.mouse.down();
      await page.mouse.move(target, y, { steps: 5 });
      await page.mouse.up();
      await expect(row.locator('[id^="score"]')).toHaveText(String(weight));
    },
    { box: true }
  );
}

export async function fillSimpleEvaluation(
  page: Page,
  marks: readonly SimpleMark[]
): Promise<void> {
  await test.step(
    'Fill in the simple evaluation',
    async () => {
      for (const mark of marks) {
        await fillAndLeave(simplePoints(page, mark.member), String(mark.points));
        await fillAndLeave(simpleComment(page, mark.member), mark.comment);
      }
    },
    { box: true }
  );
}

// Checks the values in the form, for example the saved values when a student
// opens a submitted evaluation again.
export async function expectSimpleForm(page: Page, marks: readonly SimpleMark[]): Promise<void> {
  await test.step(
    'Expect the simple evaluation values',
    async () => {
      for (const mark of marks) {
        await expect(simplePoints(page, mark.member), mark.member).toHaveValue(String(mark.points));
        await expect(simpleComment(page, mark.member), mark.member).toHaveValue(mark.comment);
      }
    },
    { box: true }
  );
}

// Writes the rows that a submit of the simple evaluation form writes (see
// saveSimpleEvaluation in app/controllers/components/evaluation.php), for the
// tests whose subject is not the form. The marks name the members as the form
// shows them.
export async function insertSimpleSubmission(
  testDatabase: TestDatabase,
  eventId: string,
  groupId: string,
  evaluator: string,
  marks: readonly SimpleMark[]
): Promise<void> {
  await test.step(
    `Insert the simple evaluation of ${evaluator}`,
    async () => {
      const { connection } = testDatabase;
      const evaluatorId = await userId(testDatabase, evaluator);
      const [[groupEvent]] = await connection.query<RowDataPacket[]>(
        `SELECT group_events.id, events.auto_release
           FROM group_events JOIN events ON events.id = group_events.event_id
          WHERE group_events.event_id = ? AND group_events.group_id = ?`,
        [eventId, groupId]
      );
      expect(groupEvent, `group ${groupId} in event ${eventId}`).toBeDefined();
      // The save stamps every row with the time of the submit, and the user
      // who submits as the creator and updater.
      const now = appTime();
      const stamps = {
        creator_id: evaluatorId,
        created: now,
        updater_id: evaluatorId,
        modified: now,
      };

      for (const mark of marks) {
        const [members] = await connection.query<RowDataPacket[]>(
          "SELECT id FROM users WHERE CONCAT(first_name, ' ', last_name) = ?",
          [mark.member]
        );
        expect(members, `the user ${mark.member}`).toHaveLength(1);
        // Before a release, the event's auto-release setting decides whether
        // the grade and the comment show.
        await insertRow(testDatabase, 'evaluation_simples', {
          evaluator: evaluatorId,
          evaluatee: String(members[0].id),
          score: mark.points,
          comment: mark.comment,
          release_status: groupEvent.auto_release,
          grade_release: groupEvent.auto_release,
          grp_event_id: groupEvent.id,
          event_id: eventId,
          date_submitted: now,
          ...stamps,
        });
      }
      await insertRow(testDatabase, 'evaluation_submissions', {
        event_id: eventId,
        grp_event_id: groupEvent.id,
        submitter_id: evaluatorId,
        submitted: 1,
        date_submitted: now,
        ...stamps,
      });

      // When every member of the group has submitted, the group is ready for
      // review.
      await connection.query(
        `UPDATE group_events SET marked = 'to review'
          WHERE id = ?
            AND (SELECT COUNT(*) FROM groups_members WHERE group_id = ?) =
                (SELECT COUNT(*) FROM evaluation_submissions s
                   JOIN groups_members m ON m.user_id = s.submitter_id AND m.group_id = ?
                  WHERE s.grp_event_id = ?)`,
        [groupEvent.id, groupId, groupId, groupEvent.id]
      );
    },
    { box: true }
  );
}

// What the results page of a simple evaluation shows to a student. A grade
// is a number such as '95.00', or 'Not Released'. Without a group average,
// the event shows no details: no Group Average column and no comments.
// Without comments, the page has no comment table, as when no comment to
// the student is released.
export type SimpleResult = {
  rating: string;
  groupAverage?: string;
  comments?: readonly string[];
};

export async function expectSimpleResult(page: Page, result: SimpleResult): Promise<void> {
  await test.step(
    'Expect the simple evaluation result',
    async () => {
      const summary = resultsTable(page, 'Summary');
      const details = result.groupAverage !== undefined;
      await expect(summary.locator('th')).toHaveText(
        details ? ['Rating', 'Group Average'] : ['Rating']
      );
      await expect(summary.locator('td')).toHaveText(
        details ? [result.rating, result.groupAverage!] : [result.rating]
      );

      // The page lists the comments in a random order.
      const comments = page
        .locator('table')
        .filter({ has: page.getByRole('columnheader', { name: 'Comments (Randomly Ordered)' }) })
        .locator('td');
      await expect(comments).toHaveCount(result.comments?.length ?? 0);
      const shown = (await comments.allTextContents()).map((text) => text.trim()).sort();
      expect(shown, 'comments').toEqual([...(result.comments ?? [])].sort());
    },
    { box: true }
  );
}
