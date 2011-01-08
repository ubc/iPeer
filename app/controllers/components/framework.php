<?php
/* SVN FILE: $Id$ */
/*
 * rdAuth Component for ipeerSession
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class frameworkComponent
{
	var $components = array('sysContainer');

  /**
   * validateUploadFile() - validates the uploaded file format
   *
   * @var $uses
   */
  function validateUploadFile($tmpFile, $filename, $uploadFile) {
    $result = true;
  	$fileParts = pathinfo('dir/' . $filename);
    if (empty($fileParts['extension'])) {
      $result = "No filename extension. Must be csv.";
      return $result;
    }
    //echo "tem file is ".$tmpFile.' file name is  '.$fileName.' upload file is '.$uploadFile.'<br>';
  	$fileExtension = strtolower($fileParts['extension']); 
  	if ($fileExtension == 'txt' || $fileExtension == 'csv')  {
  		if (!move_uploaded_file($tmpFile, $uploadFile)) {
  			$result = "Error reading file";
  		}
  	} else {
  		$result = "iPeer does not support the file type '." . $fileExtension .
  					 "'. Please use only text files (.txt) or comma seperated values files (.csv).";
  	}
  	return $result;
  }

  // returns the difference between two times
  function getTimeDifference($t1, $t2, $format='days') {
  	$seconds = strtotime($t1) - strtotime($t2);
  	$minutes = $seconds / 60;
  	$hours = $minutes / 60;
  	$days = $hours / 24;

  	if ($format == 'days') {
  		return $days;
  	}
  	else if ($format == 'hours') {
  		return $hours;
  	}
  	else if ($format == 'minutes') {
  		return $minutes;
  	}
  	else if ($format == 'seconds') {
  		return $seconds;
  	}
  	else {
  		return 0;
  	}
  }

  // returns the current date and time, in the format to be stored in the database
  function getTime($t=0, $f='Y-m-d H:i:s') {
  	if ($t == 0) {
  		$t = time();
  	}
  	return date($f, $t);
  }

  function getUser($userId) {
    
    $this->User = new User;
    return ($this->User->find('id = '.$userId));
  }
}
