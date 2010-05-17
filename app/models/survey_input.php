<?php
class SurveyInput extends AppModel
{
  var $name = 'SurveyInput';

  function getSurveyInputBySurveyIdUserIdQuestionId($surveyId=null, $userId=null, $questionId=null) {
    return $this->find('survey_id='.$surveyId.' AND user_id='.$userId.' AND question_id='.$questionId);
  }

  function getAllSurveyInputBySurveyIdUserId($surveyId=null, $userId=null) {
    return $this->findAll('survey_id='.$surveyId.' AND user_id='.$userId,null,'question_id');
  }

  function getAllSurveyInputBySurveyIdUserIdQuestionId($surveyId=null, $userId=null, $questionId=null) {
    return $this->findAll('survey_id='.$surveyId.' AND user_id='.$userId.' AND question_id='.$questionId);
  }

  function delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId=null, $userId=null, $questionId=null) {
    $result = true;
    $allId = $this->findAll('survey_id='.$surveyId.' AND user_id='.$userId.' AND question_id='.$questionId,'id');
    foreach ($allId as $id) {
      if (!$this->del($id['SurveyInput']['id'])) return false;
      $this->id = null;
    }
    return $result;
  }

	function beforeSave() {
	  //check for duplicate submission
	  return true;
	}

	function checkDuplicate() {
	  //check duplicate
	}

	function findCountInSurveyGroup($surveyId=null,$questionId=null,$responseId=null,$surveyGroupId=null) {
	  $tmp = $this->findBySql('SELECT user_id FROM survey_inputs WHERE survey_id='.$surveyId.' AND question_id='.$questionId.' AND response_id='.$responseId.' AND user_id=ANY(SELECT user_id FROM survey_group_members WHERE group_id='.$surveyGroupId.')');
	  return count($tmp);
	}

	function findResponseInSurveyGroup($surveyId=null,$questionId=null,$surveyGroupId=null) {
	  $tmp = $this->findBySql('SELECT user_id,response_text FROM survey_inputs WHERE survey_id='.$surveyId.' AND question_id='.$questionId.' AND user_id=ANY(SELECT user_id FROM survey_group_members WHERE group_id='.$surveyGroupId.')');
	  return $tmp;
	}
}

?>