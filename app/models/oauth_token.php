<?php
/**
 * OauthToken
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class OauthToken extends AppModel {
    public $name = 'OauthToken';
    public $displayField = 'key';
    public $validate = array(
        'key' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Key cannot be empty.',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'secret' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Secret cannot be empty',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'expires' => array(
            'date' => array(
                'rule' => array('date'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Retrieve the token credential secret based on the key. The token
     * credential identifies the resource owner (user).
     *
     * @param mixed $key - identifier for the secret.
     *
     * @return The secret if found, null if not.
     */
    public function getTokenSecret($key) {
        $ret = $this->findByKey($key);
        if (!empty($ret)) {
            if ($ret['OauthToken']['enabled'] &&
                strtotime($ret['OauthToken']['expires']) > time()
            ) {
                return $ret['OauthToken']['secret'];
            }
        }
        return null;
    }
}
