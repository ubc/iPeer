<?php
/* SVN FILE: $Id: personalize.php,v 1.1 2006/06/20 18:44:18 zoeshum Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.1 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:18 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */
 
/**
 * Personalize
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Personalize extends AppModel
{
  var $name = 'Personalize';

  function updateAttribute($userId='', $attributeCode='', $attributeValue = null)
  {
    $data = $this->find("user_id = ".$userId." AND attribute_code = '".$attributeCode."' ");
    $tmpValue = '';
    if (isset($data) && $attributeValue == null) {
      $tmpValue = $data['Personalize']['attribute_value'];
      if ($tmpValue == 'true') {
        $tmpValue = 'none';
      } else {
         $tmpValue = 'true';
      }
      $data['Personalize']['attribute_code'] = $attributeCode;
      $data['Personalize']['user_id'] = $userId;
      $data['Personalize']['attribute_value']=$tmpValue;
    } else {
      $data['Personalize']['attribute_value'] = ($tmpValue == 'none') ? 'true':$attributeValue;
      $data['Personalize']['attribute_code'] = $attributeCode;
      $data['Personalize']['user_id'] = $userId;
    }
    $this->save($data);
  }
}

?>