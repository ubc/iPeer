
export class User implements IUser {

  email: string | null
  first_name: string | null
  id: string | null
  last_name: string | null
  private _role_id: string | null
  student_no: string | null
  title: string | null
  username: string | null

  constructor() {
    this.email = ''
    this.first_name = ''
    this.id = ''
    this.last_name = ''
    this._role_id = ''
    this.student_no = ''
    this.title = ''
    this.username = ''
  }

  get role_id(): string | null {
    return this._role_id;
  }

  public getRequest(): void {

  }
  public setRequest(): void {

  }

  public notInTheInterface():void {}

}

export interface IUser {
  id: string|null
  // role_id: string|null
  username: string|null
  first_name: string|null
  last_name: string|null
  student_no: string|null
  title: string|null
  email: string|null
  getRequest(): void;
  setRequest(): void;

  get role_id(): string | null
}

const testVariable: User = new User()
//testVariable.role_id
//testVariable.username



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
  term: string
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
  role_name: string
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
  //
  group: IGroup
  course: ICourse
  penalty: IPenalty // rename to penalty
  members: IMember[]
  // New to all templates
  template: string // New
  group_event_id: string // New
  status: string | null // New
  // Simple
  simple?: ISimpleEvaluation
  // Rubric
  rubric?: IRubricEvaluation
  rubric_id?: string // TBD
  gen_com_req?: string // general comment section
  all_done?: string // TBD
  enrol?: string // TBD
  member_ids?: string // TODO:: Make it generic
  member_count?: string  // TODO:: Make it generic
  // Mixed
  mixed?: IMixedEvaluation
  // Response
  response?: IMixedResponse & ISimpleResponse & IRubricResponse
  reviews?: IMixedReview & ISimpleReview & IRubricReview
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
 * Page Layout
 */
export interface IPageHeading {
  title: string
  description: string
  icon: {
    src: string
    size: string
    position: string
  }
}