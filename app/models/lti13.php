<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Lib', 'Lti13Database');
App::import('Lib', 'LTI_Assignments_Grades_Service_Override', array('file'=>'lti13'.DS.'LTI_Assignments_Grades_Service_Override.php'));

use App\LTI13\LTI_Assignments_Grades_Service_Override;
use Firebase\JWT\JWT;
use IMSGlobal\LTI\LTI_Deep_Link_Resource;
use IMSGlobal\LTI\LTI_Lineitem;
use IMSGlobal\LTI\LTI_Message_Launch;
use IMSGlobal\LTI\LTI_Service_Connector;

/**
 * LTI 1.3 Model
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Lti13 extends AppModel
{
    public $useTable = false;
    public $ltidb, $launch_id, $nrps, $nrps_members, $ags, $ags_grades, $dl, $dl_response;

    public function __construct()
    {
        $this->ltidb = new Lti13Database();
    }

    /**
     * Encode the Lti13Database::$issuers array into JSON.
     *
     * @return string
     */
    public function get_registration_json()
    {
        return json_encode($this->ltidb->get_issuers(), 448);
    }

    /**
     * Initialize LTI_Message_Launch object and validate its data.
     *
     * @return string
     */
    public function launch()
    {
        $launch = LTI_Message_Launch::new($this->ltidb);
        try {
            $launch->validate();
        } catch (\Exception $e) {
            echo "Launch validation failed.";
        }
        $this->launch_id = $launch->get_launch_id();
        return $this->launch_id;
    }

    /**
     * Encode the LTI_Message_Launch object into JSON.
     *
     * @return string
     */
    public function get_launch_data()
    {
        $launch = LTI_Message_Launch::from_cache($this->launch_id, $this->ltidb);
        $jwt_payload = $launch->get_launch_data();
        return [
            'launch_id'    => $this->launch_id,
            'message_type' => $jwt_payload['https://purl.imsglobal.org/spec/lti/claim/message_type'],
            'post_as_json' => json_encode($_POST, 448),
            'jwt_header'   => json_encode($this->jwt_header(), 448),
            'jwt_payload'  => json_encode($jwt_payload, 448),
            'nrps_members' => json_encode($this->get_members(), 448),
            'ags_grades'   => json_encode($this->get_grades(), 448),
            'dl_response'  => json_encode($this->get_response_jwt(), 448),
        ];
    }

    /**
     * Get JWT header.
     *
     * @return array
     */
    private function jwt_header()
    {
        if ($jwt = @$_REQUEST['id_token']) {
            return $this->jwt_decode($jwt, 0);
        }
    }

    /**
     * Get LTI_Names_Roles_Provisioning_Service instance
     *
     * Obtained through Resource Link, not Deep Link
     * @return LTI_Names_Roles_Provisioning_Service
     */
    public function get_nrps()
    {
        $launch = LTI_Message_Launch::from_cache($this->launch_id, $this->ltidb);
        if (!$launch->has_nrps()) {
            // throw new \Exception("Don't have names and roles!");
            return;
        }
        $this->nrps = $launch->get_nrps();
        return $this->nrps;
    }

    /**
     * Get all members of the LTI_Names_Roles_Provisioning_Service instance.
     *
     * @return array
     */
    public function get_members()
    {
        if ($nrps = @$this->get_nrps()) {
            $this->members = $nrps->get_members();
            return $this->members;
        }
    }

    /**
     * Get LTI_Assignments_Grades_Service instance
     *
     * Obtained through Resource Link, not Deep Link
     * @return LTI_Assignments_Grades_Service
     */
    public function get_ags()
    {
        $launch = LTI_Message_Launch::from_cache($this->launch_id, $this->ltidb);
        if (!$launch->has_ags()) {
            // throw new \Exception("Don't have assignments and grades!");
            return;
        }
        $this->ags = $this->get_ags_override();
        return $this->ags;
    }

    /**
     * Get all members of the LTI_Assignments_Grades_Service instance.
     *
     * @return array
     */
    public function get_grades()
    {
        if ($ags = @$this->get_ags()) {
            $lineitem = LTI_Lineitem::new();
            $this->grades = $ags->get_grades($lineitem);
            return $this->grades;
        }
    }

    /**
     * Override LTI_Message_Launch::get_ags();
     *
     * @see vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Message_Launch.php::get_ags()
     * @return LTI_Assignments_Grades_Service_Override
     */
    public function get_ags_override() {
        $launch = LTI_Message_Launch::from_cache($this->launch_id, $this->ltidb);
        $jwt['body'] = $launch->get_launch_data();
        $registration = $this->ltidb->find_registration_by_issuer($jwt['body']['iss']);
        $service_connector = new LTI_Service_Connector($registration);
        $service_data = $jwt['body']['https://purl.imsglobal.org/spec/lti-ags/claim/endpoint'];
        return new LTI_Assignments_Grades_Service_Override($service_connector, $service_data);
    }

    /**
     * Get LTI_Deep_Link instance.
     *
     * @return LTI_Deep_Link
     */
    public function get_deep_link()
    {
        $launch = LTI_Message_Launch::from_cache($this->launch_id, $this->ltidb);
        if (!$launch->is_deep_link_launch()) {
            return;
        }
        $this->dl = $launch->get_deep_link();
        return $this->dl;
    }

    /**
     * Get Deep Link response.
     *
     * @return array
     */
    public function get_response_jwt()
    {
        if ($dl = @$this->get_deep_link()) {
            $resource = (new LTI_Deep_Link_Resource())
                ->set_url("https://my.tool/launch")
                ->set_custom_params(['my_param' => '\$my_param'])
                ->set_title('My Resource');
            $this->dl_response = $dl->get_response_jwt([$resource]);
            return [
                'JWT HEADER'  => $this->jwt_decode($this->dl_response, 0),
                'JWT PAYLOAD' => $this->jwt_decode($this->dl_response, 1),
            ];
        }
    }

    /**
     * Decode JWT header or payload.
     *
     * @param string $jwt
     * @param int $i 0 = header, 1 = payload
     * @return array
     */
    private function jwt_decode($jwt, $i)
    {
        return json_decode(JWT::urlsafeB64Decode(explode('.', $jwt)[$i]));
    }
}
