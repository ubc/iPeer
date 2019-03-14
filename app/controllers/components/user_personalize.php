<?php
/**
 * UserPersonalizeComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserPersonalizeComponent extends CakeObject
{
    public $components = array('Session');
    /**
     * List of preseted Preference
     *
     * @public array
     * @access private
     */
    public $personalizeList = array();

    /**
     * Function to set the personalize list
     *
     * @param string $personalizeList used for login method
     *
     * @return errors
     */
    function setPersonalizeList($personalizeList='')
    {
        $result = array();
        foreach ($personalizeList as $row) {
            $personalize = $row['Personalize'];
            $result[$personalize['attribute_code']] = $personalize['attribute_value'];
        }
        $this->personalizeList = $result;
        //$this->Session->write('ipeerSession.PersonalizeList', $this->personalizeList);
    }

    /**
     * Function to get the Personalize Value
     * Method to get: $tmp= $result['USR'];
     *                echo $tmp['function_name'];
     *
     * @param string $attributeCode used for login method
     *
     * @return errors
     */
    function getPersonalizeValue($attributeCode='')
    {
        //$this->personalizeList=$this->Session->read('ipeerSession.PersonalizeList');
        if (empty($this->personalizeList)) {
            return array();
        } else {
            isset($this->personalizeList[$attributeCode]) ?
                $value = $this->personalizeList[$attributeCode] :
                $value = 'close';
            if (isset($this->personalizeList[$attributeCode])) {
                $value = $this->personalizeList[$attributeCode];
            } else {
                if (strstr($attributeCode, "Limit.Show")) {
                    $value = 25;
                } else {
                    $value = 'close';
                }
            }

            return $value;
        }
    }

    /**
     * getPesonalizeList
     *
     *
     * @access public
     * @return void
     */
    function getPesonalizeList()
    {
        return $this->personalizeList;
    }


    /**
     * inPersonalizeList
     *
     * @param string $attributeCode
     *
     * @access public
     * @return void
     */
    function inPersonalizeList($attributeCode='')
    {
        if (isset($this->personalizeList[$attributeCode])) {
            return true;
        } else {
            return false;
        }
    }
}
