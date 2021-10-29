<?php
App::import('Model', 'EvaluationBase');
App::import('Model', 'MixevalQuestion');
App::import('Model', 'MixevalQuestionDesc');
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

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
    public $actsAs = array('Containable', 'Traceable');

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
        $this->virtualFields['peer_question'] = sprintf('SELECT count(*) as peer_question FROM mixeval_questions as q WHERE q.mixeval_id = %s.id AND q.self_eval = 0', $this->alias);
        $this->virtualFields['total_question'] = sprintf('SELECT count(*) as total_question FROM mixeval_questions as q WHERE q.mixeval_id = %s.id', $this->alias);
        $this->virtualFields['total_marks'] = sprintf('SELECT IFNULL(SUM(multiplier),0) as sum FROM mixeval_questions as q WHERE q.mixeval_id = %s.id AND q.required = 1 AND q.self_eval = 0', $this->alias);
        $this->virtualFields['self_eval'] = sprintf('SELECT count(*) as count FROM mixeval_questions as q WHERE q.mixeval_id = %s.id AND q.self_eval = 1', $this->alias);
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
            '@&(copy|#169);@i');

        $replace = array ('','','','','','','<br/>','"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169));
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->filter($value);
            }
        } else {
            $data = preg_replace($search, $replace, $data);
            // Decode the &#[0-9]+; pattern
            $data = preg_replace_callback('/&#(\d+);/m', function($matches) {
                return chr($matches[1]);
            }, $data);
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
        $eval = $this->find('first', array( 'conditions' => array($this->alias.'.id' => $id ), 'contain' => 'MixevalQuestion') );

        return $eval;
    }

    /**
     * getBelongingOrPublic
     * Returns the evaluations made by this user, and any other public ones.
     * Optionally include an additional evaluation by id that bypasses ownership and public restrictions.
     *
     * Note duplicate in evaluation_base. Unfortunately, evaluation_base
     * causes other problems for some reason, so can't inherit from it, just
     * copy and pasting the code here for now.
     *
     * @param mixed $user_id
     * @param mixed $include_id
     *
     * @access public
     * @return void
     */
    function getBelongingOrPublic($user_id, $include_id=NULL)
    {
        if (!is_numeric($user_id)) {
            return false;
        }

        if (!is_null($include_id) && !is_numeric($include_id)) {
            return false;
        }

        $conditions = array(
            'creator_id' => $user_id,
            'availability' => 'public',
        );
        if (!is_null($include_id)) {
            $conditions['id'] = $include_id;
        }
        $conditions = array('OR' => $conditions);
        return $this->find('list', array('conditions' => $conditions, 'fields' => array('name'), 'order' => 'name'));
    }

    /**
     * formatPenaltyArray return the array that student has penalty. key will
     * be the user id and value will be the penalty. The student without
     * penalty will be value 0.
     *
     * @param mixed $eventId      event id
     * @param mixed $groupId      group id
     * @param mixed $groupMembers group members
     *
     * @access public
     * @return array
     */
    function formatPenaltyArray($eventId, $groupId, $groupMembers)
    {
        $this->Penalty = ClassRegistry::init('Penalty');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');

        $memberIds = array_keys($groupMembers);

        // find the event
        $event = $this->Event->findById($eventId);

        // not due yet. no penalty
        if ($event['Event']['due_in'] >= 0) {
            return array_fill_keys($memberIds, 0);
        }

        // assign penalty to groupMember if they submitted late or never submitted by release_date_end
        $conditions = array(
            'EvaluationSubmission.event_id' => $eventId,
            'GroupEvent.group_id' => $groupId,
            'submitter_id' => $memberIds,
        );
        $submissions = $this->EvaluationSubmission->find('all', array('conditions' => $conditions));

        return $this->Penalty->getPenaltyForMembers($memberIds, $event['Event'], $submissions);
    }

    /**
     * Called after every deletion operation.
     *
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#afterDelete-1055
     */
    function afterDelete() {
        parent::afterDelete();

        CaliperHooks::mixeval_after_delete($this);
    }


    /**
     * Called before every deletion operation.
     *
     * @param boolean $cascade If true records that depend on this record will also be deleted
     * @return boolean True if the operation should continue, false if it should abort
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#beforeDelete-1054
     */
     function beforeDelete($cascade = true) {
        CaliperHooks::mixeval_before_delete($this);
        return parent::beforeDelete($cascade);
     }
}
