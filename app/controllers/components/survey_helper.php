<?php
/* SVN FILE: $Id$ */
/*
 *
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
App::import('Model', 'SurveyQuestion');

class SurveyHelperComponent extends Object {

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

  function moveQuestion($survey_id, $question_id, $position) {
    $this->SurveyQuestion = new SurveyQuestion;
    // Move to TOP case
	  // Note, this method will not work for the BOTTOM case
	  switch ($position) {
	    case "TOP":
    		// Call upon itself until function returns false (TOP of List)
    		//while($this->moveQuestion($survey_id, $question_id, "UP"));
    		// Call itself until function is bottom of list

    		$data = $this->SurveyQuestion->find('first', array('conditions' => array('question_id' => $question_id,
                                                                                 'survey_id' => $survey_id)));
    		$data['SurveyQuestion']['number'] = '0';
        $this->SurveyQuestin->id = $data['SurveyQuestion']['id'];
    		$this->SurveyQuestion->save($data);

        $this->reorderQuestions($survey_id);
    		break;
    	// Move UP case
	    case "UP":
    		// Get current question and the question before it
    		$data = $this->SurveyQuestion->find('first', array('conditions' => array('question_id' => $question_id,
                                                                                 'survey_id' => $survey_id)));

    		// Check to make sure question isn't the very first question
    		if ($data['SurveyQuestion']['number'] == 1) {
          return false;
        }
        
    		$data2 = $this->SurveyQuestion->find('first', array('conditions' => array('number ' => ($data['SurveyQuestion']['number']-1),
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

        $save[0]['SurveyQuestion']['number']++;
        $save[1]['SurveyQuestion']['number']--;
        $this->SurveyQuestion->saveAll($save, array('fieldList' => array('number')));

    	  break;
    	// Move DOWN case
      case "DOWN":
    		// Get current question and the question after it
    		$data = $this->SurveyQuestion->find('first', array('conditions' => array('question_id' => $question_id,
                                                                                 'survey_id' => $survey_id)));
    		$data2 = $this->SurveyQuestion->find('first', array('conditions' => array('number' => ($data['SurveyQuestion']['number'] + 1),
                                                                                  'survey_id' => $survey_id)));

        $save = array();
    		if( $data['SurveyQuestion']['number'] == 9999 || empty($data2) ){
          $questions = $this->reorderQuestions($survey_id);
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
        $save[0]['SurveyQuestion']['number']++;
        $save[1]['SurveyQuestion']['number']--;

        $this->SurveyQuestion->saveAll($save);
    	  break;
    	// Move to BOTTOM case
      case "BOTTOM":
    		// Call itself until function is bottom of list
    		//while( $this->moveQuestion($survey_id, $question_id, "DOWN"));

    		// get question to move to bottom and change number to huge number and resave
    		$data = $this->SurveyQuestion->find('first', array('conditions' => array('question_id' => $question_id,
                                                                                 'survey_id' => $survey_id)));
    		$data['SurveyQuestion']['number'] = '10000';
    		$this->SurveyQuestion->save($data);
        $this->reorderQuestions($survey_id);
    	  break;
      default:
        break;
  	}
    return true;
  }

  function reorderQuestions($survey_id) {
    // get all questions order by the number
    $data = $this->SurveyQuestion->find('all', array('conditions' => array('survey_id' => $survey_id),
                                                     'order' => 'number'));
    $count = sizeof($data);
    // reset numbering for each question and resave
    for( $i=1; $i<=$count; $i++ ){
      $data[$i-1]['SurveyQuestion']['number'] = $i;
    }
    $this->SurveyQuestion->saveAll($data, array('fieldList' => array('number')));
    return $data;
  }

  // used to copy questions to a new survey when a template is used
  function copyQuestions($survey_id, $id) {
    $this->SurveyQuestion = new SurveyQuestion;
  	$questions = $this->SurveyQuestion->find('all', array('conditions' => array('survey_id' => $survey_id)));

    foreach($questions as $k => $q) {
      unset($q['SurveyQuestion']['id']);
      $q['SurveyQuestion']['survey_id'] = $id;
      $questions[$k] = $q;
    }
    $this->SurveyQuestion->saveAll($questions);
  }
}
