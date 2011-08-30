<?php
/**
 * Web Baker : a web build utility for CakePHP Framework. <http://www.infor96.com/~nio/webbaker/>
 * Copyright (c) 2005, Krazy Nio <krazynio@gmail.com>
 * Licensed under The MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

class BakersController extends AppController
{
    var $name ='Bakers';

    /**
     * Initial values of form fields.
     * 
     * @var     array
     * @access  private
     */
    var $_fields = array(
        'bakeModel' => 1,
        'bakeController' => 1,
        'bakeView' => 1,
        'actions' => 'index, view, add, edit, delete',
        'overwrite' => 0,
        'type' => 'empty');

    function index()
    {
        if (empty($this->params['data'])) 
        {
            // Init fields ...
            $this->params['data']['Baker'] =& $this->_fields;
            $this->render();
        }
        else
        {
            if ($this->Baker->createFiles($this->params['data']))
            {
                $this->set('files', $this->Baker->web_baker->getCreatedFiles());
                $ctrl_name = $this->Baker->web_baker->getCtrlName();
                $this->set('url', $this->base.'/'.Inflector::underscore($ctrl_name));
                $this->render('ok');
            }
            else 
            {
                $this->set('data', $this->params['data']);
                $this->validateErrors($this->Baker);
                $this->set('errmsg', $this->Baker->web_baker->getErrmsg());
                $this->render();
            }   //end if
        }   //end if
    }   //end function
}   ///:~
?>