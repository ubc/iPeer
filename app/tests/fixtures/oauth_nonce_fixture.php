<?php
/* OauthNonce Fixture generated on: 2012-08-13 14:05:24 : 1344891924 */
class OauthNonceFixture extends CakeTestFixture {
	var $name = 'OauthNonce';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'nonce' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'expires' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'nonce' => 1,
			'expires' => 1344891924
		),
	);
}
