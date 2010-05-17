<?php
/**
 * Web Baker : a web build utility for CakePHP Framework. <http://www.infor96.com/~nio/webbaker/>
 * Copyright (c) 2005, Krazy Nio <krazynio@gmail.com>
 * Licensed under The MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

/**
 * Require needed libraries.
 */
uses('object', 'inflector');

/**
 * WebBaker.
 *
 * @package    webbaker
 */
class WebBaker extends Object
{
    /**
     * Decides whether to overwrite existing files.
     *
     * @var     boolean
     * @access  private
     */    
    var $_overwrite = false;

    /**
     * Bake type.
     *
     * @var     boolean
     * @access  private
     */
    var $_type = 'empty';

    /**
     * The last error message.
     *
     * @var     string
     * @access  private
     */
    var $_errmsg = '';

    /**
     * Baked model name.
     *
     * @var     string
     * @access  private
     */
    var $_model_name = '';

    /**
     * Baked model plural name for controller and view.
     *
     * @var     string
     * @access  private
     */
    var $_plural_name = '';

    /**
     * Baked controller name.
     *
     * @var     string
     * @access  private
     */
    var $_ctrl_name = '';

    /**
     * List of action name.
     *
     * @var     array
     * @access  private
     */
    var $_actions = array();
    
    /**
     * Created file list.
     * @var     array
     * @access  private
     */
    var $_files = array();

    /**
     * Setting overwrite switch.
     *
     * @param   boolean $switch Default is true.
     * @access  public
     */
    function setOverwrite($switch = true)
    {
        $this->_overwrite = (bool)$switch;
    }   //end function

    /**
     * Setting bake type.
     *
     * @param   string  $type   Type name. Default is 'empty'.
     * @access  public
     */
    function setType($type = 'empty')
    {
        $this->_type = $type;
    }   //end function

    /**
     * Set model name.
     * 
     * @param   string  $model_name Model name.
     * @uses    Inflector::pluralize()
     * @uses    Inflector::camelize()
     */
    function setModelName($model_name)
    {
        $this->_model_name  = $this->_getModelName($model_name);
        $this->_plural_name = Inflector::pluralize(strtolower($this->_model_name));
        $this->_ctrl_name   = Inflector::camelize($this->_plural_name);
    }   //end function

    /**
     * Set list of action name.
     * 
     * @param   array/string  $actions  Action name(s).
     */
    function setActions($actions)
    {
        if (!is_array($actions))
        {
            $actions = array($actions);
        }   //end if
        $this->_actions = array_map('trim', $actions);
    }   //end function

    /**
     * Get current controller name.
     *
     * @return  string
     * @access  public
     */
    function getCtrlName()
    {
        return $this->_ctrl_name;
    }

    /**
     * Get created file list.
     *
     * @return  array
     * @access  public
     */
    function getCreatedFiles()
    {
        return $this->_files;
    }   //end function

    /**
     * Get created file listthe last error message.
     *
     * @return  string
     * @access  public
     */
    function getErrmsg()
    {
        return $this->_errmsg;
    }   //end function

    /**
     * Bake a model file.
     *
     * @access  public
     * @uses    Inflector::underscore() 
     * @uses    WebBaker::_getTemplateContent() 
     * @uses    WebBaker::_createFile() 
     */
    function bakeModel()
    {
        $fn = MODELS.Inflector::underscore($this->_model_name).'.php';  //model file name
        if (false === ($content = $this->_getTemplateContent('model')))
        {
            return false;
        }   //end if
        $content = str_replace('%MODEL_NAME%', $this->_model_name, $content);
        return $this->_createFile($fn, $content);
    }   //end function

    /**
     * Bake a controller file base on WebBaker::_actions.
     *
     * @access  public
     * @uses    Inflector::pluralize 
     * @uses    Inflector::camelize 
     * @uses    WebBaker::_model_name 
     * @uses    WebBaker::_actions 
     * @uses    WebBaker::_getTemplateContent() 
     * @uses    WebBaker::_createFile() 
     */
    function bakeController()
    {
        $fn = CONTROLLERS.Inflector::underscore($this->_plural_name).'_controller.php';;
        $ctrl_class_name = $this->_ctrl_name.'Controller';
        if (false === ($content = $this->_getTemplateContent('controller_'.$this->_type)))
        {
            return false;
        }   //end if
        switch ($this->_type) 
        {
            case 'scaffold':    //do nothing
                $content = str_replace(
                    array('%CONTROLLER_CLASS_NAME%', '%CONTROLLER_NAME%'), 
                    array($ctrl_class_name, $this->_ctrl_name), 
                    $content);
                break;
            case 'popular':
            case 'empty':
            default:
                if (false === ($action_content = $this->_getTemplateContent('action')))
                {
                    return false;
                }   //end if
                $action_str = '';
                foreach ($this->_actions as $action)
                {
                    if ('popular' == $this->_type && preg_match('/^(index|view|add|edit|delete)$/', $action))
                        continue;   //skip!
                    $action_str .= str_replace('%ACTION_NAME%', $action, $action_content);
                }   //end foreach
                $content = str_replace(
                    array(  '%MODEL_NAME%', 
                            '%MODEL_NAME_LOWER%',  
                            '%CONTROLLER_CLASS_NAME%', 
                            '%CONTROLLER_NAME%', 
                            '%CONTROLLER_NAME_LOWER%', 
                            '%ACTION_LIST%'), 
                    array(  $this->_model_name, 
                            strtolower($this->_model_name), 
                            $ctrl_class_name,
                            $this->_ctrl_name, 
                            strtolower($this->_ctrl_name), 
                            $action_str), 
                    $content);
                break;
        }
        return $this->_createFile($fn, $content);
    }   //end function

    /**
     * Bake view file(s) base on WebBaker::_actions.
     *
     * @access  public
     * @uses    Inflector::pluralize 
     * @uses    Inflector::underscore 
     * @uses    WebBaker::_model_name 
     * @uses    WebBaker::_actions 
     * @uses    WebBaker::_getTemplateContent() 
     * @uses    WebBaker::_createDir() 
     * @uses    WebBaker::_createFile() 
     */
    function bakeView()
    {
        if ('scaffold' == $this->_type)
        {
            return true;
        }
        $dir = VIEWS.Inflector::underscore($this->_plural_name);
        if (!$this->_createDir($dir))
        {
            return false;
        }   //end if
        //get empty view file content.
        if (false === ($empty_content = $this->_getTemplateContent('view')))
        {
            return false;
        }   //end if
        foreach ($this->_actions as $action)
        {
            //skip action start with '_' because it's a private method and not need a view file.
            if (preg_match('/^_/i', $action))
                continue;
            $view_content = $empty_content; //default
            if ('popular' == $this->_type && preg_match('/^(index|view|add|edit)$/', $action))
            {
                //get popular view file content. 'add' & 'edit' use the same view file.
                $view_content = $this->_getTemplateContent(('add'==$action)?'view_edit':'view_'.$action);
            }
            $fn = $dir.DS.strtolower($action).'.tpl.php';
            $content = str_replace(
                array(  '%VIEW_FILE_PATH%', 
                        '%MODEL_NAME%', 
                        '%MODEL_NAME_LOWER%', 
                        '%CONTROLLER_NAME%', 
                        '%CONTROLLER_NAME_LOWER%'), 
                array(  $fn, 
                        $this->_model_name, 
                        strtolower($this->_model_name), 
                        $this->_ctrl_name, 
                        strtolower($this->_ctrl_name)),
                $view_content);
            if (!$this->_createFile($fn, $content))
            {
                return false;
            }   //end if
        }   //end foreach
        return true;
    }   //end function

    /**
     * Returns code template for PHP file generator.
     *
     * @param string $type
     * @return string
     * @access private
     */
    function _getTemplateContent($type)
    {
        $fn = VENDORS.'web_baker'.DS.'tpls'.DS.strtolower($type).'.tpl.php';   //template file name
        if (!file_exists($fn))
        {
            $this->_errmsg = "File {$fn} does not exists.";
            return false;
        }   //end if
        if (false === ($content = file_get_contents($fn)))
        {
            $this->_errmsg = "Couldn't read file {$fn}.";
            return false;
        }   //end if
        return $content;
    }   //end function

    /**
     * Returns CamelCased name of a model.
     *
     * @param string $name
     * @return string
     * @access private
     * @uses Inflector::camelize()
     */
    function _getModelName($name)
    {
        return Inflector::camelize($name);
    }

    /**
     * Creates a file with given path and contents.
     *
     * @param  string $path
     * @param  string $contents
     * @return boolean
     * @access private
     * @uses   WebBake::overwrite
     */
    function _createFile($path, $contents)
    {
        $shortPath = str_replace(ROOT, null, $path);

        if (@file_exists($path) && !$this->_overwrite)
        {
            $this->_errmsg = "File {$shortPath} exists.";
            return false;
        }   //end if        
        if ($f = @fopen($path, 'w'))
        {
            fwrite($f, $contents);
            fclose($f);
            $this->_files[] = $path;
            return true;
        }
        else
        {
            $this->_errmsg = "Couldn't open {$shortPath} for writing.";
            return false;
        }   //end if
    }   //end function

    /**
     * Creates a directory with given path.
     *
     * @param  string $path
     * @return boolean
     * @access private
     * @uses   Bake::stdin
     * @uses   Bake::stdout
     */
    function _createDir($path)
    {
        if (is_dir($path))
        {
            return true;
        }   //end if
        $shortPath = str_replace(ROOT, null, $path);
        if (@mkdir($path))
        {
            $this->_files[] = $path;
            return true;
        }
        else
        {
            $this->_errmsg = "Couldn't create dir {$shortPath}";
            return false;
        }   //end if
    }   //end function
}   ///:~
?>