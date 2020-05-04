<?php
namespace caliper;

use caliper\ResourceIRI;
use caliper\CaliperSensor;
use IMSGlobal\Caliper\entities\agent\SoftwareApplication;
use IMSGlobal\Caliper\entities\session\Session;
use IMSGlobal\Caliper\entities\reading\WebPage;
use IMSGlobal\Caliper\entities\lis\CourseOffering;
use IMSGlobal\Caliper\entities\assessment\Assessment;
use IMSGlobal\Caliper\entities\assessment\AssessmentItem;
use IMSGlobal\Caliper\entities\survey\Questionnaire;
use IMSGlobal\Caliper\entities\survey\QuestionnaireItem;
use IMSGlobal\Caliper\entities\question\MultiselectQuestion;
use IMSGlobal\Caliper\entities\question\OpenEndedQuestion;
use IMSGlobal\Caliper\entities\response\Response;
use IMSGlobal\Caliper\entities\response\OpenEndedResponse;
use IMSGlobal\Caliper\entities\response\MultiselectResponse;
use IMSGlobal\Caliper\entities\response\RatingScaleResponse;
use IMSGlobal\Caliper\entities\scale\NumericScale;
use IMSGlobal\Caliper\entities\scale\LikertScale;
use IMSGlobal\Caliper\entities\lis\Membership;
use IMSGlobal\Caliper\entities\lis\Group;
use IMSGlobal\Caliper\entities\lis\Status;
use IMSGlobal\Caliper\entities\feedback\Rating;
use IMSGlobal\Caliper\entities\feedback\Comment;
use IMSGlobal\Caliper\entities\question\RatingScaleQuestion;
use IMSGlobal\Caliper\util\UuidUtil;

class CaliperEntity {
    public static function iPeer() {
        $entity = (new SoftwareApplication( ResourceIRI::iPeer() ))
            ->setName("iPeer")
            ->setVersion(\IPEER_VERSION);

        return $entity;
    }

    public static function session() {
        $user_session = new \SessionComponent();
        $user = $user_session->read('Auth.User');
        $session_id = !is_null($user_session->id()) ? $user_session->id() : '';
        $session_id_hash = sha1($session_id);
        $session_start = $user_session->read('session_start');
        $session_end = $user_session->read('session_end');

        $session = (new Session( ResourceIRI::user_session($session_id_hash)) )
            ->setUser(CaliperActor::generateActor($user))
            ->setClient( CaliperEntity::client( $session_id_hash ) );

        if ($session_start) {
            $session->setStartedAtTime(new \DateTime('@'. $session_start));
        }
        if ($session_end) {
            $session->setEndedAtTime(new \DateTime('@'. $session_end));
        }
        if ($session_start && $session_end) {
            $duration = $session_end - $session_start;
            $session->setDuration(CaliperSensor::iso8601_duration($duration));
        }

		$extensions = [];
		if ( array_key_exists( 'HTTP_REFERER', $_SERVER ) ) {
			$extensions['referer'] = $_SERVER['HTTP_REFERER'];
		}
		if ( [] !== $extensions ) {
			$session->setExtensions( $extensions );
        }

        return $session;
    }

	/**
	 * Generates a SoftwareApplication entity
	 */
	public static function client( $session_id_hash ) {
		$user_client = ( new SoftwareApplication( ResourceIRI::user_client( $session_id_hash ) ) );

		if ( array_key_exists( 'HTTP_HOST', $_SERVER ) ) {
			$user_client->setHost( $_SERVER['HTTP_HOST'] );
		}

		if ( array_key_exists( 'HTTP_USER_AGENT', $_SERVER ) ) {
			$user_client->setUserAgent( $_SERVER['HTTP_USER_AGENT'] );
		}

		// get ip address if available.
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$user_client->setIpAddress( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$user_client->setIpAddress( $_SERVER['REMOTE_ADDR'] );
		} elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$user_client->setIpAddress( $_SERVER['HTTP_CLIENT_IP'] );
		}

		return $user_client;
	}

    public static function webpage($relativePath) {
        if (!is_string($relativePath)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string expected');
        }
        $entity = (new WebPage( ResourceIRI::webpage($relativePath) ));

        return $entity;
    }

    public static function course($course) {
        $entity = (new CourseOffering( ResourceIRI::course($course['id']) ))
            ->setCourseNumber($course['course'])
            ->setExtensions([
                'record_status' => $course['record_status'],
                'homepage' => $course['homepage'],
                'self_enroll' => $course['self_enroll'],
                'canvas_id' => $course['canvas_id'],
            ]);

        if (array_key_exists('title', $course) && $course['title']) {
            $entity->setName($course['title']);
        }
        if (array_key_exists('created', $course) && $course['created']) {
            $entity->setDateCreated(new \DateTime($course['created']));
        }
        if (array_key_exists('modified', $course) && $course['modified']) {
            $entity->setDateModified(new \DateTime($course['modified']));
        }

        return $entity;
    }

    public static function membership($course, $user, $roles) {
        $entity = (new Membership( ResourceIRI::membership($course['id'], $user['id']) ))
            ->setOrganization( (new CourseOffering( ResourceIRI::course($course['id']) ))->makeReference() )
            ->setMember( CaliperActor::generateActor($user) )
            ->setStatus(new Status(Status::ACTIVE));

        if ($roles && count($roles) > 0) {
            $entity->setRoles($roles);
        }

        $extensions = array();
        if (array_key_exists('canvas_id', $course) && $course['canvas_id']) {
            $extensions['canvas_course_id'] = $course['canvas_id'];
        }
        if (array_key_exists('lti_id', $user) && $user['lti_id']) {
            $extensions['lti_user_id'] = $user['lti_id'];
        }

        if (count($extensions) > 0) {
            $entity->setExtensions($extensions);
        }

        return $entity;
    }

    public static function group($course, $group) {
        $members = array();
        foreach ($group['User'] as $user) {
            $members[]= CaliperActor::generateActor($user);
        }

        $entity = (new Group( ResourceIRI::group($group['id']) ))
            ->setName($group['group_name'])
            ->setSubOrganizationOf( CaliperEntity::course($course) )
            ->setMembers( $members )
            ->setExtensions([
                'group_num' => $group['group_num'],
                'record_status' => $group['record_status'],
            ]);
        if (array_key_exists('created', $group) && $group['created']) {
            $entity->setDateCreated(new \DateTime($group['created']));
        }
        if (array_key_exists('modified', $group) && $group['modified']) {
            $entity->setDateModified(new \DateTime($group['modified']));
        }

        return $entity;
    }

    private static function _event_base($event) {
        $penalties = array();
        foreach($event['Penalty'] as $penalty) {
            $penalties[] = [
                'days_late' => $penalty['days_late'],
                'percent_penalty' => $penalty['percent_penalty'],
            ];
        }

        $entity = (new Assessment( ResourceIRI::event($event['id']) ))
            ->setName($event['title'])
            ->setDateToShow(new \DateTime($event['release_date_begin']))
            ->setDateToStartOn(new \DateTime($event['release_date_begin']))
            ->setDateToSubmit(new \DateTime($event['due_date']))
            ->setIsPartOf( CaliperEntity::course($event['Course']) )
            ->setExtensions([
                'event_template_type_id' => $event['event_template_type_id'],
                'template_id' => $event['template_id'],
                'self_eval' => $event['self_eval'],
                'com_req' => $event['com_req'],
                'auto_release' => $event['auto_release'],
                'enable_details' => $event['enable_details'],
                'record_status' => $event['record_status'],
                'canvas_assignment_id' => $event['canvas_assignment_id'],
                'due_date' => $event['due_date'],
                'release_date_begin' => $event['release_date_begin'],
                'release_date_end' => $event['release_date_end'],
                'result_release_date_begin' => $event['result_release_date_begin'],
                'result_release_date_end' => $event['result_release_date_end'],
                'penalties' => $penalties,
            ]);

        if (array_key_exists('description', $event) && $event['description']) {
            $entity->setDescription($event['description']);
        }
        if (array_key_exists('created', $event) && $event['created']) {
            $entity->setDateCreated(new \DateTime($event['created']));
        }
        if (array_key_exists('modified', $event) && $event['modified']) {
            $entity->setDateModified(new \DateTime($event['modified']));
        }

        return $entity;
    }

    public static function simple_evaluation($simple_evaluation) {
        $entity = (new Assessment( ResourceIRI::simple_evaluation($simple_evaluation['id']) ))
            ->setName($simple_evaluation['name'])
            ->setItems([
                (new AssessmentItem( ResourceIRI::simple_evaluation_question($simple_evaluation['id'], 1) )),
            ])
            ->setExtensions([
                'point_per_member' => $simple_evaluation['point_per_member'],
                'record_status' => $simple_evaluation['record_status'],
                'availability' => $simple_evaluation['availability'],
            ]);

        if (array_key_exists('description', $simple_evaluation) && $simple_evaluation['description']) {
            $entity->setDescription($simple_evaluation['description']);
        }
        if (array_key_exists('created', $simple_evaluation) && $simple_evaluation['created']) {
            $entity->setDateCreated(new \DateTime($simple_evaluation['created']));
        }
        if (array_key_exists('modified', $simple_evaluation) && $simple_evaluation['modified']) {
            $entity->setDateModified(new \DateTime($simple_evaluation['modified']));
        }

        return $entity;
    }

    public static function event_simple_evaluation($event, $simple_evaluation) {
        $entity = CaliperEntity::_event_base($event);

        $entity->setItems([
            (new AssessmentItem( ResourceIRI::event_question($event['id'], 1) )),
        ]);

        $extensions = $entity->getExtensions() ?: [];
        $extensions['type'] = 'simple_evaluation';
        $extensions['simple_evaluation'] = [
            'point_per_member' => $simple_evaluation['point_per_member'],
            'record_status' => $simple_evaluation['record_status'],
            'availability' => $simple_evaluation['availability'],
        ];
        $entity->setExtensions($extensions);

        return $entity;
    }

    public static function simple_evaluation_question($event, $simple_evaluation, $scale_max=NULL) {
        $id = !is_null($event) ?
            ResourceIRI::event_question($event['id'], 1) :
            ResourceIRI::simple_evaluation_question($simple_evaluation['id'], 1);
        $isPartOf = !is_null($event) ?
            CaliperEntity::event_simple_evaluation($event, $simple_evaluation) :
            CaliperEntity::simple_evaluation($simple_evaluation);

        $entity = (new AssessmentItem( $id ))
            ->setIsPartOf($isPartOf)
            ->setExtensions([
                'scale' => CaliperEntity::simple_evaluation_question_scale($event, $simple_evaluation, $scale_max),
            ]);

        return $entity;
    }

    public static function simple_evaluation_question_scale($event, $simple_evaluation, $scale_max=NULL) {
        $id = !is_null($event) ?
            ResourceIRI::event_question_scale($event['id'], 1) :
            ResourceIRI::simple_evaluation_question_scale($simple_evaluation['id'], 1);

        $entity = (new NumericScale( $id ))
            ->setMinValue(0.0)
            ->setStep(1.0)
            ->setExtensions([
                'point_per_member' => $simple_evaluation['point_per_member'],
            ]);

        if ($scale_max) {
            $entity->setMaxValue($scale_max);
        }

        return $entity;
    }

    public static function simple_evaluation_group_member_question($event, $simple_evaluation, $scale_max, $group, $user) {
        $entity = (new AssessmentItem( ResourceIRI::event_group_member_question($event['id'], 1, $group['id'], $user['id']) ))
            ->setIsPartOf( CaliperEntity::simple_evaluation_question($event, $simple_evaluation, $scale_max) );

        return $entity;
    }

    public static function simple_evaluation_group_member_response($event, $group, $user, $evaluation_simple) {
        $entity = (new RatingScaleResponse( ResourceIRI::event_group_member_response($event['id'], 1, $group['id'], $user['id'], $evaluation_simple['id']) ))
            ->setSelections([sprintf("%.1f", $evaluation_simple['score'])])
            ->setExtensions([
                'evaluatee' => CaliperActor::generateActor($user),
                'comment' => $evaluation_simple['comment'],
                'release_status' => $evaluation_simple['release_status'],
                'grade_release' => $evaluation_simple['grade_release'],
                'record_status' => $evaluation_simple['record_status']
            ]);

        if (array_key_exists('date_submitted', $evaluation_simple) && $evaluation_simple['date_submitted']) {
            $entity->setEndedAtTime(new \DateTime($evaluation_simple['date_submitted']));
        }
        if (array_key_exists('created', $evaluation_simple) && $evaluation_simple['created']) {
            $entity->setDateCreated(new \DateTime($evaluation_simple['created']));
        }
        if (array_key_exists('modified', $evaluation_simple) && $evaluation_simple['modified']) {
            $entity->setDateModified(new \DateTime($evaluation_simple['modified']));
        }

        return $entity;
    }

    public static function simple_evaluation_feedback_rating($rater_user, $rated_user, $event, $simple_evaluation, $scale_max, $evaluation_simple) {
        $rater_actor = CaliperActor::generateActor($rater_user);
        $rated_user = CaliperActor::generateActor($rated_user);

        $entity = (new Rating( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
            ->setRater( $rater_actor )
            ->setRated( $rated_user )
            ->setQuestion(
                (new RatingScaleQuestion( ResourceIRI::event_feedback_question($event['id'], 1) ))
                    ->setScale( CaliperEntity::simple_evaluation_question_scale($event, $simple_evaluation, $scale_max) )
            )
            ->setSelections([sprintf("%.1f", $evaluation_simple['score'])]);

        if (array_key_exists('comment', $evaluation_simple) && $evaluation_simple['comment']) {
            $entity->setRatingComment(
                (new Comment( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
                    ->setCommenter( $rater_actor )
                    ->setcommentedOn( $rated_user )
                    ->setValue( $evaluation_simple['comment'] )
            );
        }

        return $entity;
    }

    public static function rubric($rubric, $questions) {
        $items = array();
        foreach ($questions as $question) {
            $items[]= (new AssessmentItem( ResourceIRI::rubric_question($rubric['id'], $question['id']) ));
        }

        $entity = (new Assessment( ResourceIRI::rubric($rubric['id']) ))
            ->setName($rubric['name'])
            ->setItems($items)
            ->setExtensions([
                'zero_mark' => $rubric['zero_mark'],
                'lom_max' => $rubric['lom_max'],
                'criteria' => $rubric['criteria'],
                'view_mode' => $rubric['view_mode'],
                'availability' => $rubric['availability'],
                'template' => $rubric['template'],
            ]);

        if (array_key_exists('created', $rubric) && $rubric['created']) {
            $entity->setDateCreated(new \DateTime($rubric['created']));
        }
        if (array_key_exists('modified', $rubric) && $rubric['modified']) {
            $entity->setDateModified(new \DateTime($rubric['modified']));
        }

        return $entity;
    }

    public static function event_rubric($event, $rubric, $questions) {
        $entity = CaliperEntity::_event_base($event);

        $items = array();
        foreach ($questions as $question) {
            $items[]= (new AssessmentItem( ResourceIRI::event_question($event['id'], $question['id']) ));
        }
        $entity->setItems($items);

        $extensions = $entity->getExtensions() ?: [];
        $extensions['type'] = 'rubric';
        $extensions['rubric'] = [
            'zero_mark' => $rubric['zero_mark'],
            'lom_max' => $rubric['lom_max'],
            'criteria' => $rubric['criteria'],
            'view_mode' => $rubric['view_mode'],
            'availability' => $rubric['availability'],
            'template' => $rubric['template'],
        ];
        $entity->setExtensions($extensions);

        return $entity;
    }

    public static function rubric_question($event, $rubric, $questions, $question, $loms) {
        $id = !is_null($event) ?
            ResourceIRI::event_question($event['id'], $question['id']) :
            ResourceIRI::rubric_question($rubric['id'], $question['id']);
        $isPartOf = !is_null($event) ?
            CaliperEntity::event_rubric($event, $rubric, $questions) :
            CaliperEntity::rubric($rubric, $questions);

        $entity = (new AssessmentItem( $id ))
            ->setName($question['criteria'])
            ->setIsPartOf($isPartOf)
            ->setExtensions([
                'criteria_num' => $question['criteria_num'],
                'multiplier' => $question['multiplier'],
                'show_marks' => $question['show_marks'],
                'scale' => CaliperEntity::rubric_question_scale($event, $rubric, $question, $loms),
            ]);

        return $entity;
    }

    public static function rubric_question_scale($event, $rubric, $question, $loms) {
        $id = !is_null($event) ?
            ResourceIRI::event_question_scale($event['id'], $question['id']) :
            ResourceIRI::rubric_question_scale($rubric['id'], $question['id']);

        $sorted_loms = \Set::sort($loms, '{n}.lom_num', 'asc');
        $item_labels = array();
        $item_values = array();
        foreach($sorted_loms as $lom) {
            $count = $rubric['zero_mark'] ? count($loms) - 1 : count($loms);
            $lom_num = $rubric['zero_mark'] ? $lom['lom_num'] - 1 : $lom['lom_num'];
            // both must be string
            $item_labels[]= "". $lom['lom_comment'];
            $item_values[]= "". ((float) $question['multiplier'] * $lom_num / $count);
        }

        $entity = (new LikertScale( $id ))
            ->setScalePoints(count($loms))
            ->setItemLabels($item_labels)
            ->setItemValues($item_values)
            ->setExtensions([
                'comments' => \Set::extract($question, '/RubricsCriteriaComment/criteria_comment'),
            ]);

        return $entity;
    }


    public static function rubric_group_member_question($event, $rubric, $questions, $question, $loms, $group, $user) {
        $entity = (new AssessmentItem( ResourceIRI::event_group_member_question($event['id'], $question['id'], $group['id'], $user['id']) ))
            ->setIsPartOf( CaliperEntity::rubric_question($event, $rubric, $questions, $question, $loms) );

        return $entity;
    }

    public static function rubric_group_member_response($event, $question, $group, $user, $evaluation_rubric, $evaluation_rubric_detail) {
        $entity = (new RatingScaleResponse( ResourceIRI::event_group_member_response($event['id'], $question['id'], $group['id'], $user['id'], $evaluation_rubric_detail['id']) ))
            ->setSelections([sprintf("%.2f", $evaluation_rubric_detail['grade'])])
            ->setExtensions([
                'evaluatee' => CaliperActor::generateActor($user),
                'selected_lom' => $evaluation_rubric_detail['selected_lom'],
                'criteria_comment' => $evaluation_rubric_detail['criteria_comment'],
                'comment_release' => $evaluation_rubric_detail['comment_release'],
                'record_status' => $evaluation_rubric_detail['record_status']
            ]);

        if (array_key_exists('date_submitted', $evaluation_rubric) && $evaluation_rubric['date_submitted']) {
            $entity->setEndedAtTime(new \DateTime($evaluation_rubric['date_submitted']));
        }
        if (array_key_exists('created', $evaluation_rubric_detail) && $evaluation_rubric_detail['created']) {
            $entity->setDateCreated(new \DateTime($evaluation_rubric_detail['created']));
        }
        if (array_key_exists('modified', $evaluation_rubric_detail) && $evaluation_rubric_detail['modified']) {
            $entity->setDateModified(new \DateTime($evaluation_rubric_detail['modified']));
        }

        return $entity;
    }

    public static function rubric_feedback_rating($rater_user, $rated_user, $event, $rubric, $question, $loms, $evaluation_rubric_detail) {
        $rater_actor = CaliperActor::generateActor($rater_user);
        $rated_user = CaliperActor::generateActor($rated_user);

        $entity = (new Rating( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
            ->setRater( $rater_actor )
            ->setRated( $rated_user )
            ->setQuestion(
                (new RatingScaleQuestion( ResourceIRI::event_feedback_question($event['id'], $question['id']) ))
                    ->setScale( CaliperEntity::rubric_question_scale($event, $rubric, $question, $loms) )
                    ->setQuestionPosed( $question['criteria'] )
            )
            ->setSelections([sprintf("%.2f", $evaluation_rubric_detail['grade'])]);

        if (array_key_exists('criteria_comment', $evaluation_rubric_detail) && $evaluation_rubric_detail['criteria_comment']) {
            $entity->setRatingComment(
                (new Comment( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
                    ->setCommenter( $rater_actor )
                    ->setcommentedOn( $rated_user )
                    ->setValue( $evaluation_rubric_detail['criteria_comment'] )
            );
        }

        return $entity;
    }

    public static function rubric_overall_feedback_rating($rater_user, $rated_user, $event, $rubric, $questions, $evaluation_rubric) {
        $rater_actor = CaliperActor::generateActor($rater_user);
        $rated_user = CaliperActor::generateActor($rated_user);

        $min_score = $rubric['zero_mark'] ? 0.0 : 1.0;
        $total_min_score = count($questions) * $min_score;
        $total_max_score = 0.0;
        foreach($questions as $question) {
            $total_max_score += $question['multiplier'];
        }

        $entity = (new Rating( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
            ->setRater( $rater_actor )
            ->setRated( $rated_user )
            ->setQuestion(
                (new RatingScaleQuestion( ResourceIRI::event_feedback_question($event['id'], 'overall') ))
                    ->setScale(
                        (new NumericScale( ResourceIRI::event_question_scale($event['id'], 'overall') ))
                            ->setMinValue( sprintf("%.2f", $total_min_score) )
                            ->setMaxValue( sprintf("%.2f", $total_max_score) )
                    )
            )
            ->setSelections([sprintf("%.2f", $evaluation_rubric['score'])]);

        if (array_key_exists('comment', $evaluation_rubric) && $evaluation_rubric['comment']) {
            $entity->setRatingComment(
                (new Comment( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
                    ->setCommenter( $rater_actor )
                    ->setcommentedOn( $rated_user )
                    ->setValue( $evaluation_rubric['comment'] )
            );
        }

        return $entity;
    }

    public static function mixeval($mixeval, $questions) {
        $items = array();
        foreach ($questions as $question) {
            $items[]= (new AssessmentItem( ResourceIRI::mixeval_question($mixeval['id'], $question['id']) ));
        }

        $entity = (new Assessment( ResourceIRI::mixeval($mixeval['id']) ))
            ->setName($mixeval['name'])
            ->setItems($items)
            ->setExtensions([
                'zero_mark' => $mixeval['zero_mark'],
                'availability' => $mixeval['availability'],
            ]);

        if (array_key_exists('created', $mixeval) && $mixeval['created']) {
            $entity->setDateCreated(new \DateTime($mixeval['created']));
        }
        if (array_key_exists('modified', $mixeval) && $mixeval['modified']) {
            $entity->setDateModified(new \DateTime($mixeval['modified']));
        }

        return $entity;
    }

    public static function event_mixeval($event, $mixeval, $questions) {
        $entity = CaliperEntity::_event_base($event);

        $items = array();
        foreach ($questions as $question) {
            $items[]= (new AssessmentItem( ResourceIRI::event_question($event['id'], $question['id']) ));
        }
        $entity->setItems($items);

        $extensions = $entity->getExtensions() ?: [];
        $extensions['type'] = 'mixeval';
        $extensions['mixeval'] = [
            'zero_mark' => $mixeval['zero_mark'],
            'availability' => $mixeval['availability'],
        ];
        $entity->setExtensions($extensions);

        return $entity;
    }

    public static function mixeval_question($event, $mixeval, $questions, $question, $dropdown_scale_max=NULL) {
        $id = !is_null($event) ?
            ResourceIRI::event_question($event['id'], $question['id']) :
            ResourceIRI::mixeval_question($mixeval['id'], $question['id']);
        $isPartOf = !is_null($event) ?
            CaliperEntity::event_mixeval($event, $mixeval, $questions) :
            CaliperEntity::mixeval($mixeval, $questions);

        $entity = (new AssessmentItem( $id ))
            ->setName($question['title'])
            ->setIsPartOf( $isPartOf );

        $extensions = [
            'question_num' => $question['question_num'],
            'required' => $question['required'],
            'self_eval' => $question['self_eval'],
            'scale_level' => $question['scale_level'],
            'show_marks' => $question['show_marks'],
            'question_type' => $question['MixevalQuestionType']['type'],
        ];

        if (array_key_exists('instructions', $mixeval) && $mixeval['instructions']) {
            $entity->setDescription($mixeval['instructions']);
        }

        # Likert
        if ($question['mixeval_question_type_id'] == 1 || $question['mixeval_question_type_id'] == 4) {
            $extensions['scale'] = CaliperEntity::mixeval_question_scale($event, $mixeval, $question, $dropdown_scale_max);
        }

        $entity->setExtensions($extensions);

        return $entity;
    }

    public static function mixeval_question_scale($event, $mixeval, $question, $dropdown_scale_max=NULL) {
        $id = !is_null($event) ?
            ResourceIRI::event_question_scale($event['id'], $question['id']) :
            ResourceIRI::mixeval_question_scale($mixeval['id'], $question['id']);

        $entity = NULL;

        if ($question['mixeval_question_type_id'] == 1) {
            $descriptions = $question['MixevalQuestionDesc'];
            $sorted_descriptions = \Set::sort($descriptions, '{n}.scale_level', 'asc');
            $item_labels = array();
            $item_values = array();
            foreach($sorted_descriptions as $description) {
                $count = $mixeval['zero_mark'] ? count($descriptions) - 1 : count($descriptions);
                $scale_level = $mixeval['zero_mark'] ? $description['scale_level'] - 1 : $description['scale_level'];
                // both must be string
                $item_labels[]= "". $description['descriptor'];
                $item_values[]= "". ((float) $question['multiplier'] * $scale_level /  $count);
            }

            $entity = (new LikertScale( $id ))
                ->setScalePoints(count($descriptions))
                ->setItemLabels($item_labels)
                ->setItemValues($item_values);
        } else if ($question['mixeval_question_type_id'] == 4) {
            $max_value = !is_null($dropdown_scale_max) ? (float) $dropdown_scale_max : 10.0;

            $entity = (new NumericScale( $id ))
                ->setMinValue(0.0)
                ->setMaxValue($max_value)
                ->setStep(1.0);
        }

        return $entity;
    }


    public static function mixeval_group_member_question($event, $mixeval, $questions, $question, $dropdown_scale_max, $group, $user) {
        $entity = (new AssessmentItem( ResourceIRI::event_group_member_question($event['id'], $question['id'], $group['id'], $user['id']) ))
            ->setIsPartOf( CaliperEntity::mixeval_question($event, $mixeval, $questions, $question, $dropdown_scale_max) );

        return $entity;
    }

    public static function mixeval_group_member_response($event, $question, $group, $user, $evaluation_mixeval, $evaluation_mixeval_detail) {
        $id = ResourceIRI::event_group_member_response($event['id'], $question['id'], $group['id'], $user['id'], $evaluation_mixeval_detail['id']);
        $entity = NULL;

        # Likert
        if ($question['mixeval_question_type_id'] == 1 || $question['mixeval_question_type_id'] == 4) {
            $entity = (new RatingScaleResponse( $id ))
                ->setSelections([sprintf("%.2f", $evaluation_mixeval_detail['grade'])]);
        } else {
            $entity = (new OpenEndedResponse( $id ))
                ->setValue($evaluation_mixeval_detail['question_comment']);
        }

        $entity->setExtensions([
            'evaluatee' => CaliperActor::generateActor($user),
            'selected_lom' => $evaluation_mixeval_detail['selected_lom'],
            'comment_release' => $evaluation_mixeval_detail['comment_release'],
            'record_status' => $evaluation_mixeval_detail['record_status']
        ]);

        if (array_key_exists('date_submitted', $evaluation_mixeval) && $evaluation_mixeval['date_submitted']) {
            $entity->setEndedAtTime(new \DateTime($evaluation_mixeval['date_submitted']));
        }
        if (array_key_exists('created', $evaluation_mixeval_detail) && $evaluation_mixeval_detail['created']) {
            $entity->setDateCreated(new \DateTime($evaluation_mixeval_detail['created']));
        }
        if (array_key_exists('modified', $evaluation_mixeval_detail) && $evaluation_mixeval_detail['modified']) {
            $entity->setDateModified(new \DateTime($evaluation_mixeval_detail['modified']));
        }

        return $entity;
    }

    public static function mixeval_feedback($rater_user, $rated_user, $event, $mixeval, $question, $dropdown_scale_max, $evaluation_mixeval_detail) {
        $rater_actor = CaliperActor::generateActor($rater_user);
        $rated_user = CaliperActor::generateActor($rated_user);

        $entity = NULL;

        if ($question['mixeval_question_type_id'] == 1 || $question['mixeval_question_type_id'] == 4) {
            $entity = (new Rating( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
                ->setRater( $rater_actor )
                ->setRated( $rated_user )
                ->setQuestion(
                    (new RatingScaleQuestion( ResourceIRI::event_feedback_question($event['id'], $question['id']) ))
                        ->setScale( CaliperEntity::mixeval_question_scale($event, $mixeval, $question, $dropdown_scale_max) )
                        ->setQuestionPosed( $question['title'] )
                )
                ->setSelections([sprintf("%.2f", $evaluation_mixeval_detail['grade'])]);
        } else {
            $entity = (new Comment( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
                ->setCommenter( $rater_actor )
                ->setcommentedOn( $rated_user )
                ->setValue( $evaluation_mixeval_detail['question_comment'] )
                ->setExtensions([
                    'questionPosed' => $question['title'],
                ]);
        }

        return $entity;
    }

    public static function mixeval_overall_feedback_rating($rater_user, $rated_user, $event, $mixeval, $questions, $evaluation_mixeval) {
        $rater_actor = CaliperActor::generateActor($rater_user);
        $rated_user = CaliperActor::generateActor($rated_user);

        $total_max_score = 0.0;
        foreach($questions as $question) {
            $total_max_score += $question['multiplier'];
        }

        $entity = (new Rating( 'urn:uuid:' . UuidUtil::makeUuidV4() ))
            ->setRater( $rater_actor )
            ->setRated( $rated_user )
            ->setQuestion(
                (new RatingScaleQuestion( ResourceIRI::event_feedback_question($event['id'], 'overall') ))
                    ->setScale(
                        (new NumericScale( ResourceIRI::event_question_scale($event['id'], 'overall') ))
                            ->setMinValue( 0.0 )
                            ->setMaxValue( sprintf("%.2f", $total_max_score) )
                    )
            )
            ->setSelections([sprintf("%.2f", $evaluation_mixeval['score'])]);

        return $entity;
    }

    public static function survey($survey, $questions) {
        // an iPeer survey maps to a caliper questionnaire (not a caliper survey)

        $items = array();
        foreach ($questions as $question) {
            $items[]= (new QuestionnaireItem( ResourceIRI::survey_item($survey['id'], $question['id']) ));
        }

        $entity = (new Questionnaire( ResourceIRI::survey($survey['id']) ))
            ->setName($survey['name'])
            ->setItems($items)
            ->setExtensions([
                'availability' => $survey['availability'],
            ]);

        if (array_key_exists('created', $survey) && $survey['created']) {
            $entity->setDateCreated(new \DateTime($survey['created']));
        }
        if (array_key_exists('modified', $survey) && $survey['modified']) {
            $entity->setDateModified(new \DateTime($survey['modified']));
        }

        return $entity;
    }

    public static function event_survey($event, $survey, $questions) {
        $items = array();
        foreach ($questions as $question) {
            $items[]= (new QuestionnaireItem( ResourceIRI::event_survey_item($event['id'], $question['id']) ));
        }

        $entity = (new Questionnaire( ResourceIRI::event($event['id']) ))
            ->setName($event['title'])
            ->setIsPartOf( CaliperEntity::course($event['Course']) )
            ->setItems($items)
            ->setExtensions([
                'event_template_type_id' => $event['event_template_type_id'],
                'template_id' => $event['template_id'],
                'self_eval' => $event['self_eval'],
                'com_req' => $event['com_req'],
                'auto_release' => $event['auto_release'],
                'enable_details' => $event['enable_details'],
                'record_status' => $event['record_status'],
                'canvas_assignment_id' => $event['canvas_assignment_id'],
                'due_date' => $event['due_date'],
                'release_date_begin' => $event['release_date_begin'],
                'release_date_end' => $event['release_date_end'],
                'type' => 'survey',
                'survey' => [
                    'availability' => $survey['availability'],
                ]
            ]);

        if (array_key_exists('description', $event) && $event['description']) {
            $entity->setDescription($event['description']);
        }
        if (array_key_exists('created', $event) && $event['created']) {
            $entity->setDateCreated(new \DateTime($event['created']));
        }
        if (array_key_exists('modified', $event) && $event['modified']) {
            $entity->setDateModified(new \DateTime($event['modified']));
        }

        return $entity;
    }

    public static function survey_item($event, $survey, $questions, $question) {
        $id = !is_null($event) ?
            ResourceIRI::event_survey_item($event['id'], $question['id']) :
            ResourceIRI::survey_item($survey['id'], $question['id']);
        $isPartOf = !is_null($event) ?
            CaliperEntity::event_survey($event, $survey, $questions) :
            CaliperEntity::survey($survey, $questions);

        $entity = (new QuestionnaireItem( $id ))
            ->setQuestion(CaliperEntity::survey_item_question($event, $survey, $questions, $question))
            ->setIsPartOf($isPartOf)
            ->setExtensions([
                'type' => $question['type'],
                'master' => $question['master']
            ]);

        return $entity;
    }

    public static function survey_item_question($event, $survey, $questions, $question) {
        $id = !is_null($event) ?
            ResourceIRI::event_survey_item_question($event['id'], $question['id']) :
            ResourceIRI::survey_item_question($survey['id'], $question['id']);

        $entity = NULL;

        if ($question['type'] == 'M' || $question['type'] == 'C') {
            $response_values = array();
            foreach ($question['Response'] as $response) {
                $response_values[]= $response['response'];
            }

            $entity = (new MultiselectQuestion($id))
                ->setPoints(count($response_values))
                ->setItemLabels($response_values)
                ->setItemValues($response_values);
        } elseif ($question['type'] == 'S' || $question['type'] == 'L') {
            $entity = (new OpenEndedQuestion($id));
        } else {
            // prevent an error if a new survey question type is added but not taken into account here
            return $id;
        }

        $entity->setQuestionPosed($question['prompt'])
            ->setExtensions([
                'type' => $question['type']
            ]);

        return $entity;
    }

    public static function survey_item_response($event, $survey, $questions, $question, $survey_inputs) {
        $survey_inputs = is_array($survey_inputs) ? $survey_inputs : [$survey_inputs];
        $id = ResourceIRI::event_survey_item_response($event['id'], $question['id'], $survey_inputs[0]['id']);
        $entity = NULL;

        if ($question['type'] == 'M' || $question['type'] == 'C') {
            $response_values = array();
            foreach ($question['Response'] as $response) {
                $response_values[]= $response['response'];
            }
            $selections = array();
            foreach ($survey_inputs as $survey_input) {
                $selections[]= $survey_input['response_text'];
            }
            $entity = (new MultiselectResponse($id))
                ->setSelections($selections);
        } elseif ($question['type'] == 'S' || $question['type'] == 'L') {
            $entity = (new OpenEndedResponse($id))
                ->setValue($survey_inputs[0]['response_text']);
        } else {
            // prevent an error if a new survey question type is added but not taken into account here
            $entity = (new Response($id));
        }

        return $entity;
    }
}