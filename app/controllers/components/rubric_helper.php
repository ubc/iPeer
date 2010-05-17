<?php
/* SVN FILE: $Id: rubric_helper.php,v 1.2 2006/07/18 21:38:54 davychiu Exp $ */
/*
 * To use your Model’s inside of your components, you can create a new instance like this:
 *  $this->foo = new Foo;
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class RubricHelperComponent extends Object
{
  function compileViewData($tmp=null)
	{
	  $this->RubricsLom = new RubricsLom;
	  $this->RubricsCriteria = new RubricsCriteria;
	  $this->RubricsCriteriaComment = new RubricsCriteriaComment;

		$rubric_id = $tmp['Rubric']['id'];

		$data = $this->RubricsLom->getLOM($rubric_id, $tmp['Rubric']['lom_max']);
		$tmp1 = array_merge($tmp['Rubric'],$data);

		$data = $this->RubricsCriteria->getCriteria($rubric_id);
		$tmp2 = array_merge($tmp1,$data);

		$data = $this->RubricsCriteriaComment->getCriteriaComment($rubric_id, $tmp['Rubric']['criteria'], $tmp['Rubric']['lom_max']);
		$tmp3 = array_merge($tmp2,$data);

		//print_r($tmp3);
		return $tmp3;
	}

}
?>