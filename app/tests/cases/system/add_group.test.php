<?php
App::import('Lib', 'system_base');

class AddGroupTestCase extends SystemBaseTestCase
{
    protected $groupId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start AddGroup system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddGroup()
    {
        $this->session->open($this->url.'courses/home/2');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Group')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Add Group');

        $groupNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'GroupGroupNum');
        $this->assertEqual($groupNum->attribute('value'), 1);
        $this->assertTrue($groupNum->attribute('readonly'));

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message')->text();
        $this->assertEqual($msg, 'Please insert group name');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->sendKeys(' Amazing Group ');

        // adding Geoff Student
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="30"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();

        // adding Van Hong Student
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="27"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();

        // adding Denny Student
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="18"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();

        // adding Trevor Student
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="23"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();

        $count = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option'));
        $this->assertEqual($count, 4);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was added successfully.');

        // checking duplicate group name error
        $this->session->open($this->url.'groups/add/2');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->sendKeys(' Amazing Group ');
        // adding Geoff Student
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="30"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='error-message']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='error-message']");
        $this->assertEqual($msg->text(), 'A group with the name already exists.');
        $this->session->open($this->url.'groups/index/2');
    }

    public function testEditGroup()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Amazing Group (4 members)')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Groups > View');
        $_temp_url = explode('/', $this->session->url());
        $this->groupId = end($_temp_url);

        $this->checkViewLinks();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit this Group')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        // check that no duplicate name error appears
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->open($this->url.'groups/edit/'.$this->groupId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->sendKeys(' Two');

        // removing Van Hong Student
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="30"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="18"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="23"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="<< Remove "]')->click();

        $count = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option'));
        $this->assertEqual($count, 3);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was updated successfully.');
    }

    public function testDeleteGroup()
    {
        $this->session->open($this->url.'groups/delete/'.$this->groupId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was deleted successfully.');
    }

    public function checkViewLinks()
    {
        $emails = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Email');
        $this->assertTrue(strpos($emails[0]->attribute('href'), 'emailer/write/U/18'));
        $this->assertTrue(strpos($emails[1]->attribute('href'), 'emailer/write/U/23'));
        $this->assertTrue(strpos($emails[2]->attribute('href'), 'emailer/write/U/27'));
        $this->assertTrue(strpos($emails[3]->attribute('href'), 'emailer/write/U/30'));
        $this->assertTrue(strpos($emails[4]->attribute('href'), 'emailer/write/G/'.$this->groupId));

        $back = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Back to Group Listing');
        $this->assertTrue(strpos($back->attribute('href'), 'groups/index/2'));


        $view = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/users/view/18"]');
        $this->assertTrue(!empty($view));
        $view = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/users/view/23"]');
        $this->assertTrue(!empty($view));
        $view = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/users/view/27"]');
        $this->assertTrue(!empty($view));
        $view = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[href="/users/view/30"]');
        $this->assertTrue(!empty($view));
    }
}
