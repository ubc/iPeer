<?php
namespace lti;

\App::import('Model', 'LtiToolRegistration');
\App::import('Model', 'User');

class LaunchDataParser {
    public $LtiToolRegistration;
    public $launch_data;
    public $lti_tool_registration;

    public function __construct($launch_data)
    {
        $this->LtiToolRegistration = \ClassRegistry::init('LtiToolRegistration');
        $this->User = \ClassRegistry::init('User');
        $this->launch_data = $launch_data;

        $iss = $launch_data['iss'];
        $results = $this->LtiToolRegistration->findByIss($iss);
        $this->lti_tool_registration = $results['LtiToolRegistration'];
        return $this;
    }

    public function getParam($param_name) {
        if (!isset($this->launch_data)) {
            return null;
        }
        if (!isset($this->launch_data[$param_name])) {
            return null;
        }
        return $this->launch_data[$param_name];
    }

    public function getClaim($claim_name) {
        if (!isset($this->launch_data)) {
            return null;
        }
        if (!isset($this->launch_data["https://purl.imsglobal.org/spec/lti/claim/$claim_name"])) {
            return null;
        }
        return $this->launch_data["https://purl.imsglobal.org/spec/lti/claim/$claim_name"];
    }

    public function getClaimParam($claim_name, $param_name) {
        $claim = $this->getClaim($claim_name);
        if (!isset($claim)) {
            return null;
        }
        if (!isset($claim[$param_name])) {
            return null;
        }
        return $claim[$param_name];
    }

    public function getNrpsClaim() {
        if (!isset($this->launch_data)) {
            return null;
        }
        if (!isset($this->launch_data["https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice"])) {
            return null;
        }
        return $this->launch_data["https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice"];
    }

    public function getNrpsClaimParam($param_name) {
        $claim = $this->getNrpsClaim();
        if (!isset($claim)) {
            return null;
        }
        if (!isset($claim[$param_name])) {
            return null;
        }
        return $claim[$param_name];
    }

    public function getAgsClaim() {
        if (!isset($this->launch_data)) {
            return null;
        }
        if (!isset($this->launch_data["https://purl.imsglobal.org/spec/lti-ags/claim/endpoint"])) {
            return null;
        }
        return $this->launch_data["https://purl.imsglobal.org/spec/lti-ags/claim/endpoint"];
    }

    public function getAgsClaimParam($param_name) {
        $claim = $this->getAgsClaim();
        if (!isset($claim)) {
            return null;
        }
        if (!isset($claim[$param_name])) {
            return null;
        }
        return $claim[$param_name];
    }

    public function hasAgsClaimScope($scope) {
        $claim = $this->getAgsClaim();
        if (empty($claim)) {
            return 0;
        }
        if (empty($claim['scope'])) {
            return 0;
        }
        return in_array("https://purl.imsglobal.org/spec/lti-ags/scope/$scope", $claim['scope']) ? 1 : 0;
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


    public function getCourseData() {
        return array(
            'course' => $this->getClaimParam('context', 'label'),
            'title' => $this->getClaimParam('context', 'title'),
            'canvas_id' => $this->getCanvasIdValue(),
            'term' => $this->getTermValue(),
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
        $roles = $this->getClaim('roles');
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
        $data_ref = $this->launch_data;
        $field_parts = explode("|", $field);

        foreach($field_parts as $field_part) {
            if (!is_array($data_ref) || empty($data_ref[$field_part])) {
                return null;
            }
            $data_ref = $data_ref[$field_part];
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
        return $this->getParam('sub');
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

    public function getCanvasIdValue() {
        if (!empty($this->lti_tool_registration['canvas_id_field'])) {
            $canvas_id_field = $this->lti_tool_registration['canvas_id_field'];

            $value = $this->getDynamicFieldValue($canvas_id_field);
            if (!empty($value)) {
                return $value;
            }
        }
        // default canvas_id value
        return null;
    }

    public function getTermValue() {
        if (!empty($this->lti_tool_registration['term_field'])) {
            $term_field = $this->lti_tool_registration['term_field'];

            $value = $this->getDynamicFieldValue($term_field);
            if (!empty($value)) {
                return $value;
            }
        }
        // default term value
        return null;
    }

    public function getFacultyNameValue() {
        if (!empty($this->lti_tool_registration['faculty_name_field'])) {
            $faculty_name_field = $this->lti_tool_registration['faculty_name_field'];

            $value = $this->getDynamicFieldValue($faculty_name_field);
            if (!empty($value)) {
                return $value;
            }
        }
        // default term value
        return 'LTI Default Faculty';
    }
}
