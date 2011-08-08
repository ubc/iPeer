<?php
/* SVN FILE: $Id: survey_question.php 589 2011-06-21 22:38:13Z tonychiu $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 589 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/07/25 16:29:58 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class SurveyQuestion extends AppModel
{
  var $name = 'SurveyQuestion';
  
  /**
   * Returns all the question IDs of a specific survey and merges a array "count" in the result
   * @param type_INT $surveyId : survey's id
   */
  function getQuestionsID($surveyId=null) {
  	$data = $this->find('all', array('conditions'=> array('survey_id' => $surveyId),
                                     'fields' => array('number', 'question_id', 'id'),
                                     'order' => 'number'));
    $data['count'] = count($data);
    return $data;
  }

  /**
   * Reorders the survey's list of questions.
   * @param type_INT $survey_id : survey's id.
   * @param type_INT $question_id : id corresponding to the question that needs to be repositioned
   * @param type_STRING('UP', 'DOWN', 'TOP', 'DOWN') $position : specifies how the question will be repositioned
   */
  function moveQuestion($survey_id, $question_id, $position) {
    // Move to TOP case
    // Note, this method will not work for the BOTTOM case
    switch ($position) {
      case "TOP":
        // Call upon itself until function returns false (TOP of List)
        //while($this->moveQuestion($survey_id, $question_id, "UP"));
        // Call itself until function is bottom of list

        $data = $this->find('first', array('conditions' => array('question_id' => $question_id,
                                                                         'survey_id' => $survey_id)));
        $data['SurveyQuestion']['number'] = '0';
        $this->id = $data['SurveyQuestion']['id'];
    	$this->save($data);

        $this->reorderQuestions($survey_id);
      break;
      // Move UP case
      case "UP":
        // Get current question and the question before it
        $data = $this->find('first', array(
            'conditions' => array('question_id' => $question_id,
            'survey_id' => $survey_id)));
        
        // Check to make sure question isn't the very first question
        if ($data['SurveyQuestion']['number'] == 1) {
          return false;
        }
        $data2 = $this->find('first', array(
          'conditions' => array('number ' => ($data['SurveyQuestion']['number']-1),
              'survey_id' => $survey_id,
        )));

        // Sway numbers of the question and the previous question
        $save = array();
        if(empty($data2)) {
          $questions = $this->reorderQuestions($survey_id);
          foreach($questions as $k => $q) {
            if($q['SurveyQuestion']['question_id'] == $question_id) {
              if(0 == $k) return false; // first one
              $save[] = $questions[$k-1];
              $save[] = $questions[$k];
            }
          }
        } else {
          $save[] = $data2;
          $save[] = $data;
        }
        $save['0']['SurveyQuestion']['number']++;
        $save['1']['SurveyQuestion']['number']--;
        $this->saveAll($save, array('fieldList' => array('number')));

      break;
      // Move DOWN case
      case "DOWN":
        // Get current question and the question after it
        $data = $this->find('first', array(
          'conditions' => array('question_id' => $question_id, 'survey_id' => $survey_id)));
        $data2 = $this->find('first', array(
          'conditions' => array('number' => ($data['SurveyQuestion']['number'] + 1), 'survey_id' => $survey_id)));

        $save = array();
        if($data['SurveyQuestion']['number'] == 9999 || empty($data2)) {
          $questions = $this->reorderQuestions($survey_id);
          $lastQuestionNum = $this->getLastSurveyQuestionNum($survey_id);
          if($lastQuestionNum == $data['SurveyQuestion']['number']) {
          	return false;
          }
          foreach($questions as $k => $q) {
            if($q['SurveyQuestion']['number'] == $question_id) {
              if($k == count($questions) - 1) return false; // last one
              $save[] = $questions[$k];
              $save[] = $questions[$k+1];
            }
          }
        } else {
          $save[] = $data;
          $save[] = $data2;
        }

        // Sway numbers of the question and the next question
        $save['0']['SurveyQuestion']['number']++;
        $save['1']['SurveyQuestion']['number']--;

        $this->saveAll($save); 
      break;
      // Move to BOTTOM case
      case "BOTTOM":
        // Call itself until function is bottom of list
        //while( $this->moveQuestion($survey_id, $question_id, "DOWN"));

        // get question to move to bottom and change number to huge number and resave
        $data = $this->find('first', array(
          'conditions' => array('question_id' => $question_id,'survey_id' => $survey_id)));
        $data['SurveyQuestion']['number'] = '10000';
        $this->save($data);
        $this->reorderQuestions($survey_id);
      break;
      default:
      break;
    }
    return true;
  }

  /**
   * Used to copy questions to a new survey when a template is used
   * 
   * @param type_INT $survey_id : copy from this survey
   * @param type_INT $id : copy to this survey
   */
  function copyQuestions($survey_id, $id) {
    $questions = $this->find('all', array('conditions' => array('SurveyQuestion.survey_id' => $survey_id)));
    
    foreach($questions as $k => $q) {
      unset($q['SurveyQuestion']['id']);
      $q['SurveyQuestion']['survey_id'] = $id;
      $questions[$k] = $q;
    }
    $this->saveAll($questions);
  }

  /**
   * Assigned question number to each question in a survey based on its position in the list;
   * called after the survey question list is reordered
   * 
   * @param type_INT $survey_id : survey's id
   */
  function reorderQuestions($survey_id) {
    // get all questions order by the number
    $data = $this->find('all', array(
      'conditions' => array('survey_id' => $survey_id),
      'order' => 'number'
    ));
    $count = sizeof($data);
    // reset numbering for each question and resave
    for( $i=1; $i<=$count; $i++ ){
      $data[$i-1]['SurveyQuestion']['number'] = $i;
    }
    $this->saveAll($data, array('fieldList' => array('number')));
    return $data;
  }
  
  /**
   * Retrieves the last question in the survey question list 
   * 
   * @param type_INT $surveyId : survey's id
   */
  function getLastSurveyQuestionNum($surveyId) {
  	$tmp = $this->find('all', array('conditions' => array('survey_id' => $surveyId), 'fields' => array('max(number)')));
  	return $tmp[0][0]['max(number)'];
  }

  /*function deleteGroupSet($groupSetId=null) {
    $this->SurveyGroup = new SurveyGroup;
    $this->SurveyGroupMember = new SurveyGroupMember;
    $this->SurveyGroupSet = new SurveyGroupSet;
    //delete members
    //get member id's
    $memberIds = $this->SurveyGroupMember->getIdsByGroupSetId($groupSetId);
    foreach ($memberIds as $memberId) {
      $id = $memberId['SurveyGroupMember']['id'];
      $this->SurveyGroupMember->id = $id;
      $this->SurveyGroupMember->del();
      $this->SurveyGroupMember->id = null;
    }
    //delete groups
    $groupIds = $this->SurveyGroup->getIdsByGroupSetId($groupSetId);
    foreach ($groupIds as $groupId) {
      $id = $groupId['SurveyGroup']['id'];
      $this->SurveyGroup->id = $id;
      $this->SurveyGroup->del();
      $this->SurveyGroup->id = null;
    }
    //delete group set
    $this->SurveyGroupSet->id = $groupSetId;
    $this->SurveyGroupSet->del();
  }*/

}
?>