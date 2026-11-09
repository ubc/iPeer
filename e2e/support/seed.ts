// What app/config/sql/ipeer_samples_data.sql puts in the test database. These
// values are true of a freshly seeded database, and a test that changes them
// puts them back. tests/seed.setup.ts checks them before the rest of the suite
// runs, so a change to the seed file fails there instead of in many specs.

export const PASSWORD = 'ipeeripeer';

export const USERS = {
  superAdmin: 'root',
  admin: 'admin2',
  instructor1: 'instructor1',
  instructor2: 'instructor2',
  tutor: 'tutor2',
  student: 'redshirt0001',
} as const;

export const COURSES = {
  mech328: { id: '1', name: 'MECH 328', title: 'Mechanical Engineering Design Project' },
  apsc201: { id: '2', name: 'APSC 201', title: 'Technical Communication' },
  cpsc101: { id: '3', name: 'CPSC 101', title: 'Connecting with Computer Science' },
  cpsc404: { id: '4', name: 'CPSC 404', title: 'Advanced Software Engineering' },
} as const;

// The full name is what iPeer shows in page titles and course lists.
export function fullName(course: { name: string; title: string }): string {
  return `${course.name} - ${course.title}`;
}

// The courses a student is enrolled in decide which group chooser lists them,
// so the enrolment is part of the fact. Matt is in two courses.
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
