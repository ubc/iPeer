<?php
/**
 * EvaltoolsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaltoolsController extends AppController
{
    /**
     * This controller does not use a model
     *
     * @public $uses
     */
    public $uses =  array('SimpleEvaluation', 'Rubric', 'Mixeval', 'Survey', 'EmailTemplate');
    public $page;
    public $Sanitize;
    public $functionCode = 'EVAL_TOOL';

    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->set('title_for_layout', __('Evaluation Tools', true));
        parent::__construct();
    }


    /**
     * index
     *
     * @param bool $evaltool
     *
     * @access public
     * @return void
     */
    function index($evaltool = null)
    {
        //Disable the autorender, base the role to render the custom home
        $this->autoRender = false;

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

        case "emailtemplates" :
            $this->redirect('/emailtemplates/index/');
            break;

        default:
            $this->showAll();
            $this->render('index');
            break;
        }
    }


    /**
     * showAll
     *
     *
     * @access public
     * @return void
     */
    function showAll()
    {
        $simpleEvalData = $this->SimpleEvaluation->find('all', array('conditions' => array('creator_id' => $this->Auth->user('id'))));
        $this->set('simpleEvalData', $simpleEvalData);

        $rubricData = $this->Rubric->find('all', array('conditions' => array('creator_id' => $this->Auth->user('id'))));
        $this->set('rubricData', $rubricData);

        $mixevalData = $this->Mixeval->find('all', array('conditions' => array('creator_id' => $this->Auth->user('id'))));
        $this->set('mixevalData', $mixevalData);

        $surveyData = $this->Survey->find('all', array('conditions' => array('Survey.creator_id' => $this->Auth->user('id')),
            'contain' => false));
        $this->set('surveyData', $surveyData);

        $emailTemplates = $this->EmailTemplate->getMyEmailTemplate($this->Auth->user('id'));
        $this->set('emailTemplates', $emailTemplates);
    }

}
