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

    /**
     * Filter out related deployment ID blanks
     *
     * @param array $data
     * @return array
     */
    public function filterDeploymentRows($data)
    {
        // Remove empty deployment values
        if (!empty($data['LtiPlatformDeployment'])) {
            foreach ($data['LtiPlatformDeployment'] as $i => $row) {
                if (isset($row['deployment'])) {
                    if (empty(trim($row['deployment']))) {
                        unset($data['LtiPlatformDeployment'][$i]);
                    }
                }
            }
        }

        // Delete 'LtiPlatformDeployment' key if its array is empty
        if (empty($data['LtiPlatformDeployment'])) {
            unset($data['LtiPlatformDeployment']);
        }

        return $data;
    }

    /**
     * Fill related deployment ID rows with foreign key
     *
     * @param array $data
     * @param int $lti_tool_registration_id
     * @return array
     */
    public function fillDeploymentRows($data, $lti_tool_registration_id)
    {
        if (!empty($data['LtiPlatformDeployment'])) {
            foreach ($data['LtiPlatformDeployment'] as $i => $row) {
                $data['LtiPlatformDeployment'][$i]['lti_tool_registration_id'] = $lti_tool_registration_id;
            }
        }

        return $data;
    }
}
