<?php
namespace App\LTI13;

use IMSGlobal\LTI\Cache as LTI_Cache;

class LTI13Cache extends LTI_Cache {

    protected $LtiCache; // Model
    static protected $cacheId = null;

    /**
     * Inject LtiCache model and its unique cache id.
     *
     * @param LtiCache $LtiCache
     * @param int $cacheId
     */
    public function __construct($LtiCache, $cacheId)
    {
        $this->LtiCache = $LtiCache;
        if (empty(static::$cacheId)) {
            static::$cacheId = $cacheId;
        }
    }

    /**
     * Override LTI_Cache::load_cache().
     */
    public function load_cache() {
        $cache = $this->LtiCache->load_cache(static::$cacheId);
        if (empty($cache)) {
            $this->LtiCache->save_cache(static::$cacheId, '{}');
            $this->cache = [];
        }
        $this->cache = json_decode($cache, true);
        echo '<pre>$this->cache = ', print_r( $this->cache, 1), '</pre>';
    }

    /**
     * Override LTI_Cache::save_cache().
     */
    public function save_cache() {
        $this->LtiCache->save_cache(static::$cacheId, json_encode($this->cache));
    }
}
