<?php
/**
 * ExportHelper2Component
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class ExportHelper2Component extends CakeObject
{
    public $name = 'ExportHelper2';

    /**
     * getGroupMemberHelper
     *
     * @param mixed $grpEventId
     *
     * @access public
     * @return void
     */
    function getGroupMemberHelper($grpEventId)
    {
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->User = ClassRegistry::init('User');

        $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
        $i = 0;
        foreach ($groupMembers as $g) {
            $fields = array('id', 'first_name', 'last_name', 'student_no', 'email');
            $user = $this->User->findUserByidWithFields($g['GroupsMembers']['user_id'], $fields);
            $groupMembers[$i] = $user;
            $i++;
        }
        return $groupMembers;
    }

    /**
     * getGroupMemberwithoutTutorsHelper
     *
     * @param mixed $grpEventId
     *
     * @access public
     * @return void
     */
    function getGroupMemberwithoutTutorsHelper($grpEventId)
    {
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->User = ClassRegistry::init('User');

        $group = $this->GroupEvent->getGroupMembers($grpEventId);
        $i = 0;
        foreach ($group as $g) {
            if (5 == $this->User->getRoleId($g['GroupsMembers']['user_id'])) {
                $fields = array('id', 'first_name', 'last_name', 'student_no', 'email');
                $user = $this->User->findUserByidWithFields($g['GroupsMembers']['user_id'], $fields);
                $groupMembers[$i] = $user;
                $i++;
            }
        }
        return $groupMembers;
    }

    /**
     * buildExporterGrid
     *
     * @param mixed $xLim
     * @param mixed $yLim
     *
     * @access public
     * @return void
     */
    function buildExporterGrid($xLim, $yLim)
    {
        $yAxis = array_fill(0, $yLim, '');
        $grid = array_fill(0, $xLim, $yAxis);
        return $grid;
    }

    /**
     * repeatDrawByCoordinateVertical
     *
     * @param mixed &$grid        grid
     * @param mixed $initX        init X
     * @param mixed $initY        init Y
     * @param mixed $vertIndexing vert indexing
     * @param mixed $reptition    reptition
     * @param mixed $value        value
     *
     * @access public
     * @return void
     */
    function repeatDrawByCoordinateVertical(&$grid ,$initX, $initY, $vertIndexing, $reptition, $value)
    {
        while ($reptition > 0) {
            $grid[$initX][$initY] = $value;
            $initY += $vertIndexing;
            $reptition--;
        }
    }

    /**
     * fillGridHorizonally
     *
     * @param bool  &$grid  grid
     * @param mixed $xFrom  x from
     * @param mixed $yIndex y index
     * @param bool  $values value
     *
     * @access public
     * @return void
     */
    function fillGridHorizonally (&$grid, $xFrom, $yIndex, $values = array())
    {
        $index = count($values);
        for ($i=0; $i<$index; $i++) {
            $grid[$xFrom+$i][$yIndex] = $values[$i];
        }
    }

    /**
     * fillGridVertically
     *
     * @param bool  &$grid  grid
     * @param mixed $yFrom  y from
     * @param mixed $xIndex x index
     * @param bool  $values values
     *
     * @access public
     * @return void
     */
    function fillGridVertically (&$grid, $yFrom, $xIndex, $values = array())
    {
        $index = count($values);
        for ($i=0; $i<$index; $i++) {
            $grid[$xIndex][$yFrom + $i] = $values[$i];
        }
    }


    /**
     * arrayDraw
     *
     * @param mixed $grid
     *
     * @access public
     * @return void
     */
    function arrayDraw($grid)
    {
        $formatCSV = '';
        $xLim = count($grid);
        $yLim = count($grid[0]);
        for ($y=0; $y<$yLim; $y++) {
            for ($x=0; $x<$xLim-1; $x++) {
                $formatCSV .= $grid[$x][$y].",";
            }
            $formatCSV .= "\n";
        }
        return $formatCSV;
    }

    /**
     * arrayDrawMod
     *
     * @param mixed $modGrid
     *
     * @access public
     * @return void
     */
    function arrayDrawMod($modGrid)
    {
        $csv = '';
        for ($row=0; $row<count($modGrid); $row++) {
            for ($cell=0; $cell<count($modGrid[$row]); $cell++) {
                $csv .= $modGrid[$row][$cell];
            }
        }
        return $csv;
    }

    //
    /**
     * modifyGrid Swaps x and y axis, temp use for convenience
     *
     * @param mixed $grid
     *
     * @access public
     * @return void
     */
    function modifyGrid($grid)
    {
        $modGrid = array();
        $xLim = count($grid);
        $yLim = count($grid[0]);
        for ($y=0; $y<$yLim; $y++) {
            $tmp = array();
            for ($x=0; $x<$xLim; $x++) {
                array_push($tmp, $grid[$x][$y]);
            }
            array_push($modGrid, $tmp);
        }
        return $modGrid;
    }


    /**
     * formatEvaluateeHeaderArray
     *
     * @param mixed $params    params
     * @param mixed $evaluatee evaluatee
     *
     * @access public
     * @return void
     */
    function formatEvaluateeHeaderArray($params, $evaluatee)
    {
        $evaluateeHeader = array("Evaluatee :");
        if (!empty($params['include_student_name'])) {
      /*array_push($evaluateeHeader, $evaluatee['last_name']);
      array_push($evaluateeHeader, $evaluatee['first_name']);*/
            array_push($evaluateeHeader, $evaluatee['first_name'].' '.$evaluatee['last_name']);
        }
        if (!empty($params['include_student_id'])) {
            array_push($evaluateeHeader, $evaluatee['student_no']);
        }
        if (!empty($params['include_student_email'])) {
            array_push($evaluateeHeader, $evaluatee['email']);
        }
        return $evaluateeHeader;
    }


    /**
     * createEvaluatorsHeaderRow
     *
     * @param mixed $evaluators
     *
     * @access public
     * @return void
     */
    function createEvaluatorsHeaderRow($evaluators)
    {
        $headerRow = "Evaluators =>,";
        foreach ($evaluators as $e) {
            $headerRow .= ",".$e['first_name'].$e['last_name'];
        }
        $headerRow .= "\n,";
        $headerRow .= "Evaluator Student Num =>,";
        foreach ($evaluators as $e) {
            $headerRow .= ",".$e['student_no'];
        }
        $headerRow .= ",,Average Mark For Question";
        return $headerRow;
    }


    /**
     * Helper function for getting the rubrics criteria or mix eval questions.
     *
     * @param INT $grpEventId : group_event_id
     *
     * @return Evluation question corresponding to the grpEventId
     */
    function getEvaluationQuestions($grpEventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');

        $groupEvent = $this->GroupEvent->getGrpEvent($grpEventId);
        $eventId = $groupEvent['GroupEvent']['event_id'];
        $event = $this->Event->getEventById($eventId);
        $evaluationId = $event['Event']['template_id'];
        $evaluationType = $event['Event']['event_template_type_id'];

        $questions = null;
        // $EventType = 2(Rubric) or 4(Mix Eval)
        $evaluationType == 2 ? ($questions = $this->RubricsCriteria->getCriteria($evaluationId)) &&
            ($evalType = 'RubricsCriteria') :
            ($questions = $this->MixevalQuestion->getQuestion($evaluationId, '1')) &&
            ($evalType = 'MixevalQuestion');
        //Store the $evaluationType
        $questions[0][$evalType]['evaluation_type'] = $evaluationType;
        //$questions['evaluation_type'] = $evaluationType;
        return $questions;
    }

    /**
     * formatEvaluatorsHeaderArray
     *
     * @param mixed $evaluators
     *
     * @access public
     * @return void
     */
    function formatEvaluatorsHeaderArray($evaluators)
    {
        $headerArray['name'] = array();
        $headerArray['last_name'] = array();
        $headerArray['first_name'] = array();
        $headerArray['student_no'] = array();
        $headerArray['email'] = array();

        foreach ($evaluators as $e) {
            //	array_push($headerArray['first_name'], $e['first_name'].' '.$e['last_name']);
            array_push($headerArray['name'], $e['first_name'].' '.$e['last_name']);
            //	array_push($headerArray['last_name'], $e['last_name']);
            array_push($headerArray['student_no'], $e['student_no']);
            array_push($headerArray['email'], $e['email']);
        }
        return $headerArray;
    }


    /**
     * drawMixOrRubricsGrid
     *
     * @param mixed $grid             grid
     * @param mixed $params           params
     * @param mixed $groupMemberCount group member count
     *
     * @access public
     * @return void
     */
    function drawMixOrRubricsGrid($grid, $params, $groupMemberCount)
    {

        $formatCSV = array();
        $grid = $this->modifyGrid($grid);
        $rowRange = count($grid);
        $colRange = count($grid[0]);
        $sectionSize = 1;
        $i = 1;
        while ($grid[$i][0] != "Evaluatee") {
            $sectionSize++;
            $i++;
        }
        $evaluatorRow = 2; //$evaluateeRow = 0;
        $emailRow = 5;
        if (empty($params['include_student_name'])) {
            $this->repeatDrawByCoordinateVertical($grid, 0, 1, $sectionSize, $groupMemberCount, '');
            $this->repeatDrawByCoordinateVertical($grid, 0, 2, $sectionSize, $groupMemberCount, '');
            $this->unsetMultipleGridRow($grid, $evaluatorRow, $groupMemberCount, $sectionSize);
            $this->unsetMultipleGridRow($grid, 1 + $evaluatorRow, $groupMemberCount, $sectionSize);
        }
        if (empty($params['include_student_id'])) {
            $this->repeatDrawByCoordinateVertical($grid, 0, 3, $sectionSize, $groupMemberCount, '');
            $this->unsetMultipleGridRow($grid, 2 + $evaluatorRow, $groupMemberCount, $sectionSize);
        }
        if (empty($params['email'])) {
            $this->unsetMultipleGridRow($grid, $emailRow, $groupMemberCount, $sectionSize);
        }
        $formatCSV = array();
        for ($row=0; $row<$rowRange; $row++) {
            if (isset($grid[$row])) {
                for ($col=0; $col<$colRange; $col++) {
                    $formatCSV .= $grid[$row][$col].",";
                }
                $formatCSV .= "\n";
            }
        }
        return $formatCSV;
    }


    /**
     * unsetMultipleGridRow
     *
     * @param mixed &$grid      grid
     * @param mixed $initRowNum init row num
     * @param mixed $repetition repetition
     * @param mixed $indexing   indexing
     *
     * @access public
     * @return void
     */
    function unsetMultipleGridRow(&$grid, $initRowNum, $repetition, $indexing)
    {
        $spacing = 0;
        while ($repetition > 0) {
            unset($grid[$initRowNum + $spacing]);
            $spacing += $indexing;
            $repetition--;
        }
    }


    /**
     * createGroupMemberArrayBlock
     *
     * @param mixed $groupMembers group members
     * @param mixed $params        params
     *
     * @access public
     * @return void
     */
    function createGroupMemberArrayBlock($groupMembers, $params)
    {
        $memberCount = count($groupMembers);
        $grpMemberBlock = array_fill(0, $memberCount, array());
        if (!empty($params['include_student_name'])) {
            for ($i=0; $i<$memberCount; $i++) {
                array_push($grpMemberBlock[$i], $groupMembers[$i]['last_name']);
                array_push($grpMemberBlock[$i], $groupMembers[$i]['first_name']);
            }
        }
        if (!empty($params['include_student_id'])) {
            for ($i=0; $i<$memberCount; $i++) {
                array_push($grpMemberBlock[$i], $groupMembers[$i]['student_no']);
            }
        }
        return $grpMemberBlock;
    }
}

