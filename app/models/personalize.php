<?php
App::import('Model', 'User');

/**
 * Personalize
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Personalize extends AppModel
{
    public $name = 'Personalize';

    /**
     * updateAttribute
     *
     * @param string $userId         user id
     * @param string $attributeCode  attribute code
     * @param bool   $attributeValue attribute value
     *
     * @access public
     * @return void
     */
    function updateAttribute($userId='', $attributeCode='', $attributeValue = null)
    {
        $data = $this->find('first', array(
            'conditions' => array('user_id' => $userId, 'attribute_code' => $attributeCode)
        ));

        if ($data) {
            // same value, do nothing
            if ($data['Personalize']['attribute_value'] == $attributeValue) {
                return;
            }

            // update value
            $data['Personalize']['attribute_value'] = $attributeValue;
            return $this->save($data);
        }

        // if we couldn't find the record, we will create it
        $user = ClassRegistry::init('User');
        $user = $user->find('first', array('conditions' => array('User.id' => $userId)));
        // if user is not exist, do nothing
        if (!$user) {
            return;
        }

        $data['Personalize']['attribute_code'] = $attributeCode;
        $data['Personalize']['user_id'] = $userId;
        $data['Personalize']['attribute_value'] = $attributeValue;
        return $this->save($data);
    }


    /**
     * beforeSave
     *
     * @access public
     * @return void
     */
    function beforeSave($options = array())
    {
        $this->data[$this->name]['updated'] = date('Y-m-d H:i:s');
        return true;
    }
}
