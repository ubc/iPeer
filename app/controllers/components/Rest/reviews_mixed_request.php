<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class ReviewsMixedRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'JsonResponse'];
    
    public $controller;
    public $settings;
    public $params;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
        $this->params = $controller->params;
    }
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function processResourceRequest(array $event, string $groupEventId, string $autoRelease, string $userId, string $method): void
    {
        // $event = $this->controller->Event->getEventByIdGroupId($eventId, $groupId);
        switch ($method) {
            case 'GET': // Read
                $this->get($event, $groupEventId, $autoRelease, $userId);
                break;
        }
    }
    
    public function processCollectionRequest($method): void
    {
        $this->JsonResponse
            ->setContent(['method' => $method])
            ->withMessage('TBD: MixedEvaluation Collection Request.')
            ->withStatus(200);
    }
    
    
    private function get($event, $groupEventId, $autoRelease, $userId): void
    {
        $eventId = $event['Event']['id'];
        $mixeval = $this->controller->Mixeval->find('first', array(
            'conditions' => array('Mixeval.id' => $event['Event']['template_id']),
            'recursive' => 2
        ));
        $required = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.required');
        $peerQues = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.self_eval');
        // only required peer evaluation questions are counted toward the averages
        $required = array_flip(array_intersect(array_keys($required, 1), array_keys($peerQues, 0)));
        
        // get evaluator details
        $evaluatorDetails = $this->controller->EvaluationMixeval->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'evaluator' => $userId)
        ));
        // get evaluatee details
        $user = $this->controller->User->findById($userId);
        $groupMembers = $this->controller->GroupEvent->getGroupMembers($groupEventId);
        $member_ids = Set::extract($groupMembers, '/GroupsMembers/user_id');
        $evaluateeDetails = $this->controller->Evaluation->getMixevalResultDetail($groupEventId, array($user), $member_ids, array_keys($required));
        
        // JK:: ORG
        $sub = $this->controller->Event->getEventSubmission($eventId, $userId);
        $penalty = $this->controller->Penalty->getPenaltyPercent($sub);
        $score[$userId]['received_ave_score'] = array_sum(array_intersect_key($evaluateeDetails['scoreRecords'][$userId], $required));
        $avePenalty = $score[$userId]['received_ave_score'] * ($penalty / 100);
        
        // JK::ORG:: All members including TA
        // $groupMembers = $this->controller->GroupEvent->getGroupMembers($groupEventId);
        // NOTE::
        $_groupMembers = $this->controller->User->getEventGroupMembersNoTutors($event['Group']['id'], $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
        $penalties = $this->controller->Penalty->getPenaltyByEventId($event['Event']['id']);
        
        
        /** JK:: REFERENCE
        -- $mixeval = $this->Mixeval->find('first', array(
            'conditions' => array('Mixeval.id' => $event['Event']['template_id']),
            'recursive' => 2
        ));
        -- $required = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.required');
        -- $peerQues = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}.self_eval');
        // only required peer evaluation questions are counted toward the averages
        -- $required = array_flip(array_intersect(array_keys($required, 1), array_keys($peerQues, 0)));
    
        -- $user = $this->User->findById($userId);
        -- $groupMembers = $this->GroupEvent->getGroupMembers($groupEventId);
        -- $member_ids = Set::extract($groupMembers, '/GroupsMembers/user_id');
        -- $details = $this->Evaluation->getMixevalResultDetail($groupEventId, array($user), $member_ids, array_keys($required));
        -- $eventSub = $this->Event->getEventSubmission($eventId, $userId);
        -- $penalty = $this->Penalty->getPenaltyPercent($eventSub);
        -- $score[$userId]['received_ave_score'] = array_sum(array_intersect_key($details['scoreRecords'][$userId], $required));
        -- $avePenalty = $score[$userId]['received_ave_score'] * ($penalty / 100);
        */
        
        // To Be Deleted
        // REMOVE $this->set('mixeval', $mixeval);
        // REMOVE $this->set('evalResult', $details['evalResult']);
        // REMOVE $this->set('memberScoreSummary', $score);
        // REMOVE $this->set('penalty', $penalty);
        // REMOVE $this->set('avePenalty', $avePenalty);
        // REMOVE $this->render('student_view_mixeval_evaluation_results');
    
        
        $result = [
            'numMembers' => '',
            'receivedNum' => '',
            'avePenalty' => $avePenalty,
            'aveScore' => '',
            'groupAve' => '',
            'penalty'           => '', // empty($sub) ? $this->controller->Penalty->getPenaltyPercent($event) : $this->controller->Penalty->getPenaltyPercent($sub),
            'gradeReleased' => '', // array_product(Set::extract($evaluateeDetails, '/EvaluationRubric/grade_release')),
            'commentReleased' => '', // ($generalCommentRelease + $detailedCommentRelease),
            //
            'sub' => $sub,
        ];
    
        $data = [
            'penalty'               => $this->getPenalty($penalties),
            'status'                => $this->getResult($result),
            'event'                 => $this->getEvent($mixeval['Event']),
            'group'                 => $this->getGroup($event['Group']),        //$this->getGroup($mixeval['Group']),
            'course'                => $this->getCourse($mixeval['Event'][0]['course_id']),
            'mixed'                 => $this->getMixed($mixeval),
            'evaluator'             => $this->getEvaluatorReviews($evaluatorDetails, $userId),
            'evaluatee'             => $this->getEvaluateeReviews($evaluateeDetails, $userId),
            'members'               => $this->getMembers($_groupMembers),
            //
            'simple'                => [
                'remaining'             => null, // (int) '200',
                'date_submitted'        => $sub['EvaluationSubmission'][0]['date_submitted'],
                'auto_release'          => (bool) $autoRelease,
            ],
        ];
        $this->JsonResponse->setContent($data)->withStatus(200);
    }
    
    /**
     * private methods
     */
    
    private function getResult($result)
    {
        $output = [];
        $output['members_count']    = $result['numMembers'];
        $output['received_count']   = $result['receivedNum'];
        $output['average_penalty']  = $result['avePenalty'];
        $output['average_score']    = $result['aveScore'];
        $output['group_average']    = $result['groupAve'];
        $output['penalty']          = (int) $result['penalty'];
        $output['grade']            = $result['gradeReleased'];
        $output['comment']          = $result['commentReleased'];
        /**
        if(isset($result)) {
        unset($result['comments']);
        }*/
        
        return $output;
    }
    
    private function getEvent($events): array
    {
        if(!isset($events)) return $events;
        $output = [];
        foreach ($events as $event) {
            /** trim data as needed */
            $output['id'] = $event['id'];
            $output['title'] = $event['title'];
            // $output['course_id'] = $event['course_id'];
            // $output['description'] = $event['description'];
            $output['event_template_type_id'] = $event['event_template_type_id'];
            $output['template_id'] = $event['template_id'];
            $output['self_eval'] = (bool) $event['self_eval'];
            $output['com_req'] = (bool) $event['com_req'];
            $output['auto_release'] = (bool) $event['auto_release'];
            $output['enable_details'] = (bool) $event['enable_details'];
            $output['due_date'] = $event['due_date'];
            // $output['release_date_begin'] = $event['release_date_begin'];
            // $output['release_date_end'] = $event['release_date_end'];
            $output['result_release_date_begin'] = $event['result_release_date_begin'];
            $output['result_release_date_end'] = $event['result_release_date_end'];
            $output['record_status'] = $event['record_status'];
            // $output['creator_id'] = $event['creator_id'];
            // $output['created'] = $event['created'];
            // $output['updater_id'] = $event['updater_id'];
            // $output['modified'] = $event['modified'];
            // $output['canvas_assignment_id'] = $event['canvas_assignment_id'];
            // $output['creator'] = $event['creator'];
            // $output['updater'] = $event['updater'];
            $output['response_count'] = $event['response_count'];
            $output['to_review_count'] = $event['to_review_count'];
            $output['student_count'] = $event['student_count'];
            $output['group_count'] = $event['group_count'];
            $output['completed_count'] = $event['completed_count'];
            $output['is_released'] = $event['is_released'];
            $output['is_result_released'] = $event['is_result_released'];
            $output['is_ended'] = $event['is_ended'];
            $output['due_in'] = $event['due_in'];
        }
        return $output;
    }
    
    private function getMixed($mixeval): array
    {
        if(!isset($mixeval)) return [];
        $output = [];
        /** trim data as needed */
        
        // $output = $mixeval['Mixeval'];
    
        $output['id'] = $mixeval['Mixeval']['id'];
        $output['name'] = $mixeval['Mixeval']['name'];
        $output['peer_question'] = $mixeval['Mixeval']['peer_question'];
        $output['self_eval'] = $mixeval['Mixeval']['self_eval'];
        $output['total_marks'] = $mixeval['Mixeval']['total_marks'];
        $output['total_question'] = $mixeval['Mixeval']['total_question']; // ???
        $output['zero_mark'] = (bool) $mixeval['Mixeval']['zero_mark'];
        $output['availability'] = $mixeval['Mixeval']['availability'];
        
        foreach ($mixeval['MixevalQuestion'] as $key => $question) {
            $output['questions'][$key]['type'] = $question['MixevalQuestionType']['type'];
            $output['questions'][$key]['desc'] = $this->getMixedQuestionDesc($question['MixevalQuestionDesc']);
            $output['questions'][$key]['id'] = $question['id'];
            $output['questions'][$key]['title'] = $question['title'];
            $output['questions'][$key]['instructions'] = $question['instructions'];
            $output['questions'][$key]['question_num'] = $question['question_num'];
            $output['questions'][$key]['required'] = (bool) $question['required'];
            $output['questions'][$key]['self_eval'] = (bool) $question['self_eval'];
            $output['questions'][$key]['mixeval_id'] = $question['mixeval_id'];
            $output['questions'][$key]['mixeval_question_type_id'] = $question['mixeval_question_type_id'];
            $output['questions'][$key]['multiplier'] = $question['multiplier'];
            $output['questions'][$key]['scale_level'] = $question['scale_level'];
            $output['questions'][$key]['show_marks'] = (bool) $question['show_marks'];
        }
        return $output;
    }
    
    private function getMixedQuestionDesc($question): array
    {
        $output = [];
        foreach($question as $desc) {
            $tmp = [];
            $tmp = $desc;
            $output[] = $tmp;
        };
        return $output;
    }
    
    private function getMembers($members): array
    {
        $output = [];
        if (!isset($members)) return $output;
        foreach ($members as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            $output[] = $tmp;
        }
        return $output;
    }
    
    private function getGroup(array $group): array
    {
        return [
            'id'    => $group['id'],
            'name'  => $group['group_name']
        ];
    }
    
    private function getCourse(string $courseId): array
    {
        if(!isset($courseId)) return [];
        $course = $this->controller->Course->getCourseById($courseId);
        if (isset($course)) {
            return [
                'id'        => $course['Course']['id'],
                'course'    => $course['Course']['course'],
                'title'     => $course['Course']['title'],
            ];
        }
        return [];
    }
    
    private function getPenalty(array $penalties): array
    {
        if(!isset($penalties)) return [];
        //return $penalties[count($penalties)-1]['Penalty'];

        return count($penalties) > 0 ? $penalties[count($penalties)-1]['Penalty'] : [];
    }
    
    private function getEvaluatorReviews($details, $userId): array
    {
        if(!isset($details)) return [];
        $output = [];
        foreach ($details as $detail) {
            $tmp = [];
            $tmp['id']              = $detail['EvaluationMixeval']['id'];
            $tmp['score']           = $detail['EvaluationMixeval']['score'];
            $tmp['evaluatee']       = $detail['EvaluationMixeval']['evaluatee'];
            $tmp['comment_release'] = $detail['EvaluationMixeval']['comment_release'];
            $tmp['grp_event_id']    = $detail['EvaluationMixeval']['grp_event_id'];
            $tmp['grade_release']   = $detail['EvaluationMixeval']['grade_release'];
            $tmp['details']         = $this->getEvaluationDetail($detail['EvaluationMixevalDetail']);
            $output[] = $tmp;
        }
        return $output;
    }
    
    private function getEvaluateeReviews($details, $userId): array
    {
        if(!isset($details)) return [];
        $output = [];
        foreach ($details['evalResult'][$userId] as $detail) {
            $tmp = [];
            $tmp['id']              = $detail['EvaluationMixeval']['id'];
            $tmp['score']           = $detail['EvaluationMixeval']['score'];
            $tmp['evaluatee']       = $detail['EvaluationMixeval']['evaluatee'];
            $tmp['comment_release'] = $detail['EvaluationMixeval']['comment_release'];
            $tmp['grp_event_id']    = $detail['EvaluationMixeval']['grp_event_id'];
            $tmp['grade_release']   = $detail['EvaluationMixeval']['grade_release'];
            $tmp['details']         = $this->getEvaluationDetail($detail['EvaluationMixevalDetail']);
            $output[] = $tmp;
        }
        return $output;
    }
    
    private function getEvaluationDetail($details): array
    {
        if(!isset($details)) return [];
        $output = [];
        foreach ($details as $key => $detail) {
            $tmp = [];
            $tmp['id'] = $detail['id'];
            $tmp['grade'] = $detail['grade'];
            $tmp['question_number'] = $detail['question_number'];
            $tmp['selected_lom'] = $detail['selected_lom'];
            $tmp['question_comment'] = $detail['question_comment'];
            $tmp['comment_release'] = $detail['comment_release'];
            $output[] = $tmp;
        }
        return $output;
    }
    
}
