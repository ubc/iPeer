<?php

/**
 * EvaluationBase
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationBase extends AppModel
{
    public $name = 'EvaluationBase';
    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');
    public $useTable = false;
    // suppress the warning when using "cake schema generate"
    const TEMPLATE_TYPE_ID = 0;

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
        $c = get_class($this);
        $this->virtualFields['event_count'] = sprintf('SELECT count(*) as count FROM events as event WHERE event.event_template_type_id = %d AND event.template_id = %s.id', constant($c.'::TEMPLATE_TYPE_ID'), $this->alias);
    }


    /**
     * beforeSave
     *
     *
     * @access public
     * @return void
     */
    function beforeSave()
    {
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['name'])) {
            $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
            return false;
        }

        // Remove any signle quotes in the name, so that custom SQL queries are not confused.
        $this->data[$this->name]['name'] =
            str_replace("'", "", $this->data[$this->name]['name']);

        //check the duplicate name
        if (empty($this->data[$this->name]['id']) && !$this->__checkDuplicateName()) {
            return false;
        }
        //check if questions are entered
        if (!empty($this->data['Question'])&&$this->name =='Mixeval') {
            foreach ($this->data['Question'] as $row) {
                if ($row['question_type']== 'S' &&(empty($row['Description'] ) || (count($row['Description'])) < 2)) {
                    $this->errorMessage = "Please add at least two descriptors for each of the Lickert questions.";
                    return false;
                }
            }
        }

        if (empty($this->data['Question'])&&($this->name =='Mixeval')) {
            $this->errorMessage = "Please add at least one question for this " . $this->name . ".";
            return false;
        }
        return parent::beforeSave();
    }


    /**
     * __checkDuplicateName
     * Validation check on duplication of name
     *
     *
     * @access protected
     * @return void
     */
    function __checkDuplicateName()
    {
        $result = $this->find('first', array('conditions' => array('name' => $this->data[$this->name]['name'])));
        if ($result) {
            $this->errorMessage='Duplicate name found. Please change the name.';
            return false;
        }

        return true;
    }



    /**
     * getBelongingOrPublic
     * Returns the evaluations made by this user, and any other public ones.
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
        if ($this->name != 'SimpleEvaluation') {
            $conditions = array('OR' => array_merge(array('availability' => 'public'), $conditions));
        }
        return $this->find('list', array('conditions' => $conditions, 'fields' => array('name')));
    }


    /**
     * getEventCount
     *
     * @param mixed $evaluation_id
     *
     * @access public
     * @return void
     */
    function getEventCount($evaluation_id)
    {
        $eval = $this->read('event_count', $evaluation_id);
        return $eval[$this->alias]['event_count'];
    }

}
