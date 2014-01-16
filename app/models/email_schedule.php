<?php
/**
 * EmailSchedule
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailSchedule extends AppModel
{
    public $name = 'EmailSchedule';

    public $actsAs = array('Traceable');

    /**
     * Get emails to send
     *
     * @return Emails to send
     */
    function getEmailsToSend()
    {
        $this->recursive = -1;
        // use the php date to get php time with timezone instead of now() in
        // mysql, just in case the DB has different timezone setting with php
        return $this->find('all', array(
            'conditions' => array('EmailSchedule.date <=' => date('Y-m-d H:i:s'), 'EmailSchedule.sent' => '0'),
        ));
    }

    /**
     * Get whether email is sent or not
     *
     * @param int $id id
     *
     * @return Sent field
     */
    function getSent($id)
    {
        $tmp = $this->find('first', array(
            'conditions' => array('EmailSchedule.id' => $id),
            'fields' => array('EmailSchedule.sent')
        ));
        return $tmp['EmailSchedule']['sent'];
    }
}
