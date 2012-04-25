<?php
class ExportHelperComponent extends Object
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
      $groupEvents = $this->GroupEvent->findAll('event_id='.$eventId.' AND group_id != 0');

      //too much garbage... outsourced to createBody
      if (!empty($groupEvents)) {
        $csvContent .= $this->createBody($event, $groupEvents, $params, $eventTemplateId, $eventTypeId);
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
  function createBody ($event, $groupEvents,$params,$eventTemplateId,$eventTypeId) {
    global $globEventId;
    $this->Group = new Group;
    $this->GroupsMembers = new GroupsMembers;
    $this->User = new User;
    $this->SimpleEvaluation = new SimpleEvaluation;
    $this->Rubric = new Rubric;
    $this->RubricsCriteria = new RubricsCriteria;
    $this->MixEvalsQuestionDesc = new MixevalsQuestionDesc;
    $this->EvaluationSimple = new EvaluationSimple;
    $this->EvaluationRubric = new EvaluationRubric;
    $this->EvaluationMixeval = new EvaluationMixeval;
    $this->EvaluationMixevalDetail = new EvaluationMixevalDetail;
    $this->User = new User;
    $mixedEvalNumeric  = '';

    $globEventId = $groupEvents[0]['GroupEvent']['event_id'];

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

//      $groupMembers = $this->GroupsMembers->getMembers($groupId, null);
      $groupMembers = $this->User->getGroupMembers($groupId);
      $j=0;
      $data[$i]['students'] = array();

      global $globUsersArr;
      foreach($groupMembers as $groupMember) {
        $userId = $groupMember['User']['id'];
        $globUsersArr[] = $userId;
      }

      if (!empty($globUsersArr)) {
        $incompletedArr = $this->buildIncompletedArr($groupEventId);
      } else {
        $incompletedArr = array();
      }

      $count = 0;
      foreach ($groupMembers as $key => $groupMember) {
          if (in_array($groupMember['User']['id'], $incompletedArr)) {
              $groupMembers[$key]['User']['incomplete'] = true;
              $count++;
          } else {
              $groupMembers[$key]['User']['incomplete'] = false;
          }
      }

      foreach ($groupMembers as $groupMember) {
        //get student info: first_name, last_name, id, email
        $userId = $groupMember['User']['id'];
        $data[$i]['students'][$j]['student_id'] = $groupMember['User']['student_no'];
        $data[$i]['students'][$j]['first_name'] = $groupMember['User']['first_name'];
        $data[$i]['students'][$j]['last_name'] = $groupMember['User']['last_name'];
        $data[$i]['students'][$j]['email'] = $groupMember['User']['email'];
        $data[$i]['students'][$j]['incomplete'] = $groupMember['User']['incomplete'];

        switch ($eventTypeId) {
          case 1://simple
            $comments = $this->EvaluationSimple->getAllComments($groupEventId,$userId);
            $data[$i]['students'][$j]['comments'] = '';
            foreach ($comments as $comment) {
              $data[$i]['students'][$j]['comments'] .= $comment['EvaluationSimple']['eval_comment'].'; ';
            }
            $data[$i]['students'][$j]['score'] = '';
            $score_tmp = $this->EvaluationSimple->getReceivedTotalScore($groupEventId,$userId);
	    /**
	     * 6 in the group, 4 incompleted
	     * ----------------------------------------------
	     * |             | self eval on | self eval off |
             * | incompleted |    6-4       |    5-4+1      |
             * | completed   |    6-4       |    5-4        |
             * ---------------------------------------------- */
	    if ($event['Event']['self_eval']) {
                $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':($score_tmp[0]['received_total_score']/(count($groupMembers)-$count));
	    } else {
                if (in_array($userId, $incompletedArr)) {
                    $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':($score_tmp[0]['received_total_score']/((count($groupMembers)-1)-$count+1));
                } else {
                    $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score']) ? '':($score_tmp[0]['received_total_score']/((count($groupMembers)-1)-$count));
                }
	    }
            break;
          case 2://rubric
            //get the legend
            if (empty($legend)) {
              $legend_tmp = $this->RubricsCriteria->findAll('rubric_id='.$eventTemplateId, 'criteria');
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
              // get the score
              $score_tmp = $this->EvaluationMixeval->getReceivedTotalScore($groupEventId, $userId);
              $data[$i]['students'][$j]['score'] = !isset($score_tmp[0]['received_total_score'])?'':$score_tmp[0]['received_total_score'];

              // Text data for specific questions is inside the userResults variable
              $userResults = $this->EvaluationMixevalDetail->getResultsDetailByEvaluatee($groupEventId, $userId);
              $data[$i]['students'][$j]['comments'] = '';

              //Here we add some generic information to the mixedEvalNumeric variable. First we add the name of the person being evaluated in this particular mixed eval(evaluatee)
              //then we sample index 0 of the userResults array to just get the number of numeric questions. Then we can make column headers for each question (1,2,3...)
              //$nameArray = $this->User->findUserByid($userId);
              $name = $groupMember['User']['first_name'] . ' ' . $groupMember['User']['last_name'];
              $mixedEvalNumeric .= "\n".$name . "," . $data[$i]['group_name'] . "\n";
              $tempEvaluatee = '';
              $mixedEvalNumeric .= "Evaluator,";

              //go through userResults just to get the number of numeric questions.
              $counter = 1;
              $lastQ = 0;
              foreach ($userResults as $comment) {
                  if ($lastQ > $comment['EvaluationMixevalDetail']['question_number']) {
                      break;
                  }
                  //Here we create the columns for our .csv file, to be imported into excel
                  $mixedEvalNumeric .= "Q$counter".",";
                  $counter++;
                  $lastQ = $comment['EvaluationMixevalDetail']['question_number'];
              }

              foreach ($userResults as $comment) {
                  //Set some variables that hold the USER data for the evaluator and evaluatee, then set the names for convenience
                  $evaluatorArray = $groupMembers[$comment['EvaluationMixeval']['evaluator']];
                  $evaluatorName = $evaluatorArray['User']['first_name'] . ' ' . $evaluatorArray['User']['last_name'];
                  $evaluateeName = $groupMember['User']['first_name'].' '.$groupMember['User']['last_name'];

                  //The $userResults variable is a long list of entries for EVERY evaluatee. If the evaluator changes, it means we are on a new 'survey' created by a different person.
                  //Then we want to add a new line to our numeric data variable.
                  if($comment['EvaluationMixeval']['evaluator'] != $tempEvaluatee) {
                      $mixedEvalNumeric .= "\n";
                      $mixedEvalNumeric .= $evaluatorName. ",";
                  }
                  //Set the temp variable
                  $tempEvaluatee = $comment['EvaluationMixeval']['evaluator'];

                  //Here we go through the text responses for the mixed evaluation answers. If there is text content, we append it to the contents of the comments parameter for the evaluatee.
                  //$data[$i]['students'][$j]['comments'] .= isset($comment['EvaluationMixevalDetail']['question_comment'])&&!empty($comment['EvaluationMixevalDetail']['question_comment']) ? $evaluatorName.' on ' .$evaluateeName. ' Q'.$comment['EvaluationMixevalDetail']['question_number'] .' : '.$comment['EvaluationMixevalDetail']['question_comment']."\n".'':'';
                  if(!isset($comment['EvaluationMixevalDetail']['question_comment'])){
                      $mixedEvalNumeric .= trim($comment['EvaluationMixevalDetail']['grade']).",";
                  } else {
                      $mixedEvalNumeric .= '"'.trim($comment['EvaluationMixevalDetail']['question_comment']).'"'.",";
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
    return $this->formatBody($data, $params, $legends, $mixedEvalNumeric);
  }

  function formatBody($data, $params, $legends=null, $mixedEvalNumeric) {
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
    $content .= empty($params['form']['include_group_status']) ? '':'Status(X/OK),';
    $content .= empty($params['form']['include_group_names']) ? '':'Group Name,';
    $content .= empty($params['form']['include_student_first']) ? '':'First Name,';
    $content .= empty($params['form']['include_student_last']) ? '':'Last Name,';
    $content .= empty($params['form']['include_student_id']) ? '':'Student Number,';
    $content .= empty($params['form']['include_student_email']) ? '':'Email,';
    $content .= empty($params['form']['include_criteria_marks']) ? '':'Final Mark,';
    //$content .= empty($params['form']['include_']) ? '':'/Total,';

    //question count... print 1..2..3..
    $question_count = !empty($legends)&&!empty($params['form']['include_criteria_legend']) ? count($legends):0;
    for ($i=1; $i <= $question_count; $i++) {
      $content .= $i.',';
    }
    //$content .= !isset($params['form']['include_general_comments']) ? '':'Comments';
    if ($hasContent) $content .= "\n\n";

    foreach ($data as $group) {
        foreach ($group['students'] as $student) {
            if (!empty($params['form']['include_group_status'])) {
                if ($student['incomplete']) {
                    $content .= 'X,';
                } else {
                    $content .= 'OK,';
                }
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
    $content .= $mixedEvalNumeric;

    return $content;
  }

  function buildIncompletedArr($groupEventId) {
    global $globUsersArr;
    $this->EvalSubmission = new EvaluationSubmission();
    $submitters = $this->EvalSubmission->getSubmittersByGroupEventId($groupEventId);
    foreach ($globUsersArr as $globUserStuNum=>$globUserId) {
  //    if($this->EvalSubmission->getEvalSubmissionByEventIdSubmitter($globEventId, $globUserId) != null)
        if (array_search($globUserId, $submitters) !== false) {
            unset($globUsersArr[$globUserStuNum]);
        }
    }
    return $globUsersArr;
  }
}
