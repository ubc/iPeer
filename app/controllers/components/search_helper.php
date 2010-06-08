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
class SearchHelperComponent
{
  var $components = array('rdAuth','sysContainer', 'EvaluationHelper');

  function formatSearchEvaluation($conditions='', $order, $show, $page, $sortBy, $direction) {
    $matrixAry = array();

    $this->Event = new Event;

    $courseIDs =  $this->sysContainer->getMyCourseIDs();
    $conditions .= !empty($conditions) ? ' AND course_id IN ('.$courseIDs.')':'course_id IN ('.$courseIDs.')';

    //$matrixAry['conditions'] = $conditions;
    $data = $this->Event->findAll($conditions, '*, (NOW() >= release_date_begin AND NOW() <= release_date_end) AS is_released', $order, $show, $page);

    $paging['style'] = 'ajax';
    $paging['link'] = '/searchs/searchEvents/?show='.$show.'&sort='.$sortBy.'&direction='.$direction.'&page=';

    $paging['count'] = $this->Event->findCount($conditions);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $page;
    $paging['limit'] = $show;
    $paging['direction'] = $direction;

    $matrixAry['data'] = $data;
    $matrixAry['paging'] = $paging;

    return $matrixAry;
  }

  function formatSearchEvaluationResult($maxPercent=1,$minPercent=0,$eventId=null,$status=null, $order, $show, $page, $sortBy, $direction) {
    $matrixAry = array();
    if (empty($maxPercent)) $maxPercent = 1;
    if (empty($minPercent)) $minPercent = 0;

    $this->GroupEvent = new GroupEvent;
    $this->Group = new Group;
    $this->EvaluationSubmission = new EvaluationSubmission;
    $this->GroupsMembers = new GroupsMembers;
    $this->Event = new Event;

    $this->Event->setId($eventId);
    $event = $this->Event->read();
    $evlResult = $event;
    switch ($status) {
      case "listNotReviewed":
        $assignedGroupIDs = $this->GroupEvent->getNotReviewed($eventId);
        break;
      case "late":
        $assignedGroupIDs = $this->GroupEvent->getLate($eventId);
        break;
      case "low":
	      $eventTypeId = $event['Event']['event_template_type_id'];
	      $assignedGroupIDs = $this->GroupEvent->getLowMark($eventId, $eventTypeId, $maxPercent, $minPercent);
        break;
      default:
        $assignedGroupIDs = $this->GroupEvent->getGroupIDsByEventId($eventId);
        break;
    }
		if (!empty($assignedGroupIDs)) {
		  $assignedGroups = array();

			// retrieve string of group ids
  		for ($i = 0; $i < count($assignedGroupIDs); $i++) {
  			$group = $this->Group->find('id = '.$assignedGroupIDs[$i]['GroupEvent']['group_id']);
  			//$students = $this->Group->groupStudents($assignedGroupIDs[$i]['GroupEvent']['group_id']);
  			$assignedGroups[$i] = $group;
  			//$assignedGroups[$i]['Group']['Students']=$students;

  			//Get Members whom completed evaluation
    		$memberCompletedNo = $this->EvaluationSubmission->numCountInGroupCompleted($group['Group']['id'],
    		                                                                           $assignedGroupIDs[$i]['GroupEvent']['id']);
        //Check to see if all members are completed this evaluation

       	$numOfCompletedCount = $memberCompletedNo[0][0]['count'];
	  		//$numMembers=$event['Event']['self_eval'] ? $this->GroupsMembers->findCount('group_id='.$group['Group']['id']) : $this->GroupsMembers->findCount('group_id='.$group['Group']['id']) - 1;
	  		$numMembers=$this->GroupsMembers->findCount('group_id='.$group['Group']['id']);
        ($numOfCompletedCount == $numMembers) ? $completeStatus = 1:$completeStatus = 0;


        //Get release status
        $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($event['Event']['id'], $group['Group']['id']);
        $released = $this->EvaluationHelper->getGroupReleaseStatus($groupEvent);

        $assignedGroups[$i]['Group']['complete_status'] = $completeStatus;
        $assignedGroups[$i]['Group']['num_completed'] = $numOfCompletedCount;
        $assignedGroups[$i]['Group']['num_members'] = $numMembers;
        $assignedGroups[$i]['Group']['marked'] = $assignedGroupIDs[$i]['GroupEvent']['marked'];
        $assignedGroups[$i]['Group']['grade_release_status'] = $released['grade_release_status'];
        $assignedGroups[$i]['Group']['comment_release_status'] = $released['comment_release_status'];

  		}

		  $evlResult['Evaluation']['assignedGroups'] = $assignedGroups;
  	}else {
      $evlResult['Evaluation']['assignedGroups'] = array();
  	}

    $paging['style'] = 'ajax';
    $paging['link'] = '/searchs/searchEvents/?show='.$show.'&sort='.$sortBy.'&direction='.$direction.'&page=';

    $paging['count'] = count($assignedGroupIDs);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $page;
    $paging['limit'] = $show;
    $paging['direction'] = $direction;

    $matrixAry['data'] = $evlResult;
    $matrixAry['paging'] = $paging;

    return $matrixAry;
  }

  function formatSearchInstructor($conditions='', $order, $show, $page, $sortBy, $direction)
  {
    $matrixAry = array();

    $this->User = new User;

    $conditions .= empty($conditions) ? 'role <> "S" AND role <> "A"':' AND role <> "S" AND role <> "A"';

    //$matrixAry['conditions'] = $conditions;
    $data = $this->User->findAll($conditions, null, $order, $show, $page);

    $paging['style'] = 'ajax';
    $paging['link'] = '/searchs/searchEvents/?show='.$show.'&sort='.$sortBy.'&direction='.$direction.'&page=';

    $paging['count'] = $this->User->findCount($conditions);
    $paging['show'] = array('10','25','50','all');
    $paging['page'] = $page;
    $paging['limit'] = $show;
    $paging['direction'] = $direction;

    $matrixAry['data'] = $data;
    $matrixAry['paging'] = $paging;

    return $matrixAry;
  }

  function setEvaluationCondition($params=null) {
    $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
    $dueDateBegin = isset($params['data']['Search']['due_date_begin']) ? $params['data']['Search']['due_date_begin']:'';
    $dueDateEnd = isset($params['data']['Search']['due_date_end']) ? $params['data']['Search']['due_date_end']:'';
    $releaseDateBegin = isset($params['data']['Search']['release_date_begin']) ? $params['data']['Search']['release_date_begin']:'';
    $releaseDateEnd = isset($params['data']['Search']['release_date_end']) ? $params['data']['Search']['release_date_end']:'';
    $condition = '';
    $sticky = array();
    if (!empty($courseId) && $courseId != 'A') {
      $condition .= 'course_id='.$courseId;
      $sticky['course_id'] = $courseId;
    } else
      $sticky['course_id'] = '';
    if ($dueDateBegin != '') {
      $condition .= !empty($condition) ? ' AND NOW() > '.$dueDateBegin:'NOW() > '.$dueDateBegin;
      $sticky['due_date_begin'] = $dueDateBegin;
    } else
      $sticky['due_date_begin'] = '';
    if ($dueDateEnd != '') {
      $condition .= !empty($condition) ? ' AND NOW() < '.$dueDateEnd:'NOW() < '.$dueDateEnd;
      $sticky['due_date_end'] = $dueDateEnd;
    } else
      $sticky['due_date_end'] = '';
    if ($releaseDateBegin != '') {
      $condition .= !empty($condition) ? ' AND NOW() > '.$releaseDateBegin:'NOW() > '.$releaseDateBegin;
      $sticky['release_date_begin'] = $releaseDateBegin;
    } else
      $sticky['release_date_begin'] = '';
    if ($releaseDateEnd != '') {
      $condition .= !empty($condition) ? ' AND NOW() < '.$releaseDateEnd:'NOW() < '.$releaseDateEnd;
      $sticky['release_date_end'] = $releaseDateEnd;
    } else
      $sticky['release_date_end'] = '';

    $nibble = array();
    $nibble['condition'] = $condition;
    $nibble['sticky'] = $sticky;
    return $nibble;
  }

  function setInstructorCondition($params=null) {
    $sticky = array();
    $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
    $instructorName = isset($params['form']['instructorname']) ? $params['form']['instructorname']:'';
    $email = isset($params['form']['email']) ? $params['form']['email']:'';
    $condition = '';
    if (!empty($courseId) && $courseId != 'A') {
      $condition .= 'id IN(SELECT user_id AS id FROM user_courses WHERE course_id='.$courseId.')';
      $sticky['course_id'] = $courseId;
    } else
      $sticky['course_id'] = '';
    if (!empty($instructorName)) {
      $condition .= !empty($condition) ? ' AND (first_name LIKE "%'.$instructorName.'%" OR last_name LIKE "%'.$instructorName.'%")':' (first_name LIKE "%'.$instructorName.'%" OR last_name LIKE "%'.$instructorName.'%")';
      $sticky['instructor_name'] = $instructorName;
    } else
      $sticky['instructor_name'] = '';
    if (!empty($email)) {
      $condition .= !empty($condition) ? ' AND email LIKE "%'.$email.'%"':' email LIKE "%'.$email.'%"';
      $sticky['email'] = $email;
    } else
      $sticky['email'] = '';
    $nibble = array();
    $nibble['condition'] = $condition;
    $nibble['sticky'] = $sticky;
    return $nibble;
  }

  function setEvalResultCondition($params=null) {
    $sticky = array();
    $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
    !empty($courseId) ? $sticky['course_id']=$courseId:$sticky['course_id']='';
    $eventId = isset($params['form']['event_id']) ? $params['form']['event_id']:'';
    !empty($eventId) ? $sticky['event_id']=$eventId:$sticky['event_id']='';
    $status = isset($params['form']['status']) ? $params['form']['status']:'';
    !empty($status) ? $sticky['status']=$status:$sticky['status']='';
    $markFrom = isset($params['data']['Search']['mark_from']) ? $params['data']['Search']['mark_from']:'';
    !empty($markFrom) ? $sticky['mark_from']=$markFrom:$sticky['mark_from']='';
    $markTo = isset($params['data']['Search']['mark_to']) ? $params['data']['Search']['mark_to']:'';
    !empty($markTo) ? $sticky['mark_to']=$markTo:$sticky['mark_to']='';

    $nibble['maxPercent'] = $markTo/100.0;
    $nibble['minPercent'] = $markFrom/100.0;
    $nibble['sticky'] = $sticky;
    $nibble['event_id'] = $eventId;
    $nibble['status'] = $status;

    return $nibble;
  }
}
?>