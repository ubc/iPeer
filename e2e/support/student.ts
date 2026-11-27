import { expect, test, type Locator, type Page } from '@playwright/test';
import type { RowDataPacket } from 'mysql2/promise';
import type { TestDatabase } from './db';
import { goodMessage, labelledTable } from './helpers';
import { appTime, insertRow, userId } from './insert';
import { SUBMISSION } from './messages';

// The student side of a peer evaluation: the evaluation list on the home
// page, the evaluation form, and the results page. Tutors use the same pages.
// The parts that do not depend on the type of evaluation come first. The
// parts for each type (simple, rubric, mixed) follow, so that a spec for
// another type can add its own form and results helpers next to them.

// The tables of the Peer Evaluations section of the home page.
export type HomeSection = 'Due' | 'Submitted' | 'Expired With No Submission';

// The table under one heading of the Peer Evaluations section. The Surveys
// section uses the same headings, so the table is found inside its section.
export function peerEvaluationTable(page: Page, section: HomeSection): Locator {
  return page
    .getByRole('region', { name: 'Peer Evaluations', exact: true })
    .getByRole('table', { name: section, exact: true });
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
      // Due and Submitted are always there, so a row that is absent from them
      // is not absent because the table is missing. Expired shows only when
      // it lists an event.
      for (const always of ['Due', 'Submitted'] as const) {
        await expect(peerEvaluationTable(page, always), always).toHaveCount(1);
      }
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
      // The form of each type posts back to the page.
      await expect(page.locator('form[action*="/makeEvaluation/"]').first()).toBeVisible();
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

// Submits the open form, and expects the save to succeed. The rubric form
// has its own button.
export async function submitEvaluation(
  page: Page,
  button: Locator = submitButton(page)
): Promise<void> {
  await test.step(
    'Submit the evaluation',
    async () => {
      await button.click();
      await expect(goodMessage(page)).toHaveText(SUBMISSION.submitted);
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
      await insertSubmission(
        testDatabase,
        eventId,
        groupId,
        String(groupEvent.id),
        evaluatorId,
        now
      );
    },
    { box: true }
  );
}

// Writes the submission of an evaluator, as the Submit button of each form
// does. When every member of the group has submitted, the group is ready for
// review.
async function insertSubmission(
  testDatabase: TestDatabase,
  eventId: string,
  groupId: string,
  groupEventId: string,
  evaluatorId: string,
  now: string
): Promise<void> {
  await insertRow(testDatabase, 'evaluation_submissions', {
    event_id: eventId,
    grp_event_id: groupEventId,
    submitter_id: evaluatorId,
    submitted: 1,
    date_submitted: now,
    creator_id: evaluatorId,
    created: now,
    updater_id: evaluatorId,
    modified: now,
  });
  await testDatabase.connection.query(
    `UPDATE group_events SET marked = 'to review'
      WHERE id = ?
        AND (SELECT COUNT(*) FROM groups_members WHERE group_id = ?) =
            (SELECT COUNT(*) FROM evaluation_submissions s
               JOIN groups_members m ON m.user_id = s.submitter_id AND m.group_id = ?
              WHERE s.grp_event_id = ?)`,
    [groupEventId, groupId, groupId, groupEventId]
  );
}

// What the results page of a simple evaluation shows to a student. A grade
// is a number such as '95.00', or 'Not Released'. Without a group average,
// the event shows no details: no Group Average column and no comments.
// Without comments, the page has no comment table, as when no comment to
// the student is released.
// The rating in the Summary table of the results page of a student, such as
// '13.00' or 'Not Released'. The rubric and mixed results show only the
// rating; the simple results also show the group average.
export function rating(page: Page): Locator {
  return labelledTable(page, 'Summary').getByRole('cell');
}

export type SimpleResult = {
  rating: string;
  groupAverage?: string;
  comments?: readonly string[];
};

export async function expectSimpleResult(page: Page, result: SimpleResult): Promise<void> {
  await test.step(
    'Expect the simple evaluation result',
    async () => {
      const summary = labelledTable(page, 'Summary');
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

// ---------------------------------------------------------------------------
// Rubric evaluations: one panel for each member, with a level and a comment for
// each criterion, and a general comment.

// A level is the column of the rubric, from 1. The comments go with the
// criteria, in order.
export type RubricMark = {
  member: string;
  levels: readonly number[];
  comments: readonly string[];
  general: string;
};

// The panel of one member. The accordion keeps the closed panels in the page,
// so a locator in a closed panel finds hidden elements.
export function rubricPanel(page: Page, member: string): Locator {
  return page.locator(`#accordion > div.panelName[data-fullname="${member}"]`);
}

// The accordion changes the class of a header when its panel opens, so the
// header is found by its id, such as panel6Header.
export function rubricPanelHeader(page: Page, member: string): Locator {
  return rubricPanel(page, member).locator('div[id$="Header"]');
}

// The row of a criterion in the panel of a member, by the text of the
// criterion.
function rubricCriterionRow(page: Page, member: string, criterion: number): Locator {
  return rubricPanel(page, member)
    .locator('table.standardtable > tbody > tr')
    .filter({ has: page.locator(`xpath=./th/font[contains(@id, 'criteria${criterion}')]`) });
}

// The name of a criterion in the panel. The form colours it red when the
// member has no level for it at a save.
export function rubricCriterion(page: Page, member: string, criterion: number): Locator {
  return rubricCriterionRow(page, member, criterion).locator('xpath=./th/font');
}

export function rubricLevel(page: Page, member: string, criterion: number, level: number): Locator {
  return rubricCriterionRow(page, member, criterion)
    .getByRole('radio')
    .nth(level - 1);
}

export function rubricComment(page: Page, member: string, criterion: number): Locator {
  return rubricCriterionRow(page, member, criterion).locator('textarea');
}

export function rubricGeneralComment(page: Page, member: string): Locator {
  return rubricPanel(page, member).locator('textarea[name$="gen_comment"]');
}

export function saveSectionButton(page: Page, member: string): Locator {
  return rubricPanel(page, member).getByRole('button', { name: 'Save This Section' });
}

// The submit button of the rubric form has its own text.
export function submitRubricButton(page: Page): Locator {
  return page.getByRole('button', { name: 'Submit to Complete the Evaluation', exact: true });
}

// Opens the panel of a member. The accordion slides the panel open, so the
// fields can be used only at the end of the animation.
export async function openRubricPanel(page: Page, member: string): Promise<void> {
  await test.step(
    `Open the panel of ${member}`,
    async () => {
      // The inline Rico.Accordion script hides the closed panels. Before it
      // runs, a panel can look open, the click is skipped, and the script
      // then closes it.
      await page.waitForLoadState('load');
      const content = rubricPanel(page, member).locator('div.panelContent');
      if (!(await content.isVisible())) {
        await rubricPanelHeader(page, member).click();
      }
      await expect(rubricGeneralComment(page, member)).toBeVisible();
      await expect(content).not.toHaveCSS('height', '0px');
      // The slide ends at the full height of the panel.
      await expect
        .poll(async () => (await content.boundingBox())?.height ?? 0)
        .toBeGreaterThan(400);
    },
    { box: true }
  );
}

// Fills in the open panel of a member. A level or comment of the mark that is
// 0 or '' is left as it is.
export async function fillRubricPanel(page: Page, mark: RubricMark): Promise<void> {
  await test.step(
    `Fill in the rubric for ${mark.member}`,
    async () => {
      await openRubricPanel(page, mark.member);
      for (const [index, level] of mark.levels.entries()) {
        if (level > 0) {
          await rubricLevel(page, mark.member, index + 1, level).check();
        }
      }
      for (const [index, comment] of mark.comments.entries()) {
        await fillAndLeave(rubricComment(page, mark.member, index + 1), comment);
      }
      await fillAndLeave(rubricGeneralComment(page, mark.member), mark.general);
    },
    { box: true }
  );
}

// Saves the panel of a member, and expects the message of the save.
export async function saveRubricSection(page: Page, member: string, message: string) {
  await test.step(
    `Save the section of ${member}`,
    async () => {
      await saveSectionButton(page, member).click();
      await expect(page).toHaveURL(/\/evaluations\/makeEvaluation\/\d+\/\d+$/);
      await expect(page.locator('#flashMessage')).toHaveText(message);
    },
    { box: true }
  );
}

export async function expectRubricPanel(page: Page, mark: RubricMark): Promise<void> {
  await test.step(
    `Expect the rubric values for ${mark.member}`,
    async () => {
      await openRubricPanel(page, mark.member);
      for (const [index, level] of mark.levels.entries()) {
        const radios = rubricCriterionRow(page, mark.member, index + 1).getByRole('radio');
        for (let column = 1; column <= (await radios.count()); column++) {
          const radio = radios.nth(column - 1);
          await (column === level
            ? expect(radio, `criterion ${index + 1}`).toBeChecked()
            : expect(radio, `criterion ${index + 1}`).not.toBeChecked());
        }
      }
      for (const [index, comment] of mark.comments.entries()) {
        await expect(rubricComment(page, mark.member, index + 1)).toHaveValue(comment);
      }
      await expect(rubricGeneralComment(page, mark.member)).toHaveValue(mark.general);
    },
    { box: true }
  );
}

// Writes the rows that Save This Section for each mark and then Submit write
// (see saveRubricEvaluation in app/controllers/components/evaluation.php, and
// completeEvaluationRubric in evaluations_controller.php). With submit false,
// only the saves: the answers are there, but the evaluator has not submitted.
export async function insertRubricSubmission(
  testDatabase: TestDatabase,
  eventId: string,
  groupId: string,
  evaluator: string,
  marks: readonly RubricMark[],
  { submit = true }: { submit?: boolean } = {}
): Promise<void> {
  await test.step(
    `Insert the rubric evaluation of ${evaluator}`,
    async () => {
      const { connection } = testDatabase;
      const evaluatorId = await userId(testDatabase, evaluator);
      const [[groupEvent]] = await connection.query<RowDataPacket[]>(
        `SELECT group_events.id, events.auto_release, events.template_id
           FROM group_events JOIN events ON events.id = group_events.event_id
          WHERE group_events.event_id = ? AND group_events.group_id = ?`,
        [eventId, groupId]
      );
      expect(groupEvent, `group ${groupId} in event ${eventId}`).toBeDefined();
      const [[rubric]] = await connection.query<RowDataPacket[]>(
        `SELECT zero_mark, (SELECT COUNT(*) FROM rubrics_loms WHERE rubric_id = rubrics.id) AS loms
           FROM rubrics WHERE id = ?`,
        [groupEvent.template_id]
      );
      const [criteria] = await connection.query<RowDataPacket[]>(
        'SELECT multiplier FROM rubrics_criterias WHERE rubric_id = ? ORDER BY criteria_num',
        [groupEvent.template_id]
      );
      const now = appTime();
      const stamps = {
        creator_id: evaluatorId,
        created: now,
        updater_id: evaluatorId,
        modified: now,
      };

      for (const mark of marks) {
        expect(mark.levels, `levels for ${mark.member}`).toHaveLength(criteria.length);
        const [members] = await connection.query<RowDataPacket[]>(
          "SELECT id FROM users WHERE CONCAT(first_name, ' ', last_name) = ?",
          [mark.member]
        );
        expect(members, `the user ${mark.member}`).toHaveLength(1);
        // The grade of a level, as the save computes it.
        const grades = mark.levels.map(
          (level, index) =>
            ((level - rubric.zero_mark) * Number(criteria[index].multiplier)) /
            (rubric.loms - rubric.zero_mark)
        );
        const rubricRowId = await insertRow(testDatabase, 'evaluation_rubrics', {
          evaluator: evaluatorId,
          evaluatee: String(members[0].id),
          comment: mark.general,
          score: grades.reduce((sum, grade) => sum + grade, 0),
          comment_release: groupEvent.auto_release,
          grade_release: groupEvent.auto_release,
          grp_event_id: groupEvent.id,
          event_id: eventId,
          rubric_id: groupEvent.template_id,
          ...stamps,
        });
        for (const [index, level] of mark.levels.entries()) {
          await insertRow(testDatabase, 'evaluation_rubric_details', {
            evaluation_rubric_id: rubricRowId,
            criteria_number: index + 1,
            criteria_comment: mark.comments[index],
            selected_lom: level,
            grade: grades[index],
            comment_release: groupEvent.auto_release,
            ...stamps,
          });
        }
      }
      if (submit) {
        await insertSubmission(
          testDatabase,
          eventId,
          groupId,
          String(groupEvent.id),
          evaluatorId,
          now
        );
      }
    },
    { box: true }
  );
}

// ---------------------------------------------------------------------------
// Mixed evaluations: one set of questions for each member, and a set of
// self-evaluation questions when the template and the event have them. The
// form numbers the questions of each section from 1. insertMixevalSubmission()
// takes the answers by the question_num of the template, which is the same
// number while the peer questions come first, as in the seeded templates.

// An answer: a level from 1 for a Likert question, the points for a score
// dropdown question, or the text of a sentence or paragraph question. The
// answers are by the number that the form shows for the question. The
// self-evaluation questions start again at 1. A question without an answer
// is left as it is.
export type MixevalAnswers = Readonly<Record<number, number | string>>;

// The answers for one member. Self-evaluation answers name the evaluator.
export type MixevalMark = { member: string; answers: MixevalAnswers; self?: boolean };

export function submitMixevalButton(page: Page): Locator {
  return page.getByRole('button', { name: 'Submit the Evaluation', exact: true });
}

// The questions of the form for one member, a region named by the member,
// or the self-evaluation questions.
function mixevalSection(page: Page, mark: MixevalMark): Locator {
  return page.getByRole('region', {
    name: mark.self ? 'Self-Evaluation Questions' : mark.member,
    exact: true,
  });
}

// One question of the form for one member: a group named by its title, such
// as '2. Contributed? *', with the instructions and the field.
export function mixevalQuestion(page: Page, mark: MixevalMark, question: number): Locator {
  return mixevalSection(page, mark).getByRole('group', { name: new RegExp(`^${question}\\. `) });
}

// The name of an answer in a failure message, such as 'Alex Student question 2'.
function mixevalAnswerName(mark: MixevalMark, num: string): string {
  return `${mark.member}${mark.self ? ' self-evaluation' : ''} question ${num}`;
}

// The radio button of a Likert level, named by the level and its text, such
// as 'Level 3: Good'.
function likertLevel(question: Locator, level: number): Locator {
  return question.getByRole('radio', { name: new RegExp(`^Level ${level}(:|$)`) });
}

export async function fillMixevalForm(page: Page, marks: readonly MixevalMark[]): Promise<void> {
  await test.step(
    'Fill in the mixed evaluation',
    async () => {
      for (const mark of marks) {
        for (const [num, answer] of Object.entries(mark.answers)) {
          const question = mixevalQuestion(page, mark, Number(num));
          // The question must be there before count() can tell its type.
          await expect(question, mixevalAnswerName(mark, num)).toBeVisible();
          if (typeof answer === 'string') {
            await question.getByRole('textbox').fill(answer);
          } else if ((await question.getByRole('combobox').count()) > 0) {
            await question.getByRole('combobox').selectOption(String(answer));
          } else {
            await likertLevel(question, answer).check();
          }
        }
      }
    },
    { box: true }
  );
}

export async function expectMixevalForm(page: Page, marks: readonly MixevalMark[]): Promise<void> {
  await test.step(
    'Expect the mixed evaluation values',
    async () => {
      for (const mark of marks) {
        for (const [num, answer] of Object.entries(mark.answers)) {
          const question = mixevalQuestion(page, mark, Number(num));
          const name = mixevalAnswerName(mark, num);
          await expect(question, name).toBeVisible();
          if (typeof answer === 'string') {
            await expect(question.getByRole('textbox'), name).toHaveValue(answer);
          } else if ((await question.getByRole('combobox').count()) > 0) {
            await expect(question.getByRole('combobox'), name).toHaveValue(String(answer));
          } else {
            await expect(likertLevel(question, answer), name).toBeChecked();
          }
        }
      }
    },
    { box: true }
  );
}

// Writes the rows that a submit of the mixed evaluation form writes (see
// _makeMixevalEvaluation in evaluations_controller.php, and
// saveMixevalEvaluation in app/controllers/components/evaluation.php). The
// submit saves the answers in any case, but it counts as a submission only
// when every member has an answer to every required question. With submit
// false, the insert writes the answers alone, as such a submit does.
export async function insertMixevalSubmission(
  testDatabase: TestDatabase,
  eventId: string,
  groupId: string,
  evaluator: string,
  marks: readonly MixevalMark[],
  { submit = true }: { submit?: boolean } = {}
): Promise<void> {
  await test.step(
    `Insert the mixed evaluation of ${evaluator}`,
    async () => {
      const { connection } = testDatabase;
      const evaluatorId = await userId(testDatabase, evaluator);
      const [[groupEvent]] = await connection.query<RowDataPacket[]>(
        `SELECT group_events.id, events.auto_release, events.template_id, mixevals.zero_mark
           FROM group_events
           JOIN events ON events.id = group_events.event_id
           JOIN mixevals ON mixevals.id = events.template_id
          WHERE group_events.event_id = ? AND group_events.group_id = ?`,
        [eventId, groupId]
      );
      expect(groupEvent, `group ${groupId} in event ${eventId}`).toBeDefined();
      const [questions] = await connection.query<RowDataPacket[]>(
        `SELECT question_num, mixeval_question_type_id AS type, required, self_eval, multiplier,
                (SELECT COUNT(*) FROM mixeval_question_descs d WHERE d.question_id = q.id) AS scale
           FROM mixeval_questions q WHERE mixeval_id = ? ORDER BY question_num`,
        [groupEvent.template_id]
      );
      const now = appTime();
      const stamps = {
        creator_id: evaluatorId,
        created: now,
        updater_id: evaluatorId,
        modified: now,
      };

      for (const mark of marks) {
        // The form saves the self-evaluation answers in the same row as the
        // peer answers of the evaluator to themself. The insert does not
        // merge them, so the tests fill in the form for self-evaluations.
        expect(mark.self, `self-evaluation of ${mark.member}`).toBeFalsy();
        const [members] = await connection.query<RowDataPacket[]>(
          "SELECT id FROM users WHERE CONCAT(first_name, ' ', last_name) = ?",
          [mark.member]
        );
        expect(members, `the user ${mark.member}`).toHaveLength(1);
        const section = questions.filter((question) => !!question.self_eval === !!mark.self);
        // The grade of each Likert and score dropdown answer, as the save
        // computes it. The score counts the required peer questions.
        const grades = new Map<number, number>();
        for (const question of section) {
          const answer = mark.answers[question.question_num];
          if (question.type === 1 && typeof answer === 'number') {
            grades.set(
              question.question_num,
              (question.multiplier * (answer - groupEvent.zero_mark)) /
                (question.scale - groupEvent.zero_mark)
            );
          } else if (question.type === 4) {
            // A dropdown always sends a value, 0 when untouched.
            grades.set(question.question_num, typeof answer === 'number' ? answer : 0);
          }
        }
        const score = section
          .filter((question) => question.required && !question.self_eval)
          .reduce((sum, question) => sum + (grades.get(question.question_num) ?? 0), 0);
        const mixevalId = await insertRow(testDatabase, 'evaluation_mixevals', {
          evaluator: evaluatorId,
          evaluatee: String(members[0].id),
          score,
          grade_release: groupEvent.auto_release,
          grp_event_id: groupEvent.id,
          event_id: eventId,
          ...stamps,
        });
        for (const question of section) {
          const answer = mark.answers[question.question_num];
          const grade = grades.get(question.question_num);
          if (grade === undefined && (typeof answer !== 'string' || answer === '')) {
            continue;
          }
          await insertRow(testDatabase, 'evaluation_mixeval_details', {
            evaluation_mixeval_id: mixevalId,
            question_number: question.question_num,
            question_comment: typeof answer === 'string' ? answer : null,
            selected_lom: question.type === 1 ? (answer as number) : 0,
            grade: grade ?? 0,
            comment_release: groupEvent.auto_release,
            ...stamps,
          });
        }
      }
      if (submit) {
        await insertSubmission(
          testDatabase,
          eventId,
          groupId,
          String(groupEvent.id),
          evaluatorId,
          now
        );
      }
    },
    { box: true }
  );
}

// A section of the answers on the results page of a student: 'Questions'
// for the answers the teammates gave, or 'Self-Evaluation'. Its heading adds
// what is not released yet, such as 'Questions - Grades Not Released Yet'.
export function resultSection(page: Page, title: 'Questions' | 'Self-Evaluation'): Locator {
  return page.getByRole('region', { name: new RegExp(`^${title}( - |$)`) });
}

// The heading of the answers the teammates gave, on the results page of a
// student. It says what is not released yet.
export function resultsHeading(page: Page): Locator {
  return resultSection(page, 'Questions').getByRole('heading', { level: 2 });
}

// The answers to one question in a results section, such as the panel of a
// member on the instructor's Detail page. Each list is named by its question.
export function questionAnswers(section: Locator, question: number): Locator {
  return section.getByRole('list', { name: new RegExp(`^${question}\\. `) }).getByRole('listitem');
}

// The answers to one question on the results page of a student.
export function resultAnswers(page: Page, question: number): Locator {
  return questionAnswers(resultSection(page, 'Questions'), question);
}
