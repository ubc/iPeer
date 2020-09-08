<?php
namespace App\LTI13;

use IMSGlobal\LTI\Cache as LTI_Cache;

class LTI13Cache extends LTI_Cache {

    public $LtiCache;

    public function __construct($LtiCache)
    {
        $this->LtiCache = $LtiCache;
    }

    /**
     * Override LTI_Cache::load_cache().
     */
    public function load_cache() {
        $cache = $this->LtiCache->load_cache();
        if (empty($cache)) {
            $this->LtiCache->save_cache('{}');
            $this->cache = [];
        }
        $this->cache = json_decode($cache, true);
    }

    /**
     * Override LTI_Cache::save_cache().
     */
    public function save_cache() {
        $this->LtiCache->save_cache(json_encode($this->cache));
    }
}
