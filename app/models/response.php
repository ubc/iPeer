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
 * Survey
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Response extends AppModel {
  var $name = 'Response';
  var $belongsTo = array('Question');

	// saves all the responses in the database
	function linkResponses($question_id, $data)
	{
		for( $i = 1; $i <= $data['Question']['count']; $i++ ){
			if( !empty($data['Question']['response_'.$i]) ){
				$tmpData['response'] = $data['Question']['response_'.$i];
				$tmpData['question_id'] = $question_id;

				$this->save($tmpData);
				$this->id=null;
			}
		}
	}

	// prepares the data var with all the response info from the form for display
	function fillResponse($data)
	{
		for( $i=0; $i<$data['count']; $i++ ){
			$tmp = $this->find('all', array('conditions' => array('question_id' => $data[$i]['Question']['id']),
                                      'fields' => array('response','id')));
			$count = count($tmp);			
			for( $j=0; $j<$count; $j++ ){
				if( !empty($tmp))
					$data[$i]['Question']['Responses']['response_'.$j]['response'] = $tmp[$j]['Response']['response'];
					$data[$i]['Question']['Responses']['response_'.$j]['id'] = $tmp[$j]['Response']['id'];
			}
		}
		return $data;
	}

	function getResponseByQuestionId($questionId) {
    $tmp = $this->find('all', array('conditions' => array('question_id' => $questionId),
                                    'fields' => array('response','id')));
    $data = array();
		for( $j=0; $j< count($tmp); $j++ ) {
				$data['Responses']['response_'.$j]['response'] = $tmp[$j]['Response']['response'];
				$data['Responses']['response_'.$j]['id'] = $tmp[$j]['Response']['id'];
	  }
	  return $data;
	}

	function countResponses($questionId)
	{
		return $this->find('count', array('conditions' => array('question_id' => $questionId)));
	}

	function prepData($data, $questionID)
	{
		$tmp = $this->find('all', array('conditions' => array('question_id' => $questionId), 
                                    'fields' => array('response')));

		for( $i=0; $i<$data['Question']['count']; $i++ ){
			$data['Question']['response_'.($i+1)] = $tmp[$i]['Response']['response'];
		}
		return $data;
	}

	function getResponseById($id=null) {
	  $tmp = $this->find('first', array('conditions' => array('id' => $id),
                                      'fields' => array('response')));
	  return $tmp['Response']['response'];
	}
	
	function getResponseId($questionId=null, $response=null){
		$findResult = $this->find('first', array('conditions'=>array('question_id'=>$questionId, 'response'=>$response)));
		return $findResult['Response']['id'];
	}
}

?>
