<?php
App::import('Component', 'Security');

/**
 * SysParameter
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SysParameter extends AppModel
{
    public $name = 'SysParameter';
    public $actsAs = array('Traceable');

    public $validate = array (
        'parameter_code'  => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Parameter code is required'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Duplicate parameter code is found. Please select another.'
            )
        ),
        'parameter_type' => array(
            'rule' => 'notEmpty',
            'message' => 'Parameter code is required',
            'on' => 'create',
        )
    );

    /**
     * findParameter
     *
     * @param string $paramCode
     *
     * @access public
     * @return void
     */
    function findParameter ($paramCode)
    {
        return $this->find('first', array(
            'conditions' => array('parameter_code' => $paramCode),
            'fields' => array('id', 'parameter_code', 'parameter_value', 'parameter_type')
        ));
    }

    /**
     * get the value of a parameter
     *
     * @param string $paramCode param code
     * @param string $default   default value if no paramCode
     *
     * @access public
     * @return string the value for paramCode. If no such paramCode, return null or $default (if specified)
     */
    function get($paramCode, $default = null)
    {
        // search in cache first
        $result = Cache::read($paramCode, 'configuration');
        if (null == $result) {
            $param = $this->findParameter($paramCode);
            if ($param) {
                // decrypt the parameter if it's encrypted
                if ($param['SysParameter']['parameter_type'] === 'E'){
                    $param['SysParameter']['parameter_value'] = Security::cipher(base64_decode($param['SysParameter']['parameter_value']), Configure::read('Security.cipherSeed'));
                } elseif ($param['SysParameter']['parameter_type'] === 'B') {
                    $param['SysParameter']['parameter_value'] =
                        !($param['SysParameter']['parameter_value'] === 'false' ||
                        $param['SysParameter']['parameter_value'] === 'False' ||
                        $param['SysParameter']['parameter_value'] === 'FALSE' ||
                        $param['SysParameter']['parameter_value'] === 0);
                }
                $result = $param['SysParameter']['parameter_value'];
                Cache::write($paramCode, $result, 'configuration');
            } else {
                $result = $default;
            }
        }

        return $result;
    }

    /**
     * set a value to database
     *
     * @param mixed $paramCode
     * @param mixed $value
     *
     * @access public
     * @return void
     */
    function setValue($paramCode, $value)
    {
        $obj = $this->findParameter($paramCode);
        if ($obj) {
            $obj['SysParameter']['parameter_value'] = $value;
            return $this->save($obj);
        }

        return false;
    }

    /**
     * beforeSave
     *
     *
     * @access public
     * @return void
     */
    function beforeSave($options = array())
    {
        $this->data[$this->name]['modified'] = date('Y-m-d H:i:s');

        if ($this->data[$this->name]['parameter_type'] == 'E'){
            $this->data[$this->name]['parameter_value'] = base64_encode(Security::cipher($this->data[$this->name]['parameter_value'], Configure::read('Security.cipherSeed')));
        }

        return true;
    }

    /**
     * afterSave
     *
     * @param boolean $created
     *
     * @access public
     * @return void
     */
    function afterSave($created)
    {
        // clear the cache. let the next read retrieve/decrypt/convert it again
        Cache::delete($this->data['SysParameter']['parameter_code'], 'configuration');
        return true;
    }

    /**
     * getDatabaseVersion
     *
     *
     * @access public
     * @return void
     */
    public function getDatabaseVersion()
    {
        return $this->get('database.version');
    }

    /**
     * reload force reload by clearing the cache
     *
     * @access public
     * @return void
     */
    public function reload()
    {
        Cache::clear(false, 'configuration');
    }
}
