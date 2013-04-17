<?php
require_once('system_base.php');

class ImportUsersTestCase extends SystemBaseTestCase
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
        $home = $login->login('root', 'ipeeripeer');
    }
    
    public function endCase()
    {
        $this->session->deleteAllCookies();
        $this->session->close();
    }
    
    public function testImportUsers()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'APSC 201')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Import Students')->click();
        
        $file = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'UserFile');
        $file->sendKeys(dirname(__FILE__).'/newClass_APSC201.csv');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        
        // check class list - should have 16 students
        $this->session->open($this->url.'users/goToClassList/2');
        $classSize = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="dataTables_info"]')->text();
        $this->assertEqual($classSize, 'Showing 1 to 10 of 16 entries');
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('New');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $count = count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $newStudent = $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'View')->attribute('href');
        $userId = end(explode('/', $newStudent));
        
        $this->updateClassList();
        $this->session->open($this->url.'users/delete/'.$userId);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Record is successfully deleted!');
    }
    
    public function updateClassList()
    {
        $this->session->open($this->url.'users/import/2');
        $file = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'UserFile');
        $file->sendKeys(dirname(__FILE__).'/oldClass_APSC201.csv');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'UserUpdateClass')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        // check class list - should have 15 students; the new list only misses the new student
        $this->session->open($this->url.'users/goToClassList/2');
        $classSize = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="dataTables_info"]')->text();
        $this->assertEqual($classSize, 'Showing 1 to 10 of 15 entries');
    }
}