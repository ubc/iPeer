<?php
/*
 * UserPersonalizeComponent for CakePHP
 *
 * @author
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class UserPersonalizeComponent extends Object
{
	var $components = array('Session');
	/**
	* List of preseted Preference
	*
	* @var array
	* @access private
	*/
	var $personalizeList = array();

	/**
	* Function to set the personalize list
	*
	* @param string $data used for login method
	* @return errors
	*/
	function setPersonalizeList($personalizeList='')
  {
    $result = array();
    foreach($personalizeList as $row): $personalize = $row['Personalize'];
       $result[$personalize['attribute_code']] = $personalize['attribute_value'];
    endforeach;
    $this->personalizeList = $result;
    //$this->Session->write('ipeerSession.PersonalizeList', $this->personalizeList);
  }

	/**
	* Function to get the Personalize Value
	* Method to get: $tmp= $result['USR'];
  *                echo $tmp['function_name'];
	* @param string $data used for login method
	* @return errors
	*/
	function getPersonalizeValue($attributeCode='')
    {
        //$this->personalizeList=$this->Session->read('ipeerSession.PersonalizeList');
        if (empty($this->personalizeList)){
            return array();
        } else {
            isset($this->personalizeList[$attributeCode]) ?
                $value = $this->personalizeList[$attributeCode] :
                $value = 'close';
            if (isset($this->personalizeList[$attributeCode])) {
                $value = $this->personalizeList[$attributeCode];
            } else {
                if (strstr($attributeCode,"Limit.Show")) {
                    $value = 25;
                } else {
                    $value = 'close';
                }
            }

        return $value;
        }
  }

  function getPesonalizeList() {
    return $this->personalizeList;
  }

  function inPersonalizeList($attributeCode='') {
    if (isset($this->personalizeList[$attributeCode])) return true;
    else return false;
  }

}

?>