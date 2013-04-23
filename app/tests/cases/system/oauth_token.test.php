<?php
require_once('system_base.php');

class oauthTokenTestCase extends SystemBaseTestCase
{
    protected $tokenId = 0;
    
    public function startCase()
    {
        $this->getUrl();
        $wd_host = 'http://localhost:4444/wd/hub';
        $this->web_driver = new PHPWebDriver_WebDriver($wd_host);
        $this->session = $this->web_driver->session('firefox');
        $this->session->open($this->url);
        
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $this->session->deleteAllCookies();
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }
    
    public function endCase()
    {
        $this->session->deleteAllCookies();
        $this->session->close();
    }
    
    public function testAddOauthToken()
    {
        $this->session->open($this->url.'pages/admin');
        $title = $this->session->element(PHPWebDRiver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Admin');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'OAuth Token Credentials')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Token')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Create New OAuth Token Credential');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthTokenExpiresYear"] option[value="2018"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'OauthTokenComment')->sendKeys('For Testing');
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'New OAuth token created!');
        $this->assertEqual($this->session->url(), $this->url.'oauthtokens');  
    }
    
    public function testEditOauthToken()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('For Testing');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $count = count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit')->click();
        
        $this->tokenId = end(explode('/', $this->session->url()));
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthTokenExpiresYear"] option[value="2023"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'OauthTokenComment')->sendKeys('Has been edited'); 
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth token saved successfully!');
        $this->assertEqual($this->session->url(), $this->url.'oauthtokens');  
    }
    
    public function testEditProfile()
    {
        $this->session->open($this->url.'users/editProfile');
        $id = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="'.$this->tokenId.'"]')->attribute('id');
        $selectId = str_replace('Id','Enabled',$id);
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="'.$selectId.'"] option[value="0"]')->click();
    
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Profile Has Been Updated Successfully.');
        $label = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="'.$selectId.'"] option[value="0"]')->text();
        $this->assertEqual($label, 'Disabled');
    }
    
    public function testDelete()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/oauthtokens/delete/'.$this->tokenId.'"]')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth token deleted.');  
    }

    public function testAddOauthClientOtherUser()
    {
        $this->session->open($this->url.'oauthtokens/add');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="OauthTokenUserId"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'New OAuth token created!');
        
        $this->waitForLogoutLogin('instructor3');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Instructor 3')->click();
        // instructors will not be able to see the Oauth section of their profile
        $oauth = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Token Credential');
        $this->assertTrue(empty($oauth));
        
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'oauthtokens');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('instructor3');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $count = count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Delete')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'OAuth token deleted.');
    }
}