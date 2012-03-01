<?php
/**
 * SysFunction
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SysFunction extends AppModel
{
    public $name = 'SysFunction';
    public $actsAs = array('Traceable');

    /**
     * getAllAccessibleFunction
     *
     * @param mixed $role
     *
     * @access public
     * @return array
     */
    function getAllAccessibleFunction($role)
    {
        //return $this->find('all',"permission_type LIKE '%".$role."%' ", array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link'));
        return $this->find('all', array(
            'conditions' => array('permission_type LIKE' => '%'.$role.'%'),
            'fields' => array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link')
        ));
    }

    /**
     * getTopAccessibleFunction
     *
     * @param string $role
     *
     * @access public
     * @return array
     */
    function getTopAccessibleFunction($role)
    {
        //return $this->find('all',"permission_type LIKE '%".$role."%' AND parent_id = 0 ", array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link'));
        return $this->find('all', array(
            'conditions' => array('permission_type LIKE' => '%'.$role.'%', 'parent_id' => '0'),
            'fields' => array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link')
        ));
    }


    // Function is obsolete.
  /*function getCountAccessibleFunction ($role='')
{
    //return $this->find(count,"permission_type LIKE '%".$role."%' ");
      return $this->find('count', array(
          'conditions' => array('permission_type LIKE' => '%'.$role.'%')
      ));
  }*/
    /**
     * Called everytime before SysFunction->save() is called; checks all necessary
     * parameters are set prior to saving.
     *
     * @access public
     * @return void
     */
    function beforeSave()
    {
        $this->data[$this->name]['modified'] = date('Y-m-d H:i:s');

        // Ensure the name is not empty
        if (empty($this->data['SysFunction']['id']) ||
            empty($this->data['SysFunction']['function_code']) ||
            empty($this->data['SysFunction']['function_name']) ||
            empty($this->data['SysFunction']['parent_id']) ||
            empty($this->data['SysFunction']['controller_name']) ||
            empty($this->data['SysFunction']['url_link']) ||
            empty($this->data['SysFunction']['permission_type'])) {
            $this->errorMessage = "All fields are required";
            return false;
        }

        if (!is_numeric($this->data['SysFunction']['id'])) {
            $this->errorMessage = "Id must be a number";
            return false;
        }
        return parent::beforeSave();
    }
}

