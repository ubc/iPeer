<?php
/**
 * frameworkComponent
 * rdAuth Component for ipeerSession
 *
 * @package   CTLT.iPeer
 * @author    gwoo <gwoo@rd11.com>
 * @copyright 2012 All rights reserved.
 * @license   OPPL http://oppl.org
 * @Release   0.10.5.1797
 */
class frameworkComponent
{
    /**
     * validateUploadFile() - validates the uploaded file format
     *
     * @param mixed $tmpFile    tmp file
     * @param mixed $filename   file name
     * @param mixed $uploadFile upload file
     *
     * @access public
     * @return void
     */
    function validateUploadFile($tmpFile, $filename, $uploadFile)
    {
        $result = true;
        $fileParts = pathinfo('dir/' . $filename);
        if (empty($fileParts['extension'])) {
            $result = __("No filename extension. Must be csv.", true);
            return $result;
        }
        //echo "tem file is ".$tmpFile.' file name is  '.$fileName.' upload file is '.$uploadFile.'<br>';
        $fileExtension = strtolower($fileParts['extension']);
        if ($fileExtension == 'txt' || $fileExtension == 'csv') {
            if (!move_uploaded_file($tmpFile, $uploadFile)) {
                $result = __("Error reading file", true);
            }
        } else {
            $result = __("iPeer does not support the file type '.", true) . $fileExtension .
                __("'. Please use only text files (.txt) or comma seperated values files (.csv).", true);
        }
        return $result;
    }


    /**
     * getTimeDifference
     * returns the difference between two times
     *
     * @param mixed $t1     time 1
     * @param mixed $t2     time 2
     * @param bool  $format format
     *
     * @access public
     * @return void
     */
    function getTimeDifference($t1, $t2, $format='days')
    {
        $seconds = strtotime($t1) - strtotime($t2);
        $minutes = $seconds / 60;
        $hours = $minutes / 60;
        $days = $hours / 24;

        if ($format == __('days', true)) {
            return $days;
        } else if ($format == __('hours', true)) {
            return $hours;
        } else if ($format == __('minutes', true)) {
            return $minutes;
        } else if ($format == __('seconds', true)) {
            return $seconds;
        } else {
            return 0;
        }
    }

    /**
     * getTime
     * returns the current date and time, in the format to be stored in the database
     *
     * @param int  $t time
     * @param bool $f format
     *
     * @access public
     * @return void
     */
    function getTime($t=0, $f='Y-m-d H:i:s')
    {
        if ($t == 0) {
            $t = time();
        }
        return date($f, $t);
    }


    /**
     * getUser
     *
     * @param mixed $userId
     *
     * @access public
     * @return void
     */
    function getUser($userId)
    {

        $this->User = new User;
        return ($this->User->find('id = '.$userId));
    }
}
