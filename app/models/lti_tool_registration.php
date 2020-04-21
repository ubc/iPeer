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
    public $primaryKey = 'iss';

    public $hasMany = array(
        'LtiPlatformDeployment' => array(
            'className' => 'LtiPlatformDeployment',
            'foreignKey' => 'iss',
            'dependent' => true,
        ),
    );

    /**
     * Find issuers with their deployments.
     *
     * @return array
     */
    public function findIssuers()
    {
        $data = $this->find('all', array('order'=>'iss ASC'));
        $registrations = array();
        foreach ($data as $key => $array) {
            extract($array); // => $LtiToolRegistration, $LtiPlatformDeployment
            $key = $LtiToolRegistration['iss'];
            $registrations[$key] = array_diff_key($LtiToolRegistration, array('iss'=>0));
            $registrations[$key]['deployment'] = array_column($LtiPlatformDeployment, 'deployment');
        }
        return $registrations;
    }
}
