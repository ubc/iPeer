<?php
/**
 * EmailTemplate
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailTemplate extends AppModel
{
    public $name = 'EmailTemplate';

    public $actsAs = array('Traceable');
    
    public $validate = array(
        'availability' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select an availability option.'
        ),
    );

    /**
     * Get email templates that available for the user
     *
     * @param int    $user_id user id
     * @param string $type    find type
     *
     * @return List of available email templates
     */
    function getPermittedEmailTemplate($user_id, $type= 'all')
    {
        if (empty($user_id)) {
            return array();
        }

        $this->recursive = -1;
        return $this->find($type, array(
            'conditions' => array('OR' => array(
                array('creator_id' => $user_id),
                array('availability' => '1')
            )),
            'order' => 'name ASC'
        ));
    }
}
