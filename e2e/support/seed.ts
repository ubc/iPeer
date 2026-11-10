// What app/config/sql/ipeer_samples_data.sql puts in the test database. Each
// test starts from this data (see support/db.ts). If the seed file changes,
// update these values.

export const PASSWORD = 'ipeeripeer';

// How the class list, the group pages and the CSV imports name a student.
export type Person = { readonly name: string; readonly number: string };

export const USERS = {
  superAdmin: 'root',
  admin: 'admin2',
  instructor1: 'instructor1',
  instructor2: 'instructor2',
  tutor: 'tutor2',
  student: 'redshirt0001',
} as const;

// What the navigation bar shows for each account. The User model builds
// full_name from the first and last name, and falls back to the username when
// both are empty, which is why admin2 shows as its username.
export const FULL_NAMES: Record<keyof typeof USERS, string> = {
  superAdmin: 'Super Admin',
  admin: 'admin2',
  instructor1: 'Instructor 1',
  instructor2: 'Instructor 2',
  tutor: 'Tutor 2',
  student: 'Ed Student',
};

// iPeer refuses to save an instructor or an admin that has no faculty, so a
// spec that changes a role to one of those has to pick one of these.
export const FACULTIES = ['Applied Science', 'Science'] as const;

// studentCount is every enrolment row of the course, so a test that enrols
// someone twice by mistake makes it disagree.
export const COURSES = {
  mech328: {
    id: '1',
    name: 'MECH 328',
    title: 'Mechanical Engineering Design Project',
    studentCount: 13,
  },
  apsc201: {
    id: '2',
    name: 'APSC 201',
    title: 'Technical Communication',
    studentCount: 15,
  },
  cpsc101: {
    id: '3',
    name: 'CPSC 101',
    title: 'Connecting with Computer Science',
    studentCount: 2,
  },
  cpsc404: {
    id: '4',
    name: 'CPSC 404',
    title: 'Advanced Software Engineering',
    studentCount: 0,
  },
} as const;

// The full name is what iPeer shows in page titles and course lists.
export function fullName(course: { name: string; title: string }): string {
  return `${course.name} - ${course.title}`;
}

// The courses a student is enrolled in decide which group chooser lists them,
// so the enrolment is part of the fact. Matt is in two courses.
// One row per student reads as a table, so Prettier leaves it as it is.
// prettier-ignore
export const STUDENTS = {
  ed: { username: 'redshirt0001', name: 'Ed Student', number: '65498451', courses: ['mech328'] },
  alex: { username: 'redshirt0002', name: 'Alex Student', number: '65468188', courses: ['mech328'] },
  matt: { username: 'redshirt0003', name: 'Matt Student', number: '98985481', courses: ['mech328', 'apsc201'] },
  chris: { username: 'redshirt0004', name: 'Chris Student', number: '16585158', courses: ['cpsc101'] },
  johnny: { username: 'redshirt0005', name: 'Johnny Student', number: '81121651', courses: ['apsc201'] },
  travis: { username: 'redshirt0006', name: 'Travis Student', number: '87800283', courses: ['apsc201'] },
  damien: { username: 'redshirt0009', name: 'Damien Student', number: '84188465', courses: ['mech328'] },
  denny: { username: 'redshirt0014', name: 'Denny Student', number: '76035030', courses: ['apsc201'] },
  trevor: { username: 'redshirt0019', name: 'Trevor Student', number: '89947048', courses: ['apsc201'] },
  // redshirt0024 is also called Michael Student, but is in MECH 328, so the
  // name is unambiguous inside APSC 201.
  michael: { username: 'redshirt0020', name: 'Michael Student', number: '39823059', courses: ['apsc201'] },
  vanHong: { username: 'redshirt0023', name: 'Van Hong Student', number: '40289059', courses: ['apsc201'] },
  geoff: { username: 'redshirt0026', name: 'Geoff Student', number: '27879030', courses: ['apsc201'] },
} as const;

// A tutor is attached to a course apart from the student enrolments, so the
// course home page lists them in their own column. Tutor 3 tutors no course
// that MECH 328 shares, which is why the enrolment specs move them there.
export const TUTORS = {
  tutor1: { username: 'tutor1', name: 'Tutor 1', courses: ['mech328'] },
  tutor2: { username: 'tutor2', name: 'Tutor 2', courses: ['mech328'] },
  tutor3: { username: 'tutor3', name: 'Tutor 3', courses: ['apsc201', 'cpsc101'] },
} as const;
