<?php
class XmlHandlerComponent extends Object {
  function makeTeamMakerXML($survey, $numGroups, $weight) {
    if (phpversion() < 5) {
      $appendChildFunc = 'append_child';
      $setAttributeFunc = 'set_attribute';
      $createElementFunc = 'create_element';
      $doc = domxml_new_doc('1.0');
    } else {
      $appendChildFunc = 'appendChild';
      $setAttributeFunc = 'setAttribute';
      $createElementFunc = 'createElement';
      $doc = new DOMDocument('1.0');
    }

    $this->SurveyInput = new SurveyInput;
    $this->Response = new Response;

    //docroot
    $team_input = $doc->$createElementFunc('team_input');
    $team_input->$setAttributeFunc('num_groups',$numGroups);
    $doc->$appendChildFunc($team_input);
    foreach ($survey['Question'] as $q) {
      if (in_array($q['type'], array('M','C'))) {
        //questions
        $question = $doc->$createElementFunc('question');
        $question->$setAttributeFunc('id', $q['id']);
        $question->$setAttributeFunc('type', ($q['type'] == 'C' ? 'CAO':'MC'));
        $question->$setAttributeFunc('title',$q['prompt']);
        $team_input->$appendChildFunc($question);

        //weight
        $element_weight = $doc->$createElementFunc('weight');
        $element_weight->$setAttributeFunc('value',$q['id']);
        $question->$appendChildFunc($element_weight);
      }
    }
		$userData = $survey['Course']['Enrol'];//$this->User->getEnrolledStudents($courseId, $fields=null);
	//	print_r($userData);
		foreach ($userData as $user) {
      //students
      $student = $doc->$createElementFunc('student');
      $student->$setAttributeFunc('username',$user['student_no']);
      $team_input->$appendChildFunc($student);

      foreach ($survey['Question'] as $q) {
        if (in_array($q['type'], array('M','C'))) {
          //response
          $response = $doc->$createElementFunc('response');
          $response->$setAttributeFunc('q_id', $q['id']);
          $response->$setAttributeFunc('type', ($q['type'] == 'C' ? 'CAO':'MC'));
          $student->$appendChildFunc($response);

          $responses = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($survey['Survey']['id'], $user['id'], $q['id']);
          //print_r($responses);
          if (count($responses) != 0) {
            for ($j=0; $j < count($responses); $j++) {
              $response_tmp = $responses[$j]['SurveyInput'];
              if ($response_tmp['response_text']==null && $response_tmp['response_id']==null) {
                //response/answer
                $value = $doc->$createElementFunc('value');
                $value->$setAttributeFunc('id',$response_tmp['id']);
                $value->$setAttributeFunc('answer',0);
                $response->$appendChildFunc($value);
              } elseif ($response_tmp['response_text']=='' || $response_tmp['response_text']==null) {
                 //response/answer
                $value = $doc->$createElementFunc('value');
                $value->$setAttributeFunc('id',$response_tmp['id']);
                $value->$setAttributeFunc('answer',1);
                $response->$appendChildFunc($value);
              } else {
                $mcResponse = explode('_',$response_tmp['response_text']);
                if (isset($mcResponse[1])) {
                  $mcTmp = $this->Response->read(null, $mcResponse[1]);
                  if ($mcTmp['Response']['response']==$mcResponse[0]) {
                     //response/answer
                    $value = $doc->$createElementFunc('value');
                    $value->$setAttributeFunc('id',$response_tmp['id']);
                    //$value->setAttribute('answer',1);
                    $response->$appendChildFunc($value);
                  }
                }
              }
            }
          } else {
            if ('M' == $q['type']) {
              $value = $doc->$createElementFunc('value');
              $value->$setAttributeFunc('id','');
              $response->$appendChildFunc($value);
            }
          }
        }
      }
		}

    if (phpversion() < 5) {
      return $doc->dump_mem(true);
    } else {
      return $doc->saveXML();
    }
  }

	function makeTMXml4($survey, $numGroups, $weight) {
    $this->SurveyInput = new SurveyInput;
    $this->Response = new Response;
    $this->User = new User;

    $doc = domxml_new_doc('1.0');
    //docroot
    $team_input = $doc->create_element('team_input');
    $team_input->set_attribute('num_groups',$numGroups);
    $doc->append_child($team_input);
    foreach ($survey['Question'] as $q) {
      if (in_array($q['type'], array('M','C'))) {
        //questions
        $question = $doc->create_element('question');
        $question->set_attribute('id', $q['id']);
        $question->set_attribute('type', ($q['type'] == 'C' ? 'CAO':'MC'));
        $question->set_attribute('title',$q['prompt']);
        $team_input->append_child($question);

        //weight
        $element_weight = $doc->create_element('weight');
        $element_weight->set_attribute('value',$q['id']);
        $question->append_child($element_weight);
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

      foreach ($survey['Question'] as $q) {
        if (in_array($q['type'], array('M','C'))) {
          //response
          $response = $doc->create_element('response');
          $response->set_attribute('q_id', $q['id']);
          $response->set_attribute('type', ($q['type'] == 'C' ? 'CAO':'MC'));
          $student->append_child($response);

          $responses = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($survey['Survey']['id'], $user['User']['id'], $q['id']);
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

	function makeTMXml5($survey, $numGroups, $weight) {
    $this->SurveyInput = new SurveyInput;
    $this->Response = new Response;
    $this->User = new User;

    $doc = new DOMDocument('1.0');
    //docroot
    $team_input = $doc->createElement('team_input');
    $team_input->setAttribute('num_groups',$numGroups);
    $doc->appendChild($team_input);
    foreach ($survey['Question'] as $q) {
      if (in_array($q['type'], array('M','C'))) {
        //questions
        $question = $doc->createElement('question');
        $question->setAttribute('id', $q['id']);
        $question->setAttribute('type', ($q['type'] == 'C' ? 'CAO':'MC'));
        $question->setAttribute('title', $q['prompt']);
        $team_input->appendChild($question);

        //weight
        $element_weight = $doc->createElement('weight');
        $element_weight->setAttribute('value',$weight[$question_id]);
        $question->appendChild($element_weight);
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

          $responses = $this->SurveyInput->getAllSurveyInputBySurveyIdUserIdQuestionId($survey_id, $user['User']['id'],$question_id);
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

	function readTMXml($questionCount, $scoreFilePathAndName) {

    // Make sure the specified file exists
    if (!file_exists($scoreFilePathAndName) || filesize($scoreFilePathAndName) <= 0) {
      return false;
    }

    $lineSkip = $questionCount + 3;
    $row_skip = 2;
    $i = 0;
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
      $rows = $dom->getElementsByTagName('tr');
      foreach($rows as $i => $row) {
        if ($i < $row_skip) {
          continue;
        }

        foreach ($row->childNodes as $j => $item) {
      	    if ($j == 0) {
      	      $score[$i - $row_skip]['team_name'] = $item->nodeValue;
            } elseif ($j == $questionCount) {
      		    $score[$i-$row_skip]['percent'] = $item->nodeValue;
            } else {
      	      $score[$i-$row_skip]['q_'.($j-1)] = $item->nodeValue;
            }
        }
      }
      /*$scores = $dom->getElementsByTagName('td');
      foreach ($scores as $score_tmp) {
      	foreach ($score_tmp->childNodes as $item) {
      	  if ($i > $lineSkip) {
      	    if (($i-2)%($questionCount+2)==0) {
      	      $score[$i-$lineSkip-1]['team_name'] = $item->nodeValue;
            } elseif (($i-2)%($questionCount+2) <= $questionCount) {
      	      $score[($i-$lineSkip-(($i-2)%($questionCount+2)))-1]['q_'.(($i-1)%($questionCount))] = $item->nodeValue;
            } else {
      		    $score[$i-$lineSkip-($questionCount+2)]['percent'] = $item->nodeValue;
            }
      	  }
    	  }
    	  $i++;
      }*/

		}

    $score_tmp = array();
    foreach ($score as $temp)
      array_push($score_tmp,$temp);
    $score = $score_tmp;
    return $score;
	}
}
?>
