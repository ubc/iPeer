<?php
/**
 * XmlHandlerComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class XmlHandlerComponent extends CakeObject
{
    /**
     * makeTeamMakerXML
     *
     * @param mixed $questions questions for make groups
     * @param mixed $responses user responses
     * @param mixed $numGroups number of groups
     * @param mixed $weight    weight
     *
     * @access public
     * @return void
     */
    function makeTeamMakerXML($questions, $responses, $numGroups, $weight)
    {
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
        $team_input->$setAttributeFunc('num_groups', $numGroups);
        $doc->$appendChildFunc($team_input);
        foreach ($questions as $q) {
            //questions
            $question = $doc->$createElementFunc('question');
            $question->$setAttributeFunc('id', $q['id']);
            $question->$setAttributeFunc('type', ($q['type'] == 'C' ? 'CAO':'MC'));
            $question->$setAttributeFunc('title', $q['prompt']);
            $team_input->$appendChildFunc($question);

            //weight
            $element_weight = $doc->$createElementFunc('weight');
            $element_weight->$setAttributeFunc('value', $weight[$q['id']]);
            $question->$appendChildFunc($element_weight);
        }

        foreach ($responses as $user) {
            //students
            $student = $doc->$createElementFunc('student');
            $student->$setAttributeFunc('username', $user['User']['id']);
            $team_input->$appendChildFunc($student);

            foreach ($questions as $q) {
                //response
                $response = $doc->$createElementFunc('response');
                $response->$setAttributeFunc('q_id', $q['id']);
                $response->$setAttributeFunc('type', ($q['type'] == 'C' ? 'CAO':'MC'));
                $student->$appendChildFunc($response);

                // TODO: handle CAO (checkbox questions. See the example xml in teammaker
                // source directory
                $responses = Set::extract($user, '/SurveyInput[question_id='.$q['id'].']');
                if (!empty($responses)) {
                    foreach ($responses as $resp) {
                        $response_tmp = $resp['SurveyInput'];
                        //response/answer
                        $value = $doc->$createElementFunc('value');
                        $value->$setAttributeFunc('id', $response_tmp['response_id'] == null ? '':$response_tmp['response_id']);
                        //$value->$setAttributeFunc('answer', 1);
                        $response->$appendChildFunc($value);
                    }
                } else {
                    if ('M' == $q['type']) {
                        $value = $doc->$createElementFunc('value');
                        $value->$setAttributeFunc('id', '');
                        $response->$appendChildFunc($value);
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

    /**
     * readTMXml
     *
     * @param mixed $questionCount        question count
     * @param mixed $scoreFilePathAndName score file with path
     *
     * @access public
     * @return void
     */
    function readTMXml($questionCount, $scoreFilePathAndName)
    {

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
                        if (($i-2)%($questionCount+2)==0) {
                            $score[$i-$lineSkip-1]['team_name'] = $item->node_value();
                        } elseif (($i-2)%($questionCount+2) <= $questionCount) {
                            $score[($i-$lineSkip-(($i-2)%($questionCount+2)))-1]['q_'.(($i-1)%($questionCount))] = $item->node_value();
                        } else {
                           $score[$i-$lineSkip-($questionCount+2)]['percent'] = $item->node_value();
                        }
                    }
                }
                $i++;
            }
        } else {
            $dom = new DOMDocument();
            $dom->load($scoreFilePathAndName);
            $rows = $dom->getElementsByTagName('tr');
            foreach ($rows as $i => $row) {
                if ($i < $row_skip) {
                    continue;
                }

                foreach ($row->childNodes as $j => $item) {
                    if ($j == 0) {
                        $score[$i - $row_skip]['team_name'] = $item->nodeValue;
                    } elseif ($j == $questionCount+1) {
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
        foreach ($score as $temp) {
            array_push($score_tmp, $temp);
        }
        $score = $score_tmp;
        return $score;
    }

}
