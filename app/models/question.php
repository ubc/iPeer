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
 * @lastmodified $Date: 2006/06/20 18:44:18 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Question
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Question extends AppModel
{
  var $name = 'Question';
  var $displayField = 'prompt';

  var $hasMany = array('Response' =>
                       array('className' => 'Response',
                             'foreignKey' => 'question_id',
                             'dependent' => true)
                      );

  var $hasAndBelongsToMany = array('Survey' =>
                                   array('className'    =>  'Survey',
                                         'joinTable'    =>  'survey_questions',
                                         'foreignKey'   =>  'question_id',
                                         'associationForeignKey'    =>  'survey_id',
                                         'conditions'   =>  '',
                                         'order'        =>  '',
                                         'limit'        => '',
                                         'unique'       => true,
                                         'finderQuery'  => '',
                                         'deleteQuery'  => '',
                                         'dependent'    => false,
                                        ),
                       );

  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

  // prepares the data by moving varibles in the form to the data question sub array
  function prepData($data)
  {
    $data['data']['Question']['master'] = $data['form']['master'];
    $data['data']['Question']['type'] = $data['form']['type'];
    $data['data']['Question']['count'] = $data['form']['data']['Question']['count'];

    return $data;
  }

  // sets the data variable up with proper formating in the array for display
  function fillQuestion($data)
  {
    for( $i=0; $i<$data['count']; $i++ ){
      $data[$i]['Question'] = $this->find('all',array('conditions' => array('id' => $data[$i]['surveyQuestion']['question_id']),'fields' => array('prompt', 'type')));
	  //var_dump($data[$i]['Question'][0]['Question']);
      $data[$i]['Question'] = $data[$i]['Question'][0]['Question'];
      //var_dump($data[$i]['surveyQuestion']['number']);
      $data[$i]['Question']['number'] = $data[$i]['SurveyQuestion']['number'];
      $data[$i]['Question']['id'] = $data[$i]['surveyQuestion']['question_id'];
      $data[$i]['Question']['sq_id'] = $data[$i]['surveyQuestion']['id'];
	  unset($data[$i]['SurveyQuestion']);
    }

    return $data;
  }

  // delete old question references in each table
  function editCleanUp($question_id)
  {
  		$this->query('DELETE FROM questions WHERE id='.$question_id);
		$this->query('DELETE FROM responses WHERE question_id='.$question_id);
		$this->query('DELETE FROM survey_questions WHERE question_id='.$question_id);
  }

  function getTypeById($id=null) {
    $type = $this->find('first', array(
        'conditions' => array('Question.id' => $id),
        'fields' => array('type')
    ));
    return $type['Question']['type'];
  }
}

?>
