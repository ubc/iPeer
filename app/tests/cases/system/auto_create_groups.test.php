<?php
App::import('Lib', 'system_base');

class autoCreateGroupsTestCase extends SystemBaseTestCase
{
    protected $surveyGroupId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start AutoCreateGroups system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testErrorChecking()
    {
        $this->session->open($this->url.'surveygroups/makegroups/2');
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3')->text();
        $this->assertEqual($msg, 'No survey event found for this course!');
    }

    public function testStepOne()
    {
        $this->session->open($this->url.'courses/home/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Auto)')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'MECH 328 - Mechanical Engineering Design Project > Create Group Set');
        // choose survey
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="event_select"] option[value="4"]')->click();

        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $step = $session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'th');
                return ($step->text() == 'Team Making - Step One');
            }
        );

        $surveyComp = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="frm"]/table/tbody/tr[2]/td')->text();
        $this->assertEqual($surveyComp, '13 students were specified for this survey, 2 students responded');
        // choose group configuration - chose to have 6 groups
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="group_config"] option[value="6"]')->click();
        // weights for each survey question
        $weights3 = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="3"]');
        $weights3[0]->click();   // 1st question
        $weights3[1]->click();   // 2nd question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Next"]')->click();
    }

    public function testStepTwo()
    {
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Survey Groups > Teams Summary');
        $numGroups = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "tr td[width='100']"));
        $this->assertEqual($numGroups, 6);
        $students = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, "Student"));
        $this->assertEqual($students, 13);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'team_set_name')->sendKeys('Great Group');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Groups"]')->click();
    }

    public function testEditGroups()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Team Creation Survey')->click();
        $_temp_url = explode('/', $this->session->url());
        $this->surveyGroupId = end($_temp_url);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="question"] option[value="1"]')->click();
        $ques1Ans = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked]'));
        $this->assertEqual($ques1Ans, 2);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="question"] option[value="2"]')->click();
        $ques2Ans = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked]'));
        $this->assertEqual($ques2Ans, 2);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Groups"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Group set changed successfully.');
    }

    public function testReleaseGroups()
    {
        $this->session->open($this->url.'surveygroups/release/'.$this->surveyGroupId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group set was released successfully.');

        $this->session->open($this->url.'groups/index/1');
        $groups = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Great Group Team #'));
        $this->assertEqual($groups, 6);
    }

    public function testDeleteGroups()
    {
        $w = new PHPWebDriver_WebDriverWait($this->session);
        // delete the 6 groups
        for ($i=0; $i<6; $i++) {
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Great Group Team #')->click();
            $this->session->open(str_replace('view', 'delete', $this->session->url()));
            $w->until(
                function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
                }
            );
            $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
            $this->assertEqual($msg, 'The group was deleted successfully.');
        }

        // delete the survey group set
        $this->session->open($this->url.'surveygroups/delete/'.$this->surveyGroupId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group set was deleted successfully.');
    }
}
