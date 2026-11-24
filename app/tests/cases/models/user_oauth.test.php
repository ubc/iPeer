<?php
App::import('Model', 'UserOauth');

class UserOauthTestCase extends CakeTestCase
{
    public $name = 'UserOauth';
    public $fixtures = ['app.user_oauths'];

    function startCase()
    {
        echo "Start UserOauth model test.\n";
        $this->UserOauth = ClassRegistry::init('UserOauth');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
        $this->UserOauth->deleteAll(['UserOauth.id >' => 0]);
    }

    function endTest($method)
    {
    }

    function testSaveTokensCreatesARowPerUser()
    {
        // Give the first row an id that equals the second user's id, so a
        // save that uses the user id as the row id would hit this row.
        $this->UserOauth->save(['UserOauth' => [
            'id' => 5, 'user_id' => 7, 'provider' => 'canvas', 'access_token' => 'token-of-user-7',
        ]]);

        $this->assertTrue($this->UserOauth->saveTokens(5, 'canvas', ['access_token' => 'token-of-user-5']));

        $this->assertEqual($this->UserOauth->getAccessToken(7, 'canvas'), 'token-of-user-7');
        $this->assertEqual($this->UserOauth->getAccessToken(5, 'canvas'), 'token-of-user-5');
        $this->assertEqual($this->UserOauth->find('count'), 2);
    }

    function testSaveTokensUpdatesTheUsersRow()
    {
        $this->UserOauth->saveTokens(5, 'canvas', ['access_token' => 'old', 'refresh_token' => 'refresh']);
        $this->UserOauth->saveTokens(5, 'canvas', ['access_token' => 'new', 'expires' => 3600]);

        $row = $this->UserOauth->getAll(5, 'canvas');
        $this->assertEqual($row['UserOauth']['access_token'], 'new');
        $this->assertEqual($row['UserOauth']['refresh_token'], 'refresh');
        $this->assertFalse(empty($row['UserOauth']['expires']));
        $this->assertEqual($this->UserOauth->find('count'), 1);
    }
}
