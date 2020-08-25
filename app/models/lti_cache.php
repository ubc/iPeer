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

    /**
     * Find content of `lti_cache`.`json`
     *
     * @param int $cacheId
     * @return array
     */
    public function load_cache($cacheId)
    {
        if ($data = $this->find_cache($cacheId)) {
            return $data['LtiCache']['json'];
        }
    }

    /**
     * Save content of `lti_cache`.`json`
     *
     * @param int $cacheId
     * @param string $json
     */
    public function save_cache($cacheId, $json)
    {
        if ($data = $this->find_cache($cacheId)) {
            // Update
            $this->id = $data['LtiCache']['id'];
        } else {
            // Create
            $this->create();
            $this->set('cache_id', $cacheId);
        }
        $this->set('json', $json);
        $this->save();
    }

    /**
     * Find data row by $cacheId.
     *
     * @param int $cacheId
     * @return array
     */
    protected function find_cache($cacheId)
    {
        $conditions = array('LtiCache.cache_id' => $cacheId);
        return $this->find('first', compact('conditions'));
    }
}
