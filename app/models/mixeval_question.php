<?php
/**
 * MixevalQuestion
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalQuestion extends AppModel
{
    public $name = 'MixevalQuestion';

    public $belongsTo = array('MixevalQuestionType');

    public $hasMany = array(
        'MixevalQuestionDesc' =>
        array('className'   => 'MixevalQuestionDesc',
            'order'       => '',
            'foreignKey'  => 'question_id',
            'dependent'   => true,
            'exclusive'   => true,
            'finderSql'   => ''
        ),
    );

    public $validate = array(
        'multiplier' => array(
            'rule' => 'numeric',
            'message' => 'Please enter a number for marks.'
        )
    );

    /**
     * Saves Mix evaluation questions to database
     *
     * @param int   $id   id of mix corresponding mix evaluation
     * @param array $data array of the mixevals questions to be inserted
     *
     * @access public
     * @return void
     */
    function insertQuestion($id=null, $data=null)
    {
        if (!is_null($id) && !is_null($data)) {
            foreach ($data as $value) {
                $value['mixeval_id'] = $id;
                $this->save($value);
                $this->id = null;
            }
        } else {
            return false;
        }
    }

    /**
     * deleteQuestions called by the delete function in the controller
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function deleteQuestions($id)
    {
        //  	$this->query('DELETE FROM mixevals_questions WHERE mixeval_id='.$id);
        $this->delete($id);
    }

    /**
     * Get corresponding mix evaluation question corresponding to some mix evaluation
     *
     * @param int  $mixEvalId    mix evaluation id
     * @param bool $questionType question type
     *
     * @access public
     * @return void
     */
    function getQuestion($mixEvalId=null, $questionType=null)
    {
        if (isset($questionType)) {
            $condition = array('mixeval_id' => $mixEvalId, 'mixeval_question_type_id' => $questionType);
        } else {
            $condition = array('mixeval_id' => $mixEvalId);
        }

        return $this->find('all', array('conditions' => $condition, 'order' => array('question_num ASC')));
    }
}
