<?php
/* SVN FILE: $Id: controller_popular.tpl.php,v 1.3 2006/06/20 18:46:55 zoeshum Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.3 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:46:55 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: %CONTROLLER_NAME%
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class %CONTROLLER_CLASS_NAME% extends AppController
{
    var $name = '%CONTROLLER_NAME%';

	function index()
	{
		$this->set('data', $this->%MODEL_NAME%->findAll());
	}
	
	function view($id)
	{
		$this->set('data', $this->%MODEL_NAME%->read());
	}
	
	function add()
	{
		if (empty($this->params['data']))
		{
			$this->render();
		}
		else
		{
			if ($this->%MODEL_NAME%->save($this->params['data']))
			{
				$this->flash('Your %MODEL_NAME% has been saved.','/%CONTROLLER_NAME_LOWER%');
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render('edit');
			}
		}
	}
	
	function edit($id=null)
	{
		if (empty($this->params['data']))
		{
			$this->%MODEL_NAME%->setId($id);
			$this->params['data'] = $this->%MODEL_NAME%->read();
			$this->render();
		}
		else
		{
			if ( $this->%MODEL_NAME%->save($this->params['data']))
			{
				$this->flash('Your %MODEL_NAME% has been updated.','/%CONTROLLER_NAME_LOWER%');
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
	}
	
	function delete($id)
	{
		if ($this->%MODEL_NAME%->del($id))
		{
			$this->flash('The %MODEL_NAME% with id: '.$id.' has been deleted.', '/%CONTROLLER_NAME_LOWER%');
		}
	}

%ACTION_LIST%
}

?>