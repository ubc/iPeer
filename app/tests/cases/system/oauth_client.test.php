<?php
App::import('Lib', 'system_base');

class oauthClientTestCase extends SystemBaseTestCase
{
    protected $clientId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start OauthClient system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddOauthClient()
    {
        $this->session->open($this->url.'pages/admin');
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Admin');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'OAuth Client Credentials')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Client')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Create New OAuth Client Credential');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'OauthClientComment')->sendKeys('For Testing');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'A new OAuth client has been created');
        $this->assertEqual($this->session->url(), $this->url.'oauthclients');
    }

    public function testEditOauthClient()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('For Testing');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $count = count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit')->click();

        $_temp_url = explode('/', $this->session->url());
        $this->clientId = end($_temp_url);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'OauthClientComment')->sendKeys('Has been edited');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The OAuth client has been saved');
        $this->assertEqual($this->session->url(), $this->url.'oauthclients');
    }

    public function testEditProfile()
    {
        $this->session->open($this->url.'users/editProfile');
        $id = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="'.$this->clientId.'"]')->attribute('id');
        $selectId = str_replace('Id','Enabled',$id);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="'.$selectId.'"] option[value="0"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Profile Has Been Updated Successfully.');
        $label = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="'.$selectId.'"] option[value="0"]')->text();
        $this->assertEqual($label, 'Disabled');
    }

    public function testDelete()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/oauthclients/delete/'.$this->clientId.'"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth client deleted');
    }

    public function testAddOauthClientOtherUser()
    {
        $this->session->open($this->url.'oauthclients/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthClientUserId"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'A new OAuth client has been created');

        $this->waitForLogoutLogin('instructor3');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Instructor 3')->click();
        // instructors will not be able to see the Oauth section of their profile
        $oauth = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Client Credential');
        $this->assertTrue(empty($oauth));

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'oauthclients');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('instructor3');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $count = count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Delete')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth client deleted');
    }
}
