<?php
class OutputComponent extends Object{

	var $name;
	var $controller;
	var $view;

	function arrayForSelect($dataArray, $model, $key, $value)
    {
		$array = array();
		foreach($dataArray as $data)
		{
			$array[$data[$model][$key]] = $data[$model][$value];
		}
		return $array;
	}

	function randomArrayKey($array)
	{
       $max_keys = count($array)-1;
       $random_key = mt_rand(0, $max_keys);
       if(isset($array[$random_key]))
       {
       	return $array[$random_key];
       }
       return false;
   	}

	function swRender(&$controller,$layout=null)
	{
		ob_start();
		$controller->render($action,$layout);
		$sw = ob_get_clean();
		if(DEBUG == 0)
		{
			$sw = preg_replace('/\s\s+/', ' ', $sw);
			$sw = preg_replace('/<(?:!(?:--[\s\S]*?--\s*)?(>)\s*|(?:script|style|SCRIPT|STYLE)[\s\S]*?<\/(?:script|style|SCRIPT|STYLE)>)/','',$sw);
		}
		return $sw;
	}

	function render(&$controller,$action,$layout)
	{
		$controller->autoRender = false;
		$controller->params['bare'] = 1;
		$controller->action = $action;
		$controller->{$action}();
		ob_start();
		$controller->render(null,$layout);
		$return = ob_get_clean();
		return $return;

	}

	function sqlDateTime($data, $tag)
	{
		if($data[$tag.'_meridian'] == 'pm' && $data[$tag.'_hour'] < 12)
		{
			$hour = $data[$tag.'_hour'] + 12;
		}
		if($data[$tag.'_meridian'] == 'am' && $data[$tag.'_hour'] == '12')
		{
			$hour = $data[$tag.'_hour'] + 12;
		}
		if($data[$tag.'_meridian'] == 'am' && $data[$tag.'_hour'] < '10')
		{
			$hour = '0'.$data[$tag.'_hour'];
		}
		$dateTime = $data[$tag.'_year'].'-'.$data[$tag.'_month'].'-'.$data[$tag.'_day'].' '.$hour.':'.$data[$tag.'_min'].':00';
		return $dateTime;
	}

	function br2nl(&$data) {
      if(is_array($data)) {
        foreach($data as $key=>$value) {
          $data[$key] = $this->br2nl($value);
        }
      } else {
        $data = preg_replace( '!<br.*>!iU', "\n", $data );
      }
      return $data;
	}

	function filter(&$data) {
		$search = array (
				 '@<script[^>]*?>.*?</script>@si', // Strip out javascript
				 '@<object[^>]*?>.*?</object>@si', // Strip out objects
				 '@<iframe[^>]*?>.*?</iframe>@si', // Strip out iframes
				 '@<applet[^>]*?>.*?</applet>@si', // Strip out applets
				 '@<meta[^>]*?>.*?</meta>@si', // Strip out meta
				 '@<form[^>]*?>.*?</form>@si', // Strip out forms
                 '@([\n])@',                // convert to <br/>
                 '@&(quot|#34);@i',                // Replace HTML entities
                 '@&(amp|#38);@i',
                 '@&(lt|#60);@i',
                 '@&(gt|#62);@i',
                 '@&(nbsp|#160);@i',
                 '@&(iexcl|#161);@i',
                 '@&(cent|#162);@i',
                 '@&(pound|#163);@i',
                 '@&(copy|#169);@i',
                 '@&#(\d+);@e');                    // evaluate as php

		$replace = array ('','','','','','','<br/>','"','&','<','>',' ',chr(161),chr(162),chr(163),chr(169),'chr(\1)');
        if(is_array($data)) {
      foreach($data as $key=>$value) {
        $data[$key] = $this->filter($value);
      }
    } else {
			$data = preg_replace($search, $replace, $data);
		}
    return $data;
	}

	function refresh($url='/',$time='1')
	{
		echo '<meta http-equiv="refresh" content="'.$time.'; url="'.$url.'">';
	}

  /* deprecated and moved to app/libs/toolkit.php; function name unchanged
	function formatDate($timestamp=null) {
        $this->SysParameter = new SysParameter;
        $data = $this->SysParameter->findParameter('display.date_format');
        $dateFormat = $data['SysParameter']['parameter_value'];

        if (is_string($timestamp)) {
            return date($dateFormat,strtotime($timestamp));
        } else if(is_numeric($timestamp)){
            return date($dateFormat, $timestamp);
        } else {
            return "";
        }
	}*/
	
	function weeWa(){
		$this->log(__("Does nothing", true));
	}	
	
  function formatDate($timestamp) {
    $sys_parameter = new SysParameter;
    $data = $sys_parameter->findParameter('display.date_format');
    $dateFormat = $data['SysParameter']['parameter_value'];

    if (is_string($timestamp)) {
      return date($dateFormat,strtotime($timestamp));
    } else if(is_numeric($timestamp)){
      return date($dateFormat, $timestamp);
    } else {
      return "";
    }
  }
	
}

?>
