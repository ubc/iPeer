<?php

/**
 * LtiPlatformDeploymentFixture
 *
 * @uses      CakeTestFixture
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiPlatformDeploymentFixture extends CakeTestFixture
{
    public $name = 'LtiPlatformDeployment';

    public $fields = array(
        'iss' => array('type' => 'string', 'length' => 255, 'null' => false, 'key' => 'primary'),
        'deployment' => array('type' => 'string', 'length' => 64, 'null' => false),
    );

    public $import = array('table' => 'lti_platform_deployments', 'records' => true);
}
