<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/07/17 18:38:41 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Users
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class EvaltoolsController extends AppController
{
/**
 * This controller does not use a model
 *
 * @var $uses
 */
  var $uses =  array('Event', 'SimpleEvaluation', 'Rubric', 'Mixeval', 'Survey');
	var $page;
	var $Sanitize;
	var $functionCode = 'EVAL_TOOL';

	function __construct()
	{
		$this->Sanitize = new Sanitize;
 		$this->pageTitle = 'Evaluation Tools';
		parent::__construct();
	}

	function index($evaltool='') {
		//Disable the autorender, base the role to render the custom home
		$this->autoRender = false;

    $this->set('event', $this->Event);

    //General Evaluation Tools Rendering for Admin and Instructor
    switch ($evaltool) {

      case "simpleevaluations" :
        $this->redirect('/simpleevaluations/index/');
        break;

      case "rubrics" :
        $this->redirect('/rubrics/index/');
      break;

      case "surveys" :
        $this->redirect('/surveys/index/');
      break;

     default :
        $this->showAll();
     break;

   }
	}

  function showAll()
  {
    $simpleEvalData = $this->SimpleEvaluation->findAll('creator_id = '.$this->rdAuth->id);
    $this->set('simpleEvalData', $simpleEvalData);

    $rubricData = $this->Rubric->findAll('creator_id = '.$this->rdAuth->id);
    $this->set('rubricData', $rubricData);

    $mixevalData = $this->Mixeval->findAll('creator_id = '.$this->rdAuth->id);
    $this->set('mixevalData', $mixevalData);

    $surveyData = $this->Survey->findAll('Survey.creator_id = '.$this->rdAuth->id);
    $this->set('surveyData', $surveyData);

    $this->render('index');
  }
}
