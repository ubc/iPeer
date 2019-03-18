<?php
App::import('Lib', 'system_base');

class addRubricTestCase extends SystemBaseTestCase
{
    protected $rubricId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start AddRubric system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddRubricErrors()
    {
        $this->session->open($this->url.'rubrics/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Next"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $flash = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($flash, 'The evaluation was not added successfully.');
        $errors = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($errors[0]->text(), 'Please give the evaluation template a name.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'name')->sendKeys('Term Report Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $error = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($error->text(), 'Duplicate name found. Please change the name.');
    }

    public function testAddRubricStepOne()
    {
        $this->session->open($this->url.'rubrics');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Rubric')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'name')->sendKeys('Midterm Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="LOM"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="criteria"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPrivate')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'zero_mark')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
    }

    public function testAddRubricStepTwo()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom0LomComment')->sendKeys('Bad');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom1LomComment')->sendKeys('Average');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom2LomComment')->sendKeys('Good');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria0Criteria')->sendKeys('Effort');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria1Criteria')->sendKeys('Participation');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment0CriteriaComment')->sendKeys('Does a sloppy job.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment1CriteriaComment')->sendKeys('Does the minimum work');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment2CriteriaComment')->sendKeys('Exceeds my expectation');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment0CriteriaComment')->sendKeys('Does not attend meetings regularly');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment1CriteriaComment')->sendKeys('Attends all meetings');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment2CriteriaComment')->sendKeys('Very active during discussions');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria0Multiplier"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria1Multiplier"] option[value="3"]')->click();

        $crit1mk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark00')->attribute("value");
        $this->assertEqual($crit1mk1, 0);
        $crit1mk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark01')->attribute("value");
        $this->assertEqual($crit1mk2, 2);
        $crit1mk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark02')->attribute("value");
        $this->assertEqual($crit1mk3, 4);

        $crit2mk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark10')->attribute("value");
        $this->assertEqual($crit2mk1, 0);
        $crit2mk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark11')->attribute("value");
        $this->assertEqual($crit2mk2, 1.5);
        $crit2mk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark12')->attribute("value");
        $this->assertEqual($crit2mk3, 3);

        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'total')->attribute("value");
        $this->assertEqual($total, 7);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit-rubric')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric was added successfully.');
    }

    public function testRubricEdit()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Midterm Evaluation')->click();
        $url = $this->session->url();
        $_temp_url = explode('/', $url);
        $this->rubricId = end($_temp_url);
        $this->session->open(str_replace('view', 'edit', $url));

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'name')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'name')->sendKeys('Final Project Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'ViewModeCriteria')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="LOM"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="criteria"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'preview')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom3LomComment')->sendKeys('Excellent');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment3CriteriaComment')->sendKeys('Willing to help others as well');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment3CriteriaComment')->sendKeys('Continually led the group in the right direction');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria2Criteria')->sendKeys('Punctuality');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment0CriteriaComment')->sendKeys('Always late');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment1CriteriaComment')->sendKeys('On time most of the time');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment2CriteriaComment')->sendKeys('On time all of the time');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment3CriteriaComment')->sendKeys('Always early');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria0Multiplier"] option[value="5"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria1Multiplier"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria2Multiplier"] option[value="7"]')->click();

        $crit1mk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark00')->attribute("value");
        $this->assertEqual($crit1mk1, 0);
        $crit1mk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark01')->attribute("value");
        $this->assertEqual($crit1mk2, 1.67);
        $crit1mk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark02')->attribute("value");
        $this->assertEqual($crit1mk3, 3.33);
        $crit1mk4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark03')->attribute("value");
        $this->assertEqual($crit1mk4, 5);

        $crit2mk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark10')->attribute("value");
        $this->assertEqual($crit2mk1, 0);
        $crit2mk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark11')->attribute("value");
        $this->assertEqual($crit2mk2, 1.33);
        $crit2mk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark12')->attribute("value");
        $this->assertEqual($crit2mk3, 2.67);
        $crit2mk4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark13')->attribute("value");
        $this->assertEqual($crit2mk4, 4);

        $crit3mk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark20')->attribute("value");
        $this->assertEqual($crit3mk1, 0);
        $crit3mk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark21')->attribute("value");
        $this->assertEqual($crit3mk2, 2.33);
        $crit3mk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark22')->attribute("value");
        $this->assertEqual($crit3mk3, 4.67);
        $crit3mk4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark23')->attribute("value");
        $this->assertEqual($crit3mk4, 7);

        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'total')->attribute("value");
        $this->assertEqual($total, 16);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit-rubric')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric evaluation was updated successfully');
    }

    public function testRubricAccess()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Term Report Evaluation')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message error-message"]')->text();
        $this->assertEqual(substr($msg, 0, 26), 'This evaluation is in use.');
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Submissions had been made. Term Report Evaluation cannot be edited. Please make a copy.');

        $this->session->open($this->url.'evaltools');
        $eval = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation');
        $this->assertTrue(!empty($eval));

        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'rubrics/index');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to delete this rubric');
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to edit this rubric');
        $this->session->open($this->url.'evaltools');
        $eval = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation');
        $this->assertTrue(empty($eval));
        $this->session->open(str_replace('view', 'copy', $url));
    }

    public function testCopyRubric()
    {
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'name')->attribute('value');
        $this->assertEqual($title, 'Copy of Final Project Evaluation');
        $lom = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="LOM"] option[selected="selected"]')->text();
        $this->assertEqual($lom, 4);
        $numCrit = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="criteria"] option[selected="selected"]')->text();
        $this->assertEqual($numCrit, 3);
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic');
        $this->assertTrue($avail->attribute('checked'));
        $zero = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'zero_mark');
        $this->assertTrue($zero->attribute('checked'));

        $lom1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom0LomComment')->text();
        $this->assertEqual($lom1, 'Bad');
        $lom2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom1LomComment')->text();
        $this->assertEqual($lom2, 'Average');
        $lom3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom2LomComment')->text();
        $this->assertEqual($lom3, 'Good');
        $lom4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsLom3LomComment')->text();
        $this->assertEqual($lom4, 'Excellent');

        $crit1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria0Criteria')->text();
        $this->assertEqual($crit1, 'Effort');
        $crit2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria1Criteria')->text();
        $this->assertEqual($crit2, 'Participation');
        $crit3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria2Criteria')->text();
        $this->assertEqual($crit3, 'Punctuality');

        $crit1com1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment0CriteriaComment')->text();
        $this->assertEqual($crit1com1, 'Does a sloppy job.');
        $crit1com2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment1CriteriaComment')->text();
        $this->assertEqual($crit1com2, 'Does the minimum work');
        $crit1com3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment2CriteriaComment')->text();
        $this->assertEqual($crit1com3, 'Exceeds my expectation');
        $crit1com4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment3CriteriaComment')->text();
        $this->assertEqual($crit1com4, 'Willing to help others as well');

        $crit2com1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment0CriteriaComment')->text();
        $this->assertEqual($crit2com1, 'Does not attend meetings regularly');
        $crit2com2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment1CriteriaComment')->text();
        $this->assertEqual($crit2com2, 'Attends all meetings');
        $crit2com3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment2CriteriaComment')->text();
        $this->assertEqual($crit2com3, 'Very active during discussions');
        $crit2com4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment3CriteriaComment')->text();
        $this->assertEqual($crit2com4, 'Continually led the group in the right direction');

        $crit3com1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment0CriteriaComment')->text();
        $this->assertEqual($crit3com1, 'Always late');
        $crit3com2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment1CriteriaComment')->text();
        $this->assertEqual($crit3com2, 'On time most of the time');
        $crit3com3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment2CriteriaComment')->text();
        $this->assertEqual($crit3com3, 'On time all of the time');
        $crit3com4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment3CriteriaComment')->text();
        $this->assertEqual($crit3com4, 'Always early');

        $crit1mrk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark00')->attribute('value');
        $this->assertEqual($crit1mrk1, 0);
        $crit1mrk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark01')->attribute('value');
        $this->assertEqual($crit1mrk2, 1.67);
        $crit1mrk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark02')->attribute('value');
        $this->assertEqual($crit1mrk3, 3.33);
        $crit1mrk4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark03')->attribute('value');
        $this->assertEqual($crit1mrk4, 5);

        $crit2mrk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark10')->attribute('value');
        $this->assertEqual($crit2mrk1, 0);
        $crit2mrk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark11')->attribute('value');
        $this->assertEqual($crit2mrk2, 1.33);
        $crit2mrk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark12')->attribute('value');
        $this->assertEqual($crit2mrk3, 2.67);
        $crit2mrk4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark13')->attribute('value');
        $this->assertEqual($crit2mrk4, 4);

        $crit3mrk1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark20')->attribute('value');
        $this->assertEqual($crit3mrk1, 0);
        $crit3mrk2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark21')->attribute('value');
        $this->assertEqual($crit3mrk2, 2.33);
        $crit3mrk3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark22')->attribute('value');
        $this->assertEqual($crit3mrk3, 4.67);
        $crit3mrk4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark23')->attribute('value');
        $this->assertEqual($crit3mrk4, 7);

        $crit1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria0Multiplier"] option[selected="selected"]')->text();
        $this->assertEqual($crit1, 5);
        $crit2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria1Multiplier"] option[selected="selected"]')->text();
        $this->assertEqual($crit2, 4);
        $crit3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria2Multiplier"] option[selected="selected"]')->text();
        $this->assertEqual($crit3, 7);

        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'total')->attribute('value');
        $this->assertEqual($total, 16);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit-rubric')->click();

        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric was added successfully.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Copy of Final Project Evaluation')->click();
        $this->session->open(str_replace('view', 'delete', $this->session->url()));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric was deleted successfully.');
        $this->waitForLogoutLogin('root');
    }

    public function testDeleteRubric()
    {
        $this->session->open($this->url.'rubrics/delete/'.$this->rubricId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric was deleted successfully.');
    }
}
