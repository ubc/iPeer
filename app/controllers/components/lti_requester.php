<?php
/******************************
 * It would've been so much nicer if I could've just used an existing OAuth
 * library for LTI, like I did in Webworks. Unfortunately, there's no suitably
 * developed PHP library that met all my needs. Part of the problem is that
 * most of the libraries are developed as a tightly integrated OAuth library,
 * and LTI only needs to use the message signing part of LTI. Another problem
 * is that some of the libraries aren't compliant, so couldn't talk to Moodle's
 * OAuth module.
 * ***************************/
/**
 * LtiRequesterComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    John Hsu <john.hsu@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiRequesterComponent extends CakeObject
{
    public $components = array('LtiVerifier');

    /**
     * requestRoster
     *
     * @param mixed $params
     *
     * @access public
     * @return void
     */
    public function requestRoster($params)
    {
        // Check that roster requests are supported by the server
        $ltimid = $params['ext_ims_lis_memberships_id'];
        if (empty($ltimid)) {
            return "Missing 'ext_ims_lis_memberships_id'. Calling LTI consumer does not support roster requests.";
        }

        $ltimurl = $params['ext_ims_lis_memberships_url'];
        if (empty($ltimurl)) {
            return "Missing 'ext_ims_lis_memberships_url'. Calling LTI consumer does not support roster requests.";
        }

        // build an array of all the params we need to use
        $oauth_key = $params['oauth_consumer_key']; // assuming this exists
        $request = array(
            'oauth_version' => '1.0',
            'oauth_nonce' => rand() . "-" . rand(),
            'oauth_timestamp' => time(),
            'oauth_consumer_key' => $oauth_key,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_callback' => 'about:blank',
            'lti_version' => 'LTI-1p0',
            'lti_message_type' => 'basic-lis-readmembershipsforcontext',
            'id' => $ltimid,
        );

        // calculate the signature of all the params
        $secret = $this->LtiVerifier->getSecret($oauth_key);
        if (empty($secret)) {
            return "Missing secret, key '$oauth_key' not found in keystore.";
        }
        $hmac = $this->LtiVerifier->getHMAC($request, $secret, $ltimurl);

        // add the signature in into the params
        $request['oauth_signature'] = $hmac;
        $request = http_build_query($request);

        // send the actual POST request
        $params = array(
            'http' => array('method' => 'POST', 'content' => $request)
        );
        $ctx = stream_context_create($params);
        $fp = @fopen($ltimurl, 'rb', false, $ctx);
        if (!$fp) {
            return 'Unable to connect to ' . $ltimurl;
        }
        // read the response
        $response = @stream_get_contents($fp);
        if ($response === false) {
            return 'Unable to read data from ' . $ltimurl;
        }

        // parse the response xml
        $xml = new SimpleXMLElement($response);
        if (strcasecmp($xml->statusinfo->codemajor, 'Success') != 0) {
            return 'Unable to retrieve roster: ' . $xml->statusinfo->description;
        }

        // convert the response xml to an array
        $ret = array();
        foreach ($xml->memberships->member as $member) {
            $student = array();
            foreach ($member as $key => $val) {
                // note $val is still a SimpleXML object, so need cast with ""
                $student[$key] = "$val";
            }
            $ret[] = $student;
        }
        return $ret;
    }
}
