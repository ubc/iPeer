<?php
namespace lti;

use IMSGlobal\LTI\Database;
use IMSGlobal\LTI\LTI_Deployment;
use IMSGlobal\LTI\LTI_Registration;

\App::import('Model', 'LtiToolRegistration');

class LTIDatabase implements Database {
    public $LtiToolRegistration;

    public function __construct()
    {
        $this->LtiToolRegistration = \ClassRegistry::init('LtiToolRegistration');
        return $this;
    }

    /**
     * Find registration by issuer.
     *
     * @see vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Registration.php
     * @param string $iss
     * @return IMSGlobal\LTI\LTI_Registration
     */
    public function find_registration_by_issuer($iss) {
        $results = $this->LtiToolRegistration->findByIss($iss);
        if (empty($results)) {
            return false;
        }
        $lti_tool_registration = $results['LtiToolRegistration'];
        return LTI_Registration::new()
            ->set_auth_login_url($lti_tool_registration['auth_login_url'])
            ->set_auth_token_url($lti_tool_registration['auth_token_url'])
            ->set_client_id($lti_tool_registration['client_id'])
            ->set_key_set_url($lti_tool_registration['key_set_url'])
            ->set_issuer($lti_tool_registration['iss'])
            ->set_tool_private_key($lti_tool_registration['tool_private_key']);
    }

    /**
     * Find deployment by deployment_id.
     *
     * @see vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Deployment.php
     * @param string $iss
     * @param string $deployment_id
     * @return IMSGlobal\LTI\LTI_Deployment
     */
    public function find_deployment($iss, $deployment_id) {
        // ignore deployment validation (just assume valid)
        return LTI_Deployment::new()->set_deployment_id($deployment_id);
    }
}
