<?php
/* SVN FILE: $Id: survey_helper.php,v 1.4 2006/08/01 21:59:40 davychiu Exp $ */
/*
 *
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class SurveyHelperComponent extends Object {

  function deleteGroupSet($groupSetId=null) {
    $this->SurveyGroup = new SurveyGroup;
    $this->SurveyGroupMember = new SurveyGroupMember;
    $this->SurveyGroupSet = new SurveyGroupSet;
    //delete members
    //get member id's
    $memberIds = $this->SurveyGroupMember->getIdsByGroupSetId($groupSetId);
    foreach ($memberIds as $memberId) {
      $id = $memberId['SurveyGroupMember']['id'];
      $this->SurveyGroupMember->setId($id);
      $this->SurveyGroupMember->del();
      $this->SurveyGroupMember->id = null;
    }
    //delete groups
    $groupIds = $this->SurveyGroup->getIdsByGroupSetId($groupSetId);
    foreach ($groupIds as $groupId) {
      $id = $groupId['SurveyGroup']['id'];
      $this->SurveyGroup->setId($id);
      $this->SurveyGroup->del();
      $this->SurveyGroup->id = null;
    }
    //delete group set
    $this->SurveyGroupSet->setId($groupSetId);
    $this->SurveyGroupSet->del();
  }

  function moveQuestion($survey_id, $question_id, $move) {
    $this->SurveyQuestion = new SurveyQuestion;
    // Move to TOP case
	  // Note, this method will not work for the BOTTOM case
	  switch ($move) {
	    case "TOP":
    		// Call upon itself until function returns false (TOP of List)
    		//while($this->moveQuestion($survey_id, $question_id, "UP"));
    		// Call itself until function is bottom of list

    		$data = $this->SurveyQuestion->find($conditions='question_id='.$question_id.' AND survey_id='.$survey_id);
    		$data['SurveyQuestion']['number'] = '0';
        $this->SurveyQuestin->id=$data['SurveyQuestion']['id'];
    		$this->SurveyQuestion->save($data);

    		// get all questions order by the number
    		$data = $this->SurveyQuestion->findAll($conditions='survey_id='.$survey_id.' ORDER BY number');
    		$count = sizeof($data);
    		// reset numbering for each question and resave
    		for( $i=0; $i<$count; $i++ ){
    			$data[$i]['SurveyQuestion']['number'] = ($i+1);
    			$this->SurveyQuestion->save($data[$i]);
    		}
    		break;
    	// Move UP case
	    case "UP":
    		// Get current question and the question before it
    		$data = $this->SurveyQuestion->find($conditions='question_id='.$question_id.' AND survey_id='.$survey_id);
    		$data2 = $this->SurveyQuestion->find($conditions='number='.($data['SurveyQuestion']['number'] - 1).' AND survey_id='.$survey_id);

    		// Check to make sure question isn't the very first question
    		if ($data['SurveyQuestion']['number'] != 1) {

    			// Sway numbers of the question and the previous question
    			--$data['SurveyQuestion']['number'];
    			++$data2['SurveyQuestion']['number'];

    			$this->SurveyQuestion->save($data);
    			$this->SurveyQuestion->save($data2);

    			return true;
    		} else
    			return false;
    	  break;
    	// Move DOWN case
      case "DOWN":
    		// Get current question and the question after it
    		$data = $this->SurveyQuestion->find($conditions='question_id='.$question_id.' AND survey_id='.$survey_id);
    		$data2 = $this->SurveyQuestion->find($conditions='number='.($data['SurveyQuestion']['number'] + 1).' AND survey_id='.$survey_id);

    		// Check to make sure question to move down isn't last question
    		if( !empty($data2) ){

    			// Sway numbers of the question and the next question
    			++$data['SurveyQuestion']['number'];
    			--$data2['SurveyQuestion']['number'];

    			$this->SurveyQuestion->save($data);
    			$this->SurveyQuestion->save($data2);

    			return true;
    		}
    		else
    			return false;
    	  break;
    	// Move to BOTTOM case
      case "BOTTOM":
    		// Call itself until function is bottom of list
    		//while( $this->moveQuestion($survey_id, $question_id, "DOWN"));

    		// get question to move to bottom and change number to huge number and resave
    		$data = $this->SurveyQuestion->find($conditions='question_id='.$question_id.' AND survey_id='.$survey_id);
    		$data['SurveyQuestion']['number'] = '99999';
    		$this->SurveyQuestion->save($data);

    		// get all questions order by the number
    		$data = $this->SurveyQuestion->findAll($conditions='survey_id='.$survey_id.' ORDER BY number');
    		$count = sizeof($data);

    		// reset numbering for each question and resave
    		for( $i=0; $i<$count; $i++ ){
    			$data[$i]['SurveyQuestion']['number'] = ($i+1);
    			$this->SurveyQuestion->save($data[$i]);
    		}
    	  break;
      default:
        break;
  	}
  }
}