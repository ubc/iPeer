<?php

/**
 * Toolkit
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Toolkit
{
    /**
     * &getInstance
     *
     * @access public
     * @return void
     */
    function &getInstance()
    {
        static $instance = array();
        if (!$instance) {
            $instance[0] =& ClassRegistry::getObject('auth_component');
            if (false == $instance[0]) {
                throw new Exception('Could not get auth component!');
            }
        }
        return $instance[0];
    }


    /**
     * getUser
     *
     * @static
     * @access public
     * @return void
     */
    static function getUser()
    {
        $_this =& Toolkit::getInstance();
        return $_this->user();
    }


    /**
     * getUserId
     *
     * @static
     * @access public
     * @return void
     */
    static function getUserId()
    {
        $_this =& Toolkit::getInstance();
        $user = $_this->user();
        return Set::extract($user, 'User.id');
    }


  /*static function getUserGroupId()
{
    $_this =& Toolkit::getInstance();
    $user = $_this->user();
    return Set::extract($user, 'User.user_group_id');
  }*/

    /**
     * formatDate
     *
     * @param mixed $timestamp
     *
     * @static
     * @access public
     * @return void
     */
    static function formatDate($timestamp)
    {
        App::import('Model', 'SysParameter');
        $sys_parameter = new SysParameter;
        $data = $sys_parameter->findParameter('display.date_format');
        $dateFormat = $data['SysParameter']['parameter_value'];

        if (is_string($timestamp)) {
            return date($dateFormat, strtotime($timestamp));
        } else if (is_numeric($timestamp)) {
            return date($dateFormat, $timestamp);
        } else {
            return "";
        }
    }


    /**
     * parseCSV
     *
     * @param mixed $file
     *
     * @static
     * @access public
     * @return void
     */
    static function parseCSV($file)
    {
        $ret = array();
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $ret[] = $data;
            }
            fclose($handle);
        } else {
            trigger_error('Error to open file '.$file, E_USER_ERROR);
        }

        return $ret;
    }

    /**
     * pageSize
     *
     * @param int $default default selected value
     *
     * @access public
     * @return array of options for radio input of page limit size per page
     */
    static function pageSize($default = 15)
    {
        return array(
            "type"      => "radio",
            "options"   => array("15" => "15", "30" => "30", "90" => "90", "270" => "270"),
            "onclick" => "window.location= getUrl(document.activeElement.value);",
            "default" => $default,
            "label" => "radio",
            "div" => false
        );
    }

}
