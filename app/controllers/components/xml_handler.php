<?php
class XmlHandlerComponent extends Object
{
	var $components = array('rdAuth');

	function makeTMXml4($questions=null,$surveyId=null,$numGroups=null,$params=null) {
    $this->SurveyInput =& new SurveyInput;
    $this->Response =& new Response;
    $this->User =& new User;
    $this->params = $params;

    $doc = domxml_new_doc('1.0');
    //docroot
    $team_input = $doc->create_element('team_input');
    $team_input->set_attribute('num_groups',$numGroups);
    $doc->append_child($team_input);
    for ($i=1; $i <= count($questions); $i++) {
      if (in_array($questions[$i]['Question']['type'],array('M','C'))) {
        $question_id = $questions[$i]['Question']['id'];
        $type = $questions[$i]['Question']['type'] == 'C' ? 'CAO':'MC';
        $prompt = $questions[$i]['Question']['prompt'];
        //questions
        $question = $doc->create_element('question');
        $question->set_attribute('id',$question_id);
        $question->set_attribute('type',$type);
        $question->set_attribute('title',$prompt);
        $team_input->append_child($question);

        //weight
        $weight = $doc->create_element('weight');
        $weight->set_attribute('value',$this->params['form']['weight_'.$question_id]);
        $question->append_child($weight);
      }
    }
    $courseId = $this->rdAuth->courseId;
		$userData = $this->User->getEnrolledStudents($courseId, $fields=null);
	//	print_r($userData);
		foreach ($userData as $user) {
      //students
      $student = $doc->create_element('student');
      $student->set_attribute('username',$user['User']['student_no']);
      $team_input->append_child($student);

      for ($i=1; $i <= count($questions); $i++) {
        if (in_array($questions[$i]['Question']['type'],array('M','C'))) {
          $question_id = $questions[$i]['Question']['id'];
          //response
          $type = $questions[$i]['Question']['type'] == 'C' ? 'CAO':'MC';
          $response = $doc->create_element('response');
          $response->set_attribute('q_id',$question_id);
          $response->set_attribute('type',$type);
          $student->append_child($response);

          $responses = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $user['User']['id'],$question_id);
          //print_r($responses);
          if (count($responses) != 0) {
            for ($j=0; $j < count($responses); $j++) {
              $response_tmp = $responses[$j]['SurveyInput'];
              if ($response_tmp['response_text']==null && $response_tmp['response_id']==null) {
                //response/answer
                $value = $doc->create_element('value');
                $value->set_attribute('id',$response_tmp['id']);
                $value->set_attribute('answer',0);
                $response->append_child($value);
              } elseif ($response_tmp['response_text']=='' || $response_tmp['response_text']==null) {
                 //response/answer
                $value = $doc->create_element('value');
                $value->set_attribute('id',$response_tmp['id']);
                $value->set_attribute('answer',1);
                $response->append_child($value);
              } else {
                $mcResponse = explode('_',$response_tmp['response_text']);
                if (isset($mcResponse[1])) {
                  $mcTmp = $this->Response->find('id='.$mcResponse[1]);
                  if ($mcTmp['Response']['response']==$mcResponse[0]) {
                     //response/answer
                    $value = $doc->create_element('value');
                    $value->set_attribute('id',$response_tmp['id']);
                    //$value->setAttribute('answer',1);
                    $response->append_child($value);
                  }
                }
              }
            }
          } else {
            if ($type == 'MC') {
              $value = $doc->create_element('value');
              $value->set_attribute('id','');
              $response->append_child($value);
            }
          }
        }
      }
		}
		return $doc->dump_mem(true);
	}

	function makeTMXml5($questions=null,$surveyId=null,$numGroups=null,$params=null) {
    $this->SurveyInput =& new SurveyInput;
    $this->Response =& new Response;
    $this->User =& new User;
    $this->params = $params;

    $doc = new DOMDocument('1.0');
    //docroot
    $team_input = $doc->createElement('team_input');
    $team_input->setAttribute('num_groups',$numGroups);
    $doc->appendChild($team_input);
    for ($i=1; $i <= count($questions); $i++) {
      if (in_array($questions[$i]['Question']['type'],array('M','C'))) {
        $question_id = $questions[$i]['Question']['id'];
        $type = $questions[$i]['Question']['type'] == 'C' ? 'CAO':'MC';
        $prompt = $questions[$i]['Question']['prompt'];
        //questions
        $question = $doc->createElement('question');
        $question->setAttribute('id',$question_id);
        $question->setAttribute('type',$type);
        $question->setAttribute('title',$prompt);
        $team_input->appendChild($question);

        //weight
        $weight = $doc->createElement('weight');
        $weight->setAttribute('value',$this->params['form']['weight_'.$question_id]);
        $question->appendChild($weight);
      }
    }
    $courseId = $this->rdAuth->courseId;
		$userData = $this->User->getEnrolledStudents($courseId, $fields=null);
	//	print_r($userData);
		foreach ($userData as $user) {
      //students
      $student = $doc->createElement('student');
      $student->setAttribute('username',$user['User']['student_no']);
      $team_input->appendChild($student);

      for ($i=1; $i <= count($questions); $i++) {
        if (in_array($questions[$i]['Question']['type'],array('M','C'))) {
          $question_id = $questions[$i]['Question']['id'];
          //response
          $type = $questions[$i]['Question']['type'] == 'C' ? 'CAO':'MC';
          $response = $doc->createElement('response');
          $response->setAttribute('q_id',$question_id);
          $response->setAttribute('type',$type);
          $student->appendChild($response);

          $responses = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $user['User']['id'],$question_id);
          //print_r($responses);
          if (count($responses) != 0) {
            for ($j=0; $j < count($responses); $j++) {
              $response_tmp = $responses[$j]['SurveyInput'];
              if ($response_tmp['response_text']==null && $response_tmp['response_id']==null) {
                //response/answer
                $value = $doc->createElement('value');
                $value->setAttribute('id',$response_tmp['id']);
                $value->setAttribute('answer',0);
                $response->appendChild($value);
              } elseif ($response_tmp['response_text']=='' || $response_tmp['response_text']==null) {
                 //response/answer
                $value = $doc->createElement('value');
                $value->setAttribute('id',$response_tmp['id']);
                $value->setAttribute('answer',1);
                $response->appendChild($value);
              } else {
                $mcResponse = explode('_',$response_tmp['response_text']);
                if (isset($mcResponse[1])) {
                  $mcTmp = $this->Response->find('id='.$mcResponse[1]);
                  if ($mcTmp['Response']['response']==$mcResponse[0]) {
                     //response/answer
                    $value = $doc->createElement('value');
                    $value->setAttribute('id',$response_tmp['id']);
                    //$value->setAttribute('answer',1);
                    $response->appendChild($value);
                  }
                }
              }
            }
          } else {
            if ($type == 'MC') {
              $value = $doc->createElement('value');
              $value->setAttribute('id','');
              $response->appendChild($value);
            }
          }
        }
      }
		}
		return $doc->saveXML();
	}

	function readTMXml($questionCount=null,$scoreFilePathAndName=null) {
    $lineSkip = $questionCount+3;
    $i=0;
    $score = array();
		$scores = '';
		if (phpversion() < 5) {
		  //$lineSkip--;
		  $dom = domxml_open_file($scoreFilePathAndName);
		  $scores = $dom->get_elements_by_tagname('td');
  		foreach ($scores as $score_tmp) {
  			foreach ($score_tmp->child_nodes() as $item) {
      	  if ($i > $lineSkip) {
      	    if (($i-2)%($questionCount+2)==0)
      	      $score[$i-$lineSkip-1]['team_name'] = $item->node_value();
      	    elseif (($i-2)%($questionCount+2) <= $questionCount)
      	      $score[($i-$lineSkip-(($i-2)%($questionCount+2)))-1]['q_'.(($i-1)%($questionCount))] = $item->node_value();
      		  else
      		    $score[$i-$lineSkip-($questionCount+2)]['percent'] = $item->node_value();
      	  }
  			}
  			$i++;
  		}
		} else {
      $dom = new DOMDocument();
      $dom->load($scoreFilePathAndName);
      $scores = $dom->getElementsByTagName('td');
      foreach ($scores as $score_tmp) {
      	foreach ($score_tmp->childNodes AS $item) {
      	  if ($i > $lineSkip) {
      	    if (($i-2)%($questionCount+2)==0)
      	      $score[$i-$lineSkip-1]['team_name'] = $item->nodeValue;
      	    elseif (($i-2)%($questionCount+2) <= $questionCount)
      	      $score[($i-$lineSkip-(($i-2)%($questionCount+2)))-1]['q_'.(($i-1)%($questionCount))] = $item->nodeValue;
      		  else
      		    $score[$i-$lineSkip-($questionCount+2)]['percent'] = $item->nodeValue;
      	  }
    	  }
    	  $i++;
      }
		}

    $score_tmp = array();
    foreach ($score as $temp)
      array_push($score_tmp,$temp);
    $score = $score_tmp;
    return $score;
	}
}
?>
