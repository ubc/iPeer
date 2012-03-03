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
        'absolute_url' => array(
            'type' => 'string',
        ),
        'domain' => array(
            'type' => 'string',
        ),
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
        'login_text' => array(
            'type' => 'string',
        ),
        'contact_info' => array(
            'type' => 'string',
        ),
    );

    public $validate = array(
        'absolute_url' => array(
            // cakephp's url validator doesn't like my host file alias
            // for some reasons
            //'rule' => 'url',
            'rule' => 'notEmpty',
            'message' => 'Please enter a properly formatted URL.'
        ),
        'domain' => array(
            'rule' => 'notEmpty',
        ),
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
            'message' => 'Please enter a valid email address.'
        ),
    );

    /**
     * identicalWith
     *
     * @param mixed $check   check
     * @param mixed $compare compare
     *
     * @access public
     * @return void
     */
    public function identicalWith($check, $compare)
    {
        foreach ($check as $key => $val) {
            if ($val != $this->data[$this->name][$compare]) {
                return false;
            }
        }
        return true;
    }

}
