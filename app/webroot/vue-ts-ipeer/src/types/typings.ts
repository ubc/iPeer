export interface User {
  id: string | null
  role_id: string | null
  username: string | null
  first_name: string | null
  last_name: string | null
  student_no: string | null
  title: string | null
  email: string | null
}

export interface Event {
  id: string
  title: string
  course_id: string
  description: string
  event_template_type_id: string
  template_id: string
  self_eval: string
  com_req: string
  auto_release: string
  enable_details: string
  due_date: string
  release_date_begin: string
  release_date_end: string
  result_release_date_begin: string
  result_release_date_end: string
  record_status: string
  is_submitted: string
  is_released: boolean
  is_result_released: boolean
  is_ended: boolean
  due_in: string
}

export interface Evaluation {
  id: string
  title: string
  description: string
  event_template_type_id: string
  template_id: string
  self_eval: string
  com_req: string
  auto_release: string
  enable_details: string
  due_date: string
  release_date_begin: string
  release_date_end: string
  result_release_date_begin: string
  result_release_date_end: string
  group: Group
  course: Course
  penalty_final: Penalty
  members: Member[]
  template: string
  group_event_id: string
  status: string | null
  questions: object
  review: object
}

export interface __Evaluation {
  event: Event
  group: Group
  group_event: GroupEvent
  rubric_id: string
  user_id: string
  evaluatee_count: string
  member_ids: string[]
  submission: Submission
}

export interface Course {
  id: string
  course: string
  title: string
  term: string
}

export interface Group {
  id: string
  num: string
  name: string
  member_count: string
}

export interface Member {
  id: string
  first_name: string
  last_name: string
  role_name: string
}

export interface GroupEvent {
  id: string
}

export interface Penalty {
  id: string
  event_id: string
  days_late: string
  percent_penalty: string
}

export interface Submission {
  id: string
  date_submitted: string
  response: Response[]
  submitted: string
  submitter_id: string
}

export interface Response {
  id: string
  comment: string
  details: Detail[]
  evaluatee: string
  evaluator: string
  score: string
}

export interface Detail {
  criteria_comment: string
  criteria_number: string
  id: string
  selected_lom: string
}

