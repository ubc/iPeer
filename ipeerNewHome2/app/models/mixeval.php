<?php
/* SVN FILE: $Id: mixeval.php 581 2011-06-20 18:17:41Z tonychiu $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 581 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/28 18:31:49 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Mixeval
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */

App::import('Model', 'EvaluationBase');

class Mixeval extends EvaluationBase
{
  const TEMPLATE_TYPE_ID = 4;
  var $name = 'Mixeval';
  // use default table
  var $useTable = null;

  var $hasMany = array(
                  'Event' =>
                     array('className'   => 'Event',
                           'conditions'  => array('Event.event_template_type_id' => self::TEMPLATE_TYPE_ID),
                           'order'       => '',
                           'foreignKey'  => 'template_id',
                           'dependent'   => true,
                           'exclusive'   => false,
                           'finderSql'   => ''
                          ),
                  'Question' =>
                    array('className' => 'MixevalsQuestion',
                          'foreignKey' => 'mixeval_id',
                          'dependent' => true,
                          'exclusive' => true,
                          'order'     => array('question_num' => 'ASC', 'id' => 'ASC'),
                         ),
                     );
  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['lickert_question_max'] = sprintf('SELECT count(*) as lickert_question_max FROM mixevals_questions as q WHERE q.mixeval_id = %s.id AND q.question_type LIKE "S"', $this->alias);
    $this->virtualFields['prefill_question_max'] = sprintf('SELECT count(*) as prefill_question_max FROM mixevals_questions as q WHERE q.mixeval_id = %s.id AND q.question_type LIKE "T"', $this->alias);
    $this->virtualFields['total_question'] = sprintf('SELECT count(*) as total_question FROM mixevals_questions as q WHERE q.mixeval_id = %s.id', $this->alias);
    $this->virtualFields['total_marks'] = sprintf('SELECT sum(multiplier) as sum FROM mixevals_questions as q WHERE q.mixeval_id = %s.id', $this->alias);
  }

  /**
   * saveAllWithDescription save the mixed evaluation with all questions 
   * including the descriptions in lickert questions
   * 
   * @param array $data the array of data to be saved
   * @access public
   * @return boolean
   */
  function saveAllWithDescription($data) {
  }

	//sets the current userid and merges the form values into the data array
	/*function prepData($tmp=null, $userid){

//		$tmp = array_merge($tmp['data']['Mixeval'], $tmp['form']);
    $ttlQuestionNo = $tmp['data']['Mixeval']['total_question'];
    $questions = array();
    for ($i = 1; $i < $ttlQuestionNo; $i++) {
      //Format questions for mixed eval
      $question['question_num'] = $i;
      $question['title'] = $tmp['data']['Mixeval']['title'.$i];
      isset($tmp['data']['Mixeval']['text_instruction'.$i])? $question['instructions'] = $tmp['data']['Mixeval']['text_instruction'.$i] : $question['instructions'] = null;
      $question['question_type'] = $tmp['data']['Mixeval']['question_type'.$i];
      isset($tmp['data']['Mixeval']['text_require'.$i])? $question['required'] = $tmp['data']['Mixeval']['text_require'.$i] : $question['required'] = 0;
      isset($tmp['form']['criteria_weight_'.$i])? $question['multiplier'] = $tmp['form']['criteria_weight_'.$i] : $question['multiplier'] = 0;
      $question['scale_level'] = $tmp['data']['Mixeval']['scale_max'];
      isset($tmp['data']['Mixeval']['response_type'.$i])? $question['response_type'] = $tmp['data']['Mixeval']['response_type'.$i] : $question['response_type'] = null;
      $questions[$i]['MixevalsQuestion'] = $question;

      //Format lickert descriptors
      if ($question['question_type'] == 'S') {
        for ($j = 1; $j <= $question['scale_level']; $j++) {
         $desc['question_num'] = $question['question_num'];
         $desc['scale_level'] = $j;

        // Make sure empty strings cause no php errors.
         $descriptor = isset($tmp['data']['Mixeval']['criteria_comment_'.$question['question_num'].'_'.$j]) ?
                             $tmp['data']['Mixeval']['criteria_comment_'.$question['question_num'].'_'.$j] : "";
         $desc['descriptor'] = $descriptor;
         $questions[$i]['MixevalsQuestion']['descriptor'][$j] = $desc;
        }

      }

    }

		return $questions;
	}*/

  function compileViewData($mixeval=null) {
  	
    $this->MixevalsQuestion = new MixevalsQuestion;
    $this->MixevalsQuestionDesc = new MixevalsQuestionDesc;

    $mixeval_id = $mixeval['Mixeval']['id'];
    $mixEvalDetail = $this->MixevalsQuestion->getQuestion($mixeval_id);
    $tmp = array();

    if (!empty($mixEvalDetail)) {
      foreach ($mixEvalDetail as $row) {
        $evalQuestion = $row['MixevalsQuestion'];
        $this->filter($evalQuestion);
        $tmp['questions'][$evalQuestion['question_num']] = $evalQuestion;
        if ($evalQuestion['question_type'] == 'S') {
          //Retrieve the lickert descriptor
          $descriptors = $this->MixevalsQuestionDesc->getQuestionDescriptor($row['MixevalsQuestion']['id']);
          $tmp['questions'][$evalQuestion['question_num']]['descriptors'] = $descriptors;
        }
      }
    }
    $mixEvalDetail = array_merge($mixeval,$tmp);

    return $mixEvalDetail;
  }

  function compileViewDataShort($mixeval=null) {
    $this->MixevalsQuestion = new MixevalsQuestion;
    $this->MixevalsQuestionDesc = new MixevalsQuestionDesc;

    $mixeval_id = $mixeval['Mixeval']['id'];
    $mixEvalDetail = $this->MixevalsQuestion->getQuestion($mixeval_id);
    if(!empty($mixeval['Question'])) {
            $mixeval['Question'] = $mixEvalDetail;
    }
    return $mixeval;
  }

  //Filter function from Output Component
  function filter(&$data) {
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
    if(is_array($data)) {
      foreach($data as $key=>$value) {
        $data[$key] = $this->filter($value);
      }
    } else {
      $data = preg_replace($search, $replace, $data);
    }
    return $data;
  }

  
}
