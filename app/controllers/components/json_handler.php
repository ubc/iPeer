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
}