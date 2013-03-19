<?php
/**
 * Access Model
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2013 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Access extends AppModel
{
    public $name = 'Access';
    public $useTable = 'aros_acos';
    
    protected $options = array('model'=>'Aco', 'field'=>'alias');
    protected $permissions = array();
    protected $perms = array();
    protected $noAccess = array('create' => -1, 'read' => -1, 'update' => -1, 'delete' => -1);
    
    /**
     * load permissions
     *
     * @param mixed $acos      acos
     * @param mixed $group_aro group aro
     *
     * @access public
     * @return array of permissions
     */
    public function loadPermissions($acos, $group_aro)
    {
        $this->permissions = array();
        
        if (empty($group_aro)) {
            return $this->permissions;
        }

        //GET ACL PERMISSIONS
        $group_perms = Set::extract('{n}.Aco', $group_aro);
        $gpAco = array();
        foreach ($group_perms[0] as $value) {
            $gpAco[$value['id']] = $value;
        }

        $this->perms = $gpAco;
        $this->_generatePermissions($acos, 0);

        return $this->permissions;
    }
 
    /**
     * generate permissions
     *
     * @param mixed $acos
     * @param mixed $level
     * @param mixed $alias
     * @param mixed $inheritPermission
     *
     * @access protected
     * @return array of permissions
     */
    function _generatePermissions($acos, $level, $alias = '', $inheritPermission = array())
    {
        foreach ($acos as $val) {
            $thisAlias = $alias . $val[$this->options['model']][$this->options['field']];
            if (isset($this->perms[$val[$this->options['model']]['id']])) {
                $curr_perm = $this->perms[$val[$this->options['model']]['id']];
                $access = array();
                foreach ($curr_perm['Permission'] as $key => $p) {
                    if (substr($key, 0, 1) != '_') {
                        continue;
                    }
                    $access[substr($key, 1)] = $p;
                }
                $this->permissions[strtolower($thisAlias)] = $access;
                $inheritPermission[$level] = $access;
            } else {
                if (!empty($inheritPermission)) {
                    if ($inheritPermission[$level-1] != -1) {
                        //$inheritPermission[$level-1][] = $id;
                        $this->permissions[strtolower($thisAlias)] = $inheritPermission[$level-1];
                    }
                    $inheritPermission[$level] = $inheritPermission[$level-1];
                } else {
                    $this->permissions[strtolower($thisAlias)] = $this->noAccess;
                    $inheritPermission[$level] = $this->noAccess;
                }
            }
            
            if (isset($val['children'][0])) {
                $old_alias = $alias;
                $alias .= $val[$this->options['model']][$this->options['field']] .'/';
                $this->_generatePermissions($val['children'], $level+1, $alias, $inheritPermission);
                unset($inheritPermission[$level+1]);
                $alias = $old_alias;
            }
            $this->permissions[strtolower($thisAlias)]['id'] = $val[$this->options['model']]['id'];
        }
        
        return;
    }
}