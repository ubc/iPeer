import 'vue-router'

export interface IUser {
  id: string|null
  role_id: string|null
  username: string|null
  first_name: string|null
  last_name: string|null
  student_no: string|null
  title: string|null
  email: string|null
}
export interface IEvent {
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
export interface ICourse {
  id: string
  course: string
  title: string
  term?: string
}
export interface IGroup {
  id: string
  num: string
  name: string
  member_count: string
}
export interface IMember {
  id: string
  first_name: string
  last_name: string
  role_name?: string
}
export interface IPenalty {
  id: string
  event_id: string
  days_late: string
  percent_penalty: string
}

/**
 * Evaluation review and response
 */
export interface IEvaluation {
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
  group: IGroup
  course: ICourse
  penalty: IPenalty
  members: IMember[]
  group_event_id: string
  simple?: ISimpleEvaluation
  rubric?: IRubricEvaluation
  mixed?: IMixedEvaluation
  response?: IMixedResponse & ISimpleResponse & IRubricResponse
  reviews?: IMixedReview & ISimpleReview & IRubricReview
  rubric_id?: string
  gen_com_req?: string
  all_done?: string
  enrol?: string
  member_ids?: string
  member_count?: string
  status: string|null
  is_released?: boolean
  is_result_released?: boolean
  is_ended?: boolean
}

/** SimpleEvaluation/Data/Detail specifics */
export interface ISimpleEvaluation {
  id: string
  name: string
  availability: string
  description?: string
  point_per_member?: string
  remaining?: number
  status?: string
  data: ISimpleEvaluationData[]
}
export interface ISimpleEvaluationData {
  points?: string[]
  comments?: string[]
}
// export interface ISimpleEvaluationDataDetail {}
/** SimpleResponse/Data/Detail specifics */
export interface ISimpleResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: ISimpleResponseData[]
}
export interface ISimpleResponseData {
  points?: string[]
  comments?: string[]
}
/** SimpleReviews */
export interface ISimpleReview {}
export interface ISimpleReviewData {}
export interface ISimpleReviewDetail {}


/** RubricEvaluation/Data/Detail specifics */
export interface IRubricEvaluation {
  id: string
  name: string
  availability: string
  zero_mark?: string
  view_mode?: string
  template?: string
  lom_max?: string
  criteria?: string
  data: IRubricEvaluationData
}//disabled
export interface IRubricEvaluationData {
  rubrics_criteria: IRubricEvaluationDataCriteria[],
  rubrics_lom: IRubricEvaluationDataLom[],
}
export interface IRubricEvaluationDataCriteria {
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
export interface IRubricEvaluationDataLom {
  id: string
  rubric_id: string
  lom_num: string
  lom_comment: string
}
/** RubricResponse/Data/Detail specifics */
export interface IRubricResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: IRubricResponseData[]
}
export interface IRubricResponseData {
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
  details: IRubricResponseDataDetail[]
}
export interface IRubricResponseDataDetail {
  id: string
  criteria_number: string
  criteria_comment: string
  selected_lom: string
}
/** RubricReviews specifics */
/** RubricReviews specifics */
export interface IRubricReview {}
export interface IRubricReviewData {}
export interface IRubricReviewDetail {}


/** MixedEvaluation/Data/Lom specifics */
export interface IMixedEvaluation {
  id: string
  name: string
  availability: string
  peer_question: string
  self_eval: string
  total_question: string
  total_marks: string
  zero_mark: string|number
  data: IMixedEvaluationData[]
}
// object[] of questions
export interface IMixedEvaluationData {
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
  loms: IMixedEvaluationDataLom[]
}
// object[] of loms
export interface IMixedEvaluationDataLom {
  id: string
  question_id: string
  scale_level: string
  descriptor: string
}
/** MixedResponse/Data/Detail specifics */
export interface IMixedResponse {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  data: IMixedResponseData[]
}
// object[] of evaluator/evaluatee info and list of question details
export interface IMixedResponseData {
  id: string
  evaluator: string
  evaluatee: string
  score: string
  comment_release: string
  grade_release: string
  grp_event_id: string
  event_id: string
  record_status: string
  details: IMixedResponseDataDetail[]
}
// object with question detail
export interface IMixedResponseDataDetail {
  id: string
  evaluation_mixeval_id: string
  question_number: string
  question_comment: string|null,
  selected_lom: string
  grade: string
  comment_release: string
  record_status: string
}
/** MixedReviews specifics */
export interface IMixedReview {}
export interface IMixedReviewData {}
export interface IMixedReviewDetail {}


/**
 * IReview
 */
export interface IReview {
  event: IReviewEvent
  group: IReviewGroup
  course: IReviewCourse
  simple?: IReviewSimple // IReviewQuestion
  rubric?: IReviewRubric // IReviewQuestion
  mixed?: IReviewMixed // IReviewQuestion
  evaluator: IReviewEvaluation
  evaluatee: IReviewEvaluation|IReviewSimpleEvaluatee
  penalty: IPenalty
  status: IReviewStatus
}
// TODO:: setup is required on the server
export interface IReviewSimpleEvaluatee {
  scores: string[]
  comments: string[]
}
export interface IReviewGroup {
  id: string
  name: string
}
// TODO:: setup is required on the server
export interface IReviewCourse {
  id: string
  title: string
}
export interface IReviewEvent {
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
  creator_id: string
  created: string
  updater_id: string
  modified: string
  canvas_assignment_id: null
  creator: string
  updater: string
  response_count: string
  to_review_count: string
  student_count: string
  group_count: string
  completed_count: string
  is_released: boolean
  is_result_released: boolean
  is_ended: boolean
  due_in: number
}
/** */
export interface IReviewSimple {

}
/** */
export interface IReviewRubric {
  id: string
  name: string
  zero_mark: string
  lom_max: string
  criteria: string
  view_mode: string
  availability: string
  template: string
  creator_id: string
  created: string
  updater_id: string|null
  modified: string
  creator: string
  updater: string|null
  event_count: string
  total_marks: string
  criterias: IReviewRubricCriteria[]
  loms: IReviewRubricLom[]
}
export interface IReviewRubricCriteria {
  id: string
  rubric_id: string
  criteria_num: string
  criteria: string
  multiplier: string
  show_marks: string
}
export interface IReviewRubricLom {
  id: string
  rubric_id: string
  lom_num: string
  lom_comment: string
}
/** */
export interface IReviewMixed {
  id: string
  name: string
  peer_question: string
  self_eval: string
  total_marks: string
  total_question: string
  zero_mark: string
  availability: string
  questions: [
    {
      id: string
      title: string
      instructions: string
      question_num: string
      required: string
      self_eval: string
      mixeval_id: string
      mixeval_question_type_id: string
      multiplier: string
      scale_level: string
      show_marks: string
      type: string
      desc: [
        {
          id: string
          question_id: string
          scale_level: string
          descriptor: string
        }
      ]
    }
  ]
}
/** */
export interface IReviewEvaluation {
  id: string
  evaluator: string
  evaluatee: string
  comment: string
  score: string
  comment_release: string
  grade_release: string
  grp_event_id: string
  event_id: string
  rubric_id: string
  record_status: string
  creator_id: string
  created: string
  updater_id: string
  modified: string
  creator: string
  updater: string
  details: IReviewEvaluationDetail[]
}
export interface IReviewEvaluationDetail {
  id: string
  evaluation_rubric_id: string
  criteria_number: string
  criteria_comment: string
  selected_lom: string
  grade: string
  record_status: string
  creator_id: string
  created: string
  updater_id: string
  modified: string
  creator: string
  updater: string
}


interface IReviewStatus {
  members_count: number
  received_count: number
  average_penalty: number
  average_score: number
  group_average: number
  penalty: number
  grade: boolean
  comment: boolean
}


/**
 * Page Layout
 */
declare module 'vue-router' {
  interface RouteProps {
    //...
  }
  interface RouteMeta {
    breadcrumb?: string
    title?: string
    description?: string
    src?: object
  }
}

export interface IPageHeading {
  title: string
  description: string
  icon: {
    src: string
    size: string
    position: string
  }
}
export interface INotification {
  id?: string|number
  message: string
  status?: number
  type: string
}
