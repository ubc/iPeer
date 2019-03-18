<?php
/**
 * OutputComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class OutputComponent extends CakeObject
{

    public $name;
    public $controller;
    public $view;

    /**
     * arrayForSelect
     *
     * @param mixed $dataArray data array
     * @param mixed $model     model
     * @param mixed $key       key
     * @param mixed $value     value
     *
     * @access public
     * @return void
     */
    function arrayForSelect($dataArray, $model, $key, $value)
    {
        $array = array();
        foreach ($dataArray as $data) {
            $array[$data[$model][$key]] = $data[$model][$value];
        }
        return $array;
    }


    /**
     * randomArrayKey
     *
     * @param mixed $array
     *
     * @access public
     * @return void
     */
    function randomArrayKey($array)
    {
        $max_keys = count($array) - 1;
        $random_key = mt_rand(0, $max_keys);
        if (isset($array[$random_key])) {
            return $array[$random_key];
        }
        return false;
    }


    /**
     * swRender
     *
     * @param mixed &$controller controller
     * @param bool  $layout      layout
     *
     * @access public
     * @return void
     */
    function swRender(&$controller, $layout = null)
    {
        ob_start();
        $controller->render($action, $layout);
        $sw = ob_get_clean();
        if (DEBUG == 0) {
            $sw = preg_replace('/\s\s+/', ' ', $sw);
            $sw = preg_replace(
                '/<(?:!(?:--[\s\S]*?--\s*)?(>)\s*|(?:script|style|SCRIPT|STYLE)[\s\S]*?<\/(?:script|style|SCRIPT|STYLE)>)/',
                '', $sw);
        }
        return $sw;
    }


    /**
     * render
     *
     * @param mixed &$controller controller
     * @param mixed $action      action
     * @param mixed $layout      layout
     *
     * @access public
     * @return void
     */
    function render(&$controller, $action, $layout)
    {
        $controller->autoRender = false;
        $controller->params['bare'] = 1;
        $controller->action = $action;
        $controller->{$action}();
        ob_start();
        $controller->render(null, $layout);
        $return = ob_get_clean();
        return $return;

    }


    /**
     * sqlDateTime
     *
     * @param mixed $data data
     * @param mixed $tag  tag
     *
     * @access public
     * @return void
     */
    function sqlDateTime($data, $tag)
    {
        if ($data[$tag . '_meridian'] == 'pm' && $data[$tag . '_hour'] < 12) {
            $hour = $data[$tag . '_hour'] + 12;
        }
        if ($data[$tag . '_meridian'] == 'am' && $data[$tag . '_hour'] == '12') {
            $hour = $data[$tag . '_hour'] + 12;
        }
        if ($data[$tag . '_meridian'] == 'am' && $data[$tag . '_hour'] < '10') {
            $hour = '0' . $data[$tag . '_hour'];
        }
        $dateTime = $data[$tag . '_year'] . '-' . $data[$tag . '_month'] . '-'
            . $data[$tag . '_day'] . ' ' . $hour . ':' . $data[$tag . '_min']
            . ':00';
        return $dateTime;
    }


    /**
     * br2nl
     *
     * @param mixed &$data
     *
     * @access public
     * @return void
     */
    function br2nl(&$data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->br2nl($value);
            }
        } else {
            $data = preg_replace('!<br.*>!iU', "\n", $data);
        }
        return $data;
    }


    /**
     * filter
     *
     * @param mixed &$data
     *
     * @access public
     * @return void
     */
    function filter(&$data)
    {
        $search = array('@<script[^>]*?>.*?</script>@si',
            // Strip out javascript
            '@<object[^>]*?>.*?</object>@si', // Strip out objects
            '@<iframe[^>]*?>.*?</iframe>@si', // Strip out iframes
            '@<applet[^>]*?>.*?</applet>@si', // Strip out applets
            '@<meta[^>]*?>.*?</meta>@si', // Strip out meta
            '@<form[^>]*?>.*?</form>@si', // Strip out forms
            '@([\n])@', // convert to <br/>
            '@&(quot|#34);@i', // Replace HTML entities
            '@&(amp|#38);@i', '@&(lt|#60);@i', '@&(gt|#62);@i', '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i', '@&(cent|#162);@i', '@&(pound|#163);@i',
            '@&(copy|#169);@i');

        $replace = array('', '', '', '', '', '', '<br/>', '"', '&', '<', '>', ' ',
            chr(161), chr(162), chr(163), chr(169));
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->filter($value);
            }
        } else {
            $data = preg_replace($search, $replace, $data);
            // Decode the &#[0-9]+; pattern
            $data = preg_replace_callback('/&#(\d+);/m', function($matches) {
                return chr($matches[1]);
            }, $data);
        }
        return $data;
    }


    /**
     * refresh
     *
     * @param string $url  url
     * @param string $time time
     *
     * @access public
     * @return void
     */
    function refresh($url = '/', $time = '1')
    {
        echo '<meta http-equiv="refresh" content="' . $time . '; url="' . $url . '">';
    }


  /* deprecated and moved to app/libs/toolkit.php; function name unchanged
  function formatDate($timestamp=null)
{
        $this->SysParameter = new SysParameter;
        $data = $this->SysParameter->findParameter('display.date_format');
        $dateFormat = $data['SysParameter']['parameter_value'];

        if (is_string($timestamp)) {
            return date($dateFormat,strtotime($timestamp));
        } else if (is_numeric($timestamp)) {
            return date($dateFormat, $timestamp);
        } else {
            return "";
        }
  }*/


    /**
     * formatDate
     *
     * @param mixed $timestamp
     *
     * @access public
     * @return void
     */
    function formatDate($timestamp)
    {
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
}
