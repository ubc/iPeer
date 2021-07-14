<?php
namespace lti;

\App::import('Model', 'User');

class NamesAndRolesServiceDataParser {
    public $member_data;

    public function __construct($lti_tool_registration, $member_data)
    {
        $this->User = \ClassRegistry::init('User');
        $this->member_data = $member_data;
        $this->lti_tool_registration = $lti_tool_registration;
        return $this;
    }

    public function getParam($param_name) {
        if (!isset($this->member_data)) {
            return null;
        }
        if (!isset($this->member_data[$param_name])) {
            return null;
        }
        return $this->member_data[$param_name];
    }

    public function isActive() {
        return $this->getParam('status') == 'Active';
    }

    public function getUserData() {
        return array(
            'username' => $this->getUserIdentifierValue(),
            'first_name' => $this->getParam('given_name'),
            'last_name' => $this->getParam('family_name'),
            'student_no' => $this->getStudentNumberValue(),
            'email' => $this->getParam('email'),
        );
    }

    # Core context roles
    # http://purl.imsglobal.org/vocab/lis/v2/membership#Administrator
    # http://purl.imsglobal.org/vocab/lis/v2/membership#ContentDeveloper
    # http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor
    # http://purl.imsglobal.org/vocab/lis/v2/membership#Learner
    # http://purl.imsglobal.org/vocab/lis/v2/membership#Mentor
    # Instructor 	Sub-role
    # Grader, GuestInstructor, Instructor, Lecturer, PrimaryInstructor
    # SecondaryInstructor, TeachingAssistant, TeachingAssistantGroup
    # TeachingAssistantOffering, TeachingAssistantSection, TeachingAssistantTemplate
    public function getCourseRole() {
        $roles = $this->getParam('roles');
        $is_admin = $is_instructor = $is_student = $is_ta = $is_content_developer = FALSE;

        foreach ($roles as $role) {
            # supports long and short formats. ex:
            # http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor
            # Instructor
            # http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant
            # Instructor#TeachingAssistant
            $short_role = preg_replace('/http\:\/\/purl\.imsglobal\.org\/vocab\/lis\/v2\/membership(\#|\/)/i', '', $role);
            if ('Administrator' == $short_role) {
                $is_admin = TRUE;
            } elseif ('Instructor' == $short_role) {
                $is_instructor = TRUE;
            } elseif ('ContentDeveloper' == $short_role) {
                $is_content_developer = TRUE;
            } elseif ('Instructor#TeachingAssistant' == $short_role) {
                $is_ta = TRUE;
            } elseif ('Learner' == $short_role) {
                $is_student = TRUE;
            }
        }

        if ($is_admin) {
            return $this->User->USER_TYPE_INSTRUCTOR;
        } elseif ($is_instructor && !$is_ta) {
            return $this->User->USER_TYPE_INSTRUCTOR;
        } elseif ($is_content_developer) {
            return $this->User->USER_TYPE_INSTRUCTOR;
        } elseif ($is_ta) {
            return $this->User->USER_TYPE_TA;
        } else {
            return $this->User->USER_TYPE_STUDENT;
        }
    }

    private function getDynamicFieldValue($field) {
        if (empty($field)) {
            return null;
        }
        $field_parts = explode("|", $field);
        $data_ref = null;

        if (sizeof($field_parts) == 1 && !empty($this->member_data[$field_parts[0]])) {
            return $this->member_data[$field_parts[0]];
        }

        foreach ($this->member_data['message'] as $message) {
            if ($message['https://purl.imsglobal.org/spec/lti/claim/message_type'] != 'LtiResourceLinkRequest') {
                continue;
            }
            $data_ref = $message;
            foreach($field_parts as $field_part) {
                if (!is_array($data_ref) || empty($data_ref[$field_part])) {
                    return null;
                }
                $data_ref = $data_ref[$field_part];
            }
            break;
        }

        if (is_array($data_ref) || empty($data_ref)) {
            return null;
        }
        return $data_ref;
    }

    public function getUserIdentifierValue() {
        if (!empty($this->lti_tool_registration['user_identifier_field'])) {
            $user_identifier_field = $this->lti_tool_registration['user_identifier_field'];

            $value = $this->getDynamicFieldValue($user_identifier_field);
            if (!empty($value)) {
                return $value;
            }
        }
        // default user identifer value
        return $this->getParam('user_id');
    }

    public function getStudentNumberValue() {
        if (!empty($this->lti_tool_registration['student_number_field'])) {
            $student_number_field = $this->lti_tool_registration['student_number_field'];

            $value = $this->getDynamicFieldValue($student_number_field);
            if (!empty($value)) {
                return $value;
            }
        }
        // default student number value
        return null;
    }
}
