<?php
class ExportAllHelperComponent extends Object
{
	var $components = array('rdAuth');
  var $globUsersArr = array();
  var $globEventId;
  
	function createCSV($params) {
	  $this->Course = new Course;
	  $this->UserCourse = new UserCourse;
	  $this->Event = new Event;
	  $this->GroupEvent = new GroupEvent;

    $courseId = $this->rdAuth->courseId;
    $csvContent = '';

    //*******header
    $header = array();
    //get coursename
    $course = $this->Course->find('id='.$courseId,'course');
    $header['course_name'] = empty($params['form']['include_course']) ? '':$course['Course']['course'];
    //get date of export
    $header['export_date'] = empty($params['form']['include_date']) ? '':date('F t Y g:ia');
    //get instructor name
    $header['instructors'] = empty($params['form']['include_instructor']) ? '':$this->UserCourse->getInstructors($courseId);
    //print_r($header['instructors']); die;

    $csvContent .= $this->createHeader($header);

    //*******subheader
    //parse through each event... ugly...
    $events = $this->Event->getCourseEvalEvent($courseId);

    foreach ($events as $event) {
      $subHeader = array();
	    //get evaluation event names
	    $subHeader['event_name'] = empty($params['form']['include_eval_event_names']) ? '':$event['Event']['title'];
	    //get evaluation types
	    $subHeader['event_type_id'] = empty($params['form']['include_eval_event_type']) ? '':$event['Event']['event_template_type_id'];

	    $csvContent .= $this->createSubHeader($subHeader);

	    $eventId = $event['Event']['id'];
	    $eventTemplateId = $event['Event']['template_id'];
	    $eventTypeId = $event['Event']['event_template_type_id'];
	    $groupEvents = $this->GroupEvent->findAll('event_id='.$eventId);

	    //too much garbage... outsourced to createBody
	    $csvContent .= $this->createBody($groupEvents,$params,$eventTemplateId,$eventTypeId);

    }
    return $csvContent;
	}
  //create the header part of the csv file.
	function createHeader($params) {
	  if (!empty($params['course_name']) || !empty($params['export_date']) || !empty($params['instructors'])) {
	  	$header = '************************************************************************'."\n";
	  	$header .= !empty($params['course_name']) ? $params['course_name']."\n":'';
	  	$header .= !empty($params['export_date']) ? $params['export_date']."\n":'';
	  	foreach ($params['instructors'] as $instructor) {
	  	  $instructor = $instructor['User'];
	  	  $header .= $instructor['first_name'].' '.$instructor['last_name']."\n";
	  	}
	  	$header .= '************************************************************************'."\n";
	  	return  $header;
	  } else
	    return '';
	}
	//
	function createSubHeader($params) {
	  $evalTypes = array(1=>'Simple Evaluation',2=>'Rubric',4=>'Mixed Evaluation');
	  if (!empty($params['event_name']) || !empty($params['event_type_id'])) {
	    $subHeader = "\n";
	    $subHeader .= !empty($params['event_name']) ? 'Event Name: '.$params['event_name']."\n":'';
	    $subHeader .= !empty($params['event_type_id']) ? 'Event Type: '.$evalTypes[$params['event_type_id']]."\n":'';
	    $subHeader .= "\n";
	    return $subHeader;
	  } else
	    return '';
	}
	//
	function createBody ($groupEvents,$params,$eventTemplateId,$eventTypeId) {
    global $globEventId;
	  $this->Group = new Group;
	  $this->GroupsMembers = new GroupsMembers;
	  $this->User = new User;
	  $this->SimpleEvaluation = new SimpleEvaluation;
	  $this->Rubric = new Rubric;
	  $this->Mixeval = new Mixeval;
	  $this->RubricsCriteria = new RubricsCriteria;
	  $this->MixevalsQuestion = new MixevalsQuestion;
	  $this->EvaluationSimple = new EvaluationSimple;
	  $this->EvaluationRubric = new EvaluationRubric;
	  $this->EvaluationMixeval = new EvaluationMixeval;
    $globEventId = $groupEvents[0]['GroupEvent']['event_id'];

	  //bigass IF
    $data = array();
  	$legends = array();
    $i=0;
    foreach ($groupEvents as $groupEvent) {
	    //*******beef
	    $groupId = $groupEvent['GroupEvent']['group_id'];
	    $groupEventId = $groupEvent['GroupEvent']['id'];
	    $group = $this->Group->find('id='.$groupId);
	    //get group names
	    $data[$i]['group_name'] = $group['Group']['group_name'];
	    //get group stati
	    $data[$i]['group_status'] = $groupEvent['GroupEvent']['marked'];

	    $groupMembers = $this->GroupsMembers->getMembers($groupId);
	    unset($groupMembers['member_count']);
	    $j=0;
	    $data[$i]['students'] = array();

      global $globUsersArr;
      foreach($groupMembers as $groupMember) {
        $userId = $groupMember['GroupsMembers']['user_id'];
        $student = $this->User->findUserByid($userId);
        $globUsersArr[$student['User']['student_no']] = $userId;
      }
      if (!empty($globUsersArr))
        $submittedArr = $this->buildSubmittedArr();
      else
        $submittedArr = array();
      $count = 0;
      foreach($groupMembers as $groupMember) {
        if(in_array($groupMember['GroupsMembers']['user_id'], $submittedArr))
          $count++;
      }

	    foreach ($groupMembers as $groupMember) {
  	    //get student info: first_name, last_name, id, email
  	    $userId = $groupMember['GroupsMembers']['user_id'];
  	    $student = $this->User->findUserByid($userId);
  	    $data[$i]['students'][$j]['student_id'] = $userId;
  	    $data[$i]['students'][$j]['first_name'] = $student['User']['first_name'];
  	    $data[$i]['students'][$j]['last_name'] = $student['User']['last_name'];
  	    $data[$i]['students'][$j]['email'] = $student['User']['email'];

  	    switch ($eventTypeId) {
  	    	case 1://simple
  	    	  $comments = $this->EvaluationSimple->getAllComments($groupEventId,$userId);
  	    	  $data[$i]['students'][$j]['comments'] = '';
  	    	  foreach ($comments as $comment) {
  	    	    $data[$i]['students'][$j]['comments'] .= $comment['EvaluationSimple']['eval_comment'].'; ';
  	    	  }
  	    	  $data[$i]['students'][$j]['score'] = '';
  	    	  $score_tmp = $this->EvaluationSimple->getReceivedTotalScore($groupEventId,$userId);
            if (in_array($userId, $submittedArr)) {
  	    	    $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':($score_tmp[0]['received_total_score']/((count($groupMembers)-1)-$count+1));
            }
            else
              $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':($score_tmp[0]['received_total_score']/((count($groupMembers)-1)-$count));
            
            $prefills = 0;
            
  	    		break;
  	    	case 2://rubric
  	    	  //get the legend
  	    	  
  	    	  if (empty($legend)) {
    	    	  $legend_tmp = $this->RubricsCriteria->findAll('rubric_id='.$eventTemplateId, 'criteria');
    	    	  foreach ($legend_tmp as $legend)
    	    	    array_push($legends, $legend['RubricsCriteria']['criteria']);
  	    	  }
  	    	  $data[$i]['students'][$j]['sub_score'] = '';
  	    	  $subScore = $this->EvaluationRubric->getCriteriaResults($groupEventId,$userId);
  	    	  foreach ($subScore as $score)   	    	  
  	    	    $data[$i]['students'][$j]['sub_score'] .= $score[0]['score'].',';
  	    	    
  	    	  // get criteria questions
  	    	  $criteriaQuestions = $this->RubricsCriteria->findAll($conditions = 'rubric_id='.$eventTemplateId, $fields = '*');
  	    	  
  	    	  $data[$i]['students'][$j]['criteria_comments'] = '';
  	    	  
  	    	  // for each question, create a list of the associated comments for this student.
  	    	  foreach ($criteriaQuestions as $criteriaQuestion) {
  	    	  	
  	    	  	$criteriaNumber = $criteriaQuestion['RubricsCriteria']['criteria_num'];
  	    	    $criteriaComments = $this->EvaluationRubric->getCriteriaCommentsByEvaluateeCriteriaNumber($groupEventId,$userId,$criteriaNumber);

  	    	    foreach ($criteriaComments as $criteriaComment) {
  	    	      $data[$i]['students'][$j]['criteria_comments'] .= $criteriaComment['EvaluationRubricDetail']['criteria_comment'].'; ';  	    	  
  	    	    }
  	    	    $data[$i]['students'][$j]['criteria_comments'] .= ',';
  	    	  }
  	    	  	    	  
  	    	  $score_tmp = $this->EvaluationRubric->getReceivedTotalScore($groupEventId,$userId);
  	    	  $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':$score_tmp[0]['received_total_score'];
  	    	  
  	    	  //get general comments
  	    	  $data[$i]['students'][$j]['comments'] = '';
  	    	  $comments = $this->EvaluationRubric->getAllComments($groupEventId, $userId);
  	    	  foreach ($comments as $comment) {
  	    	    $data[$i]['students'][$j]['comments'] .= $comment['EvaluationRubric']['general_comment'].'; ';
  	    	  }
  	    	  
  	    	  $prefills = 0;
  	    	  
  	    	  break;
  	    	  
  	    	case 4://mixeval
  	    	
  	    	  //get the legend
  	    	  
  	    	  if (empty($legend)) {
  	    	  	$legend_tmp = $this->MixevalsQuestion->findAll('mixeval_id='.$eventTemplateId, 'title');
    	    	  foreach ($legend_tmp as $legend)
    	    	    array_push($legends, $legend['MixevalsQuestion']['title']);    	    	    
  	    	  }
  	    	  
  	    	  // find out how many likert questions there are
  	    	  $questionType = "S";
  	    	  $questionNumbers = $this->MixevalsQuestion->findAll("mixeval_id='".$eventTemplateId. "' AND question_type='".$questionType."'", 'question_num');
  	    	  $likert = count($questionNumbers);
  	    	  // get the scores for the likert questions
  	    	  $data[$i]['students'][$j]['sub_score'] = '';
  	    	  $subScore = $this->EvaluationMixeval->getQuestionResults($groupEventId,$userId);
  	    	  
  	    	  // grab scores from likert questions only
  	    	  $l = 0;
  	    	  foreach ($subScore as $score)  {
  	    	    if ( $l < $likert ) $data[$i]['students'][$j]['sub_score'] .= $score[0]['score'].',';
  	    	    $l++;
  	    	  }
  	    	    
 	    	  $score_tmp = $this->EvaluationMixeval->getReceivedTotalScore($groupEventId,$userId);
  	    	  $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':$score_tmp[0]['received_total_score'];
  	    	  
  	    	  // get a list of the prefill text question numbers
  	    	  $questionType = "T";
  	    	  $questionNumbers = $this->MixevalsQuestion->findAll("mixeval_id='".$eventTemplateId. "' AND question_type='".$questionType."'", 'question_num');
  	    	  
  	    	  // Determine how many prefill questions there are
  	    	  $prefills = count($questionNumbers);
  	    	  
  	    	  // for each of the prefill text questions, get the text responses.
  	    	  $data[$i]['students'][$j]['criteria_comments'] = '';
  	    	  
 	    	  // for each prefill text question, create a list of the associated responses for this student.
  	    	  foreach ($questionNumbers as $questionNumber) {
  	    	  	
 	    	      $questionNum = $questionNumber['MixevalsQuestion']['question_num'];
   	    
  	    	    if( $questionComments = $this->EvaluationMixeval->getQuestionCommentsByQuestionNumber($groupEventId,$userId,$questionNumber['MixevalsQuestion']['question_num']) ) {
  	    	    
  	    	      foreach ($questionComments as $questionComment) {
 	    	          $data[$i]['students'][$j]['criteria_comments'] .= $questionComment['EvaluationMixevalDetail']['comment'].'; ';  	    	  
  	    	      }
  	    	      
  	    	    }

  	    	    $data[$i]['students'][$j]['criteria_comments'] .= ',';
  	    	  }
  	    	  
  	    	  // Get the total score
  	    	  $score_tmp = $this->EvaluationMixeval->getReceivedTotalScore($groupEventId,$userId);
  	    	  $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':$score_tmp[0]['received_total_score'];
  	    	  
  	    	  // just a kludge, mixeval doesn't have general comments
  	    	  $data[$i]['students'][$j]['comments'] = '';
  	    	  
 	    	  break;   	  
  	    	  
  	    	  
  	    	default:
  	    		break;
  	    }
	      $j++;
	    }
	    //calculate final mark
	    $i++;
    }
    return $this->formatBody($data,$params,$legends,$eventTypeId,$groupEvents[0]['GroupEvent']['event_id'],$prefills);
	}

	function formatBody($data,$params,$legends=null,$eventTypeId,$eventId,$prefills) {
	  $content = '';

	  $fields = array('group_status','group_names','student_first','student_last','student_id','student_id','criteria_marks','general_comments','criteria_comments');
    $hasContent=false;
    for ($i=0;$i<count($fields);$i++) {
      if (!empty($params['form']['include_'.$fields[$i]])) {
        $hasContent=true;
        break;
      }
    }
      
	  //legend
	  if (isset($legends)&&!empty($params['form']['include_criteria_legend'])) {
	    $k=1;
	    foreach ($legends as $item) {
	      $content .= $k++.",".$item."\n";
	    }
	    $content .= "\n";
	  }

	  //group header
    $content .= empty($params['form']['include_group_status']) ? '':'Submitted,';
    $content .= empty($params['form']['include_group_names']) ? '':'Group Name,';
    $content .= empty($params['form']['include_student_first']) ? '':'First Name,';
    $content .= empty($params['form']['include_student_last']) ? '':'Last Name,';
    $content .= empty($params['form']['include_student_id']) ? '':'Student Number,';
    $content .= empty($params['form']['include_student_email']) ? '':'Email,';
    $content .= empty($params['form']['include_criteria_marks']) ? '':'Final Mark,';
    
    //question count... print 1..2..3..
    $question_count = !empty($legends)&&!empty($params['form']['include_criteria_legend']) ? count($legends):0;
    for ($i=1; $i <= $question_count; $i++) {
      $content .= $i.',';
    }
        
    // for rubrics, likerts/comments are a 1:1 ratio
    if ($eventTypeId == '2') {
      //question comments count... print 1..2..3..
      $question_count = !empty($legends)&&!empty($params['form']['include_criteria_legend']) ? count($legends):0;
      for ($i=1; $i <= $question_count; $i++) {
        $content .= $i.' comments,';
      }
    }
    
    if ( $eventTypeId != '4') {
      $content .= !isset($params['form']['include_general_comments']) ? '':'Comments';
    }
      
    if ($hasContent) $content .= "\n\n";

	  foreach ($data as $group) {
	    foreach ($group['students'] as $student) {
	      if (!empty($params['form']['include_group_status'])) {
          if($this->EvalSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $student['student_id']) == null) 
            $content .= 'No,';
          else
            $content .= 'Yes,';
        }
        
  	    $content .= empty($params['form']['include_group_names']) ? '':$group['group_name'].",";$stuff=true;
  	    $content .= empty($params['form']['include_student_first']) ? '':$student['first_name'].",";
  	    $content .= empty($params['form']['include_student_last']) ? '':$student['last_name'].",";
  	    $content .= empty($params['form']['include_student_id']) ? '':$student['student_id'].",";
  	    $content .= empty($params['form']['include_student_email']) ? '':$student['email'].",";
  	    $content .= empty($params['form']['include_criteria_marks']) ? '':$student['score'].",";
  	    $content .= (empty($params['form']['include_criteria_legend'])||!isset($student['sub_score']))? '':$student['sub_score'];
  	    $content .= (empty($params['form']['include_criteria_comments'])||!isset($student['criteria_comments']))? '':$student['criteria_comments'];
  	    $content .= empty($params['form']['include_general_comments']) ? '': "\"".$student['comments']."\",";
  	    if ($hasContent) $content .= "\n";
	    }

	    if ($hasContent) $content .= "\n";
	  
    }
    
    return $content;
    
  }
  
  function buildSubmittedArr() {
    global $globEventId, $globUsersArr;
    $this->EvalSubmission = new EvaluationSubmission();
    foreach ($globUsersArr as $globUserStuNum=>$globUserId) {
      if($this->EvalSubmission->getEvalSubmissionByEventIdSubmitter($globEventId, $globUserId) != null)
        unset($globUsersArr[$globUserStuNum]);
    }
    return $globUsersArr;    
  }

}
?>