<?php
namespace lti;

use IMSGlobal\LTI\LTI_Names_Roles_Provisioning_Service;
use IMSGlobal\LTI\LTI_Service_Connector;
use IMSGlobal\LTI\LTI_Registration;

\App::import('Model', 'User');
\App::import('Model', 'LtiContext');
\App::import('Model', 'LtiUser');

class NamesAndRolesService {
    public $User;
    public $LtiContext;
    public $LtiUser;
    public $course_id;
    public $service_connectors;
    public $lti_contexts;

    public function __construct($course_id)
    {
        $this->User = \ClassRegistry::init('User');
        $this->LtiContext = \ClassRegistry::init('LtiContext');
        $this->LtiUser = \ClassRegistry::init('LtiUser');
        $this->course_id = $course_id;
        $this->service_connectors = array();
        $this->lti_contexts = array();

        $lti_contexts = $this->LtiContext->getByCourseId($course_id);
        foreach ($lti_contexts as $lti_context) {
            if (!empty($lti_context['LtiContext']['nrps_context_memberships_url'])) {
                $this->lti_contexts []= $lti_context;

                $lti_tool_registration = $lti_context['LtiToolRegistration'];
                $registration = LTI_Registration::new()
                    ->set_auth_login_url($lti_tool_registration['auth_login_url'])
                    ->set_auth_token_url($lti_tool_registration['auth_token_url'])
                    ->set_client_id($lti_tool_registration['client_id'])
                    ->set_key_set_url($lti_tool_registration['key_set_url'])
                    ->set_issuer($lti_tool_registration['iss'])
                    ->set_tool_private_key($lti_tool_registration['tool_private_key']);
                $this->service_connectors[$lti_tool_registration['id']] = new LTI_Service_Connector($registration);
            }
        }
        return $this;
    }

    public function sync_membership() {
        $instructors = array();
        $tas = array();
        $students = array();
        foreach ($this->lti_contexts as $lti_context) {
            $lti_tool_registration = $lti_context['LtiToolRegistration'];
            $service_connector = $this->service_connectors[$lti_tool_registration['id']];
            $context_memberships_url = $lti_context['LtiContext']['nrps_context_memberships_url'];

            // get resource link to pull membership from
            $lti_resource_links = $lti_context['LtiResourceLink'];
            $resource_link_id = $lti_resource_links[0]['resource_link_id'];

            // Canvas HACK: if context_id == resource_link_id then resource link
            // will have membership for entire class (use these first if available)
            foreach ($lti_resource_links as $lti_resource_link) {
                if ($lti_resource_link['resource_link_id'] == $lti_context['LtiContext']['context_id']) {
                    $resource_link_id = $lti_resource_link['resource_link_id'];
                    break;
                }
            }

            if (strpos($context_memberships_url, '?') !== false) {
                $context_memberships_url .= "&rlid=$resource_link_id";
            } else {
                $context_memberships_url .= "?rlid=$resource_link_id";
            }

            $service_data = array('context_memberships_url' => $context_memberships_url);
            $nrps = new LTI_Names_Roles_Provisioning_Service($service_connector, $service_data);

            $all_members = $nrps->get_members();
            foreach($all_members as $member_data) {
                $nrsp_data_parser = new NamesAndRolesServiceDataParser($lti_tool_registration, $member_data);
                if ($nrsp_data_parser->isActive()) {
                    // automatically create/update lti user + user
                    $lti_user = $this->LtiUser->syncUser(
                        $nrsp_data_parser->lti_tool_registration['id'],
                        $nrsp_data_parser->getParam('user_id'),
                        $nrsp_data_parser->getUserData()
                    );
                    $user_id = $lti_user['User']['id'];
                    $role_id = $nrsp_data_parser->getCourseRole();

                    // automatically update user enrollment
                    $this->LtiUser->syncUserEnrollment($this->course_id, $user_id, null, $role_id);

                    if (in_array($role_id, [$this->User->USER_TYPE_ADMIN, $this->User->USER_TYPE_INSTRUCTOR])) {
                        $instructors[$user_id] = true;
                    } elseif ($role_id == $this->User->USER_TYPE_TA) {
                        $tas[$user_id] = true;
                    } elseif ($role_id == $this->User->USER_TYPE_STUDENT) {
                        $students[$user_id] = true;
                    }
                }
            }
        }

        // remove old instructors who are no longer present
        $current_instructor_ids = array_keys($instructors);
        $existing_instructors = $this->User->getInstructorsByCourse($this->course_id);
        foreach ($existing_instructors as $instructor) {
            $user_id = $instructor['User']['id'];
            if (!in_array($user_id, $current_instructor_ids)) {
                $this->User->removeInstructor($user_id, $this->course_id);
            }
        }

        // remove old tas who are no longer present
        $current_ta_ids = array_keys($tas);
        $existing_tas = $this->User->getTutorsByCourse($this->course_id);
        foreach ($existing_tas as $ta) {
            $user_id = $ta['User']['id'];
            if (!in_array($user_id, $current_ta_ids)) {
                $this->User->removeTutor($user_id, $this->course_id);
            }
        }

        // remove old students who are no longer present
        $current_student_ids = array_keys($students);
        $existing_students = $this->User->getEnrolledStudents($this->course_id);
        foreach ($existing_students as $student) {
            $user_id = $student['User']['id'];
            if (!in_array($user_id, $current_student_ids)) {
                $this->User->removeStudent($user_id, $this->course_id);
            }
        }
    }
}