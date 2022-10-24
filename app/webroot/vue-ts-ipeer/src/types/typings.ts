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
  rubric_id: string
  template: string
  group_event_id: string
  status: string | null
  questions: object
  review: EvaluationReview
}

/**
"review": {
  "id": "1",
  "name": "Module 1 Project Evaluation",
  "availability": "public",

  "description": "",
  "point_per_member": "100",
  "status": "A",
  "data": {},
  "response": {
    "id": "1",
    "submitter_id": "7",
    "submitted": "0",
    "date_submitted": "2022-09-02 22:28:52",
    "points": ["100","100"],
    "comments": []
  }
}*/
export interface EvaluationReview {
  id: string
  name: string
  availability: string

  // Rubric
  zero_mark?: string
  view_mode?: string
  template?: string
  lom_max?: string
  criteria?: string

  // Simple
  description?: string
  point_per_member?: string
  status?: string

  // Mixed
  //
  data: SimpleEvaluationReviewData & RubricEvaluationReviewData & MixedEvaluationReviewData
  response: EvaluationReviewResponse
}

/** SimpleEvaluationReviewData */
export interface SimpleEvaluationReviewData {
  points?: string[]
  comments?: string[]
}

/** RubricEvaluationReviewData */
export interface RubricEvaluationReviewData {
  rubrics_criteria: RubricEvaluationReviewDataCriteria[],
  rubrics_lom: RubricEvaluationReviewDataLom[],
}
export interface RubricEvaluationReviewDataCriteria {
  id: string
  rubric_id: string
  criteria_num: string
  criteria: string
  multiplier: string
  show_marks: string
  rubrics_criteria_comment: [
    {
      id: string
      criteria_id: string
      rubrics_loms_id: string
      criteria_comment: string
    }
  ]
}
export interface RubricEvaluationReviewDataLom {
  id: string
  rubric_id: string
  lom_num: string
  lom_comment: string
}

/** MixedEvaluationReviewData */
export interface MixedEvaluationReviewData {}


/**
 * Evaluation Review Response
 */
export interface EvaluationReviewResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: SimpleReviewResponseData | RubricReviewResponseData[] | MixedReviewResponseData
}
/** */
export interface EvaluationReviewResponseData {}
/** SimpleReviewResponseData specifics */
export interface SimpleReviewResponseData {
  points?: string[]
  comments?: string[]
}

/** RubricReviewResponseData specifics */
export interface RubricReviewResponseData {
  id: string
  evaluator: string
  evaluatee: string
  comment: string
  score: string
  details: RubricReviewResponseDataDetail[]
}
export interface RubricReviewResponseDataDetail {
  id: string
  criteria_number: string
  criteria_comment: string
  selected_lom: string
}

/** MixedReviewResponseData specifics */
export interface MixedReviewResponseData {

}
