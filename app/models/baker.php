<?php
/**
 * Web Baker : a web build utility for CakePHP Framework. <http://www.infor96.com/~nio/webbaker/>
 * Copyright (c) 2005, Krazy Nio <krazynio@gmail.com>
 * Licensed under The MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

/**
 * Require needed libraries.
 */
vendor('web_baker'.DS.'web_baker');

class Baker extends AppModel
{
    var $name ='Baker';

    /**
     * Model name is required.
     */
    var $validate = array('name' => VALID_NOT_EMPTY);

    /**
     * @var     object WebBaker
     * @access  public
     */
    var $web_baker = null;

    /**
     * Constructor. 
     * Here, Baker model doesn't inherit constructor from class 'Model', because it need not bind any database.
     */
    function __construct()
    {
        $this->web_baker = new WebBaker;
    }   //end function

    /**
     * Create MVC files for Cake.
     *
     * @param array $data POST data.
     * @return boolean
     */
    function createFiles($data) 
    {
        $this->set($data);
        if (!$this->validates())
        {
            return false;
        }   //end if
        //  Setting the WebBaker Instance.
        empty($data['Baker']['overwrite']) or $this->web_baker->setOverwrite(true);
        $this->web_baker->setType($data['Baker']['type']);        
        $this->web_baker->setModelName($data['Baker']['name']);
        $actions = (trim($data['Baker']['actions'])) ? explode(',', $data['Baker']['actions']) : array();
        $this->web_baker->setActions($actions);
        //  Bake files.
        foreach ($data['Baker'] as $key => $val)
        {
            if (preg_match('/^bake/', $key))
            {
                if (!$this->web_baker->$key())
                {
                    return false;
                }   //end if
            }   //end if
        }   //end foreach
        return true;
    }   //end function

}   ///:~
?>