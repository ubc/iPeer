<?php
class SurveyInput extends AppModel
{
  var $name = 'SurveyInput';

  // Function obsolete.
  /*function($surveyId, $userId, $questionId) {
    return $this->find('first', array('conditions' => array('survey_id' => $surveyId,
                                                            'user_id' => $userId,
                                                            'question_id' => $questionId)));
  }*/

  function getAllSurveyInputBySurveyIdUserId($surveyId, $userId) {
    return $this->find('all', array('conditions' => array('survey_id' => $surveyId,
                                                          'user_id' => $userId),
                                    'order' => 'question_id'));
  }

  function getAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId) {
    return $this->find('all', array('conditions' => array('survey_id' => $surveyId,
                                                          'user_id' => $userId,
                                                          'question_id' => $questionId)));
  }

  function delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId) {
    return $this->deleteAll(array('survey_id' => $surveyId,
                                  'user_id' => $userId,
                                  'question_id' => $questionId));
  }

	function beforeSave() {
	  //check for duplicate submission
	  return true;
	}

	function checkDuplicate() {
	  //check duplicate
	}

	function findCountInSurveyGroup($surveyId=null,$questionId=null,$responseId=null,$surveyGroupId=null) {
	  $tmp = $this->query('SELECT user_id FROM survey_inputs WHERE survey_id='.$surveyId.' AND question_id='.$questionId.' AND response_id='.$responseId.' AND user_id=ANY(SELECT user_id FROM survey_group_members WHERE group_id='.$surveyGroupId.')');
	  return count($tmp);
	}

	function findResponseInSurveyGroup($surveyId=null,$questionId=null,$surveyGroupId=null) {
	  $tmp = $this->query('SELECT user_id,response_text FROM survey_inputs WHERE survey_id='.$surveyId.' AND question_id='.$questionId.' AND user_id=ANY(SELECT user_id FROM survey_group_members WHERE group_id='.$surveyGroupId.')');
	  return $tmp;
	}
}
?>