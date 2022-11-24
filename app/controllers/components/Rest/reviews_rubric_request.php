<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class ReviewsRubricRequestComponent extends CakeObject
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
        $this->Sanitize = new Sanitize;
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
            ->withMessage('TBD: RubricEvaluation Collection Request.')
            ->withStatus(200);
    }
    
    private function get($event, $groupEventId, $autoRelease, $userId): void
    {
        // View Rubric Evaluation Result
        $rubric = $this->controller->Rubric->findById($event['Event']['template_id']);
        $submitted = $this->controller->EvaluationSubmission->findAllByGrpEventId($groupEventId);
        $submitted = Set::extract('/EvaluationSubmission/submitter_id', $submitted);
        $evaluatorDetails = $this->controller->EvaluationRubric->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'evaluator' => $userId)
        ));
        $evaluateeDetails = $this->controller->EvaluationRubric->find('all', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'evaluatee' => $userId, 'evaluator' => $submitted)
        ));
        /** NOTE:: I do not see any use for 'Penalty Percentage' being displayed on the page
         * $userIds = array_unique(array_merge(Set::extract($evaluatorDetails, '/EvaluationRubric/evaluatee'), array($userId)));
         * $fullNames = $this->User->getFullNames($userIds);
         * $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $userId);
         * $penalty = empty($sub) ? $this->Penalty->getPenaltyPercent($event) : $this->Penalty->getPenaltyPercent($sub);
        */
        $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $userId);
        $generalCommentRelease = array_sum(Set::extract($evaluateeDetails, '/EvaluationRubric/comment_release'));
        $detailedCommentRelease = array_sum(Set::extract($evaluateeDetails, '/EvaluationRubricDetail/comment_release'));
        $status = [
            'grade'             => array_product(Set::extract($evaluateeDetails, '/EvaluationRubric/grade_release')),
            'comment'           => ($generalCommentRelease + $detailedCommentRelease),
            'penalty'           => empty($sub) ? $this->controller->Penalty->getPenaltyPercent($event) : $this->controller->Penalty->getPenaltyPercent($sub),
            'date_submitted'    => $sub['EvaluationSubmission']['date_submitted'],
            'auto_release'      => (bool) $autoRelease,
        ];
    
        // NOTE::
        $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($event['Group']['id'], $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
        $penalties = $this->controller->Penalty->getPenaltyByEventId($event['Event']['id']);
        
        
        // REMOVE $this->set('rubric', $rubric);
        // REMOVE $this->set('membersList', $fullNames);
        // REMOVE $this->set('evaluatorDetails', $evaluatorDetails);
        // REMOVE $this->set('evaluateeDetails', $evaluateeDetails);
        // REMOVE $this->set('status', $status);
        // REMOVE $this->set('penalty', $penalty);
        // REMOVE $this->set('status', $status);
        // REMOVE $this->render('student_view_rubric_evaluation_results');
        
        $data = [
            'penalty'               => $this->getPenalty($penalties),
            'status'                => $status,
            'event'                 => $this->getEvent($rubric['Event']),
            'group'                 => $this->getGroup($event['Group']),
            'course'                => $this->getCourse($rubric['Event'][0]['course_id']),
            'rubric'                => $this->getRubric($rubric),
            'evaluator'             => $this->getReviews($evaluatorDetails),
            'evaluatee'             => $this->getReviews($evaluateeDetails),
            'members'               => $this->getMembers($groupMembers),
            //
            'simple'                => [
                'remaining'             => (int) '200',
                'date_submitted'        => $sub['EvaluationSubmission']['date_submitted'],
                'auto_release'          => (bool) $autoRelease,
            ],
        ];
        $this->JsonResponse->setContent($data)->withStatus(200);
    }
    
    
    /**
     * Private methods
     */
    
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
        }
        return $output;
    }
    
    private function getRubric($rubric): array
    {
        if(!isset($rubric)) return [];
        $output = [];
        /** trim data as needed */
        $output['id']           = $rubric['Rubric']['id'];
        $output['name']         = $rubric['Rubric']['name'];
        $output['zero_mark']    = $rubric['Rubric']['zero_mark'];
        $output['lom_max']      = $rubric['Rubric']['lom_max'];
        $output['criteria']     = $rubric['Rubric']['criteria'];
        $output['view_mode']    = $rubric['Rubric']['view_mode'];
        $output['availability'] = $rubric['Rubric']['availability'];
        $output['template']     = $rubric['Rubric']['template'];
        // $output['creator_id']   = $rubric['Rubric']['creator_id'];
        // $output['created']      = $rubric['Rubric']['created'];
        // $output['updater_id']   = $rubric['Rubric']['updater_id'];
        // $output['modified']     = $rubric['Rubric']['modified'];
        // $output['creator']      = $rubric['Rubric']['creator'];
        // $output['updater']      = $rubric['Rubric']['updater'];
        $output['event_count']  = $rubric['Rubric']['event_count'];
        $output['total_marks']  = $rubric['Rubric']['total_marks'];
        $output['criterias']    = $rubric['RubricsCriteria'];
        $output['loms']         = $rubric['RubricsLom'];
        
        return $output;
    }
    
    private function getReviews(array $details): array
    {
        $output = [];
        foreach ($details as $key => $detail) {
            $output[$key]               = $detail['EvaluationRubric'];
            $output[$key]['details']    = $detail['EvaluationRubricDetail'];
        }
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
        // return $penalties[count($penalties)-1]['Penalty'];
    
        return count($penalties) > 0 ? $penalties[count($penalties)-1]['Penalty'] : [];
    }
    
}