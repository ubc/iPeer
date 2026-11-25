<?php
App::import('Controller', 'Emailer');

class EmailerControllerTestCase extends CakeTestCase
{
    // After a recipient is removed, the keys of the recipient list have a
    // gap; a later removal must find the user by its key.
    function testSearchByUserIdReturnsTheArrayKey()
    {
        $controller = new EmailerController();
        $recipients = [
            1 => ['User' => ['id' => 6]],
            2 => ['User' => ['id' => 7]],
        ];

        $this->assertIdentical($controller->searchByUserId($recipients, 'id', 7), 2);
        $this->assertIdentical($controller->searchByUserId($recipients, 'id', 99), null);
    }
}
