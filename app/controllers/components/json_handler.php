<?php


class JsonHandlerComponent extends CakeObject
{
  public $controller;
  public $settings;
  
  public function initialize($controller, $settings) {
    $this->controller = $controller;
    $this->settings = $settings;
  }
  
  /**
   * @param array $result
   * @return array
   */
  public function formatEvents(array $result): array
  {
    $data = [];
    foreach ($result as $row) {
      $tmp = [];
      isset($row['Event']['id']) ? $tmp['event'] = [
        'id' => $row['Event']['id'],
        'title' => $row['Event']['title'],
        'course_id' => $row['Event']['course_id'],
        'description' => $row['Event']['description'],
        'event_template_type_id' => $row['Event']['event_template_type_id'],
        'template_id' => $row['Event']['template_id'],
        'self_eval' => $row['Event']['self_eval'],
        'com_req' => $row['Event']['com_req'],
        'auto_release' => $row['Event']['auto_release'],
        'enable_details' => $row['Event']['enable_details'],
        'due_date' => $row['Event']['due_date'],
        'release_date_begin' => $row['Event']['release_date_begin'],
        'release_date_end' => $row['Event']['release_date_end'],
        'result_release_date_begin' => $row['Event']['result_release_date_begin'],
        'result_release_date_end' => $row['Event']['result_release_date_end'],
        'record_status' => $row['Event']['record_status'],
        'is_released' => $row['Event']['is_released'],
        'is_result_released' => $row['Event']['is_result_released'],
        'is_ended' => $row['Event']['is_ended'],
        'due_in' => $row['Event']['due_in'],
      ] : null;
      isset($row['Course']['id']) ? $tmp['course'] = [
        'id' => $row['Course']['id'],
        'course' => $row['Course']['course'],
        'title' => $row['Course']['title'],
        'term' => $row['Course']['term'],
      ] : null;
      isset($row['Group']['id']) ? $tmp['group'] = [
        'id' => $row['Group']['id'],
        'group_num' => $row['Group']['group_num'],
        'group_name' => $row['Group']['group_name'],
        'member_count' => $row['Group']['member_count'],
      ] : null;
      isset($row['EvaluationSubmission']['id']) ? $tmp['submission'] = [
        'id' => $row['EvaluationSubmission']['id'],
        'submitter_id' =>  $row['EvaluationSubmission']['submitter_id'],
        'submitted' =>  $row['EvaluationSubmission']['submitted'],
        'date_submitted' =>  $row['EvaluationSubmission']['date_submitted'],
      ] : null;
      isset($row['Penalty']) && !empty($row['Penalty']) ? $tmp['penalties'] = $row['Penalty'] : null;
      isset($row['late']) ? $tmp['late'] = $row['late'] : null;
      isset($row['percent_penalty']) ? $tmp['percent_penalty'] = $row['percent_penalty'] : null;
      
      $data[] = $tmp;
    }
    return $data;
  }
  
  /**
   * @param array $data
   * @return void
   */
  public function formatSimpleEvaluation(array $data): void
  {
    $json               = $this->getEventData($data);
    $json['questions']  = $this->getSimpleEvaluationQuestions($data['questions']);
    $json['submission'] = $this->getSimpleEvaluationSubmission($data['submission'], $data['evaluation']);
    // NOTE:: Specific to Simple Evaluation
    $json['remaining']  = $data['remaining'];
    
    // header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($json);
    exit;
  }
  
  /**
   * @param array $data
   * @return void
   */
  public function formatRubricEvaluation(array $data): void
  {
    $json               = $this->getEventData($data);
    $json['questions']  = $this->getRubricEvaluationQuestions($data['questions']);
    $json['submission'] = $this->getRubricEvaluationSubmission($data['submission'], $data['groupMembers']);
    // NOTE:: Specific to Rubric Evaluation
    $json['rubric_id']  = $data['rubricId'];
    
    // header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($json);
    exit;
  }
  
  
  public function formatMixedEvaluation(array $data): void
  {
    $response               = $this->getEventData($data);
    $response['questions']  = $data['questions'];
    $response['answers']    = $data['mixedEvalViewData'];
    $response['submission'] = $data['mixedEvalViewData'];
    
    //header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($response);
    exit;
  }
  
  
  // JK:: PRIVATE HELPER METHODS
  /**
   * @param array $data
   * @return array
   */
  private function getEventData(array $data): array
  {
    $output = [];
    $output['event'] = [
      'id'                      => $data['event']['Event']['id'],
      'title'                   => $data['event']['Event']['title'],
      'course_id'               => $data['event']['Event']['course_id'],
      'description'             => $data['event']['Event']['description'],
      'event_template_type_id'  => $data['event']['Event']['event_template_type_id'],
      'template_id'             => $data['event']['Event']['template_id'],
      'self_eval'               => $data['event']['Event']['self_eval'],
      'com_req'                 => $data['event']['Event']['com_req'],
      'due_date'                => $data['event']['Event']['due_date'],
    ];
    $output['course']           = $this->getCourseById($data['event']['Event']['course_id']);
    $output['members']          = $this->getGroupMembers($data['groupMembers']);
    $output['penalties']        = $this->getPenalties($data['penalty']);
    $output['group_event']      = $data['event']['GroupEvent'];
    $output['group']            = $data['event']['Group'];
    $output['penalty_final']    = $data['penaltyFinal']['Penalty'];
    $output['penalty_days']     = $data['penaltyDays'];
    $output['member_ids']       = $data['memberIDs'];
    $output['evaluatee_count']  = $data['evaluateeCount'];
    $output['user_id']          = $data['userId'];
    $output['allDone']          = $data['allDone'];
    $output['comReq']           = $data['comReq'];
    
    return $output;
  }
  
  private function getCourseById(string $courseId): array
  {
    return [
      'id' => $courseId,
      'title' => "Course Title:: TODO 101 $courseId"
    ];
  }

  private function getGroupMembers(array $group_members): array
  {
    if (empty($group_members)) return $group_members;
    $data = [];
    foreach ($group_members as $member) {
      $tmp = [];
      $tmp['id'] = $member['User']['id'];
      $tmp['first_name'] = $member['User']['first_name'];
      $tmp['last_name'] = $member['User']['last_name'];
      $tmp['role_name'] = $member['Role'][0]['name'];
      
      $data[] = $tmp;
    }
    
    return $data;
  }

  private function getPenalties(array $penalties): array
  {
    if (empty($penalties)) return $penalties;
    $data = [];
    foreach ($penalties as $penalty) {
      $data[] = $penalty['Penalty'];
    }
    return $data;
  }
  
  
  // Simple
  private function getSimpleEvaluationSubmission(array $submission, array $evaluation): array
  {
    if(empty($submission['EvaluationSubmission']) || empty($evaluation)) return ['points' => [], 'comments' => []];
    $data = [];
    $data['id'] = $submission['EvaluationSubmission']['id'];
    $data['submitter_id'] = $submission['EvaluationSubmission']['submitter_id'];
    $data['submitted'] = $submission['EvaluationSubmission']['submitted'];
    $data['date_submitted'] = $submission['EvaluationSubmission']['date_submitted'];
    $data['points'] = [];
    $data['comments'] = [];
    /***/
    foreach ($evaluation as $value) {
      $tmp = [];
      $tmp['score'] = $value['EvaluationSimple']['score'];
      $tmp['comment'] = $value['EvaluationSimple']['comment'];
  
      $data['points'][] = $tmp['score'];
      $data['comments'][] = $tmp['comment'];
    };
    return $data;
  }
  
  private function getSimpleEvaluationQuestions($questions): array
  {
    if(empty($questions)) return [];
    return [
      'id'                => $questions['id'],
      'availability'      => $questions['availability'],
      'name'              => $questions['name'],
      'description'       => $questions['description'],
      'point_per_member'  => $questions['point_per_member'],
      'status'            => $questions['record_status'],
    ];
  }
  
  // Rubrics
  private function getRubricEvaluationQuestions(array $questions): array
  {
    if (empty($questions)) return $questions;
    return ['rubric' => [
        'id'              => $questions['Rubric']['id'],
        'name'            => $questions['Rubric']['name'],
        'zero_mark'       => $questions['Rubric']['zero_mark'],
        'view_mode'       => $questions['Rubric']['view_mode'],
        'template'        => $questions['Rubric']['template'],
        'availability'    => $questions['Rubric']['availability'],
        'lom_max'         => $questions['Rubric']['lom_max'],
        'criteria'        => $questions['Rubric']['criteria'],
      ],
      'rubrics_criteria'  => $this->getRubricEvaluationCriteria($questions['RubricsCriteria']),
      'rubrics_lom'       => $questions['RubricsLom']
    ];
  }
  
  private function getRubricEvaluationCriteria(array $RubricsCriteria): array
  {
    if (empty($RubricsCriteria)) return $RubricsCriteria;
    $data = [];
    foreach ($RubricsCriteria as $criteria) {
      $tmp = [];
      $tmp['id'] = $criteria['id'];
      $tmp['rubric_id'] = $criteria['rubric_id'];
      $tmp['criteria_num'] = $criteria['criteria_num'];
      $tmp['criteria'] = $criteria['criteria'];
      $tmp['multiplier'] = $criteria['multiplier'];
      $tmp['show_marks'] = $criteria['show_marks'];
    
      foreach ($criteria['RubricsCriteriaComment'] as $criteria_comment) {
        $comment = [];
        $comment['id'] = $criteria_comment['id'];
        $comment['criteria_id'] = $criteria_comment['criteria_id'];
        $comment['rubrics_loms_id'] = $criteria_comment['rubrics_loms_id'];
        $comment['criteria_comment'] = $criteria_comment['criteria_comment'];
      
        $tmp['rubrics_criteria_comment'][] = $comment;
      }
      $data[] = $tmp;
    }
    return $data;
  }
  
  private function getRubricEvaluationSubmission(array $submission, array $groupMembers): array
  {
    if(empty($submission['EvaluationSubmission']) || empty($groupMembers)) return [];
    $data['id']               = $submission['EvaluationSubmission']['id'];
    $data['submitter_id']     = $submission['EvaluationSubmission']['submitter_id'];
    $data['submitted']        = $submission['EvaluationSubmission']['submitted'];
    $data['date_submitted']   = $submission['EvaluationSubmission']['date_submitted'];
    
    foreach ($groupMembers as $member) {
      $tmp = [
        'id'                => $member['User']['Evaluation']['EvaluationRubric']['id'],
        //'evaluator'         => $member['User']['Evaluation']['EvaluationRubric']['evaluator'],
        'evaluatee'         => $member['User']['Evaluation']['EvaluationRubric']['evaluatee'],
        'comment'           => $member['User']['Evaluation']['EvaluationRubric']['comment'],
        'score'             => $member['User']['Evaluation']['EvaluationRubric']['score'],
        //'comment_release'   => $member['User']['Evaluation']['EvaluationRubric']['comment_release'],
        //'grade_release'     => $member['User']['Evaluation']['EvaluationRubric']['grade_release'],
        //'grp_event_id'      => $member['User']['Evaluation']['EvaluationRubric']['grp_event_id'],
        //'event_id'          => $member['User']['Evaluation']['EvaluationRubric']['event_id'],
        //'record_status'     => $member['User']['Evaluation']['EvaluationRubric']['record_status'],
        //'creator_id'        => $member['User']['Evaluation']['EvaluationRubric']['creator_id'],
  
        'details'             => $this->getRubricEvaluationRubricDetail($member['User']['Evaluation']['EvaluationRubricDetail'])
      ];

      $data['response'][] = $tmp;
    };
    return $data;
  }
  
  private function getRubricEvaluationRubricDetail(array $rubricDetail): array
  {
    if(empty($rubricDetail)) return [];
    $data = [];
    foreach ($rubricDetail as $detail) {
      $tmp = [];
      $tmp['id']                    = $detail['id'];
      //$tmp['evaluation_rubric_id']  = $detail['evaluation_rubric_id'];
      $tmp['criteria_number']       = $detail['criteria_number'];
      $tmp['criteria_comment']      = $detail['criteria_comment'];
      $tmp['selected_lom']          = $detail['selected_lom'];
      //$tmp['grade']                 = $detail['grade'];
      //$tmp['comment_release']       = $detail['comment_release'];
      //$tmp['record_status']         = $detail['record_status'];
      //$tmp['creator_id']            = $detail['creator_id'];
      //$tmp['updater_id']            = $detail['updater_id'];
      //$tmp['created']               = $detail['created'];
      //$tmp['modified']              = $detail['modified'];
      //$tmp['creator']               = $detail['creator'];
      //$tmp['updater']               = $detail['updater'];
      
      $data[] = $tmp;
    }
    return $data;
  }
  
  // Mixed
  
}