<?php
App::import('Lib', 'system_base');

class ImportUsersTestCase extends SystemBaseTestCase
{
    public function startCase()
    {
        parent::startCase();
        echo "Start ImportUsers system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testImportUsersError()
    {
        $this->session->open($this->url.'users/import/2');
        $file = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserFile');
        $file->sendKeys(dirname(__FILE__).'/files/docx.docx');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, "extension is not allowed.\nFileUpload::processFile() - Unable to save temp file to file system.");
    }

    public function testImportUsers()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'APSC 201')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Import Students from CSV')->click();

        $file = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserFile');
        $file->sendKeys(dirname(__FILE__).'/files/newClass_APSC201.csv');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $h3 = $session->elementWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
                return ($h3->text() == 'User(s) created successfully:');
            }
        );
        $this->checkSummary();

        // check class list - should have 17 students
        $this->session->open($this->url.'users/goToClassList/2');
        $classSize = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="dataTables_info"]')->text();
        $this->assertEqual($classSize, 'Showing 1 to 10 of 17 entries');

        $search = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]');
        $search->sendKeys('New');
        $w->until(
            function($session) {
                $count = count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $newStudent = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'View')->attribute('href');
        $_temp_url = explode('/', $newStudent);
        $userId = end($_temp_url);

        $this->updateClassList();
        $this->session->open($this->url.'users/delete/'.$userId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Record is successfully deleted!');
    }

    public function updateClassList()
    {
        $this->session->open($this->url.'users/import/2');
        $file = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserFile');
        $file->sendKeys(dirname(__FILE__).'/files/oldClass_APSC201.csv');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserUpdateClass')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        // check class list - should have 15 students; the new list only misses the new student
        $this->session->open($this->url.'users/goToClassList/2');
        $classSize = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="dataTables_info"]')->text();
        $this->assertEqual($classSize, 'Showing 1 to 10 of 15 entries');
    }

    public function checkSummary()
    {
        $h3 = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual($h3[0]->text(), 'User(s) created successfully:');
        $this->assertEqual($h3[1]->text(), 'User(s) updated successfully:');

        $user = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[1]/tbody/tr[2]/td[1]')->text();
        $this->assertEqual($user, 'username11');
        $user = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($user, 'redshirt0001');
    }
}
