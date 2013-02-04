<?php
/**
 * MixevalQuestionDesc
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalQuestionDesc extends AppModel
{
    public $name = 'MixevalQuestionDesc';

    public $belongsTo = array(
        'MixevalQuestion' => array(
            'className' => 'MixevalQuestion',
            'foreignKey' => 'question_id'
        )
    );

    /**
     * Saves Mix evaluation question descriptions to database
     *
     * @param Array $data         Mixeval_question data array
     * @param Array $question_ids Array of question_ids for Mixeval_questions
     *
     * @access public
     * @return void
     */
    function insertQuestionDescriptor($data, $question_ids)
    {
        foreach ($data as $row) {
            if (isset($row['Description'])) {
                $descriptors =  $row['Description'];
                foreach ($question_ids as $question_id) {
                    if ($question_id['MixevalQuestion']['question_num'] == $row['question_num']) {
                        $q_id = $question_id['MixevalQuestion']['id'];
                    }
                }

                foreach ($descriptors as $value) {
                    $desc = $value;
                    //$desc['mixeval_id'] = $id;
                    $desc['question_id'] = $q_id;
                    $this->save($desc);
                    $this->id = null;
                }
            }
        }
    }


    // called by mixevals controller during an edit of an
    // existing mixeval question comment(s)
  /* FUNCTION NOT BEING USED
  function updateQuestionDescriptor($id=null, $data)
{
    $this->delete($id);
    $this->insertQuestionDescriptor($id, $data);
  }*/

    //  // called by the delete function in the controller
    //  function deleteQuestionDescriptors( $id )
    //            {
    //
    //
    //
    //  	$this->query('DELETE
    //  				  FROM mixevals_question_descs
    //  				  WHERE question_id IN
    //  				  	(SELECT id
    //  				  	FROM mixevals_questions
    //  				  	WHERE id='.$id.')');
    //  }

    /**
     * getQuestionDescriptor function to return the question's descriptor
     *
     * @param mixed $questionId
     *
     * @access public
     * @return void
     */
    function getQuestionDescriptor($questionId)
    {
/*		$data = $this->find('all','mixeval_id='.$mixevalId.' AND question_num='.$questionNum, null, 'scale_level ASC');
return $data;*/
    /*return $this->find('all', array(
            'conditions' => array('mixeval_id' => $mixevalId, 'question_num' => $questionNum),
            'order' => 'MixevalQuestionDesc.id ASC'
    ));*/
        return $this->find('all', array('conditions' => array('question_id' => $questionId),
            'order' => 'MixevalQuestionDesc.id ASC'));
    }


    /**
     * getCommentQuestionsByMixEvalId Gets the mixeval comment type questions.
     *
     * @param INT $mixEvalId mixeval id.
     *
     * @access public
     * @return void
     */
    function getCommentQuestionsByMixEvalId($mixEvalId)
    {
        return $this->find('all', array('conditions' => array(
            'mixeval_id' => $mixEvalId,
            'mixeval_question_type_id' => '2')));
    }
}
