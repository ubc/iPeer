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
     * br2nl
     *
     * @param mixed &$data
     *
     * @access public
     * @return mixed
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
     * @return mixed
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
}
