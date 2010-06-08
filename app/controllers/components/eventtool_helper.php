<?php
/* SVN FILE: $Id$ */
/*
 *
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class EventtoolHelperComponent extends Object  
{
  function checkEvaluationToolInUse($evalTool=null, $templateId=null)
  {
    //Get the target event
    $this->Event = new Event;


    return $this->Event->checkEvaluationToolInUse($evalTool, $templateId);
  }

}

?>