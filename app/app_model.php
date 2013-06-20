<?php
App::import('Lib', 'Toolkit');

/**
 * AppModel
 *
 * @uses Model
 * @package App
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class AppModel extends Model
{
    protected $errorMessage = array();

    /**
     * save override save function
     *
     * @param bool $data      data to be saved
     * @param bool $validate  is validated
     * @param bool $fieldList the list of fields
     *
     * @access public
     * @return void
     */
    function save($data = null, $validate = true, $fieldList = array())
    {
        //clear modified field value before each save
        if (isset($this->data) && isset($this->data[$this->alias])) {
            unset($this->data[$this->alias]['modified']);
        }
        if (isset($data) && isset($data[$this->alias])) {
            unset($data[$this->alias]['modified']);
        }

        return parent::save($data, $validate, $fieldList);
    }


    /**
     * showErrors itterates through the errors array
     * and returns a concatinated string of errors sepearated by
     * the $sep
     *
     * @param string $sep A seperated defaults to <br />
     *
     * @return string
     * @access public
     */
    function showErrors($sep = "<br />")
    {
        $retval = "";
        foreach ($this->errorMessage as $key => $error) {
            if (!is_numeric($key)) {
                $error = $key.': '.$error;
            }
            $retval .= "$error $sep";
        }

        return $retval;
    }

    /**
     * getErrorMessage access method for $errorMessage
     *
     * @access public
     * @return string the error message
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}

