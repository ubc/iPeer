<?php
namespace lti;

use IMSGlobal\LTI\Cache;

\App::import('Model', 'LtiNonce');

class LTICache extends Cache {
    public $LtiNonce;

    public function __construct()
    {
        $this->LtiNonce = \ClassRegistry::init('LtiNonce');
        return $this;
    }

    public function get_launch_data($key) {
        // do not support launch data in 'cache'
        return null;
    }

    public function cache_launch_data($key, $jwt_body) {
        // do not support launch data in 'cache'
        return $this;
    }

    public function cache_nonce($nonce) {
        $data = array(
            'LtiNonce' => array(
                'nonce' => $nonce
            )
        );
        $this->LtiNonce->save($data);
        return $this;
    }

    public function check_nonce($nonce) {
        $lti_nonce = $this->LtiNonce->findByNonce($nonce);
        if (empty($lti_nonce)) {
            return false;
        }
        $this->LtiNonce->deleteAll(array(
            'LtiNonce.nonce' => $nonce,
        ));
        return true;
    }
}