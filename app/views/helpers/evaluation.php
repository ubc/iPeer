<?php
function array_avg($arr) {
    if (empty($arr)) {
        return 0;
    }

    return array_sum($arr) / count(array_filter($arr, 'is_numeric'));
}

class EvaluationHelper extends AppHelper {
    public $helpers = array('Html');
    public $color = array("#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");

    function getSummaryTableHeader($totalMark, $questions)
    {
        $numberQuestions = Set::extract($questions, '/MixevalsQuestion[question_type=S]');
        $header = array(__('Evaluatee', true));
        foreach ($numberQuestions as $key => $question) {
            $header[] = sprintf('<font color="%s">%d</font> (/%.1f)',
                $this->color[$key % sizeof($this->color)], $key+1, $question['MixevalsQuestion']['multiplier']);
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
                $tr[] = isset($scores[$question['MixevalsQuestion']['question_num']-1]) ?
                    $scores[$question['MixevalsQuestion']['question_num']-1] : __('N/A', true);
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
                $avg = array_avg(Set::classicExtract($scoreRecords, '{n}.'.($question['MixevalsQuestion']['question_num']-1)));
                $tr[] = number_format($avg, 2);
            } else {
                // no values in the table
                $tr[] = __('N/A', true);
            }
        }
        $tr[] = $totalCounter ? $totalScore/$totalCounter : __('N/A', true);
        $table[] = $tr;

        return $table;
    }

    function getResultTableHeader($questions)
    {
        $header = array(__('Evaluator', true));
        foreach($questions as $key => $question) {
            $header[] = sprintf('<font color="%s">%d.</font>%s',
                $this->color[$key % sizeof($this->color)], $key+1, $question['MixevalsQuestion']['title']);
        }

        return $header;
    }

    /**
     * getMixevalResultTable
     * Returning the data array for mixeval result table
     *
     * @param mixed $memberResult
     * @param mixed $memberList
     * @param mixed $questions
     * @param mixed $penalties
     *
     * @access public
     * @return void
     */
    function getMixevalResultTable($memberResult, $memberList, $questions)
    {
        $table = array();

        foreach ($memberResult as $row) {
            $memberMixeval = $row['EvaluationMixeval'];
            $tr = array($memberList[$memberMixeval['evaluator']]);
            // change the details indexed by question_number
            $resultDetails = Set::combine($row['EvaluationMixevalDetail'], '{n}.question_number', '{n}');
            foreach ($questions as $question) {
                $detail = $resultDetails[$question['MixevalsQuestion']['question_num']-1];
                $tr[] = $this->renderQuestionResult($question, $detail);
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
