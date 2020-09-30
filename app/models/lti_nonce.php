<?php

/**
 * LtiNonce
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiNonce extends AppModel
{
    public $name = 'LtiNonce';
    public $validate = array(
        'nonce' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
    );

    /**
     * Find registration by `iss` in database.
     *
     * @param string $iss
     * @return LtiNonce
     */
    public function findByNonce($nonce)
    {
        return $this->find('first', array(
            'conditions' => array(
                'LtiNonce.nonce' => $nonce
            ),
        ));
    }
}
