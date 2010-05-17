<?php
/* SVN FILE: $Id: sys_parameter.php,v 1.1 2006/06/20 18:44:19 zoeshum Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.1 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:19 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SysParameter
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class SysParameter extends AppModel
{
  var $name = 'SysParameter';
  
	function findParameter ($paramCode='') {
 		return $this->find("parameter_code = '".$paramCode."' ", array('id', 'parameter_code', 'parameter_value', 'parameter_type'));
  }
	

}

?>
