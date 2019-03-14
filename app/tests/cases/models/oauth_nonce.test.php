<?php
/* OauthNonce Test cases generated on: 2012-08-13 14:05:24 : 1344891924*/
App::import('Model', 'OauthNonce');

class OauthNonceTestCase extends CakeTestCase {
	var $fixtures = array('app.oauth_nonce');

	function startTest($method) {
		$this->OauthNonce =& ClassRegistry::init('OauthNonce');
	}

	function endTest($method) {
		unset($this->OauthNonce);
		ClassRegistry::flush();
	}

}
