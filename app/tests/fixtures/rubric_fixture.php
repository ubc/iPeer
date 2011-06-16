<?php

class RubricFixture extends CakeTestFixture {
  var $name = 'Rubric';
  //var $import = 'Rubric';
  var $fields = array(
 		'id' => array('type' => 'integer', 'key' => 'primary'),
  		'name' => array('type', 'string'),
 		'zero_mark' => array('type' => 'integer'),
 		'lom_max' => array('type' => 'integer'),
 		'criteria' => array('type' => 'integer')
 ); 
  var $records = array(
  		array('id' => 4, 
  			  'name' => 'Some Rubric',
  			  'zero_mark' => 0,
  			  'lom_max' => 2,
  			  'criteria' => 2
  			 )
  		);
}
?>