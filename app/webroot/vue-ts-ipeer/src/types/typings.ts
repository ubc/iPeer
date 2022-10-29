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

export interface Penalty {
  id: string
  event_id: string
  days_late: string
  percent_penalty: string
}


/**
 * Evaluation review and response
 */
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
  //
  group: Group
  course: Course
  penalty: Penalty // rename to penalty
  members: Member[]
  // New to all templates
  template: string
  group_event_id: string
  status: string | null
  // Simple
  // Rubric
  rubric_id: string
  gen_com_req: string // general comment section
  all_done: string
  enrol?: string
  self?: string
  member_ids?: string
  member_count?: string
  // Mixed
  //
  review?: MixedReview & SimpleReview & RubricReview & EvaluationReview
  response?: MixedResponse & SimpleResponse & RubricResponse
}

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
  data: SimpleEvaluationReviewData & RubricEvaluationReviewData
  response?: EvaluationReviewResponse
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


/**
 * Evaluation Review Response
 */
export interface EvaluationReviewResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: SimpleReviewResponseData | RubricReviewResponseData[]
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


/**
 * refactored
 */
/** SimpleReview/Data/Detail specifics */
export interface SimpleReview {
  id: string
  name: string
  availability: string
  description?: string
  point_per_member?: string
  status?: string
  data: SimpleReviewData[]
}
export interface SimpleReviewData {}
export interface SimpleReviewDataDetail {}
/** SimpleResponse/Data/Detail specifics */
export interface SimpleResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: SimpleResponseData[]
}
export interface SimpleResponseData {
  details: SimpleResponseDataDetail
}
export interface SimpleResponseDataDetail {
  points?: string[]
  comments?: string[]
}



/** RubricReview/Data/Detail specifics */
export interface RubricReview {
  id: string
  name: string
  availability: string
  zero_mark?: string
  view_mode?: string
  template?: string
  lom_max?: string
  criteria?: string
  data: RubricReviewData[]
}
export interface RubricReviewData {
  rubrics_criteria: RubricReviewDataCriteria[],
  rubrics_lom: RubricReviewDataLom[],
}
export interface RubricReviewDataCriteria {
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
export interface RubricReviewDataLom {
  id: string
  rubric_id: string
  lom_num: string
  lom_comment: string
}
/** RubricResponse/Data/Detail specifics */
export interface RubricResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: RubricResponseData[]
}
export interface RubricResponseData {
  id: string
  evaluator: string
  evaluatee: string
  comment: string
  score: string
  comment_release: string
  grade_release: string
  grp_event_id: string
  event_id: string
  record_status: string
  details: RubricResponseDataDetail[]
}
export interface RubricResponseDataDetail {
  id: string
  criteria_number: string
  criteria_comment: string
  selected_lom: string
}



/** MixedReview/Data/Lom specifics */
export interface MixedReview {
  id: string
  name: string
  availability: string
  peer_question: string
  self_eval: string
  total_question: string
  total_marks: string
  zero_mark: string
  data: MixedReviewData[]
}
export interface MixedReviewData {
  id: string
  type: string
  title: string
  instructions: string
  mixeval_id: string
  mixeval_question_type_id: string
  multiplier: string
  question_num: string
  scale_level: string
  self_eval: boolean,
  show_marks: string
  required: boolean,
  loms: MixedReviewDataLom[]
}
export interface MixedReviewDataLom {
  id: string
  question_id: string
  scale_level: string
  descriptor: string
}
/** MixedResponse/Data/Detail specifics */
export interface MixedResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: MixedResponseData[]
}
export interface MixedResponseData {
  id: string
  evaluator: string
  evaluatee: string
  score: string
  comment_release: string
  grade_release: string
  grp_event_id: string
  event_id: string
  record_status: string
  details: MixedResponseDataDetail[]
}
export interface MixedResponseDataDetail {
  id: string
  evaluation_mixeval_id: string
  question_number: string
  question_comment: string|null,
  selected_lom: string
  grade: string
  comment_release: string
  record_status: string
}
