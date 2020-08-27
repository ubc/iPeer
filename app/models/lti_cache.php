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
     * @param string $cacheId
     * @return array
     */
    public function load_cache($cacheId)
    {
        $this->log(__METHOD__, 'lti13/cache');
        $this->log('$cacheId = ' . $cacheId, 'lti13/cache');
        if ($data = $this->find_cache($cacheId)) {
            return $data['LtiCache']['json'];
        }
    }

    /**
     * Save content of `lti_cache`.`json`
     *
     * @param string $cacheId
     * @param string $json
     */
    public function save_cache($cacheId, $json)
    {
        if ($data = $this->find_cache($cacheId)) {
            $this->log($data, 'lti13/cache');
            $this->update_cache($data['LtiCache']['id'], $cacheId, $json);
        } else {
            $this->create_cache($cacheId, $json);
        }
    }

    /**
     * Find data row by $this->login_hint.
     *
     * @param string $cacheId
     * @return array
     */
    protected function find_cache($cacheId)
    {
        $this->log(__METHOD__, 'lti13/cache');
        $this->log('$cacheId = ' . $cacheId, 'lti13/cache');
        $conditions = array('LtiCache.cache_id' => $cacheId);
        return $this->find('first', compact('conditions'));
    }

    /**
     * Create data row with $this->login_hint.
     *
     * @param string $cacheId
     * @param string $json
     * @return bool
     */
    protected function create_cache($cacheId, $json)
    {
        $this->log(__METHOD__, 'lti13/cache');
        $this->log('$cacheId = ' . $cacheId, 'lti13/cache');
        $this->create(array(
            'cache_id' => $cacheId,
            'json' => $json,
        ));
        return (bool)$this->save();
    }

    /**
     * Update data row with $this->login_hint.
     *
     * @param int $id
     * @param string $cacheId
     * @param string $json
     * @return bool
     */
    protected function update_cache($id, $cacheId, $json)
    {
        $this->log(__METHOD__, 'lti13/cache');
        $this->log('$cacheId = ' . $cacheId, 'lti13/cache');
        $this->id = $id;
        return (bool)$this->save(array(
            'id' => $id,
            'json' => $json,
        ));
    }
}
