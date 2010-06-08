<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:19 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SysFunction
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class SysFunction extends AppModel
{
  var $name = 'SysFunction';

  function getAllAccessibleFunction ($role='') {
   	return $this->findAll("permission_type LIKE '%".$role."%' ", array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link'));
  }    

  function getTopAccessibleFunction ($role='') {
   	return $this->findAll("permission_type LIKE '%".$role."%' AND parent_id = 0 ", array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link'));
  }  
   
  function getCountAccessibleFunction ($role='') {
   	return $this->findCount("permission_type LIKE '%".$role."%' ");
  }       
}

?>