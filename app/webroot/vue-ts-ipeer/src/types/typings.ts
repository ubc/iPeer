export interface User {
  "id": string | null
  "role_id": string | null
  "username": string | null
  "first_name": string | null
  "last_name": string | null
  "student_no": string | null
  "title": string | null
  "email": string | null
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
  is_released: boolean,
  is_result_released: boolean,
  is_ended: boolean,
  due_in: string
}

export interface Course {
  id: string
  course: string
  title: string
  term: string
}

export interface Group {
  id: string
  group_num: string
  group_name: string
  member_count: string
}

export interface Penalty {
  id: string
  event_id: string
  days_late: string
  percent_penalty: string
}

