<?php
/**
 * InstallValidationStep4
 * This is a dummy model class used only for validating the
 * database configuration form during installation.
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class InstallValidationStep4 extends AppModel
{
    public $useTable = false;
    public $_schema = array(
        'super_admin' => array(
            'type' => 'string',
        ),
        'password' => array(
            'type' => 'string',
        ),
        'confirm_password' => array(
            'type' => 'string',
        ),
        'admin_email' => array(
            'type' => 'string',
        ),
        'email_domain' => array(
            'type' => 'string',
        ),
        'email_port' => array(
            'type' => 'string',
        ),
        'email_username' => array(
            'type' => 'string',
        ),
        'email_password' => array(
            'type' => 'string',
        ),
    );

    public $validate = array(
        'super_admin' => array(
            'rule' => 'notEmpty',
        ),
        'password' => array(
            'pwrule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Please fill in a password.',
                'last' => true
            ),
            'pwrule2' => array(
                'rule' => array('minLength', 8),
                'message' => 'Please use a password of at least 8 characters long.',
                'last' => true
            ),
        ),
        'confirm_password' => array(
            'rule' => array('identicalWith', 'password'),
            'message' => 'The passwords do not match.'
        ),
        'admin_email' => array(
            'rule' => 'email',
            'allowEmpty' => true,
            'message' => 'Please enter a valid email address.'
        ),
        'email_host' => array(
        ),
        'email_port' => array(
            'rule' => 'numeric',
            'allowEmpty' => true,
            'message' => 'Port must be a number.'
        ),
        'email_username' => array(
        ),
        'email_password' => array(
        ),
    );

    /**
     * Custom validation rule for checking to see that the password
     * and confirm password fields are identical.
     *
     * @param mixed $check the values of the field being validated
     * @param mixed $compare the name of the field that needs to be enabled
     *
     * @access public
     * @return boolean - true if both fields have the same value
     */
    public function identicalWith($check, $compare)
    {
        foreach ($check as $val) {
            if ($val != $this->data[$this->name][$compare]) {
                return false;
            }
        }
        return true;
    }

}
