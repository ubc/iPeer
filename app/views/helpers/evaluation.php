<?php
/**
 * EvaluationHelper
 *
 * @uses AppHelper
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationHelper extends AppHelper
{
    public $helpers = array('Html');

    /**
     * array_avg 
     * calculate the average of the numbers in an array
     *
     * @param mixed $arr
     *
     * @access public
     * @return the average of the array
     */
    function array_avg($arr)
    {
        if (empty($arr)) {
            return 0;
        }
    
        if (!($count = count(array_filter($arr, 'is_numeric')))) {
            return 0;
        }
    
        return array_sum($arr) / $count;
    }

    /**
     * getSummaryTableHeader
     *
     * @param mixed $totalMark
     * @param mixed $questions
     *
     * @access public
     * @return array of table headers
     */
    function getSummaryTableHeader($totalMark, $questions)
    {
        $header = array(__('Evaluatee', true));
        $num = 1;
        foreach ($questions as $ques) {
            if ($ques['self_eval']) {
                continue;
            } else if (!$ques['required']) {
                $num++;
                continue;
            }
            if (in_array($ques['mixeval_question_type_id'], array(1, 4))) {
                $header[] = sprintf('%d (/%.1f)', $num, $ques['multiplier']);
            }
            $num++;            
        }
        $header[] = __("Total", true) . ' (/' . number_format($totalMark, 2) . ')';

        return $header;
    }

    /**
     * getSummaryTable
     *
     * @param mixed $memberList
     * @param mixed $scoreRecords
     * @param mixed $numberQuestions
     * @param mixed $mixeval
     * @param mixed $penalties
     * @param mixed $notInGroup
     *
     * @access public
     * @return array for generating summary table
     */
    function getSummaryTable($memberList, $scoreRecords, $numberQuestions, $mixeval, $penalties, $notInGroup)
    {
        $totalScore = 0;
        $totalCounter = 0;
        $table = array();

        $required = Set::combine($numberQuestions, '{n}.question_num', '{n}.required');
        $peerQues = Set::combine($numberQuestions, '{n}.question_num', '{n}.self_eval');
        // only required peer evaluation questions are counted toward the averages
        $required = array_intersect(array_keys($required, 1), array_keys($peerQues, 0));

        foreach ($scoreRecords as $evaluteeId => $scores) {
            $tr = array();
            (in_array($evaluteeId, Set::extract($notInGroup, '/User/id'))) ? $class=array('class'=>'blue') : $class=array();
            $tr[] = array($memberList[$evaluteeId], $class);
            foreach ($numberQuestions as $question) {
                if (!in_array($question['question_num'], $required)) {
                    unset($scores[$question['question_num']]);
                    continue;
                }
                $tr[] = isset($scores[$question['question_num']]) ?
                    number_format($scores[$question['question_num']], 2) : __('N/A', true);
            }

            // find out penalties for total column
            $filteredScore = array_filter($scores, 'is_numeric');
            if (empty($filteredScore)) {
                $tr[] = __('N/A', true);
            } else {
                $total = array_sum($filteredScore);
                $penalty = ($penalties[$evaluteeId] / 100) * $total;
                if ($penalty > 0) {
                    $tr[] = sprintf('%.2f - <font color="red">%.2f</font> = %.2f (%.2f%%)',
                        $total, $penalty, $total-$penalty, ($total-$penalty)/$mixeval['Mixeval']['total_marks']*100);
                } else {
                    $tr[] = sprintf('%.2f (%.2f%%)', $total, $total/$mixeval['Mixeval']['total_marks']*100);
                }
                $totalScore += $total-$penalty;
                $totalCounter ++;
            }
            $table[$evaluteeId] = $tr;
        }

        // group average row
        $tr = array(array(__('Group Average', true), array()));
        foreach ($numberQuestions as $question) {
            if (!in_array($question['question_num'], $required)) {
                continue;
            }
            if ($totalCounter) {
                $avg = $this->array_avg(Set::classicExtract($scoreRecords, '{n}.'.($question['question_num'])));
                $tr[] = number_format($avg, 2);
            } else {
                // no values in the table
                $tr[] = __('N/A', true);
            }
        }
        $tr[] = $totalCounter ? number_format($totalScore/$totalCounter, 2) : __('N/A', true);
        $table[] = $tr;

        return $table;
    }

    /**
     * getRubricSummaryTableHeader
     *
     * @param mixed $total
     * @param mixed $criteria
     *
     * @access public
     * @return array for generating table header
     */
    function getRubricSummaryTableHeader($total, $criteria) {
        $header = array(__('Evaluatee', true));
        foreach ($criteria as $key => $criterion) {
            $header[] = sprintf('%d (/%.1f)', $key+1, $criterion['multiplier']);
        }
        $header[] = __("Total", true) . ' (/' . number_format($total, 2) . ')';

        return $header;
    }

    /**
     * getRubricSummaryTable
     *
     * @param mixed $memberList list of members as id => name
     * @param mixed $notInGroup users not in group
     * @param mixed $scores     scores
     * @param mixed $penalties  penalties
     * @param mixed $total      total
     *
     * @access public
     * @return array for generate summary table
     */
    function getRubricSummaryTable($memberList, $notInGroup, $scores, $penalties, $total)
    {
        $average = array_pop($scores);
        $totalAve = 0;
        $numMembers = 0;
        $table = array();
        foreach ($scores as $userId => $score) {
            $user = array();
            in_array($userId, $notInGroup) ? $class=array('class' => 'blue') : $class=array();
            $user[] = array($memberList[$userId], $class);
            foreach ($score['grades'] as $criterion) {
                $user[] = is_numeric($criterion) ? number_format($criterion, 2) : 'N/A';
            }

            if (!isset($scores[$userId]['total'])) {
                $user[] = sprintf('%.2 (%.2f%%)', 0, 0);
                $totalAve += 0;
            } else if ($penalties[$userId] > 0) {
                $penalty = number_format($penalties[$userId]/100 * $scores[$userId]['total'], 2);
                $diff = number_format($scores[$userId]['total'] - $penalty, 2);
                $user[] = sprintf('%.2f - <font color="red">%.2f</font> = %.2f (%.2f%%)',
                    number_format($scores[$userId]['total'], 2), $penalty, $diff, number_format($diff/$total*100, 2));
                $totalAve += $diff;
            } else {
                $user[] = sprintf('%.2f (%.2f%%)', $scores[$userId]['total'], number_format($scores[$userId]['total']/$total*100, 2));
                $totalAve += $scores[$userId]['total'];
            }
            $numMembers++;
            $table[] = $user;
        }
        $user = array();
        $user[] = __('Group Average', true);
        foreach ($average as $ave) {
            $user[] = number_format($ave, 2);
        }
        $user[] = number_format($totalAve/$numMembers, 2);
        $table[] = $user;

        return $table;
    }
    
    /**
     * getIndividualRubricHeader
     *
     * @param mixed $criteria
     *
     * @access public
     * @return void
     */
    function getIndividualRubricHeader($criteria)
    {
        $header = array(__('Evaluator', true));
        foreach ($criteria as $criterion) {
            $header[$criterion['criteria_num']] = '('.$criterion['criteria_num'].') '.$criterion['criteria'];
        }
        return $header;
    }

    /**
     * getReviewButton
     *
     * @param mixed $event
     * @param mixed $displayFormat
     *
     * @access public
     * @return review button for evaluation results
     */
    function getReviewButton($event, $displayFormat)
    {
        $button = '<form name="evalForm" id="evalForm" method="POST" action="'.$this->Html->url('markEventReviewed').'">'.
              '<input type="hidden" name="event_id" value="'.$event['Event']['id'].'" />'.
              '<input type="hidden" name="group_id" value="'.$event['Group']['id'].'" />'.
              '<input type="hidden" name="course_id" value="'.$event['Event']['course_id'].'" />'.
              '<input type="hidden" name="group_event_id" value="'.$event['GroupEvent']['id'].'" />'.
              '<input type="hidden" name="display_format" value="'.$displayFormat.'" />';
        if ($event['GroupEvent']['marked'] == "reviewed") {
            $button .= '<input class="reviewed" type="submit" name="mark_not_reviewed" value="'.__('Mark Peer Evaluations as Not Reviewed', true).'" />';
        } else {
            $button .= '<input class="reviewed" type="submit" name="mark_reviewed" value="'.__('Mark Peer Evaluations as Reviewed', true).'" />';
        }
        $button .= '</form>';

        return $button;
    }

    /**
     * getReleaseStatusTableHeader
     *
     * @access public
     * @return array for header
     */
    function getReleaseStatusTableHeader()
    {
        $header = array(__('Evaluatee', true));
        $header[] = __('Grades', true);
        $header[] = __('Comments', true);
        
        return $header;
    }
    
    /**
     * getReleaseStatusButtons
     *
     * @param mixed $evaluatees
     * @param mixed $notInGroup
     * @param mixed $grpEventId
     * @param mixed $status
     *
     * @access public
     * @return array for body of table
     */
    function getReleaseStatusTableButtons($evaluatees, $notInGroup, $grpEventId, $status)
    {
        $body = array();
        unset($status['grades']);
        $button = '<form name="evalForm" id="evalForm" method="POST" action="'.$this->Html->url('markCommentRelease').'">'.
            '<input type="hidden" name="group_event_id" value="'.$grpEventId.'" />';
        
        foreach ($status as $id => $details) {
            $row = array();
            $class = in_array($id, $notInGroup) ? array('class' => 'blue') : array();
            $row[] = array($evaluatees[$id], $class);
            if ($details['release_status']['gradeRelease']) {
                $row[] = '<input type="button" name="UnreleaseGrades" value="'.__('Unrelease Grades', true).'" onClick="location.href=\''.$this->webroot.$this->theme.'evaluations/markGradeRelease/'.$grpEventId.'/0/'.$id.'\'">';
            } else {
                $row[] = '<input type="button" name="ReleaseGrades" value="'.__('Release Grades', true).'" onClick="location.href=\''.$this->webroot.$this->theme.'evaluations/markGradeRelease/'.$grpEventId.'/1/'.$id.'\'">';
            }
            $comment = $button.'<input type="hidden" name="evaluatee" value="'.$id.'" />';
            if ($details['release_status']['commentRelease']) {
                $comment .= '<input name="submit" type="submit" value="'.__('Unrelease Comments', true).'" /></form>';
            } else {
                $comment .= '<input name="submit" type="submit" value="'.__('Release Comments', true).'" /></form>';
            }
            $row[] = $comment;
            $body[] = $row;
        }
        
        $all = array('');
        $allCommentStatus = array_product(Set::classicExtract($status, '{n}.release_status.commentRelease'));
        $allGradeStatus = array_product(Set::classicExtract($status, '{n}.release_status.gradeRelease'));
        if ($allGradeStatus) {
            $all[] = '<input type="button" name="UnreleaseGrades" value="'.__('Unrelease All Grades', true).'" onClick="location.href=\''.$this->webroot.$this->theme.'evaluations/markGradeRelease/'.$grpEventId.'/0\'">';
        } else {
            $all[] = '<input type="button" name="ReleaseGrades" value="'.__('Release All Grades', true).'" onClick="location.href=\''.$this->webroot.$this->theme.'evaluations/markGradeRelease/'.$grpEventId.'/1\'">';
        }
        if ($allCommentStatus) {
            $comment = $button.'<input name="submit" type="submit" value="'.__('Unrelease All Comments', true).'" /></form>';
        } else {
            $comment = $button.'<input name="submit" type="submit" value="'.__('Release All Comments', true).'" /></form>';
        }
        $all[] = $comment;
        $body[] = $all;
        
        return $body;
    }

}
