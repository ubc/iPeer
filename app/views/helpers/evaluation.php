<?php
/**
 * array_avg calculate the average of the numbers in an array
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

class EvaluationHelper extends AppHelper
{
    public $helpers = array('Html');
    public $color = array("#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");

    function getSummaryTableHeader($totalMark, $questions)
    {
        $numberQuestions = Set::extract($questions, '/MixevalsQuestion[question_type=S]');
        $header = array(__('Evaluatee', true));
        foreach ($numberQuestions as $key => $question) {
            $header[] = sprintf('%d (/%.1f)', $key+1, $question['MixevalsQuestion']['multiplier']);
        }
        $header[] = __("Total", true) . ' (/' . number_format($totalMark, 2) . ')';

        return $header;
    }

    function getSummaryTable($memberList, $scoreRecords, $numberQuestions, $mixeval, $penalties)
    {
        $totalScore = 0;
        $totalCounter = 0;
        $table = array();

        foreach ($scoreRecords as $evaluteeId => $scores) {
            $tr = array();
            $tr[] = $memberList[$evaluteeId];
            foreach ($numberQuestions as $question) {
                $tr[] = isset($scores[$question['MixevalsQuestion']['question_num']]) ?
                    $scores[$question['MixevalsQuestion']['question_num']] : __('N/A', true);
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
            $table[] = $tr;
        }

        // group average row
        $tr = array(__('Group Average', true));
        foreach ($numberQuestions as $question) {
            if ($totalCounter) {
                $avg = array_avg(Set::classicExtract($scoreRecords, '{n}.'.($question['MixevalsQuestion']['question_num'])));
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
     * @param mixed $memberList   list of members as id => name
     * @param mixed $scores       scores
     * @param mixed $scoreSummary score summary
     * @param mixed $penalties    penalties
     * @param mixed $total        total
     *
     * @access public
     * @return array for generate summary table
     */
    function getRubricSummaryTable($memberList, $scores, $scoreSummary, $penalties, $total)
    {
        $average = array_pop($scores);
        $totalAve = 0;
        $numMembers = 0;
        $table = array();
        foreach ($scores as $userId => $score) {
            $user = array();
            $user[] = $memberList[$userId];
            foreach ($score['rubric_criteria_ave'] as $criterion) {
                $user[] = is_numeric($criterion) ? number_format($criterion, 2) : 'N/A';
            }

            if (!isset($scoreSummary[$userId]['received_ave_score'])) {
                $user[] = sprintf('%.2 (%.2f%%)', 0, 0);
                $totalAve += 0;
            } else if ($penalties[$userId] > 0) {
                $penalty = number_format($penalties[$userId]/100 * $scoreSummary[$userId]['received_ave_score'], 2);
                $diff = number_format($scoreSummary[$userId]['received_ave_score'] - $penalty, 2);
                $user[] = sprintf('%.2f - <font color="red">%.2f</font> = %.2f (%.2f%%)',
                    number_format($scoreSummary[$userId]['received_ave_score'], 2), $penalty, $diff, number_format($diff/$total*100, 2));
                $totalAve += $diff;
            } else {
                $user[] = sprintf('%.2f (%.2f%%)', $scoreSummary[$userId]['received_ave_score'], $scoreSummary[$userId]['received_ave_score']/$total*100);
                $totalAve += $scoreSummary[$userId]['received_ave_score'];
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
     * getResultTableHeader
     *
     * @param mixed $questions   questions
     * @param mixed $firstColumn first column text
     *
     * @access public
     * @return void
     */
    function getResultTableHeader($questions, $firstColumn)
    {
        $header = array($firstColumn);
        foreach ($questions as $key => $question) {
            $header[] = sprintf('%d.%s', $key+1, $question['MixevalsQuestion']['title']);
        }

        return $header;
    }

    /**
     * getMixevalResultTable
     * Returning the data array for mixeval result table
     *
     * @param mixed $memberResult member result
     * @param mixed $memberList   member list
     * @param mixed $questions    questions
     * @param mixed $type         type of the table, possible values 'evaluator', 'evaluatee', any other string
     *                            when it is another string, the string will be shown on the first column. Otherwise,
     *                            the evaluator/evaluatee name will show on the first column
     *
     * @access public
     * @return void
     */
    function getMixevalResultTable($memberResult, $memberList, $questions, $type = 'evaluator')
    {
        $table = array();

        // randomize the result
        if ($type != 'evaluator' && $type != 'evaluatee') {
            shuffle($memberResult);
        }

        foreach ($memberResult as $row) {
            $memberMixeval = $row['EvaluationMixeval'];
            $tr = array(isset($memberMixeval[$type]) ? $memberList[$memberMixeval[$type]] : $type);
            // change the details indexed by question_number
            $resultDetails = Set::combine($row['EvaluationMixevalDetail'], '{n}.question_number', '{n}');
            foreach ($questions as $question) {
                $detail = $resultDetails[$question['MixevalsQuestion']['question_num']];
                // check if the result is released
                if (($type == 'evaluator' || $type == 'evaluatee') ||
                    (($memberMixeval['grade_release'] && $question['MixevalsQuestion']['question_type'] == 'S') ||
                    ($memberMixeval['comment_release'] && $question['MixevalsQuestion']['question_type'] == 'T'))) {

                    $tr[] = $this->renderQuestionResult($question, $detail);
                } else {
                    $tr[] = 'n/a';
                }
            }
            $table[] = $tr;
        }

        return $table;
    }

    function renderQuestionResult($question, $detail)
    {
        $result = '';

        switch($question['MixevalsQuestion']['question_type']) {
        case 'S':
            //Point Description Detail
            $result = $question['Description'][$detail['selected_lom']-1]['descriptor'];
            $result .= "<br />";

            //Points Detail
            $result .= "<strong>".__('Points', true).": </strong>";
            $result .= isset($detail) ? $this->getPoints($detail["grade"], $question['MixevalsQuestion']['multiplier']) : __('N/A', true);
            $result .= "<br />";

            //Grade Detail
            $result .= "<strong>".__('Grade', true).": </strong>";
            $result .= isset($detail) ? $detail["grade"] . " / " . $question['MixevalsQuestion']['multiplier'] : __('N/A', true);
            $result .= "<br />";
            break;
        case 'T':
            $result = "<strong>".__('Comment', true).": </strong>";
            $result .= isset($detail) ? $detail["question_comment"] : __('N/A', true);
            break;
        }

        return $result;
    }

    function getPoints($point, $total)
    {
        // suppose we want to show 5 balls
        $valuePerBall = $total / 5;
        return
            str_repeat(
                $this->Html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle')),
                round($point / $valuePerBall)).
            str_repeat(
                $this->Html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'cicle_empty')),
                round(($total - $point)/$valuePerBall));
    }

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

}
