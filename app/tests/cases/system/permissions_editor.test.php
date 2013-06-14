<?php
require_once('system_base.php');

class PermissionsEditorTestCase extends SystemBaseTestCase
{    
    public function startCase()
    {
        $this->getUrl();
        echo "Start PermissionsEditor system test.\n";
        $wd_host = 'http://localhost:4444/wd/hub';
        $this->web_driver = new SystemWebDriver($wd_host);
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
    
    public function testAllowAccess()
    {
        // access permission editor
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');
        
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Admin');
        
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Permissions Editor')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > superadmin');
        
        // change the role from super admin to instructor
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="role"] option[value="3"]')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > instructor');
        
        // search for functions/user/index
        $this->findPermissions('controllers/accesses/view');
        
        // allow access
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow All')->click();
        $this->updatePermissions();
    }
    
    public function testAccess()
    {
        $this->waitForLogoutLogin('instructor1');
        
        $this->session->open($this->url.'accesses/view/5');
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > student');
        
        $this->waitForLogoutLogin('root');
    }
    
    public function testDenyAccess()
    {
        $this->session->open($this->url.'accesses/view/3');
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > instructor');

        // search for functions/user/index
        $this->findPermissions('controllers/accesses/view');
        
        // deny access
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny All')->click();
        $this->updatePermissions();
    }
    
    public function testDenyIndividualActions()
    {
        $this->session->open($this->url.'accesses/view/3');
        
        // deny create
        $this->findPermissions('controllers/mixevals/index');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Create')->click();
        $this->updatePermissions();
        // deny read
        $this->findPermissions('controllers/mixevals/index');
        $deny = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Create');
        $allow = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Create');
        $this->assertTrue(empty($deny));
        $this->assertTrue(!empty($allow));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Read')->click();
        $this->updatePermissions();
        // deny update
        $this->findPermissions('controllers/mixevals/index');
        $deny = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Read');
        $allow = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Read');
        $this->assertTrue(empty($deny));
        $this->assertTrue(!empty($allow));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Update')->click();
        $this->updatePermissions();
        // deny delete
        $this->findPermissions('controllers/mixevals/index');
        $deny = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Update');
        $allow = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Update');
        $this->assertTrue(empty($deny));
        $this->assertTrue(!empty($allow));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Delete')->click();
        $this->updatePermissions();
        
        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'evaltools');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluations')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage'));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage');
        $this->assertEqual($msg->text(), 'Error: You do not have permission to access the page.');
        $this->waitForLogoutLogin('root');
    }
    
    public function testAllowIndividualActions()
    {
        $this->session->open($this->url.'accesses/view/3');
        
        // allow create
        $this->findPermissions('controllers/mixevals/index');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Create')->click();
        $this->updatePermissions();
        // allow read
        $this->findPermissions('controllers/mixevals/index');
        $deny = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Create');
        $allow = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Create');
        $this->assertTrue(!empty($deny));
        $this->assertTrue(empty($allow));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Read')->click();
        $this->updatePermissions();
        // allow update
        $this->findPermissions('controllers/mixevals/index');
        $deny = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Read');
        $allow = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Read');
        $this->assertTrue(!empty($deny));
        $this->assertTrue(empty($allow));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Update')->click();
        $this->updatePermissions();
        // allow delete
        $this->findPermissions('controllers/mixevals/index');
        $deny = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny Update');
        $allow = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Update');
        $this->assertTrue(!empty($deny));
        $this->assertTrue(empty($allow));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow Delete')->click();
        $this->updatePermissions();
        
        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'evaltools');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluations')->click();
        $this->assertEqual($this->session->url(), $this->url.'mixevals');
    }
    
    public function findPermissions($permission)
    {
        // search for the permission to access users/index
        $search = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]');
        $search->sendKeys($permission);
        
        // open the table row for options
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $count = count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
    }
    
    public function updatePermissions()
    {
        $this->session->accept_alert();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Permissions have been updated');
    }
}