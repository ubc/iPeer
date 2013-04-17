<?php
require_once('system_base.php');

class ImportGroupsTestCase extends SystemBaseTestCase
{    
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
        $home = $login->login('instructor2', 'ipeeripeer');
    }
    
    public function endCase()
    {
        $this->session->deleteAllCookies();
        $this->session->close();
    }
    
    public function testImportGroups()
    {
        $this->session->open($this->url.'groups/import/2');
        $file = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'GroupFile');
        $file->sendKeys(dirname(__FILE__).'/importGroup.csv');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[id='title']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[id='title']")->text();
        $this->assertEqual($msg, 'The group CSV file was processed.');
        
        $this->session->open($this->url.'courses/home/2');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Groups')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Team (3 members)')->click();
        $groupId = end(explode('/', $this->session->url()));
        
        $this->session->open($this->url.'groups/edit/'.$groupId);
        $groupName = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->attribute('value');
        $this->assertEqual($groupName, 'Team');
        $inGroup = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option'));
        $this->assertEqual($inGroup, 3);
        
        $this->session->open($this->url.'groups/delete/'.$groupId);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was deleted successfully.');
    }
}