<?php
/**
 * SurveyQuestion
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyQuestion extends AppModel
{
    public $name = 'SurveyQuestion';

    /**
     * Returns all the question IDs of a specific survey and merges a array "count" in the result
     * @param type_INT $surveyId : survey's id
     *
     * @access public
     * @return array  list of question related to the survey ID
     */
    function getQuestionsID($surveyId = null)
    {
        if (null == $surveyId) {
            return array();
        }

        return $this->find(
            'all',
            array(
                'conditions'=> array('survey_id' => $surveyId),
                'fields' => array('number', 'question_id', 'id'),
                'order' => 'number'
            )
        );
    }

    /**
     * Reorders the survey's list of questions.
     *
     * @param INT    $survey_id   survey's id.
     * @param INT    $question_id id corresponding to the question that needs to be repositioned
     * @param STRING $position    specifies how the question will be repositioned ('UP', 'DOWN', 'TOP', 'DOWN')
     *
     * @access public
     * @return void
     */
    function moveQuestion($survey_id, $question_id, $position)
    {
        // Move to TOP case
        // Note, this method will not work for the BOTTOM case
        switch ($position) {
        case "TOP":
            // Call upon itself until function returns false (TOP of List)
            //while($this->moveQuestion($survey_id, $question_id, "UP"));
            // Call itself until function is bottom of list

            $data = $this->find('first', array('conditions' => array('question_id' => $question_id,
                'survey_id' => $survey_id)));
            $data['SurveyQuestion']['number'] = '0';
            $this->id = $data['SurveyQuestion']['id'];
            $this->save($data);

            $this->reorderQuestions($survey_id);
            break;
            // Move UP case
        case "UP":
            // Get current question and the question before it
            $data = $this->find('first', array(
                'conditions' => array('question_id' => $question_id,
                'survey_id' => $survey_id)));

            // Check to make sure question isn't the very first question
            if ($data['SurveyQuestion']['number'] == 1) {
                return false;
            }
            $data2 = $this->find('first', array(
                'conditions' => array('number ' => ($data['SurveyQuestion']['number']-1),
                'survey_id' => $survey_id,
            )));

            // Sway numbers of the question and the previous question
            $save = array();
            if (empty($data2)) {
                $questions = $this->reorderQuestions($survey_id);
                foreach ($questions as $k => $q) {
                    if ($q['SurveyQuestion']['question_id'] == $question_id) {
                        if (0 == $k) {
                            // first one
                            return false;
                        }
                        $save[] = $questions[$k-1];
                        $save[] = $questions[$k];
                    }
                }
            } else {
                $save[] = $data2;
                $save[] = $data;
            }
            $save['0']['SurveyQuestion']['number']++;
            $save['1']['SurveyQuestion']['number']--;
            $this->saveAll($save, array('fieldList' => array('number')));

            break;
            // Move DOWN case
        case "DOWN":
            // Get current question and the question after it
            $data = $this->find('first', array(
                'conditions' => array('question_id' => $question_id, 'survey_id' => $survey_id)));
            $data2 = $this->find('first', array(
                'conditions' => array('number' => ($data['SurveyQuestion']['number'] + 1), 'survey_id' => $survey_id)));

            $save = array();
            if ($data['SurveyQuestion']['number'] == 9999 || empty($data2)) {
                $questions = $this->reorderQuestions($survey_id);
                $lastQuestionNum = $this->getLastSurveyQuestionNum($survey_id);
                if ($lastQuestionNum == $data['SurveyQuestion']['number']) {
                    return false;
                }
                foreach ($questions as $k => $q) {
                    if ($q['SurveyQuestion']['number'] == $question_id) {
                        if ($k == count($questions) - 1) {
                            // last one
                            return false;
                        }
                        $save[] = $questions[$k];
                        $save[] = $questions[$k+1];
                    }
                }
            } else {
                $save[] = $data;
                $save[] = $data2;
            }

            // Sway numbers of the question and the next question
            $save['0']['SurveyQuestion']['number']++;
            $save['1']['SurveyQuestion']['number']--;

            $this->saveAll($save);
            break;
            // Move to BOTTOM case
        case "BOTTOM":
            // Call itself until function is bottom of list
            //while( $this->moveQuestion($survey_id, $question_id, "DOWN"));

            // get question to move to bottom and change number to huge number and resave
            $data = $this->find('first', array(
                'conditions' => array('question_id' => $question_id,'survey_id' => $survey_id)));
            $data['SurveyQuestion']['number'] = '10000';
            $this->save($data);
            $this->reorderQuestions($survey_id);
            break;
        default:
            break;
        }
        return true;
    }

    /**
     * Assigned question number to each question in a survey based on its position in the list;
     * called after the survey question list is reordered
     *
     * @param type_INT $survey_id : survey's id
     *
     * @access public
     * @return void
     */
    function reorderQuestions($survey_id)
    {
        // get all questions order by the number
        $data = $this->find('all', array(
            'conditions' => array('survey_id' => $survey_id),
            'order' => 'number'
        ));
        $count = sizeof($data);
        // reset numbering for each question and resave
        for ($i=1; $i<=$count; $i++) {
            $data[$i-1]['SurveyQuestion']['number'] = $i;
        }
        $this->saveAll($data, array('fieldList' => array('number')));
        return $data;
    }
    
    /**
     * Assign the same question number to each question that they had in the source survey
     * when copying surveys
     *
     * @param mixed $questionNo
     * @param mixed $surveyId
     *
     * @access public
     * @return void
     */
    function assignNumber($questionNo, $surveyId)
    {
        $questions = $this->findAllBySurveyId($surveyId);
        foreach ($questions as $ques) {
            $ques['SurveyQuestion']['number'] = $questionNo[$ques['SurveyQuestion']['question_id']];
            $this->save($ques['SurveyQuestion']);
        }
    }

    /**
     * Retrieves the last question in the survey question list
     *
     * @param type_INT $surveyId : survey's id
     *
     * @access public
     * @return void
     */
    function getLastSurveyQuestionNum($surveyId)
    {
        $tmp = $this->find('all', array('conditions' => array('survey_id' => $surveyId), 'fields' => array('max(number)')));
        return $tmp[0][0]['max(number)'];
    }

    /**
     * getQuestionsByEventId
     *
     * @param mixed $eventId event id
     * 
     * @access public
     * @return void
     */
    function getQuestionsByEventId($eventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $event = $this->Event->find('first', array(
            'conditions' => array('id' => $eventId),
            'contain' => false,
            'fields' => array('template_id'),
        ));

        return $this->find('all', array(
            'conditions' => array('survey_id' => $event['Event']['template_id']),
        ));
    }
}
