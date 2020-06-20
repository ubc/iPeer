<?php

/**
 * LtiPlatformDeployment
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiPlatformDeployment extends AppModel
{
    public $name = 'LtiPlatformDeployment';
    public $belongsTo = array(
        'LtiToolRegistration' => array(
            'className' => 'LtiToolRegistration',
            'order'     => 'LtiToolRegistration.iss ASC, LtiToolRegistration.client_id ASC',
        ),
    );
    public $validate = array(
        'deployment' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 64),
                'message' => 'Maximum 64 characters',
                'required' => false,
            ),
        ),
    );
}
