<?php
class EvaluationMixevalDetail extends AppModel
{
  var $name = 'EvaluationMixevalDetail';

  var $belongsTo = array(
      'EvaluationMixeval' =>
      array(
          'className' => 'EvaluationMixeval',
          'foreignKey' => 'evaluation_mixeval_id'
      )
  );

  function getByEvalMixevalIdCritera($MixevalId=null, $question=null){
      $sql = 'evaluation_mixeval_id='.$MixevalId;
      if ($question != null) {
          $sql .= ' AND question_number='.$question;
      }
      return $this->find($sql);
  }

  function getAllByEvalMixevalId($MixevalId=null, $question=null){
      $sql = 'evaluation_mixeval_id='.$MixevalId;
      if ($question != null) {
          $sql .= ' AND question_number='.$question;
      }
      return $this->findAll($sql);
  }

  // gets Mixeval evaluation result for a specific assignment and evaluator
  function getResultsDetailByEvaluatee($grpEventId=null, $evaluatee=null) {
      $condition = 'EvaluationMixeval.grp_event_id=' . $grpEventId .
          ' AND EvaluationMixeval.evaluatee=' .$evaluatee;
      $fields = 'EvaluationMixevalDetail.*';

      return $this->findAll($condition, $fields, 'EvaluationMixeval.id, question_number ASC', null, null, 2);
  }
}

