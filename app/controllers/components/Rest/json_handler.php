<?php


class JsonHandlerComponent extends CakeObject
{
    public $controller;
    public $settings;
    public $components = [
        'JsonHandler', 'JsonResponse',
        'EventResource',
        'CourseResource',
        'GroupResource',
        'UserCollection',
        'PenaltyResource', 'PenaltyCollection',
    ];
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    /**
     * @param array $data
     * @return void
     */
    public function formatSimpleEvaluation(array $data): void
    {
        $json                   = $this->EventResource->toArray($data);
        $json['course']         = $this->CourseResource->courseById($data['event']['Event']['course_id']);
        $json['group']          = $this->GroupResource->groupByIdEventId($data['event']['Group']['id'], $data['event']['Event']['id']);
        $json['members']        = $this->UserCollection->eventGroupMembersNoTutors($data['event']['Group']['id'], $data['event']['Event']['self_eval']);
        $json['penalty']        = $this->PenaltyResource->penaltyFinalByEventId($data['event']['Event']['id']);
        //
        $json['member_count']   = $data['evaluateeCount'];
        $json['member_ids']     = $data['memberIDs'];
        //
        $json['simple']         = $this->getSimpleEvaluationSettings($data);
        $json['response']       = $this->getSimpleEvaluationSubmission($data['submission'], $data['evaluation']);
        //
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
    }
    
    /**
     * @param array $data
     * @return void
     */
    public function formatRubricEvaluation(array $data): void
    {
        $json                   = $this->EventResource->toArray($data);
        $json['course']         = $this->CourseResource->courseById($data['event']['Event']['course_id']);
        $json['group']          = $this->GroupResource->groupByIdEventId($data['event']['Group']['id'], $data['event']['Event']['id']);
        $json['members']        = $this->UserCollection->eventGroupMembersNoTutors($data['event']['Group']['id'], $data['event']['Event']['self_eval']);
        $json['penalty']        = $this->PenaltyResource->penaltyFinalByEventId($data['event']['Event']['id']);
        //
        $json['member_count']   = $data['evaluateeCount'];
        $json['member_ids']     = $data['memberIDs'];
        //
        $json['rubric_id']      = $data['rubricId'];
        $json['all_done']       = $data['allDone'];
        $json['gen_com_req']    = $data['comReq']; // general comment section
        //
        $json['rubric']         = $this->getRubricEvaluationSettings($data['questions']) ?? null;
        $json['rubric']['data'] = $this->getRubricEvaluationData($data['questions']) ?? null;
        $json['response']       = isset($data['groupMembers']) ? $this->getRubricEvaluationSubmission($data['submission'], $data['groupMembers']) : null;
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
    }
    
    /**
     * @param array $data
     * @return void
     */
    public function formatMixedEvaluation(array $data): void
    {
        $json                   = $this->EventResource->toArray($data);
        $json['course']         = $this->CourseResource->courseById($data['event']['Event']['course_id']);
        $json['group']          = $this->GroupResource->groupByIdEventId($data['event']['Group']['id'], $data['event']['Event']['id']);
        $json['members']        = $this->UserCollection->eventGroupMembersNoTutors($data['event']['Group']['id'], $data['event']['Event']['self_eval']);
        $json['penalty']        = $this->PenaltyResource->penaltyFinalByEventId($data['event']['Event']['id']);
        //
        $json['member_count']   = $data['memberCount'];
        $json['member_ids']     = $data['memberIDs'];
        //
        $json['enrol']          = $data['enrol'];
        //
        $json['mixed']          = $this->getMixedEvaluationSettings($data);
        $json['response']       = $this->getMixedEvaluationSubmission($data['submission'], $data['groupMembers']);
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
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
    
    private function getSimpleEvaluationSettings($data): array
    {
        if (empty($data)) return [];
        return [
            'id'                => $data['questions']['id'],
            'availability'      => $data['questions']['availability'],
            'name'              => $data['questions']['name'],
            'description'       => $data['questions']['description'],
            'point_per_member'  => $data['questions']['point_per_member'],
            'status'            => $data['questions']['record_status'],
            'data'              => ['points' => [], 'comments' => []],
            'remaining'         => $data['remaining']
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
    private function getMixedEvaluationSettings(array $data): array
    {
        return [
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
    }
    
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