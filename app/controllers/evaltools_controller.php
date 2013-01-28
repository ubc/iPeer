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
    function index()
    {
        $simpleEvalData = $this->SimpleEvaluation->findAllByCreatorId($this->Auth->user('id'));
        $this->set('simpleEvalData', $simpleEvalData);

        $rubricData = $this->Rubric->findAllByCreatorId($this->Auth->user('id'));
        $this->set('rubricData', $rubricData);

        $mixevalData = $this->Mixeval->findAllByCreatorId($this->Auth->user('id'));
        $this->set('mixevalData', $mixevalData);

        $surveyData = $this->Survey->findAllByCreatorId($this->Auth->user('id'));
        $this->set('surveyData', $surveyData);

        $emailTemplates = $this->EmailTemplate->findAllbyCreatorId($this->Auth->user('id'));
        $this->set('emailTemplates', $emailTemplates);
    }
}
