<?php

/**
 * Toolkit
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Toolkit
{
    /**
     * &getInstance
     *
     * @access public
     * @return void
     */
    function &getInstance()
    {
        static $instance = array();
        if (!$instance) {
            $instance[0] =& ClassRegistry::getObject('auth_component');
            if (false == $instance[0]) {
                throw new Exception('Could not get auth component!');
            }
        }
        return $instance[0];
    }


    /**
     * getUser
     *
     * @static
     * @access public
     * @return void
     */
    static function getUser()
    {
        $_this =& Toolkit::getInstance();
        return $_this->user();
    }


    /**
     * getUserId
     *
     * @static
     * @access public
     * @return void
     */
    static function getUserId()
    {
        $_this =& Toolkit::getInstance();
        $user = $_this->user();
        return Set::extract($user, 'User.id');
    }


  /*static function getUserGroupId()
{
    $_this =& Toolkit::getInstance();
    $user = $_this->user();
    return Set::extract($user, 'User.user_group_id');
  }*/

    /**
     * formatDate
     *
     * @param mixed $timestamp
     *
     * @static
     * @access public
     * @return void
     */
    static function formatDate($timestamp)
    {
        $sys_parameter = ClassRegistry::init('SysParameter');
        $dateFormat = $sys_parameter->get('display.date_format');

        if (is_string($timestamp)) {
            return date($dateFormat, strtotime($timestamp));
        } else if (is_numeric($timestamp)) {
            return date($dateFormat, $timestamp);
        } else {
            return "";
        }
    }


    /**
     * parseCSV
     *
     * @param mixed $file
     *
     * @static
     * @access public
     * @return void
     */
    static function parseCSV($file)
    {
        $ret = array();
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $ret[] = array_map('trim', $data);
            }
            fclose($handle);
        } else {
            trigger_error('Error to open file '.$file, E_USER_ERROR);
        }

        return $ret;
    }

    /**
     * pageSize
     *
     * @param int $default default selected value
     *
     * @access public
     * @return array of options for radio input of page limit size per page
     */
    static function pageSize($default = 15)
    {
        return array(
            "type"      => "radio",
            "options"   => array("15" => "15", "30" => "30", "90" => "90", "270" => "270"),
            "onclick" => "window.location= getUrl(document.activeElement.value);",
            "default" => $default,
            "label" => "radio",
            "div" => false
        );
    }

    /**
     * isStartWith test if the haystack starts with needle
     *
     * @param mixed $haystack the string to test
     * @param mixed $needle   the string to search
     *
     * @static
     * @access public
     * @return boolean true if the haystack starts with needle, false otherwise
     */
    static function isStartWith($haystack, $needle)
    {
        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    /**
     * getSimpleEvalDemoData return demo data for simple evaluation preview
     *
     * @param mixed $points the points assign to the students
     *
     * @static
     * @access public
     * @return array demo data
     */
    static function getSimpleEvalDemoData($points)
    {
        return array(
            'event' => array(
                'Event' => array(
                    'id' => 0,
                    'title' => 'Preview Event',
                    'due_date' => Toolkit::formatDate(time()+(5*24*60*60)),
                    'description' => 'Preview for simple evaluation event.',
                    'com_req' => true,
                ),
                'Group' => array(
                    'id' => 0,
                    'group_name' => 'Demo Group',
                ),
            ),
            'groupMembers' => array(
                array(
                    'User' => array(
                        'id' => 1,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student1',
                    ),
                ),
                array(
                    'User' => array(
                        'id' => 2,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student2',
                    ),
                ),
                array(
                    'User' => array(
                        'id' => 3,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student3',
                    ),
                ),
            ),
            'courseId' => 0,
            'userId' => 1,
            'evaluateeCount' => 2,
            'fullName' => User::get('full_name'),
            'remaining' => $points,
            'preview' => true,
        );
    }

    /**
     * getRubricEvalDemoData get demo data for rubric evaluation preview
     *
     * @param mixed $data rubric data
     *
     * @static
     * @access public
     * @return array demo data
     */
    static function getRubricEvalDemoData($data)
    {
        return array(
            'event' => array(
                'Event' => array(
                    'id' => 0,
                    'title' => 'Preview Event',
                    'course_id' => 0,
                    'due_date' => Toolkit::formatDate(time()+(5*24*60*60)),
                    'description' => 'Preview for rubric evaluation event.',
                    'com_req' => true,
                    'course_id' => 0,
                ),
                'Group' => array(
                    'id' => 0,
                    'group_name' => 'Demo Group',
                ),
                'GroupEvent' => array(
                	'id' => 0,
                ),
            ),
            'groupMembers' => array(
                array(
                    'User' => array(
                        'id' => 1,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student1',
                    ),
                ),
                array(
                    'User' => array(
                        'id' => 2,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student2',
                    ),
                ),
                array(
                    'User' => array(
                        'id' => 3,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student3',
                    ),
                ),
            ),
            'viewData' => array('id' => $data['Rubric']['id']),
            'courseId' => 0,
            'userId' => 1,
            'evaluateeCount' => 3,
            'fullName' => User::get('full_name'),
            'preview' => true,
            'viewData' => array(
                'id' => 0,
            ),
            'allDone' => 0,
            'comReq' => 0,
            'userIds' => array(1,2,3),
        );
    }

    /**
     * getMixEvalDemoData get demo data for mix evaluation preview
     *
     * @param mixed $data mix data
     *
     * @static
     * @access public
     * @return array demo data
     */
    static function getMixEvalDemoData($mixeval, $selfEval = 1)
    {
        return array(
            'event' => array(
                'Event' => array(
                    'id' => 0,
                    'title' => 'Preview Event',
                    'self_eval' => $selfEval,
                    'due_date' => Toolkit::formatDate(time()+(5*24*60*60)),
                    'release_date_end' => Toolkit::formatDate(time()+(6*24*60*60)),
                    'description' => 'Preview for mix evaluation event.',
                    'com_req' => true,
                    'template_id' => 0,
                ),
                'Group' => array(
                    'id' => 0,
                    'group_name' => 'Demo Group',
                ),
                'GroupEvent' => array(
                    'id' => 0,
                ),
            ),
            'groupMembers' => array(
                array(
                    'User' => array(
                        'id' => 1,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student1',
                        'full_name' => 'Demo Student1',
                    ),
                ),
                array(
                    'User' => array(
                        'id' => 2,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student2',
                        'full_name' => 'Demo Student2',
                    ),
                ),
                array(
                    'User' => array(
                        'id' => 3,
                        'first_name' => 'Demo',
                        'last_name'  => 'Student3',
                        'full_name' => 'Demo Student3',
                    ),
                ),
            ),
            'mixeval' => array('Mixeval' => $mixeval),
            'courseId' => 0,
            'userId' => 1,
            'evaluateeCount' => 2,
            'fullName' => User::get('full_name'),
            'members' => 0,
            'enrol' => 1,
            'self' => null,
            'preview' => true,
        );
    }

    /**
     * formatRubricEvaluationResultsMatrix
     *
     * @param mixed $evalResult evel result
     *
     * @access public
     * @return void
     */
    static function formatRubricEvaluationResultsMatrix($evalResult)
    {
        $summary = array();
        $criteria = array();        // for storing criteria numbers

        // If array is null, returns false
        if($evalResult == null) {
            return false;
        }

        foreach ($evalResult as $result) {
            $userId = $result['EvaluationRubric']['evaluatee'];
            $evaluator = $result['EvaluationRubric']['evaluator'];
            $summary[$userId]['release_status']['gradeRelease'][] = $result['EvaluationRubric']['grade_release'];
            $summary[$userId]['release_status']['commentRelease'][] = $result['EvaluationRubric']['comment_release'];
            $summary[$userId]['total']['score'] = (isset($summary[$userId]['total']['score'])) ?
                $summary[$userId]['total']['score'] + $result['EvaluationRubric']['score'] : $result['EvaluationRubric']['score'];
            $summary[$userId]['evaluator_count'] = (isset($summary[$userId]['evaluator_count'])) ?
                $summary[$userId]['evaluator_count'] + 1 : 1;
            foreach ($result['EvaluationRubricDetail'] as $detail) {
                $criteria[] = $detail['criteria_number'];
                $summary[$userId]['grades'][$detail['criteria_number']]['grade'] = (isset($summary[$userId]['grades'][$detail['criteria_number']]['grade'])) ?
                    $summary[$userId]['grades'][$detail['criteria_number']]['grade'] + $detail['grade'] : $detail['grade'];
                $summary[$userId]['grades'][$detail['criteria_number']]['evaluator_count'] = (isset($summary[$userId]['grades'][$detail['criteria_number']]['evaluator_count'])) ?
                    $summary[$userId]['grades'][$detail['criteria_number']]['evaluator_count'] + 1 : 1;
                $summary[$userId]['individual'][$evaluator][$detail['criteria_number']]['grade'] =
                    $detail['grade'];
                $summary[$userId]['individual'][$evaluator][$detail['criteria_number']]['comment'] =
                    $detail['criteria_comment'];
                $summary[$userId]['individual'][$evaluator][$detail['criteria_number']]['id'] =
                    $detail['id'];
                $summary[$userId]['individual'][$evaluator][$detail['criteria_number']]['comment_release'] =
                    $detail['comment_release'];
                $summary[$userId]['release_status']['commentRelease'][] = $detail['comment_release'];
            }
            $summary[$userId]['individual'][$evaluator]['general_comment']['comment'] =
                $result['EvaluationRubric']['comment'];
            $summary[$userId]['individual'][$evaluator]['general_comment']['comment_release'] =
                $result['EvaluationRubric']['comment_release'];
        }

        foreach ($summary as $id => $score) {
            $summary[$id]['release_status']['gradeRelease'] = array_product($summary[$id]['release_status']['gradeRelease']);
            $summary[$id]['release_status']['commentRelease'] = array_product($summary[$id]['release_status']['commentRelease']);
            $summary[$id]['total'] = $score['total']['score'] / $score['evaluator_count'];
            foreach ($score['grades'] as $num => $grade) {
                $summary[$id]['grades'][$num] = $grade['grade']/$grade['evaluator_count'];
            }
        }

        $group = array();
        foreach (array_unique($criteria) as $num) {
            $grades = Set::extract($summary, '/grades/'.$num);
            $group['grades'][$num] = array_sum($grades) / count($grades);
        }

        return $summary + $group;
    }
}
