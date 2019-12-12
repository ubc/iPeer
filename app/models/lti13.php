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

    /**
     * Encode the Lti13Database::$issuers array into JSON.
     *
     * @param Lti13Database $ltidb
     * @return string
     */
    public function get_registration_json(Lti13Database $ltidb)
    {
        return $this->to_json($ltidb->get_issuers());
    }

    /**
     * Encode the LTI_Message_Launch object into JSON.
     *
     * @param string $launch_id
     * @param Lti13Database $ltidb
     * @return string
     */
    public function get_launch_data($launch_id, Lti13Database $ltidb)
    {
        $cached_launch = LTI_Message_Launch::from_cache($launch_id, $ltidb);
        $jwt_payload = $cached_launch->get_launch_data();
        return [
            'launch_id'    => $launch_id,
            'message_type' => $jwt_payload['https://purl.imsglobal.org/spec/lti/claim/message_type'],
            'post_as_json' => $this->to_json($_POST),
            'jwt_header'   => $this->jwt_header(),
            'jwt_payload'  => $this->to_json($jwt_payload),
        ];
    }

    /**
     * Get JWT header.
     *
     * @return string
     */
    private function jwt_header()
    {
        if ($jwt = $_REQUEST['id_token']) {
            $jwt_header = explode('.', $jwt)[0];
            $jwt_header = json_decode(JWT::urlsafeB64Decode($jwt_header));
            return $this->to_json($jwt_header);
        }
    }

    /**
     * Format value as pretty print JSON.
     *
     * @param mixed $value
     * @return string
     */
    private function to_json($value)
    {
        return json_encode($value, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}
