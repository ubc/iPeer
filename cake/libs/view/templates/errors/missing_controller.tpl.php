<?php
/* SVN FILE: $Id: missing_controller.tpl.php,v 1.3 2006/06/20 18:46:43 zoeshum Exp $ */

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
 * @lastmodified	$Date: 2006/06/20 18:46:43 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<h1>Missing controller</h1>
<p class="error">You are seeing this error because controller <em><?php echo $controller;?></em>
  could not be found.
</p>

<p>
<span class="notice"><strong>Notice:</strong> this error is being rendered by the <code>app/views/errors/missing_controller.tpl.php</code>
view file, a user-customizable error page for handling invalid controller dispatches.</span>
</p>

<p>
<strong>Fatal</strong>: Unable to load controller <em><?php echo $controller;?></em>
</p>
<p>
<strong>Fatal</strong>: Create Class:
</p>
<br />
<p>&lt;?php<br />
class <?php echo $controller;?> extends AppController<br />
{<br />
&nbsp;&nbsp;&nbsp;&nbsp;var $name = '<?php echo $controllerName;?>';<br />
}<br />
?&gt;<br />
</p>
<p>
in file : <?php echo "app".DS."controllers".DS.Inflector::underscore($controller).".php"; ?>
</p>