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

App::import('Model', 'UserCourse');

class SearchComponent extends Object
{   

  function setEvaluationCondition($params=null) {
 
    $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
    $dueDateBegin = isset($params['data']['Search']['due_date_begin']) ? $params['data']['Search']['due_date_begin']:'';
    $dueDateEnd = isset($params['data']['Search']['due_date_end']) ? $params['data']['Search']['due_date_end']:'';
    $releaseDateBegin = isset($params['data']['Search']['release_date_begin']) ? $params['data']['Search']['release_date_begin']:'';
    $releaseDateEnd = isset($params['data']['Search']['release_date_end']) ? $params['data']['Search']['release_date_end']:'';
    $condition = array();
    $sticky = array();
    if (!empty($courseId) && $courseId != 'A') {
      $condition['course_id'] = $courseId;
      $sticky['course_id'] = $courseId;
    } else
      $sticky['course_id'] = '';
    if ($dueDateBegin != '') {
      //$condition .= !empty($condition) ? ' AND due_date > "'.$dueDateBegin.'"' : 'due_date > "'.$dueDateBegin.'"';
      $condition['due_date > '] = $dueDateBegin;
      $sticky['due_date_begin'] = $dueDateBegin;
    } else
      $sticky['due_date_begin'] = '';
    if ($dueDateEnd != '') {
      //$condition .= !empty($condition) ? ' AND due_date < "'.$dueDateEnd.'"' : 'due_date < "'.$dueDateEnd.'"';
      $condition['due_date < '] = $dueDateEnd;
      $sticky['due_date_end'] = $dueDateEnd;
    } else
      $sticky['due_date_end'] = '';
    if ($releaseDateBegin != '') {
      //$condition .= !empty($condition) ? ' AND release_date_end > "'.$releaseDateBegin.'"' :' release_date_end > "'.$releaseDateBegin.'"';
      $condition['release_date_end > '] = $releaseDateBegin;
      $sticky['release_date_begin'] = $releaseDateBegin;
    } else
      $sticky['release_date_begin'] = '';
    if ($releaseDateEnd != '') {
      //$condition .= !empty($condition) ? ' AND release_date_begin < "'.$releaseDateEnd.'"' : 'release_date_begin < "'.$releaseDateEnd.'"';
      $condition['release_date_begin < '] = $releaseDateEnd;
      $sticky['release_date_end'] = $releaseDateEnd;
    } else
      $sticky['release_date_end'] = '';

    $nibble = array();
    $nibble['condition'] = $condition;
    $nibble['sticky'] = $sticky;
    return $nibble;
  }

  function setInstructorCondition($params=null) {
    $this->UserCourse = new UserCourse;
    $sticky = array();
  
    $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
    $instructorName = isset($params['form']['instructorname']) ? $params['form']['instructorname']:'';
    $email = isset($params['form']['email']) ? $params['form']['email']:'';
    $condition = array();
    if (!empty($courseId) && $courseId != 'A') {
      //$condition .= 'id IN(SELECT user_id AS id FROM user_courses WHERE course_id='.$courseId.')';
      $condition['User.id'] = $this->UserCourse->getInstructorsId($courseId);
      $sticky['course_id'] = $courseId;
    } else
      $sticky['course_id'] = '';
    if (!empty($instructorName)) {
      //$condition .= !empty($condition) ? ' AND (first_name LIKE "%'.$instructorName.'%" OR last_name LIKE "%'.$instructorName.'%")':' (first_name LIKE "%'.$instructorName.'%" OR last_name LIKE "%'.$instructorName.'%")';
      $condition['User.full_name LIKE '] = '%'.$instructorName.'%';
      $sticky['instructor_name'] = $instructorName;
    } else
      $sticky['instructor_name'] = '';
    if (!empty($email)) {
      //$condition .= !empty($condition) ? ' AND email LIKE "%'.$email.'%"':' email LIKE "%'.$email.'%"';
      $condition['email LIKE '] = '%'.$email.'%';
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
