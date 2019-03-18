<?php
App::import('Lib', 'system_base');

class addSurveyTestCase extends SystemBaseTestCase
{
    public function startCase()
    {
        parent::startCase();
        echo "Start AddSurvey system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddSurvey() {
        $this->session->open($this->url.'surveys/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyName')->sendKeys('grp making');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPrivate')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Survey is saved!');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'grp making')->click();
        $_temp_url = explode('/', $this->session->url());
        $surveyId = end($_temp_url);
        $this->session->open($this->url.'surveys/questionsSummary/'.$surveyId);
        $this->addMC($surveyId);
        $this->addMultipleAnswers($surveyId);
        $this->addTextQues($surveyId);

        // check that the questions have been added correctly
        $this->session->open($this->url.'surveys/view/'.$surveyId);
        $radio = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="radio"]'));
        $this->assertEqual($radio, 5);
        $check = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="checkbox"]'));
        $this->assertEqual($check, 4);
        $text = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="text"]'));
        $this->assertEqual($text, 1);
        $textarea = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'textarea'));
        $this->assertEqual($textarea, 1);

        // edit survey
        $this->session->open($this->url.'surveys/edit/'.$surveyId);
        $this->editSurvey();

        // edit survey questions
        $this->session->open($this->url.'surveys/questionsSummary/'.$surveyId);
        $this->editQuestions();

        // access
        $this->accessSurvey();

        // copy survey
        $this->session->open($this->url.'surveys/copy/'.$surveyId);
        $this->copySurvey();

        // delete survey template & their questions
        $this->session->open($this->url.'surveys/questionsSummary/'.$surveyId);
        $this->deleteQues();
        $this->deleteQues();
        $this->deleteQues();
        $this->deleteQues();
        $this->deleteQues();
        $this->session->open($this->url.'surveys/delete/'.$surveyId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The survey was deleted successfully.');
    }

    public function addMC($surveyId) {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('What year are you in your program?');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response0Response')->sendKeys('1st');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->sendKeys('2nd');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response2Response')->sendKeys('3rd');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response3Response')->sendKeys('4th');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response4Response')->sendKeys('5th +');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }

    public function addMultipleAnswers($surveyId)
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('Which operating systems have you used before?');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="C"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionMaster"] option[value="yes"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response0Response')->sendKeys('Windows');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->sendKeys('Mac');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response2Response')->sendKeys('Linux');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response3Response')->sendKeys('Chrome');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }

    public function addTextQues($surveyId)
    {
        // short answer
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('What is your favourite course this term?');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="S"]')->click();
        $masterQ = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionTemplateId"] option');
        $this->assertEqual(count($masterQ), 2);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');

        // long answer
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('Tell me a little bit about yourself.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="L"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }

    public function editSurvey()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyName')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyName')->sendKeys('Group Making Survey');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Survey"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Survey was edited successfully.');
    }

    public function editQuestions()
    {
        // edit m/c
        $edits = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit');
        $this->session->open($edits[0]->attribute('href'));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="rmResponseInput(3); return false;"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response4Response')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response4Response')->sendKeys('4th +');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('What year are you in?');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was updated successfully.');

        // edit multiple answers
        $edits = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit');
        $this->session->open($edits[1]->attribute('href'));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('Which OS will you be programming in?');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="rmResponseInput(3); return false;"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->sendKeys('Mac OS X');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was updated successfully.');

        // edit sentence question
        $edits = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit');
        $this->session->open($edits[2]->attribute('href'));
        // the "Add Response" link is still there, just hidden
        // $resp = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response');
        // $this->assertTrue(empty($resp));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys(
            'Which part of the course are you most looking forward to?');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was updated successfully.');

        // edit paragraph question
        $edits = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit');
        $this->session->open($edits[3]->attribute('href'));
        // the "Add Response" link is still there, just hidden
        // $resp = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response');
        // $this->assertTrue(empty($resp));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="S"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was updated successfully.');

        // checking 1st question
        $options = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="radio"]');
        $this->assertEqual(count($options), 4);
        $lastOp = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "/html/body/div[1]/div[4]/div[2]/label[4]");
        $this->assertEqual($lastOp->text(), '4th +');

        // checking 2nd question
        $options = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="checkbox"]');
        $this->assertEqual(count($options), 3);
        $lastOp = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "/html/body/div[1]/div[4]/div[5]/div[2]/label");
        $this->assertEqual($lastOp->text(), 'Mac OS X');

        // checking last two questions
        $text = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="text"]');
        $this->assertEqual(count($text), 2);

        // move questions
        $bottom = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Bottom');
        $bottom[0]->click();
        $top = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Top');
        $top[2]->click();
        $down = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Down');
        $down[1]->click();
        $up = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Up');
        $up[1]->click();

        $prompts = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'prompt');
        $this->assertEqual($prompts[0]->text(), '1. Which part of the course are you most looking forward to?');
        $this->assertEqual($prompts[1]->text(), '2. Tell me a little bit about yourself.');
        $this->assertEqual($prompts[2]->text(), '3. Which OS will you be programming in?');
        $this->assertEqual($prompts[3]->text(), '4. What year are you in?');

        // load master question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $templates = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionTemplateId"] option');
        $templates[1]->click(); // choose first question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Load"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $prompt = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->attribute('value');
                return ($prompt == 'Which OS will you be programming in?');
            }
        );
        $master = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionMaster"] option[value="no"]');
        $this->assertTrue($master->attribute('selected'));
        $desc = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="responseInput"] div[class="input text"]');
        $this->assertEqual(count($desc), 3);
        $first = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response0Response')->attribute('value');
        $this->assertEqual($first, 'Windows');
        $snd = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->attribute('value');
        $this->assertEqual($snd, 'Mac OS X');
        $third = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Response2Response')->attribute('value');
        $this->assertEqual($third, 'Linux');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }

    public function copySurvey()
    {
        $name = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyName');
        $this->assertEqual($name->attribute('value'), 'Copy of Group Making Survey');
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic');
        $this->assertTrue($avail->attribute('checked'));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Copy Survey"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Survey is saved!');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Copy of Group Making Survey')->click();
        $prompts = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'prompt');
        $this->assertEqual($prompts[0]->text(), '1. Which part of the course are you most looking forward to?');
        $this->assertEqual($prompts[1]->text(), '2. Tell me a little bit about yourself.');
        $this->assertEqual($prompts[2]->text(), '3. Which OS will you be programming in?');
        $this->assertEqual($prompts[3]->text(), '4. What year are you in?');

        // there should be two textfields
        $text = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="text"]');
        $this->assertEqual(count($text), 2);

        // 3 checkboxes
        $check = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="checkbox"] label');
        $this->assertEqual(count($check), 6);
        $this->assertEqual($check[0]->text(), 'Windows');
        $this->assertEqual($check[1]->text(), 'Mac OS X');
        $this->assertEqual($check[2]->text(), 'Linux');
        $this->assertEqual($check[3]->text(), 'Windows');
        $this->assertEqual($check[4]->text(), 'Mac OS X');
        $this->assertEqual($check[5]->text(), 'Linux');

        // 4 radio buttons
        $radio = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="input radio"] label');
        $this->assertEqual(count($radio), 4);
        $this->assertEqual($radio[0]->text(), '1st');
        $this->assertEqual($radio[1]->text(), '2nd');
        $this->assertEqual($radio[2]->text(), '3rd');
        $this->assertEqual($radio[3]->text(), '4th +');

        // delete
        $this->session->open(str_replace('view', 'questionsSummary', $this->session->url()));
        // there could be a timing issue for javascript to react. sleep for 1 sec
        $this->deleteQues(); sleep(1);
        $this->deleteQues(); sleep(1);
        $this->deleteQues(); sleep(1);
        $this->deleteQues(); sleep(1);
        $this->deleteQues();
        $this->session->open(str_replace('questionsSummary', 'delete', $this->session->url()));
        $w->until(
            function($session) {
                    return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The survey was deleted successfully.');
    }

    public function accessSurvey()
    {
        $this->session->open($this->url.'surveys');
        // public survey (in use)
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Team Creation Survey')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 27), 'Submissions have been made.');
        $this->session->open(str_replace('view', 'questionsSummary', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 27), 'Submissions have been made.');
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 26), 'Submissions had been made.');
        // All My Tools
        $this->session->open($this->url.'evaltools');
        $mySurvey = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Group Making Survey');
        $this->assertTrue(!empty($mySurvey));

        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'surveys');
        // public survey (in use)
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Team Creation Survey')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 27), 'Submissions have been made.');
        $this->session->open(str_replace('view', 'questionsSummary', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 27), 'Submissions have been made.');
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 26), 'Submissions had been made.');

        // public survey (not in use) but not the creator
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Group Making Survey')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to edit this survey');
        $this->session->open(str_replace('view', 'questionsSummary', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to edit this survey');
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to delete this survey');

        // All My Tools
        $this->session->open($this->url.'evaltools');
        $mySurvey = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Group Making Survey');
        $this->assertTrue(empty($mySurvey));

        $this->waitForLogoutLogin('root');
    }

    public function deleteQues()
    {
        $delete = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Delete')->click();
        $this->session->accept_alert();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was removed successfully.');
    }
}
