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
    public $uses =  array('SimpleEvaluation', 'Rubric', 'Mixeval', 'Survey', 'EmailTemplate', 'Event');
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
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        $this->set('title_for_layout', __('Evaluation Tools',true));
    }

    /**
     * index
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
        foreach ($mixevalData as &$mixeval) {
            $mixeval['Mixeval']['event_count'] = $this->Event->find('count',
                array('conditions' => 
                    array('event_template_type_id' => 4,
                        'template_id' => $mixeval['Mixeval']['id'])
                )
            );
        }
        $this->set('mixevalData', $mixevalData);

        $surveyData = $this->Survey->findAllByCreatorId($this->Auth->user('id'));
        $this->set('surveyData', $surveyData);

        $emailTemplates = $this->EmailTemplate->findAllbyCreatorId($this->Auth->user('id'));
        $this->set('emailTemplates', $emailTemplates);
    }
}
