<?php
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

    /**
     * findParameter
     *
     * @param string $paramCode
     *
     * @access public
     * @return void
     */
    function findParameter ($paramCode='')
    {
        return $this->find('first', array(
            'conditions' => array('parameter_code' => $paramCode),
            'fields' => array('id', 'parameter_code', 'parameter_value', 'parameter_type')
        ));
    }


    /**
     * beforeSave
     *
     *
     * @access public
     * @return void
     */
    function beforeSave()
    {
        $this->data[$this->name]['modified'] = date('Y-m-d H:i:s');
        // Ensure the name is not empty
        if (empty($this->data['SysParameter']['id'])) {


            $this->errorMessage = "Id is required";
            return false;
        }

        if (!is_numeric($this->data['SysParameter']['id'])) {

            $this->errorMessage = "Id must be a number";
            return false;
        }


        if (empty($this->data['SysParameter']['parameter_code'])) {

            $this->errorMessage = "Parameter code is required";
            return false;
        }
        if (empty($this->data['SysParameter']['parameter_type'])) {
            $this->errorMessage = "Parameter type is required";
            return false;
        }

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
        $ret = $this->field('parameter_value', array('parameter_code' => 'database.version'));
        if ($ret == false) {
            throw new UnexpectedValueException(__('Could not retrieve database version from the database.', true));
        }
        return $ret;
    }

}
