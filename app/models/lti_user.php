<?php

/**
 * LtiUser
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiUser extends AppModel
{
    public $name = 'LtiUser';
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'ipeer_user_id'
        )
    );
    public $validate = array(
        'lti_tool_registration_id' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'lti_user_id' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
    );

    public function syncUser($lti_tool_registration_id, $lti_user_id, $user_data)
    {
        $this->User = ClassRegistry::init('User');

        $lti_user = $this->getByRegistrationIdAndLtiUserId($lti_tool_registration_id, $lti_user_id);
        if (empty($lti_user)) {
            $lti_user = array(
                'LtiUser' => array(
                    'lti_tool_registration_id' => $lti_tool_registration_id,
                    'lti_user_id' => $lti_user_id,
                )
            );
        }

        //setup user link if needed
        if (!isset($lti_user['LtiUser']['ipeer_user_id'])) {
            $existing_user = $this->User->find('first', array(
                'conditions' => array('User.username' => $user_data['username']),
                'contain' => false
            ));
            if (!empty($existing_user)) {
                $lti_user['User'] = $existing_user['User'];
            } else {
                $lti_user['User'] = $user_data;
            }
        }
        //skip updating username
        if (!empty($user_data['first_name'])) {
            $lti_user['User']['first_name'] = $user_data['first_name'];
        }
        if (!empty($user_data['last_name'])) {
            $lti_user['User']['last_name'] = $user_data['last_name'];
        }
        if (!empty($user_data['student_no'])) {
            $lti_user['User']['student_no'] = $user_data['student_no'];
        }
        if (!empty($user_data['email'])) {
            $lti_user['User']['email'] = $user_data['email'];
        }

        $this->saveAll($lti_user);

        // refresh data after save
        $lti_user = $this->getByRegistrationIdAndLtiUserId($lti_tool_registration_id, $lti_user_id);
        return $lti_user;
    }

    public function syncUserEnrollment($course_id, $user_id, $faculty_id, $role_id)
    {
        $this->User = ClassRegistry::init('User');
        $this->Role = ClassRegistry::init('Role');

        # add new system roles as needed
        $results = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user_id),
            'recursive' => 0
        ));
        $system_role_ids = Set::extract('/Role/id', $results);
        if (!in_array($role_id, $system_role_ids)) {
            $this->User->registerRole($user_id, $role_id);
        }

        # handle enrollments
        $results = $this->User->find('first', array(
            'conditions' => array('User.id' => $user_id),
            'contain' => array(
                'Faculty' => array('conditions' => array('Faculty.id' => $faculty_id)),
                'Course' => array('conditions' => array('Course.id' => $course_id)),
                'Tutor' => array('conditions' => array('Tutor.id' => $course_id)),
                'Enrolment' => array('conditions' => array('Enrolment.id' => $course_id)),
            )
        ));

        if (in_array($role_id, [$this->User->USER_TYPE_ADMIN, $this->User->USER_TYPE_INSTRUCTOR])) {
            if (sizeof($results['Course']) == 0) {
                $this->User->addInstructor($user_id, $course_id);
            }
            # ensure user is assigned to a Faculty if instructor or admin
            if (sizeof($results['Faculty']) == 0 && !empty($faculty_id)) {
                $this->User->habtmAdd('Faculty', $user_id, $faculty_id);
            }
        } else {
            if (sizeof($results['Course']) > 0) {
                $this->User->removeInstructor($user_id, $course_id);
            }
        }

        if ($role_id == $this->User->USER_TYPE_TA) {
            if (sizeof($results['Tutor']) == 0) {
                $this->User->addTutor($user_id, $course_id);
            }
        } else {
            if (sizeof($results['Tutor']) > 0) {
                $this->User->removeTutor($user_id, $course_id);
            }
        }

        if ($role_id == $this->User->USER_TYPE_STUDENT) {
            if (sizeof($results['Enrolment']) == 0) {
                $this->User->addStudent($user_id, $course_id);
            }
        } else {
            if (sizeof($results['Enrolment']) > 0) {
                $this->User->removeStudent($user_id, $course_id);
            }
        }
    }

    public function getByRegistrationIdAndLtiUserId($lti_tool_registration_id, $lti_user_id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'LtiUser.lti_tool_registration_id' => $lti_tool_registration_id,
                'LtiUser.lti_user_id' => $lti_user_id,
            ),
            'contain' => array(
                'User'
            )
        ));
    }
}
