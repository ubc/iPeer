<?php
/**
 * This is a dummy model class used only for validating the
 * database configuration form during installation.
 * */
class InstallValidationStep3 extends AppModel
{
  var $useTable = false;
  var $_schema = array(
    'data_setup_option' => array(
      'type' => 'string',
      'length' => '1',
    ),
    'host' => array(
      'type' => 'string',
      'length' => '9001', // practically, we won't see urls this long,
      // but I don't remember exactly what the rfc for uris limit it to
    ),
    'login' => array(
      'type' => 'string',
      'length' => '255',
    ),
    'password' => array(
      'type' => 'string',
      'length' => '255',
    ),
    'database' => array(
      'type' => 'string',
      'length' => '255',
    ),
  );
  var $validate = array(
    'data_setup_option' => array(
      'hassomething' => array(
        'rule' => 'notEmpty',
      ),
      'allowedChoice' => array(
        'rule' => array('inList', array('A', 'B')),
        'message' => 'Invalid selection for data setup option',
      )
    ),
    'host' => array(
      'rule' => 'notEmpty',
    ),
    'login' => array(
      'rule' => 'notEmpty',
    ),
    'password' => array(
      'rule' => 'notEmpty',
    ),
    'database' => array(
      'rule' => 'notEmpty',
    ),
  );
}
?>
