<?php
App::import('Controller', 'Event');
App::import('Model', 'Event');
//$tmptitle=$this->params['form']['newtitle'];

$this->Event= & ClassRegistry::init('Event');

    //$Event = new EventsController;
    //Load model, components...
    //$Event->constructClasses();
$data = $this->Event->field('title', array("title = '".mysql_real_escape_string($this->params['form']['newtitle'])."'"));
$params = array('controller'=>'events', 'data'=>$data, 'fieldvalue'=>$this->params['form']['newtitle']);
echo $this->element('events/ajax_title_validate', $params);
?>
