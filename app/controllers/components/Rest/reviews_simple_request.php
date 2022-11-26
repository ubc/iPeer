<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class ReviewsSimpleRequestComponent extends CakeObject
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
    
    function pre_r($val)
    {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
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
            ->withMessage('TBD: SimpleEvaluation Collection Request.')
            ->withStatus(200);
    }
    
    private function get($event, $groupEventId, $autoRelease, $userId): void
    {
        // View Simple Evaluation Result
        $studentResult = $this->controller->EvaluationSimple->formatStudentViewOfSimpleEvaluationResult($event, $userId);
    
        $submitted = $this->controller->EvaluationSubmission->findAllByGrpEventId($groupEventId);
        $submitted = Set::extract('/EvaluationSubmission/submitter_id', $submitted);
        $evaluatorDetails = $this->controller->EvaluationSimple->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'evaluator' => $userId)
        ));
        $evaluateeDetails = $this->controller->EvaluationSimple->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'evaluatee' => $userId, 'evaluator' => $submitted)
        ));
    
        $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $userId);
        $generalCommentRelease = array_sum(Set::extract($evaluateeDetails, '/EvaluationRubric/comment_release'));
        $detailedCommentRelease = array_sum(Set::extract($evaluateeDetails, '/EvaluationRubricDetail/comment_release'));
        
        $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($event['Group']['id'], $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
        $penalties = $this->controller->Penalty->getPenaltyByEventId($event['Event']['id']);
        
        $simple = [
            'remaining'             => (int) '200',
            'date_submitted'        => $sub['EvaluationSubmission']['date_submitted'],
            'auto_release'          => (bool) $autoRelease,
        ];
        
        $data = [
            'penalty'               => $this->getPenalty($penalties),
            'status'                => $this->getResult($studentResult),
            'event'                 => $this->getEvent($event['Event']),
            'group'                 => $this->getGroup($event['Group']),
            'course'                => $this->getCourse($event['Event']['course_id']),
            'simple'                => $simple,
            'evaluator'             => $this->getReviews($evaluatorDetails),
            'evaluatee'             => $this->getEvaluateeReviews($evaluateeDetails), /** returns string[] with the scores & comments only */
            'members'               => $this->getMembers($groupMembers),
        ];
        
        $this->JsonResponse->setContent($data)->withStatus(200);
    }
    
    /**
     * @param $result
     * @return mixed
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
    
    /**
     * @param $event
     * @return array
     */
    private function getEvent($event): array
    {
        if(!isset($event)) return $event;
        $output = [];
        // trim data as needed
        $output['id'] = $event['id'];
        $output['title'] = $event['title'];
        // $output['course_id'] = $event['course_id'];
        // $output['description'] = $event['description'];
        $output['event_template_type_id'] = $event['event_template_type_id'];
        $output['template_id'] = $event['template_id'];
        $output['self_eval'] = $event['self_eval'];
        $output['com_req'] = $event['com_req'];
        $output['auto_release'] = $event['auto_release'];
        $output['enable_details'] = $event['enable_details'];
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
        
        return $output;
    }
    
    private function getSimple($simple): array
    {
        if(!isset($simple)) return [];
        $output = [];
        /** trim data as needed
        $output['id']           = $simple['Simple']['id'];
        $output['name']         = $simple['Simple']['name'];
        $output['zero_mark']    = $simple['Simple']['zero_mark'];
        $output['lom_max']      = $simple['Simple']['lom_max'];
        $output['criteria']     = $simple['Simple']['criteria'];
        $output['view_mode']    = $simple['Simple']['view_mode'];
        $output['availability'] = $simple['Simple']['availability'];
        $output['template']     = $simple['Simple']['template'];
        // $output['creator_id']   = $simple['Simple']['creator_id'];
        // $output['created']      = $simple['Simple']['created'];
        // $output['updater_id']   = $simple['Simple']['updater_id'];
        // $output['modified']     = $simple['Simple']['modified'];
        // $output['creator']      = $simple['Simple']['creator'];
        // $output['updater']      = $simple['Simple']['updater'];
        $output['event_count']  = $simple['Simple']['event_count'];
        $output['total_marks']  = $simple['Simple']['total_marks']; */
        
        return $output;
    }
    
    private function getReviews(array $details): array
    {
        $output = [];
        foreach ($details as $key => $detail) {
            $output[$key]               = $detail['EvaluationSimple'];
            // $output[$key]['details']    = $detail['EvaluationSimpleDetail'];
        }
        return $output;
    }
    
    private function getEvaluateeReviews(array $details): array
    {
        $output = [];
        $scores = [];
        $comments = [];
        /**
        foreach ($details as $key => $detail) {
            $output[$key]               = $detail['EvaluationSimple'];
            // $output[$key]['details']    = $detail['EvaluationSimpleDetail'];
        }*/
        foreach ($details as $key => $detail) {
            $scores[$key]   = $detail['EvaluationSimple']['score'];
            $comments[$key] = $detail['EvaluationSimple']['comment'];
            
        }
        
        return ['scores' => $scores, 'comments' => $comments];
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
        return count($penalties) > 0 ? $penalties[count($penalties)-1]['Penalty'] : [];
    }
    
}
