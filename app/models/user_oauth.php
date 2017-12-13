<?php
/**
 * UserOauth
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Aidin Niavarani <aidin.niavarani@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserOauth extends AppModel
{
    public $name = 'UserOauth';
    
    public $validate = array (
        'userId' => 'numeric',
        'provider'  => array(
            'inList' => array(
                'rule' => array('inList', array('canvas')),
                'message' => 'Invalid Oauth provider'
            )
        ),
        'accessToken' => 'alphaNumeric',
        'refreshToken' => 'alphaNumeric'
    );
    
    /**
     * save user oauth for given provider
     *
     * @param mixed $userId         user id
     * @param mixed $provider       provider
     * @param mixed $accessToken    access token
     * @param mixed $refreshToken   refresh token
     * @param mixed $expires        access token expiry in seconds relative to current time
     *
     * @access private
     * @return boolean
     */
    function saveTokens($userId, $provider, $saveData)
    {
        if (empty($userId) || empty($provider) || empty($saveData)) {
            return false;
        }

        // first read this record
        $this->read(null, array('user_id' => $userId, 'provider' => $provider));

        // then save it
        $newOauth = array('UserOauth' => $saveData);
        $newOauth['UserOauth']['user_id'] = $userId;
        $newOauth['UserOauth']['provider'] = $provider;

        if (isset($saveData['expires'])) {
            $expiryDateTime = new DateTime('+'.$saveData['expires'].' seconds');
            $newOauth['UserOauth']['expires'] = $expiryDateTime->format("Y-m-d H:i:s");
        }

        $this->save($newOauth);
    }
    
    /**
        * get user access token for given user/provider
        *
        * @param mixed $userId         user id
        * @param mixed $provider       provider
        *
        * @access public
        * @return mixed
        */
    function getAccessToken($userId, $provider)
    {
        $oauth = $this->getAll($userId, $provider);

        return isset($oauth['UserOauth']['access_token']) ? $oauth['UserOauth']['access_token'] : false;
    }
    
    /**
        * get full user oauth record for given user/provider
        *
        * @param mixed $userId         user id
        * @param mixed $provider       provider
        *
        * @access public
        * @return object
        */
    function getAll($userId, $provider)
    {
        $oauth = $this->find('first', array(
            'conditions' => array('UserOauth.user_id =' => $userId, 'UserOauth.provider =' => $provider)
        ));
        $this->data = $oauth;

        return $oauth;
    }

    function deleteToken($userId, $provider)
    {
        return $this->deleteAll(array('UserOauth.user_id =' => $userId, 'UserOauth.provider =' => $provider));
    }
    
    /**
        * Returns a list of fields from the database, and sets the current model
        * data (Model::$data) with the record found. 
        * 
        * This method uses the parent
        * method unless the $id parameter is an array containing the user_id and provider,
        * in which case it uses those parameters to retrieve the id first before calling
        * the parent
        *
        * @param mixed $fields String of single fieldname, or an array of fieldnames.
        * @param mixed $id The ID of the record to read, or an array of fields to retrieve by
        * @return array Array of database fields, or false if not found
        * @access public
        */
    function read($fields = null, $id = null)
    {
        if (is_array($id) && isset($id['user_id']) && isset($id['provider'])) {
            $oauth = $this->find('first', array(
                'conditions' => array('UserOauth.user_id =' => $id['user_id'], 'UserOauth.provider =' => $id['provider']),
                'fields' => $fields
            ));
            $id = $oauth['UserOauth']['id'];
        }

        return parent::read($fields, $id);
    }
}
