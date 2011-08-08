<?php
App::import('Model', 'UserCourse');
class ExportComponent extends Object
{
  var $components = array('rdAuth');
  var $globUsersArr = array();
  var $globEventId;
  var $mixedEvalNumeric;
   
  function createCSV($params, $courseId=null) {
    $this->Course = new Course;
    $this->Event = new Event;
    $this->GroupEvent = new GroupEvent;
    $this->UserCourse = new UserCourse;
    
    $csvContent = '';

    //*******header
    $header = array();
    //get coursename
    $course = $this->Course->find('all', array('conditions'=>array('Course.id'=>$courseId)));
    $header['course_name'] = empty($params['form']['include_course']) ? '':$course[0]['Course']['course'];
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
      $groupEvents = $this->GroupEvent->find('all', 'event_id='.$eventId);

      //too much garbage... outsourced to createBody
      if (!empty($groupEvents)) {
        $csvContent .= $this->createBody($groupEvents,$params,$eventTemplateId,$eventTypeId);
      }
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
    } else {
      return '';
    }
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
    } else {
      return '';
    }
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
    $this->MixEvalsQuestionDesc = new MixevalsQuestionDesc;
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
      $group = $this->Group->find('all', array('conditions' => array('Group.id' => $groupId)));
      //get group names
      $data[$i]['group_name'] = $group[0]['Group']['group_name'];
      //get group stati
      $data[$i]['group_status'] = $groupEvent['GroupEvent']['marked'];

      $groupMembers = $this->GroupsMembers->getMembers($groupId);
      unset($groupMembers['member_count']);
      $j=0;
      $data[$i]['students'] = array();

      global $globUsersArr;
      foreach($groupMembers as $groupMember) {
        $userId = $groupMember;
        $student = $this->User->findUserByid($userId);
        $globUsersArr[$student['User']['student_no']] = $userId;
      }
      if (!empty($globUsersArr)) {
        $submittedArr = $this->buildSubmittedArr();
      } else {
        $submittedArr = array();
      }
      $count = 0;
      foreach($groupMembers as $groupMember) {
        if(in_array($groupMember, $submittedArr)) {
          $count++;
        }
      }

      foreach ($groupMembers as $groupMember) {
        //get student info: first_name, last_name, id, email
        $userId = $groupMember;
        $student = $this->User->findUserByid($userId);
        $data[$i]['students'][$j]['student_id'] = $student['User']['student_no'];
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
            } else {
              $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':($score_tmp[0]['received_total_score']/((count($groupMembers)-1)-$count));
            }
            break;
          case 2://rubric
            //get the legend
            if (empty($legend)) {
              $legend_tmp = $this->RubricsCriteria->find('all','rubric_id='.$eventTemplateId, 'criteria');
              foreach ($legend_tmp as $legend) {
                array_push($legends, $legend['RubricsCriteria']['criteria']);
              }
            }
            $data[$i]['students'][$j]['sub_score'] = '';
            $data[$i]['students'][$j]['score'] = 0;
            $subScore = $this->EvaluationRubric->getCriteriaResults($groupEventId, $userId);
            foreach ($subScore as $score)
            {
              $data[$i]['students'][$j]['sub_score'] .= $score.',';
              $data[$i]['students'][$j]['score'] += $score;
            }

            //get comments
            $data[$i]['students'][$j]['comments'] = '';
            $comments = $this->EvaluationRubric->getAllComments($groupEventId, $userId);
            foreach ($comments as $comment) {
              $data[$i]['students'][$j]['comments'] .= $comment['EvaluationRubric']['general_comment'].'; ';
            }
            break;
          case 4://mixeval

            $score_tmp = $this->EvaluationMixeval->getReceivedTotalScore($groupEventId,$userId);
#Text data for specific questions is inside the userResults variable
            $userResults = $this->EvaluationMixeval->getResultsDetailByEvaluatee($groupEventId,$userId);
            $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score'])?'':$score_tmp[0]['received_total_score'];
            $data[$i]['students'][$j]['comments'] = '';

#Here we add some generic information to the mixedEvalNumeric variable. First we add the name of the person being evaluated in this particular mixed eval(evaluatee)
#then we sample index 0 of the userResults array to just get the number of numeric questions. Then we can make column headers for each question (1,2,3...)
            global $mixedEvalNumeric;
            $nameArray = $this->User->findUserByid($userId);
            $name=$nameArray['User']['first_name'] . ' ' . $nameArray['User']['last_name'];
            $mixedEvalNumeric .= "\n".$name . "," . $data[$i]['group_name'] . "\n";
            $tempEvaluatee = '';
            $counter = 0;

#go through userResults just to get the number of numeric questions.
#YES we could combine this with the other loop but I'm running out of time and this is so much easier.
            foreach($userResults as $comment){
              if(!isset($comment['EvaluationMiexevalDetail']['question_comment'])){
                if(is_null($comment['EvaluationMixevalDetail']['question_comment'])){
                  $counter++;
                } else {
                  break;
                }
              }

            }
#Here we create the columns for our .csv file, to be imported into excel
            $mixedEvalNumeric .= ",";
            for ($k = 1; $k < $counter+1; $k++) {
              $mixedEvalNumeric .= "Q$k".",";
            }
            $mixedEvalNumeric .= "\n";
##########
            foreach ($userResults as $comment){            	
              $results = $this->EvaluationMixeval->find('all', array('conditions' => array('EvaluationMixeval.id' => $comment['EvaluationMixevalDetail']['evaluation_mixeval_id'])));
			if(!empty($results)) {              
#Set some variables that hold the USER data for the evaluator and evaluatee, then set the names for convenience
              $evaluatorArray = $this->User->find('all', array('conditions' => array('User.id' => $results[0]['EvaluationMixeval']['evaluator'])));
              $evaluatorName = $evaluatorArray[0]['User']['first_name'] . ' ' . $evaluatorArray[0]['User']['last_name'];
              $evaluateeArray = $this->User->find('all', array('conditions' => array('User.id' => $results[0]['EvaluationMixeval']['evaluatee'])));
              $evaluateeName = $evaluateeArray[0]['User']['first_name'] . ' ' . $evaluateeArray[0]['User']['last_name'];

#The $userResults variable is a long list of entries for EVERY evaluatee. If the evaluator changes, it means we are on a new 'survey' created by a different person.
#Then we want to add a new line to our numeric data variable.
              if($evaluatorArray[0]['User']['username'] != $tempEvaluatee){
                $mixedEvalNumeric .= "\n";
                $mixedEvalNumeric .= $evaluatorName. ",";
              }
#Set the temp variable
              $tempEvaluatee = $evaluatorArray[0]['User']['username'];

#Here we go through the text responses for the mixed evaluation answers. If there is text content, we append it to the contents of the comments parameter for the evaluatee.
              $data[$i]['students'][$j]['comments'] .= isset($comment['EvaluationMixevalDetail']['question_comment'])&&!empty($comment['EvaluationMixevalDetail']['question_comment']) ? $evaluatorName.' on ' .$evaluateeName. ' Q'.$comment['EvaluationMixevalDetail']['question_number'] .' : '.$comment['EvaluationMixevalDetail']['question_comment']."\n".'':'';
              if(!isset($comment['EvaluationMixevalDetail']['question_comment'])){
                $mixedEvalNumeric .= trim($comment['EvaluationMixevalDetail']['grade']).",";
              }
            }
          }  
            $mixedEvalNumeric .= "\n";
            break;
          default:
            break;
        }
        $j++;
      }
      //calculate final mark
      $i++;
    }
    return $this->formatBody($data, $params, $legends);
  }

  function formatBody($data, $params, $legends=null) {
    $content = '';
    //sloppy code... sorry...
    $fields = array('group_status','group_names','student_first','student_last','student_id','student_id','criteria_marks','general_comments');
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
    $content .= empty($params['form']['include_group_status']) ? '':__('Status(X/OK),', true);
    $content .= empty($params['form']['include_group_names']) ? '':__('Group Name,', true);
    $content .= empty($params['form']['include_student_first']) ? '':__('First Name,',true);
    $content .= empty($params['form']['include_student_last']) ? '':__('Last Name,', true);
    $content .= empty($params['form']['include_student_id']) ? '':__('Student Number,', true);
    $content .= empty($params['form']['include_student_email']) ? '':__('Email,', true);
    $content .= empty($params['form']['include_criteria_marks']) ? '':__('Final Mark,', true);
    //$content .= empty($params['form']['include_']) ? '':'/Total,';

    //question count... print 1..2..3..
    $question_count = !empty($legends)&&!empty($params['form']['include_criteria_legend']) ? count($legends):0;
    for ($i=1; $i <= $question_count; $i++) {
      $content .= $i.',';
    }
    $content .= !isset($params['form']['include_general_comments']) ? '':__('Comments', true);
    if ($hasContent) $content .= "\n\n";

    foreach ($data as $group) {
      foreach ($group['students'] as $student) {
        if (!empty($params['form']['include_group_status'])) {
          $submittedArr = $this->buildSubmittedArr();
          set_time_limit(1200);
          if(array_key_exists($student['student_id'], $submittedArr) )
            $content .= 'X,';
          else
            $content .= 'OK,';
        }
        $content .= empty($params['form']['include_group_names']) ? '':$group['group_name'].",";$stuff=true;
        $content .= empty($params['form']['include_student_first']) ? '':"\"".$student['first_name']."\",";
        $content .= empty($params['form']['include_student_last']) ? '':"\"".$student['last_name']."\",";
        $content .= empty($params['form']['include_student_id']) ? '':$student['student_id'].",";
        $content .= empty($params['form']['include_student_email']) ? '':"\"".$student['email']."\",";
        $content .= empty($params['form']['include_criteria_marks']) ? '':$student['score'].",";
        $content .= (empty($params['form']['include_criteria_legend'])||!isset($student['sub_score']))? '':$student['sub_score'];
        $content .= empty($params['form']['include_general_comments']) ? '': "\"".str_replace('"', '""', $student['comments'])."\",";

        if ($hasContent) $content .= "\n";
      }
      if ($hasContent) $content .= "\n";
    }
####
#Append the numeric mixed evaluation variable to the end of the .csv. This should be a list of tables for each evaluatee. 
#This is not a good way of doing things, but it works.
    global $mixedEvalNumeric;
    $content .= $mixedEvalNumeric;
###
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
