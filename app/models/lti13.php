<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Lib', 'Lti13Database');

use Firebase\JWT\JWT;
use IMSGlobal\LTI\LTI_Message_Launch;

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
    public $ltidb, $launch_id, $nrps, $members;

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
        } catch (Exception $e) {
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
            'members'      => json_encode($this->get_members(), 448),
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
            $jwt_header = explode('.', $jwt)[0];
            return json_decode(JWT::urlsafeB64Decode($jwt_header), true);
        }
    }

    /**
     * Get LTI_Names_Roles_Provisioning_Service instance
     *
     * @return LTI_Names_Roles_Provisioning_Service
     */
    public function get_nrps()
    {
        $launch = LTI_Message_Launch::from_cache($this->launch_id, $this->ltidb);
        if (!$launch->has_nrps()) {
            throw new Exception("Don't have names and roles!");
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
        $this->members = $this->get_nrps()->get_members();
        return $this->members;
    }
}
