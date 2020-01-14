<?php
namespace App\LTI13;

use IMSGlobal\LTI\Database as LTI_Database;
use IMSGlobal\LTI\LTI_Deployment;
use IMSGlobal\LTI\LTI_Registration;

/**
 * @link https://github.com/IMSGlobal/lti-1-3-php-example-tool/blob/master/src/db/example_database.php
 */
class LTI13Database implements LTI_Database {

    private $issuers = array();

    public function __construct()
    {
        return $this->set_issuers();
    }

    /**
     * Load all registration JSON files from app/config/lti13 folder.
     *
     * @return App\LTI13\LTI13Database
     */
    private function set_issuers()
    {
        $filenames = glob(ROOT.'/app/config/lti13/*.json');
        foreach ($filenames as $filename) {
            $json = json_decode(file_get_contents($filename), true);
            $this->issuers = array_merge($this->issuers, $json);
        }
        return $this;
    }

    /**
     * Get the LTI13Database::$issuers array.
     *
     * @return array
     */
    public function get_issuers()
    {
        return $this->issuers;
    }

    /**
     * Find registration by issuer.
     *
     * @see vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Registration.php
     * @param string $iss
     * @return IMSGlobal\LTI\LTI_Registration
     */
    public function find_registration_by_issuer($iss) {
        if (empty($this->issuers) || empty($this->issuers[$iss])) {
            return false;
        }
        $issuer = $this->issuers[$iss];
        $tool_private_key = $this->tool_private_key($iss);
        return LTI_Registration::new()
            ->set_auth_login_url($issuer['auth_login_url'])
            ->set_auth_token_url($issuer['auth_token_url'])
            ->set_client_id($issuer['client_id'])
            ->set_key_set_url($issuer['key_set_url'])
            ->set_issuer($iss)
            ->set_tool_private_key($tool_private_key);
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
        if (!in_array($deployment_id, $this->issuers[$iss]['deployment'])) {
            return false;
        }
        return LTI_Deployment::new()->set_deployment_id($deployment_id);
    }

    /**
     * Load tool private key from file in storage folder.
     *
     * @param string $iss
     * @return string
     */
    private function tool_private_key($iss) {
        $filename = ROOT.DS.$this->issuers[$iss]['private_key_file'];
        return file_get_contents($filename);
    }
}
