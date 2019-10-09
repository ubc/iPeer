<?php
App::import('Model', 'UserCourse');

/**
 * SearchComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SearchComponent extends CakeObject
{

    /**
     * setEvaluationCondition
     *
     * @param bool $params
     *
     * @access public
     * @return void
     */
    function setEvaluationCondition($params=null)
    {
        $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
        $eventType = isset($params['data']['Search']['event_type']) ? $params['data']['Search']['event_type']:'';
        $dueDateBegin = isset($params['data']['Search']['due_date_begin']) ? $params['data']['Search']['due_date_begin']:'';
        $dueDateEnd = isset($params['data']['Search']['due_date_end']) ? $params['data']['Search']['due_date_end']:'';
        $releaseDateBegin = isset($params['data']['Search']['release_date_begin']) ? $params['data']['Search']['release_date_begin']:'';
        $releaseDateEnd = isset($params['data']['Search']['release_date_end']) ? $params['data']['Search']['release_date_end']:'';
        $condition = array();
        $sticky = array();
        if (!empty($courseId) && $courseId != 'A') {
            $condition['course_id'] = $courseId;
            $sticky['course_id'] = $courseId;
        } else {
            $sticky['course_id'] = '';
        }
        if (!empty($eventType)) {
            $condition['EventTemplateType.id'] = $eventType;
            $sticky['EventTemplateType.id'] = $eventType;
        } else {
            $sticky['EventTemplateType.id'] = '';
        }
        if ($dueDateBegin != '') {
            //$condition .= !empty($condition) ? ' AND due_date > "'.$dueDateBegin.'"' : 'due_date > "'.$dueDateBegin.'"';
            $condition['due_date > '] = $dueDateBegin;
            $sticky['due_date_begin'] = $dueDateBegin;
        } else {
            $sticky['due_date_begin'] = '';
        }
        if ($dueDateEnd != '') {
            //$condition .= !empty($condition) ? ' AND due_date < "'.$dueDateEnd.'"' : 'due_date < "'.$dueDateEnd.'"';
            $condition['due_date < '] = $dueDateEnd;
            $sticky['due_date_end'] = $dueDateEnd;
        } else {
            $sticky['due_date_end'] = '';
        }
        if ($releaseDateBegin != '') {
            //$condition .= !empty($condition) ? ' AND release_date_end > "'.$releaseDateBegin.'"' :' release_date_end > "'.$releaseDateBegin.'"';
            $condition['release_date_end > '] = $releaseDateBegin;
            $sticky['release_date_begin'] = $releaseDateBegin;
        } else {
            $sticky['release_date_begin'] = '';
        }
        if ($releaseDateEnd != '') {
            //$condition .= !empty($condition) ? ' AND release_date_begin < "'.$releaseDateEnd.'"' : 'release_date_begin < "'.$releaseDateEnd.'"';
            $condition['release_date_begin < '] = $releaseDateEnd;
            $sticky['release_date_end'] = $releaseDateEnd;
        } else {
            $sticky['release_date_end'] = '';
        }

        $nibble = array();
        $nibble['condition'] = $condition;
        $nibble['sticky'] = $sticky;
        return $nibble;
    }


    /**
     * setInstructorCondition
     *
     * @param bool $params
     *
     * @access public
     * @return void
     */
    function setInstructorCondition($params=null)
    {
        $this->UserCourse = new UserCourse;
        $sticky = array();

        $courseId = isset($params['form']['course_id']) ? $params['form']['course_id']:'';
        $instructorName = isset($params['form']['instructorname']) ? $params['form']['instructorname']:'';
        $email = isset($params['form']['email']) ? $params['form']['email']:'';
        $condition = array();
        if (!empty($courseId) && $courseId != 'A') {
            //$condition .= 'id IN(SELECT user_id AS id FROM user_courses WHERE course_id='.$courseId.')';
            $condition['User.id'] = array_keys($this->User->getInstructors('list',
                array('conditions' => array('Course.id' => $courseId))));
            $sticky['course_id'] = $courseId;
        } else {
            $sticky['course_id'] = '';
        }
        if (!empty($instructorName)) {
            //$condition .= !empty($condition) ? ' AND (first_name LIKE "%'.$instructorName.'%" OR last_name LIKE "%'.$instructorName.'%")':' (first_name LIKE "%'.$instructorName.'%" OR last_name LIKE "%'.$instructorName.'%")';
            $condition['User.full_name LIKE '] = '%'.$instructorName.'%';
            $sticky['instructor_name'] = $instructorName;
        } else {
            $sticky['instructor_name'] = '';
        }
        if (!empty($email)) {
            //$condition .= !empty($condition) ? ' AND email LIKE "%'.$email.'%"':' email LIKE "%'.$email.'%"';
            $condition['email LIKE '] = '%'.$email.'%';
            $sticky['email'] = $email;
        } else {
            $sticky['email'] = '';
        }
        $nibble = array();
        $nibble['condition'] = $condition;
        $nibble['sticky'] = $sticky;
        return $nibble;
    }


    /**
     * setEvalResultCondition
     *
     * @param bool $params
     *
     * @access public
     * @return void
     */
    function setEvalResultCondition($params=null)
    {
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

        $nibble['maxPercent'] = floatval($markTo)/100.0;
        $nibble['minPercent'] = floatval($markFrom)/100.0;
        $nibble['sticky'] = $sticky;
        $nibble['event_id'] = $eventId;
        $nibble['status'] = $status;

        return $nibble;
    }
}
