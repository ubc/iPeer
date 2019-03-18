<?php
App::import('Lib', 'system_base');

class addSimpleTestCase extends SystemBaseTestCase
{
    protected $simpleId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start AddSimple system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddError()
    {
        $this->session->open($this->url.'simpleevaluations/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $flash = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($flash, 'The evaluation was not added successfully.');
        $errors = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($errors[0]->text(), 'Please give the evaluation template a name.');
        $this->assertEqual($errors[1]->text(), 'Please enter a positive integer.');
        $this->assertEqual($errors[2]->text(), 'Please select an availability option.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationName')->sendKeys('Module 1 Project Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $dupError = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message')->text();
        $this->assertEqual($dupError, 'Duplicate name found. Please change the name.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember')->sendKeys('-100');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $errors = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($errors[1]->text(), 'Please enter a positive integer.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember')->sendKeys('0');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $errors = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($errors[1]->text(), 'Please enter a positive integer.');
    }

    public function testAdd()
    {
        $this->session->open($this->url.'simpleevaluations');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Simple Evaluation')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Simple Evaluations > Add');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationName')->sendKeys('Project Eval Part One');
        $desc = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationDescription');
        $desc->sendKeys('Please evaluate your group members for the first part of the project.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember')->sendKeys('50');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The evaluation was added successfully.');
    }

    public function testEdit()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Project Eval Part One')->click();
        $url = $this->session->url();
        $_temp_url = explode('/', $url);
        $this->simpleId = end($_temp_url);
        $this->session->open(str_replace('view', 'edit', $url));
        $points = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember');
        $this->assertEqual($points->attribute('value'), 50);
        $points->clear();
        $points->sendKeys('72');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The simple evaluation was updated successfully.');
    }

    public function testAccess()
    {
        $this->session->open($this->url.'evaltools');
        $creator = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Project Eval Part One');
        $this->assertTrue(!empty($creator));
        $this->session->open($this->url.'simpleevaluations');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Module 1 Project Evaluation')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $url));
        $flash = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($flash, 0, 30), 'This evaluation is now in use,');
        $this->session->open(str_replace('view', 'edit', $url));
        $flash = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($flash, 'Submissions had been made. Module 1 Project Evaluation cannot be edited. Please make a copy.');

        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'evaltools');
        $creator = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Project Eval Part One');
        $this->assertTrue(empty($creator));
        $this->session->open($this->url.'simpleevaluations');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Module 1 Project Evaluation')->click();
        $this->session->open(str_replace('view', 'delete', $url));
        $flash = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($flash, 'Error: You do not have permission to delete this evaluation');
        $this->session->open(str_replace('view', 'edit', $url));
        $flash = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($flash, 'Error: You do not have permission to edit this evaluation');

        $this->waitForLogoutLogin('root');
    }

    public function testCopy()
    {
        $this->session->open($this->url.'simpleevaluations');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Project Eval Part One')->click();
        $this->session->open(str_replace('view', 'copy', $this->session->url()));

        $name = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationName')->attribute('value');;
        $this->assertEqual($name, 'Copy of Project Eval Part One');
        $desc = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationDescription')->attribute('value');
        $this->assertEqual($desc, 'Please evaluate your group members for the first part of the project.');
        $point = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember')->attribute('value');;
        $this->assertEqual($point, 72);
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic');
        $this->assertTrue($avail->attribute('checked'));

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The evaluation was added successfully.');
    }

    public function testView()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Copy of Project Eval Part One')->click();
        $name = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='frm']/table/tbody/tr[2]/td[2]")->text();
        $this->assertEqual($name, 'Copy of Project Eval Part One');
        $desc = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='frm']/table/tbody/tr[3]/td[2]")->text();
        $this->assertEqual($desc, 'Please evaluate your group members for the first part of the project.');
        $points = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='frm']/table/tbody/tr[4]/td[2]")->text();
        $this->assertEqual($points, 72);
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='frm']/table/tbody/tr[5]/td[2]")->text();
        $this->assertEqual($avail, 'public');
        $status = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'statusMsg')->text();
        $this->assertEqual(substr($status, 0, 32), 'Please allocate 216 more points.');

        $this->session->open(str_replace('view', 'delete', $this->session->url()));
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The evaluation was deleted successfully.');
    }

    public function testDelete()
    {
        $this->session->open($this->url.'simpleevaluations/delete/'.$this->simpleId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The evaluation was deleted successfully.');
    }
}
