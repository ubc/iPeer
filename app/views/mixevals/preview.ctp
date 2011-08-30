<?php
print_r(array_keys($this->params));
echo "<br><br>";
print_r(array_values($this->params)); 
$params = array('controller'=>'mixevals', 'data'=>$this->data,'LOM_field'=>$this->data['Rubric']['lom_max'],'criteria_field'=>$this->data['Rubric']['criteria']);
echo $this->element('mixevals/ajax_mixeval_preview', $params);
?>
