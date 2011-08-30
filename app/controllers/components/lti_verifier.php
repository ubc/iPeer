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

class LtiVerifierComponent extends Object {
  var $components = array('Auth');

  private $store = array('lti_secret' => 'secret');
  # time range in seconds within which the timestamp is considered valid
  # Note that for security reasons, since we're not storing the nonces,
  # the timestamp plays a double role as a nonce, the delay should be
  # kept low, only a few minutes max.
  private $timestamptimeout = 60;

  /**
   * The chances of this function working would be significantly improved
   * if the User table has a column named 'lti_id'. Also would help if the
   * Auth component was used.
   *
   * @param $params - the LTI parameters
   * @param $user - the user model used by this app
   * @return - false if authentication successful, a string describing the 
   * error otherwise
   * */

  public function login($params, $user) {
    $userid = $params['user_id'];
    $ret = $user->find(
        'first',
        array('conditions' => array('User.lti_id' => $userid)));
    if (empty($ret)) {
      return 'User not found';
    }
    $userid = $ret['User']['id'];
    $ret = $this->Auth->login($userid);
    if ($ret == 0) {
      return 'Access denied';
    }

    return false;
  }

  /**
   * Checks for compliance to LTI specs
   *
   * @param $params - the LTI parameters
   * @return - false if params are LTI compliant, a string describing the 
   * error otherwise
   * */

  public function checkParams($params) {
    // Perform basic LTI validation by checking that all
    // required fields are present
    $lti_ver = $params['lti_version'];
    if (strcmp($lti_ver, 'LTI-1p0') != 0) {
      return 'Incompatible or missing lti_version.';
    }
    $lti_type = $params['lti_message_type'];
    if (strcmp($lti_type, 'basic-lti-launch-request') != 0) {
      return 'Incompatible or missing basic lti request.';
    }
    $lti_resource = $params['resource_link_id'];
    if (empty($lti_resource)) {
      return 'Missing resource link id.';
    }

    // Check that oauth parameters have been transmitted
    $oauth_key = $params['oauth_consumer_key'];
    if (empty($oauth_key)) {
      return 'Missing oauth parameter: oauth_consumer_key.';
    }
    $oauth_method = $params['oauth_signature_method'];
    if (strcmp($oauth_method, "HMAC-SHA1") != 0) {
      return 'Incompatible oauth signature method, only HMAC-SHA1 is supported.';
    }
    $oauth_time = $params['oauth_timestamp'];
    if (abs($oauth_time - time()) > $this->timestamptimeout) {
      return 'Failed oauth timestamp verification.';
    }
    $oauth_nonce = $params['oauth_nonce'];
    if (empty($oauth_nonce)) {
      return 'Missing oauth nonce.';
    }
    $oauth_version = $params['oauth_version'];
    if (strcmp($oauth_version, "1.0") != 0) {
      return 'Incompatible OAuth version.';
    }

    // Check that messages haven't been tampered with using OAuth
    $secret = $this->getSecret($oauth_key);
    if (empty($secret)) {
      return "Missing secret, key '$oauth_key' not found in keystore.";
    }
    $hmac = $this->getHMAC($params, $secret);
    $oauth_signature = $params['oauth_signature'];
    if (strcmp($hmac, $oauth_signature) != 0) {
      return 'Message integrity could not be verified.';
    }

    return false;
  }

  /**
   * Reads the LTI parameters and return an array containing
   * information about the course.
   *
   * @param $params - the LTI request parameters
   * @return false if the LTI request is missing course information, the
   * array containing two keys 'course' and 'title' otherwise.
   * */

  public function getCourseInfo($params) {
    $ret = array();
    $ret['course'] = $params['context_label'];
    $ret['title'] = $params['context_title'];
    if ($ret['course'] && $ret['title']) {
      return $ret;
    }
    return false;
  }

  /**
   * Given a key, check the keystore for the corresponding secret.
   *
   * Currently, the store is just a hard coded array.
   *
   * @param $key - the key used to access the secret
   * @return false if the secret was not found, a string containing the
   * secret if found
   * */

  public function getSecret($key) {
    if (isset($this->store[$key])) {
      return $this->store[$key];
    }
    return false;
  }

  /**
   * Calculate the HMAC value according to OAuth specs.
   *
   * @param $params - the array of POST parameters of the LTI request
   * @param $secret - the secret string
   * @param $url - the url which is receiving the LTI request, if left
   * blank, defaults to the URL that the current script is executing on
   *
   * @return the resulting hash string
   * */

  public function getHMAC($params, $secret, $url = '') {
    $secret .= '&';
    $input = $this->getHashInput($params, $url);
    return $this->hash($input, $secret);
  }

  /**
   * Given a set of data and a secret, generate a hash and
   * base64 encode it.
   *
   * @param $data - the data to be hashed
   * @param $secret - the secret used to salt the data
   *
   * @return base64 encodeded hash
   * */

  private function hash($data, $secret) {
    return base64_encode(hash_hmac("sha1", $data, $secret, true));
  }

  /**
   * Convert the LTI params into the proper data format
   * to pass into the hash function.
   *
   * @param $params - the LTI parameters
   * @param $url - the URL of the page receiving the LTI request. Defaults to
   * a blank string. If left blank, will use the URL of the current page
   * that the php scrip is running on.
   *
   * @return A correctly formatted data string suitable for use in calculating
   * the OAuth HMac.
   * */

  private function getHashInput($params, $url = '') {
    $ret = "POST&";
    if (empty($url)) {
      $ret .= $this->rfc3986($this->getURL()) . '&';
    } else {
      $ret .= $this->rfc3986($url) . '&';
    }
    $ret .= $this->rfc3986($this->convertParams($params));
    return $ret;
  }

  /**
   * Convert each key-value pair into the correct RFC 3986 encoding
   * required by OAuth.
   *
   * @param $params - the LTI parameters
   *
   * @return A string containing all the key-value pairs of $param
   * delimited by an ampersand and encoded into RFC 3986
   * */

  private function convertParams($params) {
    $tmp = array();
    foreach ($params as $key => $val) {
      $tmp[$this->rfc3986($key)] = $this->rfc3986($val);
    }
    $params = $tmp;

    // sort by byte order
    uksort($params, 'strcmp');

    $tmp = array();
    foreach ($params as $parameter => $value) {
      if (strcmp($parameter, "oauth_signature") == 0) {
        continue;
      }
      array_push($tmp, $parameter . '=' . $value);
    }
    return implode('&', $tmp);
  }

  /**
   * Convert a string into rfc3986 compliant encoding
   *
   * @param $input - the string to be converted
   * @return The rfc3986 compliant string
   * */

  private function rfc3986($input) {
    // Note workaround for:
    // - Prior to PHP 5.3.0, rawurlencode encoded tildes (~) per rfc1738. 
    return str_replace('%7E', '~', rawurlencode($input));
  }

  /**
   * Get the URL of the current page that PHP is being executed on.
   *
   * @return The URL of the current page that PHP is being executed on.
   * */

  private function getURL() {
    $pageURL = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
      $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"]
          . $_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
  }
}

?>
