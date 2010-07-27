<?php
/* SVN FILE: $Id$ */
/*
 * To use your Models inside of your components, you can create a new instance like this:
 *  $this->foo = new Foo;
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class RubricHelperComponent extends Object
{
    function compileViewData($tmp) {
        $this->RubricsLom = new RubricsLom;
        $this->RubricsCriteria = new RubricsCriteria;
        $this->RubricsCriteriaComment = new RubricsCriteriaComment;

        $rubric_id = $tmp['Rubric']['id'];

        $data = $this->RubricsLom->getLOM($rubric_id, $tmp['Rubric']['lom_max']);
        $tmp1 = array_merge($tmp['Rubric'],$data);

        $data = $this->RubricsCriteria->getCriteria($rubric_id);
        $tmp2 = array_merge($tmp1,$data);

        // add some empty ones if needed
        $criteria_count = count($data)/2;

        for(;$criteria_count < $tmp['Rubric']['criteria'];$criteria_count++) {
            $tmp2['criteria'.($criteria_count+1)] = '';
            $tmp2['criteria_weight_'.($criteria_count+1)] = 1;
        }

        $data = $this->RubricsCriteriaComment->getCriteriaComment($rubric_id, $tmp['Rubric']['criteria'], $tmp['Rubric']['lom_max']);
        $tmp3 = array_merge($tmp2,$data);

        /* Now, replace all the elements of this database array with the submitted array,
           unless there are missing */
        $submitedRubric = $tmp['Rubric'];
        foreach ($tmp3 as $key => $value) {

            if (!empty($submitedRubric[$key])) {
                //echo "<b>$key Replacing $tmp3[$key] with $submitedRubric[$key]</b><br />";
                $tmp3[$key] = $submitedRubric[$key]; // Copy the submited value over, overwriting the one got from the database.
            }
        }

        return $tmp3;

    }

}
?>
