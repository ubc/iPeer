<?php


class JsonHandlerComponent extends CakeObject
{
    public $controller;
    public $settings;
    public $components = ['JsonHandler', 'RestResponseHandler'];
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    /**
     * @param array $result
     * @param string|null $userId
     * @return array
     */
    public function formatEvents(array $result, string $userId = null): array
    {
        $data = [];
        foreach ($result as $row) {
            $tmp = [];
            isset($row['Event']['id']) ? $tmp['event'] = [
                'id' => $row['Event']['id'],
                'title' => $row['Event']['title'],
                'course_id' => $row['Event']['course_id'],
                'description' => $row['Event']['description'],
                'event_template_type_id' => $row['Event']['event_template_type_id'],
                'template_id' => $row['Event']['template_id'],
                'self_eval' => $row['Event']['self_eval'],
                'com_req' => $row['Event']['com_req'],
                'auto_release' => $row['Event']['auto_release'],
                'enable_details' => $row['Event']['enable_details'],
                'due_date' => $row['Event']['due_date'],
                'release_date_begin' => $row['Event']['release_date_begin'],
                'release_date_end' => $row['Event']['release_date_end'],
                'result_release_date_begin' => $row['Event']['result_release_date_begin'],
                'result_release_date_end' => $row['Event']['result_release_date_end'],
                'record_status' => $row['Event']['record_status'],
                'is_submitted' => $this->isSubmitted($row['Event']['id'], $row['Group']['id'], $userId),
                'is_released' => $row['Event']['is_released'],
                'is_result_released' => $row['Event']['is_result_released'],
                'is_ended' => $row['Event']['is_ended'],
                'due_in' => $row['Event']['due_in'],
            ] : null;
            isset($row['Course']['id']) ? $tmp['course'] = [
                'id' => $row['Course']['id'],
                'course' => $row['Course']['course'],
                'title' => $row['Course']['title'],
                'term' => $row['Course']['term'],
            ] : null;
            isset($row['Group']['id']) ? $tmp['group'] = [
                'id' => $row['Group']['id'],
                'group_num' => $row['Group']['group_num'],
                'group_name' => $row['Group']['group_name'],
                'member_count' => $row['Group']['member_count'],
            ] : null;
            isset($row['Penalty']) && !empty($row['Penalty']) ? $tmp['penalties'] = $row['Penalty'] : null;
            isset($row['late']) ? $tmp['late'] = $row['late'] : null;
            isset($row['percent_penalty']) ? $tmp['percent_penalty'] = $row['percent_penalty'] : null;
            array_push($data, $tmp);
        }
        return $data;
    }
    
    /**
     * @param array $data
     * @return void
     */
    public function formatSimpleEvaluation(array $data): void
    {
        $json = $this->getEventData($data);
        // other
        $json['template'] = 'SimpleEvaluation';
        $json['member_count'] = $data['evaluateeCount'];
        $json['member_ids'] = $data['memberIDs'];
        $json['simple'] = $this->getSimpleEvaluationSettings($data['questions']);
        $json['simple']['remaining'] = $data['remaining'];
        $json['simple']['data'] = [
            'points'    => [],
            'comments'  => []
        ];
        $json['response'] = $this->getSimpleEvaluationSubmission($data['submission'], $data['evaluation']);
        
        $this->RestResponseHandler->toJson('SimpleEvaluation', 200, $json);
        exit;
    }
    
    /**
     * @param array $data
     * @return void
     */
    public function formatRubricEvaluation(array $data): void
    {
        $json = $this->getEventData($data);
        // other
        $json['template'] = 'RubricEvaluation';
        $json['member_count'] = $data['evaluateeCount'];
        $json['rubric_id'] = $data['rubricId'];
        $json['all_done'] = $data['allDone'];
        $json['gen_com_req'] = $data['comReq']; // general comment section
        $json['member_ids'] = $data['memberIDs'];
        // questions/answers
        $json['rubric'] = $this->getRubricEvaluationSettings($data['questions']) ?? null;
        $json['rubric']['data'] = $this->getRubricEvaluationData($data['questions']) ?? null;
        $json['response'] = isset($data['groupMembers']) ? $this->getRubricEvaluationSubmission($data['submission'], $data['groupMembers']) : null;
        
        $this->RestResponseHandler->toJson('RubricEvaluation', 200, $json);
        exit;
    }
    
    /**
     * @param array $data
     * @return void
     */
    public function formatMixedEvaluation(array $data): void
    {
        $json = $this->getEventData($data);
        // other
        $json['template'] = 'MixedEvaluation';
        $json['member_count'] = $data['memberCount'];
        $json['enrol'] = $data['enrol'];
        // $json['member_ids'] = $data['memberIDs'];
        // questions/answers
        $json['mixed'] = [
            'id' => $data['mixeval']['Mixeval']['id'],
            'availability' => $data['mixeval']['Mixeval']['availability'],
            'name' => $data['mixeval']['Mixeval']['name'],
            'peer_question' => $data['mixeval']['Mixeval']['peer_question'],
            'self_eval' => $data['mixeval']['Mixeval']['self_eval'],
            'total_question' => $data['mixeval']['Mixeval']['total_question'],
            'total_marks' => $data['mixeval']['Mixeval']['total_marks'],
            'zero_mark' => $data['mixeval']['Mixeval']['zero_mark'],
            'data' => $this->getMixedEvaluationQuestions($data['questions']),
        ];
        $json['response'] = $this->getMixedEvaluationSubmission($data['submission'], $data['groupMembers']);
    
        $this->RestResponseHandler->toJson('MixedEvaluation', 200, $json);
        exit;
    }
    
    
    // NOTE:: PRIVATE HELPER METHODS
    private function isSubmitted(string $eventId, string $groupId, string $userId)
    {
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
        return $submission['EvaluationSubmission']['submitted'] ?? null;
    }
    
    private function getEventData($data): array
    {
        $output = [
            'id' => $data['event']['Event']['id'],
            'title' => $data['event']['Event']['title'],
            'description' => $data['event']['Event']['description'],
            'event_template_type_id' => $data['event']['Event']['event_template_type_id'],
            'template_id' => $data['event']['Event']['template_id'],
            'self_eval' => $data['event']['Event']['self_eval'],
            'com_req' => $data['event']['Event']['com_req'],
            'due_date' => $data['event']['Event']['due_date'],
            'release_date_begin' => $data['event']['Event']['release_date_begin'],
            'release_date_end' => $data['event']['Event']['release_date_end'],
            'result_release_date_begin' => $data['event']['Event']['result_release_date_begin'],
            'result_release_date_end' => $data['event']['Event']['result_release_date_end'],
            'group_event_id' => $data['event']['GroupEvent']['id'],
            'group' => [
                'id' => $data['event']['Group']['id'],
                'name' => $data['event']['Group']['group_name'],
            ],
            'course' => $this->getCourseById($data['event']['Event']['course_id']),
            'members' => $this->getGroupMembers($data['groupMembers']),
            'penalty' => $data['penaltyFinal']['Penalty'],
            'status' => $this->isSubmitted($data['event']['Event']['id'], $data['event']['Group']['id'], $data['userId']),
        ];
        return $output;
    }
    
    /**
     * @param array $data
     * @return array
     */
    private function getCustomEventData(array $data): array
    {
        if (empty($data)) return $data;
        
        $output = [];
        $output['event'] = [
            'id' => $data['event']['Event']['id'],
            'title' => $data['event']['Event']['title'],
            'course_id' => $data['event']['Event']['course_id'],
            'description' => $data['event']['Event']['description'],
            'event_template_type_id' => $data['event']['Event']['event_template_type_id'],
            'template_id' => $data['event']['Event']['template_id'],
            'self_eval' => $data['event']['Event']['self_eval'],
            'com_req' => $data['event']['Event']['com_req'],
            'due_date' => $data['event']['Event']['due_date'],
        ];
        $output['course'] = $this->getCourseById($data['event']['Event']['course_id']);
        $output['members'] = $this->getGroupMembers($data['groupMembers']);
        $output['penalties'] = $this->getPenalties($data['penalty']);
        $output['group_event'] = $data['event']['GroupEvent'];
        $output['group'] = $data['event']['Group'];
        $output['penalty_final'] = $data['penaltyFinal']['Penalty'];
        $output['penalty_days'] = $data['penaltyDays'];
        $output['member_ids'] = $data['memberIDs'];
        $output['evaluatee_count'] = $data['evaluateeCount'];
        $output['user_id'] = $data['userId'];
        $output['allDone'] = $data['allDone'];
        $output['comReq'] = $data['comReq'];
        
        return $output;
    }
    
    private function getCourseById(string $courseId): array
    {
        return [
            'id' => $courseId,
            'title' => $this->controller->Course->getCourseName($courseId)
        ];
    }
    
    private function getGroupMembers(array $group_members): array
    {
        if (empty($group_members)) return $group_members;
        $data = [];
        foreach ($group_members as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            $tmp['role_name'] = $member['Role'][0]['name'];
            
            $data[] = $tmp;
        }
        
        return $data;
    }
    
    private function getPenalties(array $penalties): array
    {
        if (empty($penalties)) return $penalties;
        $data = [];
        foreach ($penalties as $penalty) {
            $data[] = $penalty['Penalty'];
        }
        return $data;
    }
    
    
    // Simple
    private function getSimpleEvaluationSubmission(array $submission, array $evaluation): array
    {
        if (empty($submission['EvaluationSubmission']) || empty($evaluation)) return []; // 'points' => [], 'comments' => []
        $data = [];
        $data['id'] = $submission['EvaluationSubmission']['id'];
        $data['submitter_id'] = $submission['EvaluationSubmission']['submitter_id'];
        $data['submitted'] = $submission['EvaluationSubmission']['submitted'];
        $data['date_submitted'] = $submission['EvaluationSubmission']['date_submitted'];
        //$data['points'] = [];
        //$data['comments'] = [];
        $data['data'] = [];
        foreach ($evaluation as $value) {
            $tmp = [];
            $tmp['score'] = $value['EvaluationSimple']['score'];
            $tmp['comment'] = $value['EvaluationSimple']['comment'];
            
            $data['data']['points'][] = $tmp['score'];
            $data['data']['comments'][] = $tmp['comment'];
        };
        return $data;
    }
    
    private function getSimpleEvaluationSettings($questions): array
    {
        if (empty($questions)) return [];
        return [
            'id' => $questions['id'],
            'availability' => $questions['availability'],
            'name' => $questions['name'],
            'description' => $questions['description'],
            'point_per_member' => $questions['point_per_member'],
            'status' => $questions['record_status'],
        ];
    }
    
    // Rubrics
    private function getRubricEvaluationSettings(array $questions): array
    {
        if (empty($questions)) return $questions;
        return [
            'id' => $questions['Rubric']['id'],
            'name' => $questions['Rubric']['name'],
            'zero_mark' => $questions['Rubric']['zero_mark'],
            'view_mode' => $questions['Rubric']['view_mode'],
            'template' => $questions['Rubric']['template'],
            'availability' => $questions['Rubric']['availability'],
            'lom_max' => $questions['Rubric']['lom_max'],
            'criteria' => $questions['Rubric']['criteria'],
        ];
    }
    
    private function getRubricEvaluationData(array $questions): array
    {
        if (empty($questions)) return $questions;
        return [
            'rubrics_criteria' => $this->getRubricEvaluationCriteria($questions['RubricsCriteria']),
            'rubrics_lom' => $questions['RubricsLom']
        ];
    }
    
    private function getRubricEvaluationCriteria(array $RubricsCriteria): array
    {
        if (empty($RubricsCriteria)) return $RubricsCriteria;
        $data = [];
        foreach ($RubricsCriteria as $criteria) {
            $tmp = [];
            $tmp['id'] = $criteria['id'];
            $tmp['rubric_id'] = $criteria['rubric_id'];
            $tmp['criteria_num'] = $criteria['criteria_num'];
            $tmp['criteria'] = $criteria['criteria'];
            $tmp['multiplier'] = $criteria['multiplier'];
            $tmp['show_marks'] = $criteria['show_marks'];
            
            foreach ($criteria['RubricsCriteriaComment'] as $criteria_comment) {
                $comment = [];
                $comment['id'] = $criteria_comment['id'];
                $comment['criteria_id'] = $criteria_comment['criteria_id'];
                $comment['rubrics_loms_id'] = $criteria_comment['rubrics_loms_id'];
                $comment['criteria_comment'] = $criteria_comment['criteria_comment'];
                
                $tmp['rubrics_criteria_comment'][] = $comment;
            }
            $data[] = $tmp;
        }
        return $data;
    }
    
    private function getRubricEvaluationSubmission(array $submission, array $groupMembers): array
    {
        if (empty($submission['EvaluationSubmission']) || empty($groupMembers)) return [];
        $data['id'] = $submission['EvaluationSubmission']['id'];
        $data['submitter_id'] = $submission['EvaluationSubmission']['submitter_id'];
        $data['submitted'] = $submission['EvaluationSubmission']['submitted'];
        $data['date_submitted'] = $submission['EvaluationSubmission']['date_submitted'];
        
        foreach ($groupMembers as $member) {
            if (isset($member['User']['Evaluation'])) {
                $tmp = [
                    'id' => $member['User']['Evaluation']['EvaluationRubric']['id'],
                    'evaluator' => $member['User']['Evaluation']['EvaluationRubric']['evaluator'],
                    'evaluatee' => $member['User']['Evaluation']['EvaluationRubric']['evaluatee'],
                    'comment' => $member['User']['Evaluation']['EvaluationRubric']['comment'],
                    'score' => $member['User']['Evaluation']['EvaluationRubric']['score'],
                    'comment_release'   => $member['User']['Evaluation']['EvaluationRubric']['comment_release'],
                    'grade_release'     => $member['User']['Evaluation']['EvaluationRubric']['grade_release'],
                    'grp_event_id'      => $member['User']['Evaluation']['EvaluationRubric']['grp_event_id'],
                    'event_id'          => $member['User']['Evaluation']['EvaluationRubric']['event_id'],
                    'record_status'     => $member['User']['Evaluation']['EvaluationRubric']['record_status'],
                    'details'           => $this->getRubricEvaluationDetail($member['User']['Evaluation']['EvaluationRubricDetail'])
                ];
                $data['data'][] = $tmp;
            }
            
        };
        return $data;
    }
    
    private function getRubricEvaluationDetail(array $rubricDetail): array
    {
        if (empty($rubricDetail)) return [];
        $data = [];
        foreach ($rubricDetail as $detail) {
            $tmp = [];
            $tmp['id'] = $detail['id'];
            //$tmp['evaluation_rubric_id']  = $detail['evaluation_rubric_id'];
            $tmp['criteria_number'] = $detail['criteria_number'];
            $tmp['criteria_comment'] = $detail['criteria_comment'];
            $tmp['selected_lom'] = $detail['selected_lom'];
            //$tmp['grade']                 = $detail['grade'];
            //$tmp['comment_release']       = $detail['comment_release'];
            //$tmp['record_status']         = $detail['record_status'];
            //$tmp['creator_id']            = $detail['creator_id'];
            //$tmp['updater_id']            = $detail['updater_id'];
            //$tmp['created']               = $detail['created'];
            //$tmp['modified']              = $detail['modified'];
            //$tmp['creator']               = $detail['creator'];
            //$tmp['updater']               = $detail['updater'];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
    // Mixed
    private function getMixedEvaluationQuestions(array $questions): array
    {
        if (empty($questions)) return $questions;
        $output = [];
        foreach ($questions as $question) {
            $tmp = [];
            $tmp['id'] = $question['MixevalQuestion']['id'];
            $tmp['type'] = $question['MixevalQuestionType']['type'];
            $tmp['title'] = $question['MixevalQuestion']['title'];
            $tmp['instructions'] = $question['MixevalQuestion']['instructions'];
            $tmp['mixeval_id'] = $question['MixevalQuestion']['mixeval_id'];
            $tmp['mixeval_question_type_id'] = $question['MixevalQuestion']['mixeval_question_type_id'];
            $tmp['multiplier'] = $question['MixevalQuestion']['multiplier'];
            $tmp['question_num'] = $question['MixevalQuestion']['question_num'];
            $tmp['scale_level'] = $question['MixevalQuestion']['scale_level'];
            $tmp['self_eval'] = (bool)$question['MixevalQuestion']['self_eval'];
            $tmp['show_marks'] = $question['MixevalQuestion']['show_marks'];
            $tmp['required'] = (bool)$question['MixevalQuestion']['required'];
            
            $tmp['loms'] = $question['MixevalQuestionDesc'];
            
            $output[] = $tmp;
        }
        
        return $output;
    }
    
    private function getMixedEvaluationSubmission(array $submission, array $groupMembers): array
    {
        if (empty($submission['EvaluationSubmission']) || empty($groupMembers)) return [];
        $output['id'] = $submission['EvaluationSubmission']['id'];
        $output['submitter_id'] = $submission['EvaluationSubmission']['submitter_id'];
        $output['submitted'] = $submission['EvaluationSubmission']['submitted'];
        $output['date_submitted'] = $submission['EvaluationSubmission']['date_submitted'];
        
        foreach ($groupMembers as $member) {
            if (isset($member['User']['Evaluation'])) {
                $tmp = [
                    'id' => $member['User']['Evaluation']['EvaluationMixeval']['id'],
                    'evaluator' => $member['User']['Evaluation']['EvaluationMixeval']['evaluator'],
                    'evaluatee' => $member['User']['Evaluation']['EvaluationMixeval']['evaluatee'],
                    'score' => $member['User']['Evaluation']['EvaluationMixeval']['score'],
                    'comment_release'   => $member['User']['Evaluation']['EvaluationMixeval']['comment_release'],
                    'grade_release'     => $member['User']['Evaluation']['EvaluationMixeval']['grade_release'],
                    'grp_event_id'      => $member['User']['Evaluation']['EvaluationMixeval']['grp_event_id'],
                    'event_id'          => $member['User']['Evaluation']['EvaluationMixeval']['event_id'],
                    'record_status'     => $member['User']['Evaluation']['EvaluationMixeval']['record_status'],
                    'details' => $this->getMixedEvaluationDetail($member['User']['Evaluation']['EvaluationMixevalDetail'])
                ];
                $output['data'][] = $tmp;
            }
            
        };
        
        return $output;
    }
    
    private function getMixedEvaluationDetail(array $mixedDetail): array
    {
        if (empty($mixedDetail)) return [];
        $output = [];
        foreach ($mixedDetail as $detail) {
            $tmp = [];
            $tmp['id'] = $detail['id'];
            $tmp['evaluation_mixeval_id'] = $detail['evaluation_mixeval_id'];
            $tmp['question_number'] = $detail['question_number'];
            $tmp['question_comment'] = $detail['question_comment'];
            $tmp['selected_lom'] = $detail['selected_lom'];
            $tmp['grade'] = $detail['grade'];
            $tmp['comment_release'] = $detail['comment_release'];
            $tmp['record_status'] = $detail['record_status'];
            //$tmp['creator_id']            = $detail['creator_id'];
            //$tmp['updater_id']            = $detail['updater_id'];
            //$tmp['created']               = $detail['created'];
            //$tmp['modified']              = $detail['modified'];
            //$tmp['creator']               = $detail['creator'];
            //$tmp['updater']               = $detail['updater'];
            
            $output[] = $tmp;
        }
        return $output;
    }
    
}