<?php
App::import('Model', 'SurveyQuestion');
class EvaluationSurveyHelperComponent extends Object
{
	var $components = array('Auth', 'Session', 'Evaluation');        

    function saveSurveyEvaluation($params=null)
    { 
    	$this->Response = new Response;
        $this->Question = new Question;
        $this->EvaluationSubmission = new EvaluationSubmission;

        $userId = $params['data']['Evaluation']['surveyee_id'];
        $eventId = $params['form']['event_id'];
        $surveyId = $params['form']['survey_id'];
        
        //get existing record if there is one
        $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId,$userId);
        if (empty($evaluationSubmission)) {//if there is no existing record, fill in all fields
            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $userId;
            $evaluationSubmission['EvaluationSubmission']['survey_id'] = $surveyId;
            $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
        } else { //if existing record, just update the time submitted
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
        }
        $surveyInput=array();
        $surveyInput['SurveyInput']['user_id'] = $userId;
        $surveyInput['SurveyInput']['survey_id'] = $surveyId;
        $successfullySaved = true;
        for($i=1; $i<=$params['form']['question_count']; $i++){
			$this->SurveyInput = new SurveyInput;
        	//Set survey and user id
        	$surveyInput[$i]['SurveyInput']['user_id'] = $userId;
        	$surveyInput[$i]['SurveyInput']['survey_id'] = $surveyId;
        	//Set question Id
        	$questionId = $params['form']['question_id'.$i];
        	$surveyInput[$i]['SurveyInput']['question_id'] = $questionId;
        	//Set answers
        	$answer = $params['form']['answer_'.$questionId];
        	$modAnswer =  $this->filterString($answer);
			$surveyInput[$i]['SurveyInput']['response_text']=$modAnswer;
			//Set reponse_id
			$response = $params['form']['answer_'.$questionId];
			$modResponse = $this->filterString($response);
			$responseId = $this->Response->getResponseId($questionId,$modResponse);			
			$surveyInput[$i]['SurveyInput']['response_id']=$responseId;
			//Save data
			if(!$this->SurveyInput->save($surveyInput[$i]['SurveyInput']))$successfullySaved=false;	
        }
        
        //Indicate that evaluation has been submitted
        if($successfullySaved)
        {	
			$this->EvaluationSubmission->save($evaluationSubmission);
			return true;
        }else return false;
        /*
        // if no submission exists, create one
        //$surveyInput['SurveyInput']['event_id'] = $params['form']['event_id'];
        // save evaluation submission
        //$surveyInput['SurveyInput']['date_submitted'] = date('Y-m-d H:i:s');

        //parse for question id and their response id/text
        //then save
        for ($i=1; $i < $params['form']['question_count']+1; $i++) {
            $questionId = $params['form']['question_id'.$i];
            if (isset($params['form']['answer_'.$i])) {
                if (is_array($params['form']['answer_'.$i])) {
                    if (!$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId,$userId,$questionId)) {
                        die('delete failed');
                    }
                    //parse answers for 'any choice' type
                    for ($j=0; $j < count($params['form']['answer_'.$i]); $j++) {
                        $surveyInput['SurveyInput']['user_id'] = $userId;
                        $surveyInput['SurveyInput']['survey_id'] = $surveyId;
                        $surveyInput['SurveyInput']['question_id'] = $questionId;

                        $answer = explode("_",$params['form']['answer_'.$i][$j]);
                        $surveyInput['SurveyInput']['response_id'] = $answer[1];
                        if (!$this->SurveyInput->save($surveyInput)) {
                            return false;
                        }
                        $this->SurveyInput->id = null;
                    }
                } else {
                    //get existing 'answer' record
                    $surveyInput = $this->SurveyInput->getSurveyInputBySurveyIdUserIdQuestionId($surveyId,$userId,$questionId);

                    //if none exists fill in fields
                    if (empty($surveyInput)) {
                        $surveyInput['SurveyInput']['user_id'] = $userId;
                        $surveyInput['SurveyInput']['survey_id'] = $surveyId;
                    }
                    $surveyInput['SurveyInput']['question_id'] = $questionId;

                    //check for MC
                    $type = $this->Question->getTypeById($questionId);
                    if ($type == 'M') {
                        $answer = explode("_",$params['form']['answer_'.$i]);
                        $surveyInput['SurveyInput']['response_id'] = $answer[1];
                    } else {
                        $surveyInput['SurveyInput']['response_text'] = $params['form']['answer_'.$i];
                    }
                    if (!$this->SurveyInput->save($surveyInput)) {
                        return false;
                    }
                    $this->SurveyInput->id = null;
                }
            } else {
                if (!$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId,$userId,$questionId)) {
                die('delete failed');
                } else {
                    if (!$this->SurveyInput->delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId,$userId,$questionId)) {
                        die('delete failed');
                    }
                    //parse answers for 'any choice' type
                    $surveyInput['SurveyInput']['user_id'] = $userId;
                    $surveyInput['SurveyInput']['survey_id'] = $surveyId;
                    $surveyInput['SurveyInput']['question_id'] = $questionId;
                    if (!$this->SurveyInput->save($surveyInput)) {
                        return false;
                    }
                    $this->SurveyInput->id = null;
                }
            }
        }
        if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
            echo "this->EvaluationSubmission->save() returned false";
            return false;
        }
        $this->EvaluationSubmission->id = null;
        return true;
       */ 
    }

	function formatStudentViewOfSurveyEvaluationResult($event=null)
	{
	  $this->EvaluationSimple = new EvaluationSimple;
	  $this->GroupsMembers = new GroupsMembers;
	  $gradeReleaseStatus = 0;
	  $aveScore = 0; $groupAve = 0;
	  $studentResult = array();

	  $results = $this->EvaluationSimple->getResultsByEvaluatee($event['group_event_id'], $this->Auth->user('id'));
		if ($results !=null) {
       //Get Grade Release: grade_release will be the same for all evaluatee records
       $gradeReleaseStatus = $results[0]['EvaluationSimple']['grade_release'];
       if ($gradeReleaseStatus) {
          //Grade is released; retrieve all grades

          //Get total mark each member received
  				$receivedTotalScore = $this->EvaluationSimple->getReceivedTotalScore($event['group_event_id'],
  				                                                                         $this->Auth->user('id'));
  				$totalScore = $receivedTotalScore[0]['received_total_score'];

  				$numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->find(count,'group_id='.$event['group_id']) :
  				                                           $this->GroupsMembers->find(count,'group_id='.$event['group_id']) - 1;
          $aveScore = $totalScore / $numMembers;
          $studentResult['numMembers'] = $numMembers;
          $studentResult['receivedNum'] = count($receivedTotalScore);

          $groupTotal = $this->EvaluationSimple->getGroupResultsByGroupEventId($event['group_event_id']);
          $groupTotalCount = $this->EvaluationSimple->getGroupResultsCountByGroupEventId($event['group_event_id']);
          $groupAve = $groupTotal[0]['received_total_score'] / $groupTotalCount[0]['received_total_count'];
       }

        $studentResult['aveScore'] = $aveScore;
        $studentResult['groupAve'] = $groupAve;

       //Get Comment Release: release_status will be the same for all evaluatee
       $commentReleaseStatus = $results[0]['EvaluationSimple']['release_status'];
       if ($commentReleaseStatus) {
         //Comment is released; retrieve all comments
          $comments = $this->EvaluationSimple->getAllComments($event['group_event_id'], $this->Auth->user('id'));
          if (shuffle($comments)) {
            $studentResult['comments'] = $comments;
          }
      		$studentResult['commentReleaseStatus'] = $commentReleaseStatus;
       }

		}
		$studentResult['gradeReleaseStatus'] = $gradeReleaseStatus;

		return $studentResult;
	}

	function formatSurveyEvaluationResult($event=null,$studentId=null)
	{
	  $this->Survey = new Survey;
	  $this->SurveyQuestion = new SurveyQuestion;
	  $this->Question = new Question;
	  $this->Response = new Response;
	  $this->SurveyInput = new SurveyInput;

	  $result = array();

		$survey_id = $this->Survey->getSurveyIdByCourseIdTitle($this->Session->read('ipeerSession.courseId'), $event['Event']['title']);
  	//$this->set('survey_id', $survey_id);
  	$result['survey_id'] = $survey_id;

		// Get all required data from each table for every question
		$tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
		$tmp = $this->Question->fillQuestion($tmp);
		$tmp = $this->Response->fillResponse($tmp);
		$questions = null;

		// Sort the resultant array by question number
		$count = 1;
		for( $i=0; $i<=$tmp['count']; $i++ ){
			for( $j=0; $j<$tmp['count']; $j++ ){
				if( $i == $tmp[$j]['Question']['number'] ){
					$questions[$count]['Question'] = $tmp[$j]['Question'];
					$count++;
				}
			}
		}
		$answers = $this->SurveyInput->getAllSurveyInputBySurveyIdUserId($survey_id,$studentId);
		//$this->set('answers', $answers);
		$result['answers'] = $answers;
		//$this->set('questions', $result);
		$result['questions'] = $questions;
    //$this->set('event', $event);
    $result['event'] = $event;

    return $result;
	}

	function formatSurveyGroupEvaluationResult($surveyId=null, $surveyGroupId=null) {
	  $this->Survey = new Survey;
	  $this->SurveyQuestion = new SurveyQuestion;
	  $this->Question = new Question;
	  $this->Response = new Response;
	  $this->SurveyInput = new SurveyInput;
	  $this->User = new User;

	  $result = array();

		// Get all required data from each table for every question
		$tmp = $this->SurveyQuestion->getQuestionsID($surveyId);
		$tmp = $this->Question->fillQuestion($tmp);
		$tmp = $this->Response->fillResponse($tmp);
		$questions = null;

		// Sort the resultant array by question number
		$count = 1;
		for( $i=0; $i<=$tmp['count']; $i++ ){
			for( $j=0; $j<$tmp['count']; $j++ ){
				if( $i == $tmp[$j]['Question']['number'] ){
					$questions[$count]['Question'] = $tmp[$j]['Question'];
					$count++;
				}
			}
		}

		for ($i=1; $i < count($questions)+1; $i++) {
		  $questionType = $questions[$i]['Question']['type'];
		  $questionTypeAllowed = array('C','M');
  	  $questionId = $questions[$i]['Question']['id'];

  	  //count the choice responses
		  if (in_array($questionType,$questionTypeAllowed)) {
		    $totalResponsePerQuestion = 0;
  		  for ($j=0; $j < count($questions[$i]['Question']['Responses']); $j++) {
  		    $responseId = $questions[$i]['Question']['Responses']['response_'.$j]['id'];
  		    $answerCount = $this->SurveyInput->findCountInSurveyGroup($surveyId,$questionId,$responseId,$surveyGroupId);
  		    //echo $surveyId.';'.$questionId.' '.$responseId.' '.$surveyGroupId;
  		    //print_r($answerCount); die;
  		    $questions[$i]['Question']['Responses']['response_'.$j]['count'] = $answerCount;
  		    $totalResponsePerQuestion += $answerCount;
  		  }
  		  $questions[$i]['Question']['total_response'] = $totalResponsePerQuestion;
		  } else {
		    $responses = $this->SurveyInput->findResponseInSurveyGroup($surveyId,$questionId,$surveyGroupId);
		    $questions[$i]['Question']['Responses'] = array();

		    //sort results by last name
		    $tmpUserResponse = array();
		    for ($j=0; $j < count($responses); $j++) {
		      $responseText = $responses[$j]['SurveyInput']['response_text'];
		      $userId = $responses[$j]['SurveyInput']['user_id'];
		      $userName = $this->User->findUserByid($userId);
		      $userName = $userName['User']['last_name'].", ".$userName['User']['first_name'];
		      $tmpUserResponse[$userName]['response_text'] = $responseText;
		    }
		    ksort($tmpUserResponse);

		    $k=1;
		    foreach ($tmpUserResponse as $username => $response) {
		      $questions[$i]['Question']['Responses']['response_'.$k]['response_text'] = $response['response_text'];
		      $questions[$i]['Question']['Responses']['response_'.$k]['user_name'] = $username;
		      $k++;
		    }
		  }
		}

    return $questions;
	}

	function formatSurveyEvaluationSummary($surveyId=null)
	{
	  $this->Survey = new Survey;
	  $this->SurveyQuestion = new SurveyQuestion;
	  $this->Question = new Question;
	  $this->Response = new Response;
	  $this->SurveyInput = new SurveyInput;
	  $this->User = new User;

	  $result = array();
    $survey_id = $surveyId;

		// Get all required data from each table for every question
		$tmp = $this->SurveyQuestion->getQuestionsID($survey_id);
		$tmp = $this->Question->fillQuestion($tmp);
		$tmp = $this->Response->fillResponse($tmp);
		$questions = null;

		// Sort the resultant array by question number
		$count = 1;
		for( $i=0; $i<=$tmp['count']; $i++ ){
			for( $j=0; $j<$tmp['count']; $j++ ){
				if( $i == $tmp[$j]['Question']['number'] ){
					$questions[$count]['Question'] = $tmp[$j]['Question'];
					$count++;
				}
			}
		}

		for ($i=1; $i < count($questions)+1; $i++) {
		  $questionType = $questions[$i]['Question']['type'];
		  $questionTypeAllowed = array('C','M');
  	  $questionId = $questions[$i]['Question']['id'];

  	  //count the choice responses
		  if (in_array($questionType,$questionTypeAllowed)) {
		    $totalResponsePerQuestion = 0;
  		  for ($j=0; $j < count($questions[$i]['Question']['Responses']); $j++) {
  		    $responseId = $questions[$i]['Question']['Responses']['response_'.$j]['id'];
  		    $answerCount = $this->SurveyInput->find('count', array('conditions' => array('survey_id' => $surveyId,
                                                                                       'question_id' => $questionId,
                                                                                       'response_id' => $responseId)));
  		    $questions[$i]['Question']['Responses']['response_'.$j]['count'] = $answerCount;
  		    $totalResponsePerQuestion += $answerCount;
  		  }
  		  $questions[$i]['Question']['total_response'] = $totalResponsePerQuestion;
		  } else {
		    $responses = $this->SurveyInput->find('all', 'survey_id='.$surveyId.' AND question_id='.$questionId,'user_id,response_text');
		    $questions[$i]['Question']['Responses'] = array();

		    //sort results by last name
		    $tmpUserResponse = array();
		    for ($j=0; $j < count($responses); $j++) {
		      $responseText = $responses[$j]['SurveyInput']['response_text'];
		      $userId = $responses[$j]['SurveyInput']['user_id'];
		      $userName = $this->User->findUserByid($userId);
		      $userName = $userName['User']['last_name'].", ".$userName['User']['first_name'];
		      $tmpUserResponse[$userName]['response_text'] = $responseText;
		    }
		    ksort($tmpUserResponse);

		    $k=1;
		    foreach ($tmpUserResponse as $username => $response) {
		      $questions[$i]['Question']['Responses']['response_'.$k]['response_text'] = $response['response_text'];
		      $questions[$i]['Question']['Responses']['response_'.$k]['user_name'] = $username;
		      $k++;
		    }
		  }
		}

    return $questions;
	}
	
//Helper function
	
	//Filters a input string by removing unwanted chars of {"_", "0", "1", "2", "3",...}
	function filterString($string=null){
		$unwantedChar = array("_","0","1","2","3","4","5","6","7","8","9");
		return str_replace($unwantedChar, "", $string);
	}

}?>
