import { expect, test, type Page } from '../support/test';
import { expectClassSize } from '../support/courses';
import type { TestDatabase } from '../support/db';
import {
  fixture,
  flashMessage,
  labelledTable,
  login,
  notCsvFile,
  resultsTable,
} from '../support/helpers';
import { COURSES, MECH328_GROUPS, STUDENTS, USERS, fullName } from '../support/seed';
import { expectInClassList, expectNotInClassList } from '../support/users';
import type { ResultSetHeader } from 'mysql2/promise';
import { UPLOAD } from '../support/messages';

// Ported from app/tests/cases/system/mass_move.test.php and
// app/tests/cases/system/move_student.test.php. The CSV files list the same
// students as the old files, with LF line endings in place of CR.
//
// Surveys are out of scope. The single-student form (courses/move) lists only
// the students who submitted a survey, and only courses with an active survey,
// so it is not tested here. The CSV form (courses/import) works without a
// survey: it enrols the students in the destination course and, for a move,
// unenrols them from the source course and takes them out of its groups. Peer
// evaluation submissions stay with the source events; the summary only adds a
// note.

const { jennifer: JENNIFER, jonathan: JONATHAN, bill: BILL, hui: HUI, edna: EDNA } = STUDENTS;

const FAILED = 'User(s) failed to transfer:';
const TRANSFERRED = 'User(s) successfully transferred:';
const SUCCESS = 'Success.';
// The seed has peer evaluation submissions of Ed, Matt and Hui in MECH 328.
const SUBMITTED =
  'Success. The student has already submitted a peer evaluation in the source course.';

function notInSource(username: string): string {
  return `Success. However no student with username ${username} was enrolled in the source course.`;
}

// The form asks for a source survey when the source course has an active one,
// and MECH 328 has two (the only course in the tests with surveys). Making them
// inactive lets the move run without a
// survey, as for a course that has none.
async function deactivateSurveys(testDatabase: TestDatabase, courseId: string): Promise<void> {
  await test.step(
    `Deactivate the surveys of course ${courseId}`,
    async () => {
      const [result] = await testDatabase.connection.query<ResultSetHeader>(
        "UPDATE events SET record_status = 'I' WHERE course_id = ? AND event_template_type_id = 3",
        [courseId]
      );
      expect(result.affectedRows, `surveys of course ${courseId}`).toBe(2);
    },
    { box: true }
  );
}

type Course = { id: string; name: string; title: string };

type MoveForm = {
  file: string | { name: string; mimeType: string; buffer: Buffer };
  identifier: 'Student No.' | 'Username';
  from: Course;
  to: Course;
  action: 'Move' | 'Copy';
};

// Fills the form of courses/import, but does not submit it.
async function fillMoveForm(page: Page, form: MoveForm): Promise<void> {
  await test.step(
    `Fill the form to ${form.action.toLowerCase()} students from ${form.from.name} to ${form.to.name}`,
    async () => {
      await page.goto('/courses/import');
      const submit = page.getByRole('button', { name: 'Submit', exact: true });
      // The page script enables the button only when all fields are filled.
      await expect(submit).toBeDisabled();

      await page.locator('#CourseFile').setInputFiles(form.file);
      await page.getByLabel(form.identifier, { exact: true }).check();
      await page.getByLabel('Source Course').selectOption({ label: fullName(form.from) });
      // The page asks the server whether the source course has a survey. The
      // answer decides if the survey field is required, so wait for it before
      // the next change, which checks the required fields.
      await expect(page.locator('#noSourceSurvey')).toBeVisible();
      // Another request refills the destination courses without the source
      // course, and the refill clears a course already picked. So wait for the
      // refill before picking.
      const destination = page.getByLabel('Destination Course');
      await expect(
        destination.getByRole('option', { name: fullName(form.from), exact: true })
      ).toHaveCount(0);
      await destination.selectOption({ label: fullName(form.to) });
      await page.getByLabel(form.action, { exact: true }).check();
      await expect(submit).toBeEnabled();
    },
    { box: true }
  );
}

async function moveStudents(page: Page, form: MoveForm): Promise<void> {
  await fillMoveForm(page, form);
  await test.step(
    'Submit the form',
    async () => {
      await page.getByRole('button', { name: 'Submit', exact: true }).click();
      await expect(page.getByRole('heading', { name: TRANSFERRED })).toBeVisible();
    },
    { box: true }
  );
}

// Checks one table of the summary: exactly these identifiers, each with its
// note or reason.
async function expectSummary(
  page: Page,
  heading: string,
  rows: Readonly<Record<string, string>>
): Promise<void> {
  await test.step(
    `Expect ${heading} ${Object.keys(rows).join(', ') || 'none'}`,
    async () => {
      const table = labelledTable(page, heading);
      // One header row, then one row for each user. The count rules out a user
      // who is listed but not expected.
      await expect(table.locator('tr')).toHaveCount(Object.keys(rows).length + 1);
      for (const [identifier, note] of Object.entries(rows)) {
        const row = table
          .locator('tr')
          .filter({ has: page.getByRole('cell', { name: identifier, exact: true }) });
        await expect(row, identifier).toHaveCount(1);
        await expect(row.getByRole('cell'), `row of ${identifier}`).toHaveText([identifier, note]);
      }
    },
    { box: true }
  );
}

async function expectNoFailures(page: Page): Promise<void> {
  await test.step(
    'Expect no failed transfers',
    async () => {
      // The summary has loaded when the other heading shows.
      await expect(page.getByRole('heading', { name: TRANSFERRED })).toBeVisible();
      await expect(page.getByRole('heading', { name: FAILED })).toHaveCount(0);
    },
    { box: true }
  );
}

// By the names on the group page, unlike expectGroupMembers() in
// support/groups.ts, which reads the group form.
async function expectGroupMemberNames(
  page: Page,
  group: { id: string; name: string },
  names: readonly string[]
): Promise<void> {
  await test.step(
    `Expect members of ${group.name}: ${names.join(', ') || 'none'}`,
    async () => {
      await page.goto(`/groups/view/${group.id}`);
      const table = resultsTable(page, 'Group Members');
      await expect(table).toBeVisible();
      // Each member row links to the user. The last row, with the email link
      // for the whole group, does not.
      const memberNames = table.locator('tr:has(a[href*="/users/view/"]) > td:first-child');
      await expect(memberNames).toHaveCount(names.length);
      expect((await memberNames.allInnerTexts()).map((name) => name.trim()).sort()).toEqual(
        [...names].sort()
      );
    },
    { box: true }
  );
}

test('a superadmin moves students by username, which also takes them out of their groups', async ({
  page,
  testDatabase,
}) => {
  await deactivateSurveys(testDatabase, COURSES.mech328.id);
  await login(page, USERS.superAdmin);

  await moveStudents(page, {
    file: fixture('massMove.csv'),
    identifier: 'Username',
    from: COURSES.mech328,
    to: COURSES.cpsc404,
    action: 'Move',
  });

  // The file also names an instructor and a tutor, which are not students.
  await expectSummary(page, FAILED, {
    [USERS.instructor1]: `No student with username ${USERS.instructor1} exists.`,
    tutor1: 'No student with username tutor1 exists.',
  });
  await expectSummary(page, TRANSFERRED, {
    [STUDENTS.ed.username]: SUBMITTED,
    [STUDENTS.alex.username]: SUCCESS,
    [JENNIFER.username]: SUCCESS,
    [JONATHAN.username]: SUCCESS,
    [STUDENTS.matt.username]: SUBMITTED,
    [BILL.username]: SUCCESS,
    [HUI.username]: SUBMITTED,
  });

  await expectClassSize(page, COURSES.cpsc404.id, 7);
  await expectClassSize(page, COURSES.mech328.id, COURSES.mech328.studentCount - 7);
  await expectInClassList(page, COURSES.cpsc404.id, STUDENTS.alex);
  await expectNotInClassList(page, COURSES.mech328.id, STUDENTS.alex);
  // A move unenrols from the source course only.
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.matt);

  // The moved students leave their MECH 328 groups; the others stay.
  await expectGroupMemberNames(page, MECH328_GROUPS.reapers, ['Tutor 1']);
  await expectGroupMemberNames(page, MECH328_GROUPS.lazyEngineers, [
    'Bowinn Student',
    'Joe Student',
  ]);

  // Matt's seeded peer evaluation of event 1 (Term 1 Evaluation, Reapers) stays
  // with the event. The results list him as a former member who submitted.
  await page.goto(`/evaluations/viewEvaluationResults/1/${MECH328_GROUPS.reapers.id}`);
  await expect(
    page
      .locator('table')
      .filter({
        has: page.getByRole('columnheader', {
          name: 'Left the group, but had submitted or were evaluated',
          exact: true,
        }),
      })
      .getByRole('cell', { name: `${STUDENTS.matt.name} (student)`, exact: true })
  ).toBeVisible();
  // The list comes from the submission record, so check his scores too: 95 to
  // Ed and 105 to Alex. The order of the columns does not matter here.
  const mattsMarks = resultsTable(page, 'Evaluation Results', 'h3').locator(
    `xpath=./tbody/tr/th[1][normalize-space()='${STUDENTS.matt.name}']/following-sibling::td`
  );
  await expect(mattsMarks.filter({ hasText: /^\s*95\.00\s*$/ })).toHaveCount(1);
  await expect(mattsMarks.filter({ hasText: /^\s*105\.00\s*$/ })).toHaveCount(1);
});

test('a superadmin copies students by student number, which keeps them in the source course', async ({
  page,
  testDatabase,
}) => {
  await deactivateSurveys(testDatabase, COURSES.mech328.id);
  await login(page, USERS.superAdmin);

  await moveStudents(page, {
    file: fixture('massMoveStudentNo.csv'),
    identifier: 'Student No.',
    from: COURSES.mech328,
    to: COURSES.apsc201,
    action: 'Copy',
  });

  await expectSummary(page, FAILED, {
    '12345678': 'No student with student number 12345678 exists.',
    '87654321': 'No student with student number 87654321 exists.',
  });
  // Matt is already in APSC 201. A copy does not add the note about peer
  // evaluations, because the submissions stay with the student in the source
  // course.
  await expectSummary(page, TRANSFERRED, {
    [STUDENTS.ed.number]: SUCCESS,
    [STUDENTS.alex.number]: SUCCESS,
    [STUDENTS.matt.number]: SUCCESS,
    [JENNIFER.number]: SUCCESS,
    [JONATHAN.number]: SUCCESS,
    [BILL.number]: SUCCESS,
    [HUI.number]: SUCCESS,
    [STUDENTS.damien.number]: SUCCESS,
    [EDNA.number]: SUCCESS,
  });

  // Eight new students; Matt is not enrolled a second time.
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount + 8);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.matt);
  await expectInClassList(page, COURSES.apsc201.id, HUI);
  await expectClassSize(page, COURSES.mech328.id, COURSES.mech328.studentCount);
  await expectInClassList(page, COURSES.mech328.id, HUI);
  await expectGroupMemberNames(page, MECH328_GROUPS.reapers, MECH328_GROUPS.reapers.members);
});

test('a superadmin move notes students who were not in the source course or already submitted', async ({
  page,
  testDatabase,
}) => {
  await deactivateSurveys(testDatabase, COURSES.mech328.id);
  await login(page, USERS.superAdmin);

  // Ed is in MECH 328, Chris is in CPSC 101, and Johnny is already in APSC 201.
  await moveStudents(page, {
    file: fixture('fromMECH328.csv'),
    identifier: 'Username',
    from: COURSES.mech328,
    to: COURSES.apsc201,
    action: 'Move',
  });

  await expectNoFailures(page);
  await expectSummary(page, TRANSFERRED, {
    [STUDENTS.ed.username]: SUBMITTED,
    [STUDENTS.chris.username]: notInSource(STUDENTS.chris.username),
    [STUDENTS.johnny.username]: notInSource(STUDENTS.johnny.username),
  });

  // Ed and Chris are new to APSC 201; Johnny is not enrolled a second time.
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount + 2);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.chris);
  await expectInClassList(page, COURSES.apsc201.id, STUDENTS.johnny);
  await expectClassSize(page, COURSES.mech328.id, COURSES.mech328.studentCount - 1);
  await expectNotInClassList(page, COURSES.mech328.id, STUDENTS.ed);
});

test('a superadmin move from a file that is not a CSV is refused and moves no one', async ({
  page,
}) => {
  await login(page, USERS.superAdmin);

  await fillMoveForm(page, {
    file: notCsvFile('students'),
    identifier: 'Username',
    from: COURSES.apsc201,
    to: COURSES.cpsc404,
    action: 'Move',
  });
  await page.getByRole('button', { name: 'Submit', exact: true }).click();
  await expect(flashMessage(page)).toHaveText(UPLOAD.wrongExtension);

  await expectClassSize(page, COURSES.cpsc404.id, 0);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});

test('a superadmin move from a blank CSV moves no one', async ({ page }) => {
  await login(page, USERS.superAdmin);

  // APSC 201 has no survey, so the form turns the survey fields off.
  await fillMoveForm(page, {
    file: fixture('blank.csv'),
    identifier: 'Student No.',
    from: COURSES.apsc201,
    to: COURSES.cpsc404,
    action: 'Move',
  });
  await expect(page.getByLabel('Source Survey')).toBeDisabled();
  await expect(page.locator('#noDestSurvey')).toHaveText(
    'No surveys were found in the Source Course.'
  );
  await expect(page.getByLabel('Duplicate', { exact: true })).toBeChecked();
  await expect(page.getByLabel('Duplicate', { exact: true })).toBeDisabled();
  await expect(page.getByLabel('Existing', { exact: true })).toBeDisabled();

  await page.getByRole('button', { name: 'Submit', exact: true }).click();
  await expect(page.getByRole('heading', { name: TRANSFERRED })).toBeVisible();
  await expectNoFailures(page);
  await expectSummary(page, TRANSFERRED, {});

  await expectClassSize(page, COURSES.cpsc404.id, 0);
  await expectClassSize(page, COURSES.apsc201.id, COURSES.apsc201.studentCount);
});
