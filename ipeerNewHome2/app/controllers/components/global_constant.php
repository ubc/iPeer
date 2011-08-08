<?php
/* SVN FILE: $Id: global_constant.php 284 2010-07-27 00:56:18Z compass $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 284 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:15 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * GlobalConstant
 *
 * Class of global constants.
 *
 * @package
 * @subpackage
 * @since
 */
class GlobalConstantComponent
{
  
  var $DEFAULT_PASSWORD = 'ipeer';


  /* Record Status - Active, Inactive */
  var $STATUS_ACTIVE = 'A';
  var $STATUS_INACTIVE = 'I';
  

  
  /* Access Right - Owner, All, ReadOnly*/
  var $ACCESS_RIGHT_OWNER = 'O';
  var $ACCESS_RIGHT_ALL = 'A';
  var $ACCESS_RIGHT_READ_ONLY = 'R';
  
  /* System Function Code */
  var $SYS_FUNCTION_HOME = 'HOME';
  var $SYS_FUNCTION_USER = 'USR';
}

?>