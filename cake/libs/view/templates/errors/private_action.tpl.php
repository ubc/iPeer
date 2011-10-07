<?php
/* SVN FILE: $Id: private_action.tpl.php,v 1.3 2006/06/20 18:46:44 zoeshum Exp $ */

/**
 *
 *
 *
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright (c)	2006, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright (c) 2006, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.errors
 * @since			CakePHP v 0.10.0.1076
 * @version			$Revision: 1.3 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2006/06/20 18:46:44 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<h1>Private Method in <?php echo $controller;?></h1>

<p class="error">You are seeing this error because the private class method <em><?php echo $action;?></em>
  should not be accessed directly
</p>