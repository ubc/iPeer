<?php
/**
 * PenaltyComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class PenaltyComponent extends CakeObject
{
    /**
     * calculatePenalty
     *
     * @param mixed $eventDueDate   event due date
     * @param mixed $eventEndDate   event end date
     * @param mixed $submissionDate submission date, no submission if null
     * @param mixed $penalties      penalty array
     *
     * @access public
     * @return void
     */
    public function calculate($eventDueDate, $eventEndDate, $submissionDate, $penalties)
    {
        // storing the timestamp of the due date of the event and submission date
        $eventDueDate = strtotime($eventDueDate);
        $eventEndDate = strtotime($eventEndDate);
        $submissionDate = strtotime($submissionDate);

        // not due yet. no penalty
        if ($eventDueDate >= time()) {
            return 0;
        }

        // prepare penalty array in reverse order, hight -> low
        $penalties = Set::combine($penalties, '{n}.days_late', '{n}.percent_penalty');
        if (empty($penalties)) {
            return __('N/A', true);
        }
        krsort($penalties);
        $keys = array_keys($penalties);

        // no submission - if now is after release date end then - gets final deduction
        // else return N/A
        if (!$submissionDate) {
            if ($eventEndDate < time()) {
                return $penalties[$keys[0]];
            } else {
                return __('N/A', true);
            }
        }

        // there is submission - may be on time or late
        $late_diff = $submissionDate - $eventDueDate;
        if (0 >= $late_diff) {
            return 0;
        }

        // late
        $days_late = $late_diff/(24*60*60);
        // find the correct days
        $penaltyDay = $keys[0];
        foreach (array_reverse($keys) as $day) {
            if ($days_late <= $day) {
                $penaltyDay = $day;
                break;
            }
        }

        return $penalties[$penaltyDay];
    }
}
