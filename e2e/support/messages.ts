// The flash messages of the app that the specs check, in one place. A check
// compares the whole message, so it also catches a wrong message, and a change
// of wording needs one edit here. Grouped by the page that shows them.

// Access and missing records, on many pages.
export const ACCESS = {
  noCourse: 'Error: Course does not exist or you do not have permission to view this course.',
  invalidId: 'Error: Invalid ID.',
} as const;

// A user without the right to a template, by the kind of template and the
// action.
export function noTemplatePermission(
  action: 'view' | 'edit' | 'copy' | 'delete',
  template: 'evaluation' | 'rubric'
): string {
  return `Error: You do not have permission to ${action} this ${template}`;
}

export const COURSE = {
  created: 'Course created!',
  createFailed: 'Add course failed.',
  updated: 'The course was updated successfully.',
  deleted: 'The course was deleted successfully.',
} as const;

export const GROUP = {
  added: 'The group was added successfully.',
  updated: 'The group was updated successfully.',
  deleted: 'The group was deleted successfully.',
} as const;

export const USER = {
  updated: 'User successfully updated!',
  enrolled: 'User is successfully enrolled.',
  unenrolled: 'Student is successfully unenrolled!',
  alreadyEnrolled: 'Error: The student is already enrolled.',
  noEnrolPermission: 'Error: You do not have permission to enrol this user.',
  merged: 'The two accounts have successfully merged.',
  mergeDifferentRoles: 'Error: The users do not have the same role.',
  mergeSignedIn: 'Error: The secondary account is the currently logged in user.',
  mergeSameAccount: 'Error: No merger needed. The primary and secondary accounts are the same.',
} as const;

export const PERMISSIONS = {
  updated: 'Permissions have been updated',
} as const;

export const UPLOAD = {
  noFile: 'Please select a file to upload.',
  wrongExtension: 'extension is not allowed.',
} as const;

export const EVENT = {
  added: 'Add event successful!',
  addFailed: 'Add event failed.',
  edited: 'Edit event successful!',
  deleted: 'The event has been deleted successfully.',
  reminderSent: 'Cannot cancel: Email is already sent.',
  imported: (count: number) => `${count} event(s) were imported successfully.`,
} as const;

export const SIMPLE_TEMPLATE = {
  added: 'The evaluation was added successfully.',
  addFailed: 'The evaluation was not added successfully.',
  updated: 'The simple evaluation was updated successfully.',
  deleted: 'The evaluation was deleted successfully.',
  inUse: (name: string) =>
    `Submissions had been made. ${name} cannot be edited. Please make a copy.`,
} as const;

export const RUBRIC = {
  added: 'The rubric was added successfully.',
  addFailed: 'The evaluation was not added successfully.',
  updated: 'The rubric evaluation was updated successfully',
  deleted: 'The rubric was deleted successfully.',
  inUse: (name: string) =>
    `Submissions had been made. ${name} cannot be edited. Please make a copy.`,
} as const;

export const MIXEVAL = {
  saved: 'The mixed evaluation was saved successfully.',
  notSaved: 'Unable to save, please check below for error messages.',
  removed: 'The Mixed Evaluation was removed successfully.',
  invalidId: 'Error: Invalid Mixeval ID.',
  inUse: (name: string) =>
    `${name} cannot be edited now that submissions have been made. Please make a copy.`,
} as const;

// The student evaluation forms.
export const SUBMISSION = {
  submitted: 'Your Evaluation was submitted successfully.',
  mixevalSavedNotSubmitted:
    'Your answers have been saved. Please answer all the required questions before it can be considered submitted.',
  negativePoints:
    'One or more of your group members have negative points. Please use positive numbers.',
} as const;

// The message of a saved rubric section. It says what is left to do, as
// evaluations_controller.php builds it.
export function rubricSectionSaved(left: { comments?: boolean; submit?: boolean } = {}): string {
  const parts = [
    ...(left.comments ? ['some comments are missing'] : []),
    ...(left.submit
      ? ['you still have to submit the evaluation with the Submit button below']
      : []),
  ];
  return `Your evaluation has been saved${parts.length ? `, but ${parts.join(' and ')}` : ''}.`;
}
