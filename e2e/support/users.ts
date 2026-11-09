import { expect, type Locator, type Page } from '@playwright/test';
import { goodMessage } from './helpers';

export type ClassListPerson = { readonly name: string; readonly number: string };

export function usernameWarning(page: Page): Locator {
  return page.locator('#usernameErr');
}

export async function typeExistingUsername(page: Page, username: string): Promise<void> {
  await page.getByLabel('Username', { exact: true }).fill(username);
  // The warning arrives through a field observer that polls once a second, so
  // the assertion is what waits for it.
  await expect(usernameWarning(page)).toContainText(`Username "${username}" already exists.`);
}

export async function enrolExistingUser(
  page: Page,
  courseId: string,
  username: string
): Promise<void> {
  await page.goto(`/users/add/${courseId}`);
  await typeExistingUsername(page, username);
  await usernameWarning(page).getByRole('link', { name: 'here' }).click();
}

export async function openClassList(page: Page, courseId: string): Promise<void> {
  await page.goto(`/users/goToClassList/${courseId}`);
  await expect(page.locator('#table_id_info')).toBeVisible();
}

async function searchClassList(page: Page, term: string): Promise<void> {
  // The table filters on keyup, which fill() does not produce.
  await page.locator('#table_id_filter input').pressSequentially(term);
}

function classListRows(page: Page): Locator {
  return page.locator('#table_id tbody tr');
}

async function findInClassList(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<Locator> {
  await openClassList(page, courseId);
  // The student number is unique, so filtering on it leaves the one row even
  // where two people share a full name.
  await searchClassList(page, person.number);
  return classListRows(page).filter({ hasText: person.name });
}

export async function expectInClassList(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<void> {
  await expect(
    await findInClassList(page, courseId, person),
    `${person.name} in course ${courseId}`
  ).toHaveCount(1);
}

export async function expectNotInClassList(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<void> {
  await expect(
    await findInClassList(page, courseId, person),
    `${person.name} in course ${courseId}`
  ).toHaveCount(0);
}

export async function dropFromClassList(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<void> {
  const row = await findInClassList(page, courseId, person);
  await expect(row).toHaveCount(1);
  // The row actions live in a panel that a click on the row opens.
  await row.getByRole('cell').first().click();
  page.once('dialog', (dialog) => dialog.accept());
  await page.getByRole('link', { name: 'Drop', exact: true }).click();
  await expect(goodMessage(page)).toHaveText('Student is successfully unenrolled!');
}

export async function dropIfEnrolled(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<void> {
  // Used for cleanup, so a student who already left the course is not an error.
  const row = await findInClassList(page, courseId, person);
  if ((await row.count()) > 0) {
    await dropFromClassList(page, courseId, person);
  }
}

export async function classListUserId(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<string> {
  const row = await findInClassList(page, courseId, person);
  await expect(row).toHaveCount(1);
  await row.getByRole('cell').first().click();
  const href = await page.getByRole('link', { name: 'View', exact: true }).getAttribute('href');
  const id = href?.split('/').pop();
  if (!id) {
    throw new Error(`no user id for ${person.name} in the class list of course ${courseId}`);
  }
  return id;
}

export async function classListUserIdIfPresent(
  page: Page,
  courseId: string,
  person: ClassListPerson
): Promise<string | null> {
  const row = await findInClassList(page, courseId, person);
  if ((await row.count()) === 0) {
    return null;
  }
  return classListUserId(page, courseId, person);
}

export async function deleteUser(page: Page, userId: string): Promise<void> {
  await page.goto(`/users/delete/${userId}`);
  await page.getByRole('button', { name: 'Delete User' }).click();
  await expect(goodMessage(page)).toHaveText('Record is successfully deleted!');
}

export async function deleteUserIfPresent(page: Page, userId: string): Promise<void> {
  // An account the test already removed leaves no confirmation form, which is
  // not an error. One that is still there has to go, so the removal of it is
  // asserted rather than assumed.
  await page.goto(`/users/delete/${userId}`);
  const confirm = page.getByRole('button', { name: 'Delete User' });
  if (await confirm.count()) {
    await confirm.click();
    await expect(goodMessage(page)).toHaveText('Record is successfully deleted!');
  }
}

function tutorRow(page: Page, tutorName: string): Locator {
  return page
    .locator('#tutors div[id^="tutorsList"]')
    .filter({ has: page.locator(`input[value="${tutorName}"]`) });
}

export async function removeTutorFromCourse(
  page: Page,
  courseId: string,
  tutorName: string
): Promise<void> {
  await page.goto(`/courses/edit/${courseId}`);
  const row = tutorRow(page, tutorName);
  await expect(row).toHaveCount(1);
  await row.getByRole('link', { name: 'X', exact: true }).click();
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('The course was updated successfully.');
}

export async function removeTutorIfPresent(
  page: Page,
  courseId: string,
  tutorName: string
): Promise<void> {
  // Used for cleanup, so a tutor who no longer tutors the course is not an error.
  // The tutor chooser has to be there before an empty row list may be read as
  // "not a tutor": iPeer answers a page it refuses with a redirect and a 200,
  // where every locator is empty and the cleanup would quietly do nothing.
  await page.goto(`/courses/edit/${courseId}`);
  await expect(page.locator('#CourseTutors')).toBeVisible();
  if ((await tutorRow(page, tutorName).count()) > 0) {
    await removeTutorFromCourse(page, courseId, tutorName);
  }
}

export function userSummaryTable(page: Page, heading: string): Locator {
  return page.locator(
    `xpath=//h3[normalize-space()='${heading}']/following-sibling::table[1]`
  );
}

// 'none' is only a course role: it is how the user edit form unenrols someone.
export type CourseRole = 'none' | SystemRole;
export type SystemRole = 'superadmin' | 'admin' | 'instructor' | 'tutor' | 'student';

export type NewUser = ClassListPerson & {
  readonly username: string;
  readonly firstName: string;
  readonly lastName: string;
  readonly role: SystemRole;
  readonly courseIds: readonly string[];
};

// New for each worker process, so the accounts of a worker that restarted
// after a failure, or of a run over a database that was not reseeded, are
// still new ones.
const RUN = Date.now().toString(36);

let accountsMade = 0;

export function nextTestStudent(
  firstName: string,
  lastName: string,
  courseIds: readonly string[]
): NewUser {
  // Deleting an account only marks it inactive, so its username stays taken
  // and no test can reuse one.
  accountsMade += 1;
  const suffix = `${RUN}${accountsMade}`;
  return {
    username: `e2e${suffix}`,
    firstName,
    lastName,
    name: `${firstName} ${lastName}`,
    number: suffix,
    role: 'student',
    courseIds,
  };
}

export async function createUser(page: Page, user: NewUser): Promise<string> {
  await page.goto('/users/add');
  await page.getByLabel('Username', { exact: true }).fill(user.username);
  await page.getByLabel('First Name').fill(user.firstName);
  await page.getByLabel('Last Name').fill(user.lastName);
  await page.getByLabel('Student Number').fill(user.number);
  await page.getByLabel('Role', { exact: true }).selectOption({ label: user.role });
  for (const courseId of user.courseIds) {
    await page.locator(`#CoursesId${courseId}`).check();
  }
  await page.getByRole('button', { name: 'Save', exact: true }).click();

  // iPeer generates the password itself and shows it once, in the message that
  // confirms the account, so this is the only chance to capture it. The caller
  // needs it to sign in as the account it has just made.
  const message = (await goodMessage(page).innerText()).trim();
  const password = message.match(/Password:\s*(\S+)/)?.[1];
  if (!password) {
    throw new Error(`no account created for ${user.username}`);
  }
  return password;
}

// A handle on an account a test made, for test.afterEach to remove it. The id
// is filled in once the test has it; the course is the fallback for a test that
// failed before that.
export type CreatedAccount = {
  readonly person: ClassListPerson;
  readonly courseId: string;
  id?: string;
};

export async function createTestUser(
  page: Page,
  user: NewUser,
  cleanup: Set<CreatedAccount>
): Promise<{ id: string; password: string }> {
  // The handle joins the cleanup set before the account exists, so a failure
  // part way through this helper still leaves the account to be removed.
  const record: CreatedAccount = { person: user, courseId: user.courseIds[0] };
  cleanup.add(record);
  const password = await createUser(page, user);
  const id = await classListUserId(page, record.courseId, user);
  record.id = id;
  return { id, password };
}

export async function deleteCreatedAccount(page: Page, account: CreatedAccount): Promise<void> {
  // The captured id is the handle that keeps working: by the end of a test the
  // account may hold another role or have lost the enrolment, and then the
  // class list of the course it was made in no longer names it.
  const userId =
    account.id ?? (await classListUserIdIfPresent(page, account.courseId, account.person));
  if (userId) {
    await deleteUserIfPresent(page, userId);
  }
}

export async function setUserRoles(
  page: Page,
  userId: string,
  roles: {
    readonly primary: SystemRole;
    readonly faculty?: string;
    readonly courses?: Readonly<Record<string, CourseRole>>;
  }
): Promise<void> {
  await page.goto(`/users/edit/${userId}`);
  for (const [courseId, role] of Object.entries(roles.courses ?? {})) {
    await page.locator(`#course_${courseId}`).selectOption({ label: role });
  }
  // The primary role goes last: picking an instructor course raises it on its
  // own, and this must be the value that ends up submitted. It also decides
  // whether the faculty field is live, and iPeer refuses to save an instructor
  // or an admin that has no faculty.
  await page.getByLabel('Primary Role').selectOption({ label: roles.primary });
  if (roles.faculty) {
    await page.locator('#FacultyFaculty').selectOption({ label: roles.faculty });
  }
  await page.getByRole('button', { name: 'Save' }).click();
  await expect(goodMessage(page)).toHaveText('User successfully updated!');
}

export type MergeAccount = 'Primary' | 'Secondary';
export type MergeSearchField = 'Full Name' | 'Username' | 'Student No.';

export async function openMergeUsers(page: Page): Promise<void> {
  await page.goto('/users/merge');
  // Nothing is picked yet, so the form refuses to submit. The specs search by
  // pressing Enter in a search box, which would submit the form instead if the
  // button were live.
  await expect(mergeButton(page)).toBeDisabled();
}

export function mergeButton(page: Page): Locator {
  return page.getByRole('button', { name: 'Merge' });
}

export function mergeCandidates(page: Page, account: MergeAccount): Locator {
  return page.locator(`#User${account}Account`);
}

export async function searchMergeAccount(
  page: Page,
  account: MergeAccount,
  field: MergeSearchField,
  value: string
): Promise<void> {
  await page.locator(`#User${account}Search`).selectOption({ label: field });
  const search = page.locator(`#User${account}SearchValue`);
  await search.fill(value);

  // The form has no search button: the page looks the accounts up over AJAX
  // when Enter is pressed in the search box, and the search is finished once
  // the prompt option gives way to a pick or to a miss. Now and again the
  // answer arrives and the page does nothing with it, leaving "Searching ..."
  // standing, so press Enter again, the way a reader of the page would. A
  // search that really found nobody still ends as a miss, which the specs
  // assert on.
  const prompt = /Search for|Searching/;
  const firstOption = mergeCandidates(page, account).locator('option').first();
  await search.press('Enter');
  const answered = await expect(firstOption)
    .not.toHaveText(prompt, { timeout: 5000 })
    .then(
      () => true,
      () => false
    );
  if (!answered) {
    await search.press('Enter');
  }
  await expect(firstOption, `${account} account lookup`).not.toHaveText(prompt);
}

export async function pickMergeAccount(
  page: Page,
  account: MergeAccount,
  label: string
): Promise<void> {
  await mergeCandidates(page, account).selectOption({ label });
}

export async function submitMerge(page: Page): Promise<void> {
  page.once('dialog', (dialog) => dialog.accept());
  await mergeButton(page).click();
}

export async function expectNoMergeCandidates(
  page: Page,
  account: MergeAccount
): Promise<void> {
  const candidates = mergeCandidates(page, account);
  await expect(candidates.locator('option')).toHaveText(['-- No users found --']);
  await expect(candidates).toBeDisabled();
}
