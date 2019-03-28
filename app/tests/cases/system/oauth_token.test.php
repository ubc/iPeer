<?php
App::import('Lib', 'system_base');

class oauthTokenTestCase extends SystemBaseTestCase
{
    protected $tokenId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start OauthToken system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddOauthToken()
    {
        $this->session->open($this->url.'pages/admin');
        $title = $this->session->elementWithWait(PHPWebDRiver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Admin');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'OAuth Token Credentials')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Token')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Create New OAuth Token Credential');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthTokenExpiresYear"] option[value="2018"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'OauthTokenComment')->sendKeys('For Testing');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'New OAuth token created!');
        $this->assertEqual($this->session->url(), $this->url.'oauthtokens');
    }

    public function testEditOauthToken()
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
        $this->tokenId = end($_temp_url);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthTokenExpiresYear"] option[value="2023"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'OauthTokenComment')->sendKeys('Has been edited');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth token saved successfully!');
        $this->assertEqual($this->session->url(), $this->url.'oauthtokens');
    }

    public function testEditProfile()
    {
        $this->session->open($this->url.'users/editProfile');
        $id = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="'.$this->tokenId.'"]')->attribute('id');
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
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/oauthtokens/delete/'.$this->tokenId.'"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth token deleted.');
    }

    public function testAddOauthClientOtherUser()
    {
        $this->session->open($this->url.'oauthtokens/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthTokenUserId"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'New OAuth token created!');

        $this->waitForLogoutLogin('instructor3');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Instructor 3')->click();
        // instructors will not be able to see the Oauth section of their profile
        $oauth = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Token Credential');
        $this->assertTrue(empty($oauth));

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'oauthtokens');
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
        $this->assertEqual($msg, 'OAuth token deleted.');
    }
}
