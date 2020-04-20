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
    public $primaryKey = 'iss';

    public $belongsTo = array(
        'LtiToolRegistration' => array(
            'className' => 'LtiToolRegistration',
            'foreignKey' => 'iss',
            'fields' => array('deployment'),
        ),
    );

    /**
     * Find all deployments.
     *
     * @return array
     */
    public function findDeployments()
    {
      return $this->find('all', array('order'=>'iss ASC'));
    }
}
