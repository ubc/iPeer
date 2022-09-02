<?php


class JsonHandlerComponent extends CakeObject
{
  public $controller;
  public $settings;
  
  public function initialize($controller, $settings) {
    $this->controller = $controller;
    $this->settings = $settings;
  }
  
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
  
  public function formatRubricEvaluation(array $result): array
  {
    $data = $this->getEventData($result);
    //$data['penalty_final'] = $result['penaltyFinal']['Penalty'];
    //$data['penalty_days'] = $result['penaltyDays'];
    $data['evaluation'] = [
      'rubric' => [
        'id' => $result['data']['Rubric']['id'],
        'name' => $result['data']['Rubric']['name'],
        'zero_mark' => $result['data']['Rubric']['zero_mark'],
        'view_mode' => $result['data']['Rubric']['view_mode'],
        'template' => $result['data']['Rubric']['template'],
        'availability' => $result['data']['Rubric']['availability'],
        'lom_max' => $result['data']['Rubric']['lom_max'],
        'criteria' => $result['data']['Rubric']['criteria'],
      ],
      'rubrics_criteria' => $this->getRubricsCriteria($result['data']['RubricsCriteria']),
      'rubrics_lom' => $result['data']['RubricsLom']
    ];
    $data['submission'] = $this->getRubricsResponse($result['groupMembers']);
    
    $data['userIds'] = $result['userIds'];
    $data['evaluateeCount'] = $result['evaluateeCount'];
    $data['allDone'] = $result['allDone'];
    $data['comReq'] = $result['comReq'];
    $data['studentId'] = $result['studentId'];
    $data['fullName'] = $result['fullName'];
    $data['userId'] = $result['userId'];
    
    // header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($data);
    exit;
  }
  
  /**
   * @param array $result
   * @return array
   */
  private function getEventData(array $result): array
  {
    $data = [];
    $data['event'] = [
      'id' => $result['event']['Event']['id'],
      'title' => $result['event']['Event']['title'],
      'course_id' => $result['event']['Event']['course_id'],
      'description' => $result['event']['Event']['description'],
      'event_template_type_id' => $result['event']['Event']['event_template_type_id'],
      'template_id' => $result['event']['Event']['template_id'],
      'self_eval' => $result['event']['Event']['self_eval'],
      'com_req' => $result['event']['Event']['com_req'],
    ];
    $data['group_event'] = $result['event']['GroupEvent'];
    $data['group'] = $result['event']['Group'];
    $data['members'] = $this->getGroupMembers($result['groupMembers']);
    $data['penalties'] = $this->getPenalties($result['penalty']);
    return $data;
  }
  
  /**
   * @param array $group_members
   * @return array
   */
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
  
  /**
   * @param array $penalties
   * @return array
   */
  private function getPenalties(array $penalties): array
  {
    if (empty($penalties)) return $penalties;
    $data = [];
    foreach ($penalties as $penalty) {
      $data[] = $penalty['Penalty'];
    }
    return $data;
  }
  
  /**
   * @param array $rubrics_criteria
   * @return array
   */
  private function getRubricsCriteria(array $rubrics_criteria): array
  {
    if (empty($rubrics_criteria)) return $rubrics_criteria;
    $data = [];
    foreach ($rubrics_criteria as $criteria) {
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
  
  /**
   * @param array $members
   * @return array
   */
  private function getRubricsResponse(array $members): array
  {
    if (empty($members)) return $members;
    $data = [];
    foreach ($members as $member) {
      $tmp = [];
      isset($member['User']['Evaluation']) ? $tmp = [
        'id' => $member['User']['Evaluation']['EvaluationRubric']['id'],
        'evaluator' => $member['User']['Evaluation']['EvaluationRubric']['evaluator'],
        'evaluatee' => $member['User']['Evaluation']['EvaluationRubric']['evaluatee'],
        'comment' => $member['User']['Evaluation']['EvaluationRubric']['comment'],
        'score' => $member['User']['Evaluation']['EvaluationRubric']['score'],
        'comment_release' => $member['User']['Evaluation']['EvaluationRubric']['comment_release'],
        'grade_release' => $member['User']['Evaluation']['EvaluationRubric']['grade_release'],
        'grp_event_id' => $member['User']['Evaluation']['EvaluationRubric']['grp_event_id'],
        'event_id' => $member['User']['Evaluation']['EvaluationRubric']['event_id'],
        'record_status' => $member['User']['Evaluation']['EvaluationRubric']['record_status'],
        'creator_id' => $member['User']['Evaluation']['EvaluationRubric']['creator_id'],
        
        'details' => $member['User']['Evaluation']['EvaluationRubricDetail']
      ] : null;
    
      isset($member['User']['Evaluation']) ? $data[] = $tmp : null;
    }
    
    return $data;
  }
  
  
}