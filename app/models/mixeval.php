<?php
App::import('Model', 'EvaluationBase');
App::import('Model', 'MixevalQuestion');
App::import('Model', 'MixevalQuestionDesc');

/**
 * Mixeval
 *
 * @uses EvaluationBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Mixeval extends AppModel
{
    const TEMPLATE_TYPE_ID = 4;
    public $name = 'Mixeval';
    public $actsAs = array('Traceable');

    public $hasMany = array(
        'Event' => array(
            'className'   => 'Event',
            'conditions'  => array('Event.event_template_type_id' => self::TEMPLATE_TYPE_ID),
            'order'       => '',
            'foreignKey'  => 'template_id',
            'dependent'   => true,
            'exclusive'   => false,
            'finderSql'   => ''
        ),
        'MixevalQuestion' => array(
            'className' => 'MixevalQuestion',
            'foreignKey' => 'mixeval_id',
            'dependent' => true,
            'order'     => array('question_num' => 'ASC', 'id' => 'ASC'),
        ),
    );
    
    public $validate = array(
        'name' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Another evaluation already exists with this name.'
            ),
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter an evaluation name.'
            ),
        ),
        'availability' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select an availability option.'
        ),
    );

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['total_question'] = sprintf('SELECT count(*) as total_question FROM mixeval_questions as q WHERE q.mixeval_id = %s.id', $this->alias);
        $this->virtualFields['total_marks'] = sprintf('SELECT sum(multiplier) as sum FROM mixeval_questions as q WHERE q.mixeval_id = %s.id', $this->alias);
    }

    /**
     * compileViewData
     *
     * @param bool $mixeval
     *
     * @access public
     * @return void
     */
    function compileViewData($mixeval=null)
    {
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
        $this->MixevalQuestionDesc = ClassRegistry::init('MixevalQuestionDesc');

        $mixeval_id = $mixeval['Mixeval']['id'];
        $mixEvalDetail = $this->MixevalQuestion->getQuestion($mixeval_id);
        $tmp = array();

        if (!empty($mixEvalDetail)) {
            foreach ($mixEvalDetail as $row) {
                $evalQuestion = $row['MixevalQuestion'];
                $this->filter($evalQuestion);
                $tmp['questions'][$evalQuestion['question_num']] = $evalQuestion;
                if ($evalQuestion['mixeval_question_type_id'] == '1') {
                    //Retrieve the lickert descriptor
                    $descriptors = $this->MixevalQuestionDesc->getQuestionDescriptor($row['MixevalQuestion']['id']);
                    $tmp['questions'][$evalQuestion['question_num']]['descriptors'] = $descriptors;
                }
            }
        }
        $mixEvalDetail = array_merge($mixeval, $tmp);

        return $mixEvalDetail;
    }


    /**
    * copy generate a copy of mixeval with specific ID. The generated copy
    * is cleaned up by removing all the IDs in it
    *
    * @param mixed $id source rubric ID
    *
    * @access public
    * @return array copy of mixeval
    */
    function copy($id) {
        $data = $this->find('first', array('conditions' => array('id' => $id),
            'contain' => array('Question.Description',
            )));

        $data['Mixeval']['name'] = __('Copy of ', true).$data['Mixeval']['name'];

        if (null != $data) {
            unset ($data['Mixeval']['id'],
                $data['Mixeval']['creator_id'],
                $data['Mixeval']['created'],
                $data['Mixeval']['updater_id'],
                $data['Mixeval']['modified']);

            for ($i = 0; $i < count($data['Question']); $i++) {
                unset ($data['Question'][$i]['id'],
                    $data['Question'][$i]['mixeval_id']);
                if ('1' == $data['Question'][$i]['mixeval_question_type_id']) {
                    for ($j = 0; $j < count($data['Question'][$i]['Description']); $j++) {
                        unset($data['Question'][$i]['Description'][$j]['id'],
                            $data['Question'][$i]['Description'][$j]['question_id']);
                    }
                }
            }
        }

        return $data;
    }


    /**
     * filter Filter function from Output Component
     *
     * @param mixed &$data
     *
     * @access public
     * @return void
     */
    function filter(&$data)
    {
        $search = array (
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<object[^>]*?>.*?</object>@si', // Strip out objects
            '@<iframe[^>]*?>.*?</iframe>@si', // Strip out iframes
            '@<applet[^>]*?>.*?</applet>@si', // Strip out applets
            '@<meta[^>]*?>.*?</meta>@si', // Strip out meta
            '@<form[^>]*?>.*?</form>@si', // Strip out forms
            '@([\n])@',                // convert to <br/>
            '@&(quot|#34);@i',                // Replace HTML entities
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@&(cent|#162);@i',
            '@&(pound|#163);@i',
            '@&(copy|#169);@i',
            '@&#(\d+);@e');                    // evaluate as php

        $replace = array ('','','','','','','<br/>','"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169),'chr(\1)');
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->filter($value);
            }
        } else {
            $data = preg_replace($search, $replace, $data);
        }
        return $data;
    }

    /**
     * getEvaluation
     *
     * @param mixed $id id
     * 
     * @access public
     * @return void
     */
    public function getEvaluation($id)
    {
        $eval = $this->find('first', array('conditions' => array($this->alias.'.id' => $id), 'contain' => 'Question'));

        return $eval;
    }

    /**
     * getBelongingOrPublic
     * Returns the evaluations made by this user, and any other public ones.
     *
     * Note duplicate in evaluation_base. Unfortunately, evaluation_base
     * causes other problems for some reason, so can't inherit from it, just
     * copy and pasting the code here for now.
     *
     * @param mixed $user_id
     *
     * @access public
     * @return void
     */
    function getBelongingOrPublic($user_id)
    {
        if (!is_numeric($user_id)) {
            return false;
        }

        $conditions = array('creator_id' => $user_id);
        $conditions = array('OR' => array_merge(array('availability' => 'public'), $conditions));
        return $this->find('list', array('conditions' => $conditions, 'fields' => array('name')));
    }
    
    /**
     * formatPenaltyArray return the array that student has penalty. key will
     * be the user id and value will be the penalty. The student without
     * penalty will be value 0.
     *
     * @param mixed $groupMembers group members
     * @param mixed $eventId      event id
     * @param mixed $groupId      group id
     *
     * @access public
     * @return void
     */
    function formatPenaltyArray($groupMembers, $eventId, $groupId)
    {
        $this->Penalty = ClassRegistry::init('Penalty');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');

        $memberIds = array_keys($groupMembers);
        $userPenalty = array_fill_keys($memberIds, 0);

        // find the event
        $event = $this->Event->findById($eventId);

        // not due yet. no penalty
        if ($event['Event']['due_in'] >= 0) {
            return $userPenalty;
        }

        // storing the timestamp of the due date of the event
        $event_due = strtotime($event['Event']['due_date']);
        // assign penalty to groupMember if they submitted late or never submitted by release_date_end
        $submissions = $this->EvaluationSubmission->find('all', array(
            'conditions' => array('submitter_id' => $memberIds, 'EvaluationSubmission.event_id' => $eventId),
            'contain' => array(
                'GroupEvent' => array(
                    'conditions' => array('GroupEvent.group_id' => $groupId, 'GroupEvent.event_id' => $eventId),
                ),
            )
        ));

        foreach ($submissions as $submission) {
            // there is submission - may be on time or late
            $late_diff = strtotime($submission['EvaluationSubmission']['date_submitted']) - $event_due;
            // late
            if (0 < $late_diff) {
                $days_late = $late_diff/(24*60*60);
                $penalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                $userPenalty[$submission['EvaluationSubmission']['submitter_id']] = $penalty['Penalty']['percent_penalty'];
            }
        }

        // no submission - if now is after release date end then - gets final deduction
        $penalty = $this->Penalty->getPenaltyFinal($eventId);
        $noSubmissions = array_intersect($memberIds, Set::extract($submissions, '/EvluationSubmission/submitter_id'));
        foreach ($noSubmissions as $userId) {
            $userPenalty[$userId] = $penalty['Penalty']['percent_penalty'];
        }

        return $userPenalty;
    }

}
