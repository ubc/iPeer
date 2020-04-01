<?php
namespace caliper;

use caliper\CaliperActor;
use caliper\CaliperEntity;
use IMSGlobal\Caliper\events\Event;
use IMSGlobal\Caliper\entities\lis\Role;

class CaliperEvent {
    public static function addDefaults(Event &$event, $course = NULL, $roles = NULL, $group = NULL) {
        $User = \ClassRegistry::init('User');

        $session = new \SessionComponent();
        $user = $session->read('Auth.User');

        $event->setActor(CaliperActor::generateActor($user));
        $event->setSession(CaliperEntity::session());
        $event->setEdApp(CaliperEntity::iPeer());

        if (!$event->getEventTime()) {
            $event->setEventTime(new \DateTime('@'. time()));
        }

        if (!is_null($course)) {
            if (!is_null($group)) {
                // load group members if needed
                if (!array_key_exists('User', $group)) {
                    $members = $User->getMembersByGroupId($group['id']);
                    $group['User'] = array();
                    foreach ($members as $member) {
                        $group['User'][] = $member['User'];
                    }
                }
                $event->setGroup(CaliperEntity::group($course, $group));
            } else {
                $event->setGroup(CaliperEntity::course($course));
            }
            $event->setMembership(CaliperEntity::membership($course, $user, $roles));
        }
    }

    public static function generateRolesForCourse($course) {
        $User = \ClassRegistry::init('User');
        $Role = \ClassRegistry::init('Role');

        $session = new \SessionComponent();
        $user = $session->read('Auth.User');

        $roles = array();
        if ($course && array_key_exists('id', $course) && $user && array_key_exists('id', $user)) {
            $results = $Role->find('all', array(
                'conditions' => array('User.id' => $user['id']),
                'recursive' => 0
            ));
            $system_roles = array_combine(\Set::extract('/Role/id', $results), \Set::extract('/Role/name', $results));
            $results = $User->find('first', array(
                'conditions' => array('User.id' => $user['id']),
                'contain' => array(
                    'Course' => array('conditions' => array('Course.id' => $course['id'])),
                    'Tutor' => array('conditions' => array('Tutor.id' => $course['id'])),
                    'Enrolment' => array('conditions' => array('Enrolment.id' => $course['id'])),
                )
            ));

            if (sizeof($results['Course']) > 0 || sizeof($results['Tutor']) > 0) {
                $roles[] = new Role(Role::INSTRUCTOR);
            }
            if (sizeof($results['Tutor']) > 0) {
                $roles[] = new Role(Role::TEACHING_ASSISTANT);
            }
            if (sizeof($results['Enrolment']) > 0) {
                $roles[] = new Role(Role::LEARNER);
            }
            if (in_array('superadmin', $system_roles) || in_array('admin', $system_roles)) {
                $roles[] = new Role(Role::ADMINISTRATOR);
            }
        }
        return $roles;
    }
}