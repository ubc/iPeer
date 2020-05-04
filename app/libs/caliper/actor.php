<?php
namespace caliper;

use caliper\ResourceIRI;
use IMSGlobal\Caliper\entities\agent\Person;

class CaliperActor {
    private static function _generateIPeerActor(&$user) {
        $id = ResourceIRI::actor_homepage($user['id']);

        # ex CALIPER_ACTOR_BASE_URL = 'http://www.ubc.ca/%s'
        if (is_string(getenv('CALIPER_ACTOR_BASE_URL')) && is_string(getenv('CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM'))) {
            if (array_key_exists(getenv('CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM'), $user)) {
                $unique_id = $user[getenv('CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM')];
                $id = sprintf(getenv('CALIPER_ACTOR_BASE_URL'), $unique_id);
            }
        }

        $actor = (new Person($id))
            ->setName($user['full_name']);

        if ($user['created']) {
            $actor->setDateCreated(new \DateTime($user['created']));
        }
        if ($user['modified']) {
            $actor->setDateModified(new \DateTime($user['modified']));
        }

        $extensions = [];
        if ($user['title']) {
            $extensions['title'] = $user['title'];
        }
        if ($user['student_no']) {
            $extensions['student_no'] = $user['student_no'];
        }

        if ([] !== $extensions) {
            $actor->setExtensions($extensions);
        }

        return $actor;
    }

    public static function generateActor(&$user) {
        # happens when not logged in
        if (!$user['id']) {
            return Person::makeAnonymous();
        }

        return self::_generateIPeerActor($user);
    }
}
