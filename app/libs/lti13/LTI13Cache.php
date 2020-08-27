<?php
namespace App\LTI13;

\App::import('Model', 'LtiCache', array('file'=>'models'.DS.'lti_cache.php'));

use App\LTI13\LtiCache;
use IMSGlobal\LTI\Cache as LTI_Cache;

class LTI13Cache extends LTI_Cache {

    public $LtiCache;
    static public $cacheId = null; // Value from Lti13::login()

    public function __construct()
    {
        $this->LtiCache = new LtiCache();
    }

    /**
     * Override LTI_Cache::load_cache().
     */
    public function load_cache() {
        $cache = $this->LtiCache->load_cache(self::$cacheId);
        if (empty($cache)) {
            $this->LtiCache->save_cache(self::$cacheId, '{}');
            $this->cache = [];
        }
        $this->cache = json_decode($cache, true);
        echo '<pre>$this->cache = ', print_r( $this->cache, 1), '</pre>';
    }

    /**
     * Override LTI_Cache::save_cache().
     */
    public function save_cache() {
        $this->LtiCache->save_cache(self::$cacheId, json_encode($this->cache));
    }
}
