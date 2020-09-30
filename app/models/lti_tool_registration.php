<?php

/**
 * LtiToolRegistration
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiToolRegistration extends AppModel
{
    public $name = 'LtiToolRegistration';
    public $validate = array(
        'iss' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'client_id' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'auth_login_url' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'auth_token_url' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'key_set_url' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'tool_private_key' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'tool_public_key' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'user_identifier_field' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => false,
                'allowEmpty' => true,
            ),
        ),
        'student_number_field' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => false,
                'allowEmpty' => true,
            ),
        ),
        'term_field' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => false,
                'allowEmpty' => true,
            ),
        ),
        'canvas_id_field' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => false,
                'allowEmpty' => true,
            ),
        ),
        'faculty_name_field' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => false,
                'allowEmpty' => true,
            ),
        ),
    );

    public $hasMany = array(
        'LtiContext' => array(
            'className'   => 'LtiContext',
            'foreignKey'  => 'lti_tool_registration_id',
            'dependent'   => true
        ),
    );

    /**
     * Find registration by `iss` in database.
     *
     * @param string $iss
     * @return LtiToolRegistration
     */
    public function findByIss($iss)
    {
        return $this->find('first', array(
            'conditions' => array(
                'LtiToolRegistration.iss' => $iss
            ),
        ));
    }
}
