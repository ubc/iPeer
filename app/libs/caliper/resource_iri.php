<?php
namespace caliper;

class ResourceIRI {
    public static function getBaseUrl() {
        if (is_string(getenv('CALIPER_BASE_URL'))) {
            return rtrim(getenv('CALIPER_BASE_URL'), '/');
        }
        return rtrim(\Router::url('/', true), '/');
    }

    public static function iPeer() {
        return self::getBaseUrl();
    }

    public static function actor_homepage($user_id) {
        if (!is_string($user_id) && !is_integer($user_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/users/view/$user_id";
    }

    public static function user_session($session_id_hash) {
        if (!is_string($session_id_hash)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string expected');
        }
        return self::getBaseUrl() . "/session/$session_id_hash";
    }

	public static function user_client( $session_id_hash ) {
		return self::user_session( $session_id_hash ) . '#client';
	}

    public static function webpage($relativePath) {
        if (!is_string($relativePath)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string expected');
        }
        return self::getBaseUrl() . "/$relativePath";
    }

    public static function course($course_id) {
        if (!is_string($course_id) && !is_integer($course_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/courses/view/$course_id";
    }

    public static function membership($course_id, $user_id) {
        if (!is_string($user_id) && !is_integer($user_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::course($course_id) . "/users/$user_id";
    }

    public static function group($group_id) {
        if (!is_string($group_id) && !is_integer($group_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/groups/view/$group_id";
    }

    public static function event($event_id) {
        if (!is_string($event_id) && !is_integer($event_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/events/view/$event_id";
    }

    public static function event_question($event_id, $question_id) {
        if (!is_string($question_id) && !is_integer($question_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::event($event_id) . "/questions/$question_id";
    }

    public static function event_feedback_question($event_id, $question_id) {
        return self::event_question($event_id, $question_id) . "?feedback=true";
    }

    public static function event_group_member_question($event_id, $question_id, $group_id, $user_id) {
        if (!is_string($group_id) && !is_integer($group_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        if (!is_string($user_id) && !is_integer($user_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::event_question($event_id, $question_id) . "/group/$group_id/user/$user_id";
    }

    public static function event_group_member_response($event_id, $question_id, $group_id, $user_id, $response_id) {
        if (!is_string($response_id) && !is_integer($response_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::event_group_member_question($event_id, $question_id, $group_id, $user_id) . "/responses/$response_id";
    }

    public static function event_question_scale($event_id, $question_id) {
        return self::event_question($event_id, $question_id) . "/scale";
    }

    public static function event_survey_item($event_id, $question_id) {
        if (!is_string($question_id) && !is_integer($question_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::event($event_id) . "/questions/$question_id";
    }

    public static function event_survey_item_question($event_id, $question_id) {
        return self::event_survey_item($event_id, $question_id) . "?question=true";
    }

    public static function event_survey_item_response($event_id, $question_id, $survey_input_id) {
        if (!is_string($survey_input_id) && !is_integer($survey_input_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::event_survey_item($event_id, $question_id) . "/responses/$survey_input_id";
    }

    public static function simple_evaluation($simple_evaluation_id) {
        if (!is_string($simple_evaluation_id) && !is_integer($simple_evaluation_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/simpleevaluations/view/$simple_evaluation_id";
    }

    public static function simple_evaluation_question($simple_evaluation_id, $question_number) {
        if (!is_string($question_number) && !is_integer($question_number)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::simple_evaluation($simple_evaluation_id) . "/questions/$question_number";
    }

    public static function simple_evaluation_question_scale($simple_evaluation_id, $question_number) {
        return self::simple_evaluation_question($simple_evaluation_id, $question_number) . "/scale";
    }

    public static function rubric($rubric_id) {
        if (!is_string($rubric_id) && !is_integer($rubric_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/rubrics/view/$rubric_id";
    }

    public static function rubric_question($rubric_id, $question_id) {
        if (!is_string($question_id) && !is_integer($question_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::rubric($rubric_id) . "/questions/$question_id";
    }

    public static function rubric_question_scale($rubric_id, $question_id) {
        return self::rubric_question($rubric_id, $question_id) . "/scale";
    }

    public static function mixeval($mixeval_id) {
        if (!is_string($mixeval_id) && !is_integer($mixeval_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/mixevals/view/$mixeval_id";
    }

    public static function mixeval_question($mixeval_id, $question_id) {
        if (!is_string($question_id) && !is_integer($question_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::mixeval($mixeval_id) . "/questions/$question_id";
    }

    public static function mixeval_question_scale($mixeval_id, $question_id) {
        return self::mixeval_question($mixeval_id, $question_id) . "/scale";
    }

    public static function survey($survey_id) {
        if (!is_string($survey_id) && !is_integer($survey_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::getBaseUrl() . "/surveys/view/$survey_id";
    }

    public static function survey_item($survey_id, $question_id) {
        if (!is_string($question_id) && !is_integer($question_id)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string/integer expected');
        }
        return self::survey($survey_id) . "/questions/$question_id";
    }

    public static function survey_item_question($survey_id, $question_id) {
        return self::survey_item($survey_id, $question_id) . "?question=true";
    }
}