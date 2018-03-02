<?php
App::import('Lib', 'system_base');

class MergeUsersTestCase extends SystemBaseTestCase
{
    public function startCase()
    {
        parent::startCase();
        echo "Start MergeUsers system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddUsers()
    {
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');

        $user1 = array(
            'UserUsername' => 'bobby1234',
            'UserFirstName' => 'Bob',
            'UserLastName' => 'Black',
            'UserEmail' => 'rob@bobby.com',
            'UserStudentNo' => '15123578',
        );
        $course1 = array('CoursesId2');

        $user2 = array(
            'UserUsername' => 'bobby5678',
            'UserFirstName' => 'Rob',
            'UserLastName' => 'Black',
            'UserEmail' => 'rob@bobby.com',
            'UserStudentNo' => '15123578',
        );
        $course2 = array('CoursesId1', 'CoursesId2');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Users');

        $this->addUser($user1, $course1);
        $this->addUser($user2, $course2);
    }

    public function addUser($user, $courses)
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add User')->click();
        $heading = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'h1.title')->text();
        $this->assertEqual($heading, 'Add User');

        foreach ($user as $id => $txt) {
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, $id)->sendKeys($txt);
        }

        foreach ($courses as $course) {
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, $course)->click();
        }

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[Form][save]')->click();
    }

    public function testMergeUsers() {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Merge Users')->click();
        $heading = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'h1.title')->text();
        $this->assertEqual($heading, 'Merge Users');

        $return = new PHPWebDriver_WebDriverKeys('ReturnKey');

        $merge = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'merge');
        $this->assertTrue($merge->attribute('disabled'));
        $prmy = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimaryAccount');
        $this->assertTrue($prmy->attribute('disabled'));
        $sndy = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserSecondaryAccount');
        $this->assertTrue($sndy->attribute('disabled'));

        $primary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimarySearchValue');
        $primary->sendKeys('Bob B');
        $primary->sendKeys($return->key);
        sleep(1);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondarySearch"] option[value="username"]')->click();
        $secondary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserSecondarySearchValue');
        $secondary->sendKeys('bby567');
        $secondary->sendKeys($return->key);
        sleep(1);

        // wait for primary account search results
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option')) - 1;
            }
        );
        $primaryUser = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option');
        $this->assertEqual($primaryUser[0]->text(), '-- Pick the primary account --');
        $this->assertEqual($primaryUser[1]->text(), 'Bob Black');
        $primaryId = $primaryUser[1]->attribute('value');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option[value="'.$primaryId.'"]')->click();

        // wait for secondary account search results
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option')) - 1;
            }
        );
        $secondaryUser = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option');
        $this->assertEqual($secondaryUser[0]->text(), '-- Pick the secondary account --');
        $this->assertEqual($secondaryUser[1]->text(), 'bobby5678');
        $secondaryId = $secondaryUser[1]->attribute('value');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option[value="'.$secondaryId.'"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $this->session->accept_alert();

        // wait for merger to finish
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The two accounts have successfully merged.');

        $this->session->open($this->url.'users/delete/'.$primaryId);
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Record is successfully deleted!');
    }

    public function testUnMatchingRoles()
    {
        $this->session->open($this->url.'users/merge');
        $return = new PHPWebDriver_WebDriverKeys('ReturnKey');

        $primary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimarySearchValue');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimarySearch"] option[value="username"]')->click();
        $primary->sendKeys('root');
        $primary->sendKeys($return->key);
        sleep(1);

        $secondary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserSecondarySearchValue');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondarySearch"] option[value="student_no"]')->click();
        $secondary->sendKeys('65498451');
        $secondary->sendKeys($return->key);
        sleep(1);

        // wait for primary account search results
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option')) - 1;
            }
        );
        $primaryUser = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option');
        $this->assertEqual($primaryUser[0]->text(), '-- Pick the primary account --');
        $this->assertEqual($primaryUser[1]->text(), 'root');
        $primaryUser[1]->click();

        // wait for secondary account search results
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option')) - 1;
            }
        );
        $secondaryUser = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option');
        $this->assertEqual($secondaryUser[0]->text(), '-- Pick the secondary account --');
        $this->assertEqual($secondaryUser[1]->text(), '65498451');
        $secondaryUser[1]->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $this->session->accept_alert();

        // wait for merger to finish
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage")->text();
        $this->assertEqual($msg, 'Error: The users do not have the same role.');
    }

    public function testNoUsersFound()
    {
        $primary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimarySearchValue');
        $primary->clear();
        $primary->sendKeys('redshirt9999');
        $return = new PHPWebDriver_WebDriverKeys('ReturnKey');
        $primary->sendKeys($return->key);
        sleep(1);

        // wait for primary account search results
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $option = $session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option');
                return ($option->text() == '-- No users found --');
            }
        );

        $prmy = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimaryAccount');
        $this->assertTrue($prmy->attribute('disabled'));
    }

    public function testMergeLoggedInUser()
    {
        $this->waitForLogoutLogin('admin1');
        $this->session->open($this->url.'users/merge');
        $return = new PHPWebDriver_WebDriverKeys('ReturnKey');

        $primary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimarySearchValue');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimarySearch"] option[value="username"]')->click();
        $primary->sendKeys('admin');
        $primary->sendkeys($return->key);
        sleep(1);

        $secondary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserSecondarySearchValue');
        $secondary->sendKeys('admin');
        $secondary->sendKeys($return->key);
        sleep(1);

        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option')) - 1;
            }
        );
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option')) - 1;
            }
        );

        $primaryUser = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option');
        $this->assertEqual($primaryUser[0]->text(), '-- Pick the primary account --');
        $this->assertEqual($primaryUser[1]->text(), 'admin1');
        $this->assertEqual($primaryUser[2]->text(), 'admin2');

        $secondaryUser = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option');
        $this->assertEqual($secondaryUser[0]->text(), '-- Pick the secondary account --');
        $this->assertEqual($secondaryUser[1]->text(), 'admin1');
        $this->assertEqual($secondaryUser[2]->text(), 'admin2');

        $primaryUser[2]->click();
        $secondaryUser[1]->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $this->session->accept_alert();

        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage'));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage');
        $this->assertEqual($msg->text(), 'Error: The secondary account is the currently logged in user.');
    }

    public function testMergeSameUsers()
    {
        $return = new PHPWebDriver_WebDriverKeys('ReturnKey');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimarySearchValue')->sendKeys($return->key);
        sleep(1);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserSecondarySearchValue')->sendKeys($return->key);
        sleep(1);

        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option')) - 1;
            }
        );
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option')) - 1;
            }
        );

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option[value="38"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserSecondaryAccount"] option[value="38"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $this->session->accept_alert();

        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage'));
            }
        );
        // unfortunately this test method is riding on previous method "testMergeLoggedInUser",
        // which already has a flash message displayed. to avoid picking up the prev
        // flash message, sleep for 2s
        // TODO: rewrite this method so it can run independently and cleanly
        sleep(2);
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage');
        $this->assertEqual($msg->text(), 'Error: No merger needed. The primary and secondary accounts are the same.');
    }

    public function testAccessibleRoles()
    {
        $primary = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimarySearchValue');
        $primary->clear();
        $primary->sendKeys('root');
        $return = new PHPWebDriver_WebDriverKeys('ReturnKey');
        $primary->sendKeys($return->key);
        sleep(1);
        
        // wait for primary account search results
        // will not find root (super admin) because it is not an accessible role for admins
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $option = $session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="UserPrimaryAccount"] option');
                return ($option->text() == '-- No users found --');
            }
        );

        $prmy = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserPrimaryAccount');
        $this->assertTrue($prmy->attribute('disabled'));
    }
}
