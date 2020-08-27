<?php

/**
 * LtiCache
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiCache extends AppModel
{
    public $name = 'LtiCache';
    public $useTable = 'lti_cache';
    static public $cacheId = null; // Value input from Lti13::__construct()

    /**
     * Find content of `lti_cache`.`json`
     *
     * @return array
     */
    public function load_cache()
    {
        $this->log(__METHOD__, 'lti13/cache');
        $this->log('self::$cacheId = ' . self::$cacheId, 'lti13/cache');
        if ($data = $this->find_cache()) {
            return $data['LtiCache']['json'];
        }
    }

    /**
     * Save content of `lti_cache`.`json`
     *
     * @param string $json
     */
    public function save_cache($json)
    {
        $this->log(__METHOD__, 'lti13/cache');
        if ($data = $this->find_cache()) {
            // Update
            $data['LtiCache']['json'] = $json;
            $this->log('update = ', 'lti13/cache');
            $this->log($data, 'lti13/cache');
            return (bool)$this->save($data);
        } else {
            // Create
            $data = array(
                'LtiCache' => array(
                    'cache_id' => self::$cacheId,
                    'json' => $json,
                ),
            );
            $this->create($data);
            $this->log('create = ', 'lti13/cache');
            $this->log($data, 'lti13/cache');
            return (bool)$this->save();
        }
    }

    /**
     * Find data row by $this->login_hint.
     *
     * @return array
     */
    protected function find_cache()
    {
        $this->log(__METHOD__, 'lti13/cache');
        $this->log('self::$cacheId = ' . self::$cacheId, 'lti13/cache');
        $conditions = array('LtiCache.cache_id' => self::$cacheId);
        return $this->find('first', compact('conditions'));
    }
}
