<?php

/**
 * LtiToolRegistration
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiToolRegistration extends AppModel
{
    public $name = 'LtiToolRegistration';
    public $hasMany = array(
        'LtiPlatformDeployment' => array(
            'className' => 'LtiPlatformDeployment',
            'dependent' => true,
            'order'     => 'LtiPlatformDeployment.deployment ASC',
        ),
    );
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
        'kid' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => false,
                'allowEmpty' => true,
            ),
        ),
        'tool_private_key_file' => array(
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
    );

    /**
     * Find issuers with their deployments for LTIDatabase.
     *
     * @return array
     */
    public function findIssuers()
    {
        $data = $this->find('all');
        $rows = array();
        foreach ($data as $array) {
            extract($array); // => $LtiToolRegistration, $LtiPlatformDeployment
            $key = $LtiToolRegistration['iss'];
            $rows[$key] = array_diff_key($LtiToolRegistration, array_flip(array('id', 'iss')));
            $rows[$key]['deployment'] = array_column($LtiPlatformDeployment, 'deployment');
        }
        return $rows;
    }

    /**
     * Find issuers with their deployments for LtiToolRegistrationsController->index().
     *
     * @return array
     */
    public function findAll()
    {
        $data = $this->find('all');
        $rows = array();
        foreach ($data as $key => $array) {
            extract($array); // => $LtiToolRegistration, $LtiPlatformDeployment
            $id = $LtiToolRegistration['id'];
            $settings = array_diff_key($LtiToolRegistration, array_flip(array('id', 'iss')));
            $settings['deployment'] = array_column($LtiPlatformDeployment, 'deployment');
            $settings = json_encode($settings, 448);
            $settings = preg_replace("/^\{|\}$/", "", $settings);
            $settings = preg_replace("/^[ ]{4}/m", "", $settings);
            $rows[$id] = array(
                'Issuer'   => $LtiToolRegistration['iss'],
                'Settings' => $settings,
            );
        }
        return $rows;
    }
}
