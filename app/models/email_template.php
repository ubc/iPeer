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

    /**
     * Get my email templates
     *
     * @param int    $user_id user id
     * @param string $type    find type
     *
     * @return List of my email templates
     */
    function getMyEmailTemplate($user_id, $type = 'all')
    {
        $this->recursive = -1;
        return $this->find($type, array(
            'conditions' => array('creator_id' => $user_id)
        ));
    }

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
        $this->recursive = -1;
        return $this->find($type, array(
            'conditions' => array('OR' => array(
                array('creator_id' => $user_id),
                array('availability' => '1')
            ))
        ));
    }

    /**
     * Get creator id
     *
     * @param int $template_id template id
     *
     * @return Creator Id
     */
    function getCreatorId($template_id)
    {
        $tmp = $this->find('first', array(
            'conditions' => array('EmailTemplate.id' => $template_id),
            'fields' => array('EmailTemplate.creator_id')
        ));
        return $tmp['EmailTemplate']['creator_id'];
    }
}
