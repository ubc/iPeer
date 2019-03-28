<?php
App::import('Lib', 'system_base');

class AddMixEvalTestCase extends SystemBaseTestCase
{

    protected $mixeval = '';

    public function startCase()
    {
        parent::startCase();
        echo "Start AddMixeval system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testAddMixEval()
    {
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Evaluation Tools');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluations')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Mixed Evaluation')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations > Add');

        // template info
        $name = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalName');
        $name->sendKeys('Final Project Evaluation');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalAvailabilityPublic')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalZeroMark')->click();
    }

    public function testAddLikert()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="button"]')->click();

        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title');
        $question->sendKeys('In your opinion, how is their work ethics?');

        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Instructions');
        $instructions->sendKeys('Please be honest.');

        $marks = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "MixevalQuestion0Multiplier");
        $marks->sendKeys('8');

        $add = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]');
        for ($i=0; $i<5; $i++) {
            $add->click();
        }
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return (count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="DescsDiv0"] div')) - 4);
            }
        );
        for ($i=0; $i<5; $i++) {
            $desc = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc'.$i.'Descriptor');
            $mark = $i * 2;
            $desc->sendKeys($mark.' marks');
        }
    }

    public function testAddSentence()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();

        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title');
        $question->sendKeys('Which part of the project was their greatest contribution?');

        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Instructions');
        $instructions->sendKeys('Choose one of the following: Research, Report, Presentation.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Required')->click();
    }

    public function testAddParagraph()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();

        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title');
        $question->sendKeys('What have they done well? How can they improve?');

        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Instructions');
        $instructions->sendKeys('Please give constructive comments.');
    }

    public function testAddScoreDropdown()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();

        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion3Title');
        $question->sendKeys('Distributed Marks');

        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion3Instructions');
        $instructions->sendKeys('Distribute the marks among your members.');
    }

    public function testSubmitAndError()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        // wait for creation of template to finish
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The mixed evaluation was saved successfully.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Default Mix Evaluation')->click();
        $this->mixeval = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $this->mixeval));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 30), 'This evaluation is now in use,');
        $this->session->open(str_replace('view', 'edit', $this->mixeval));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Default Mix Evaluation cannot be edited now that submissions have been made. Please make a copy.');

        $this->session->open($this->url.'evaltools');
        $eval = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation');
        $this->assertTrue(!empty($eval));

        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'mixevals/index');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $this->mixeval = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $this->mixeval));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to delete this evaluation');
        $this->session->open(str_replace('view', 'edit', $this->mixeval));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to edit this evaluation');
        $this->session->open($this->url.'evaltools');
        $eval = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation');
        $this->assertTrue(empty($eval));
    }

    public function testCopyTemplate()
    {
        $this->session->open(str_replace('view', 'copy', $this->mixeval));
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalName')->attribute('value');
        $this->assertEqual($title, 'Copy of Final Project Evaluation');
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalAvailabilityPublic');
        $this->assertTrue($avail->attribute('checked'));
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalAvailabilityPrivate');
        $this->assertNull($avail->attribute('checked'));
        $zero = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalZeroMark_');
        $this->assertNull($zero->attribute('checked'));
        $zero = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalZeroMark');
        $this->assertTrue($zero->attribute('checked'));

        // 1st question - Likert
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title');
        $this->assertEqual($title->attribute('value'), 'In your opinion, how is their work ethics?');
        $instr = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Instructions');
        $this->assertEqual($instr->text(), 'Please be honest.');
        $req = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Required');
        $this->assertTrue($req->attribute('checked'));
        $mark = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Multiplier');
        $this->assertEqual($mark->attribute('value'), 8);
        $desc1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc0Descriptor');
        $this->assertEqual($desc1->attribute('value'), '0 marks');
        $desc2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc1Descriptor');
        $this->assertEqual($desc2->attribute('value'), '2 marks');
        $desc3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc2Descriptor');
        $this->assertEqual($desc3->attribute('value'), '4 marks');
        $desc4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc3Descriptor');
        $this->assertEqual($desc4->attribute('value'), '6 marks');
        $desc5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc4Descriptor');
        $this->assertEqual($desc5->attribute('value'), '8 marks');

        // 2nd question - Sentence
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title');
        $this->assertEqual($title->attribute('value'), 'Which part of the project was their greatest contribution?');
        $instr = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Instructions');
        $this->assertEqual($instr->text(), 'Choose one of the following: Research, Report, Presentation.');
        $req = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Required');
        $this->assertNull($req->attribute('checked'));

        // 3rd question - Paragraph
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title');
        $this->assertEqual($title->attribute('value'), 'What have they done well? How can they improve?');
        $instr = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Instructions');
        $this->assertEqual($instr->text(), 'Please give constructive comments.');
        $req = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Required');
        $this->assertTrue($req->attribute('checked'));

        // save
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The mixed evaluation was saved successfully.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Copy of Final Project Evaluation')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations > View > Copy of Final Project Evaluation');

        // delete
        $this->session->open(str_replace('view', 'delete', $this->session->url()));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
        $this->waitForLogoutLogin('root');
    }

    public function testViewTemplate()
    {
        $this->session->open($this->mixeval);
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "html/body/div[1]/div[3]/dl/dd[2]")->text();
        $this->assertEqual($avail, 'Public');
        $zero = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "html/body/div[1]/div[3]/dl/dd[4]")->text();
        $this->assertEqual($zero, 'On');
        $req = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered floatright"]'));
        $this->assertEqual($req, 9);
        //$total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'marks')->text();
        //$this->assertEqual($total, 'Total Marks: 18');
    }

    public function testEditTemplate()
    {
        // the UI animation for adding / removing / re-arranging questions may confure Chrome if the commands executed too fast
        // added some sleeps here
        
        $this->session->open(str_replace('view', 'edit', $this->mixeval));
        // delete the score dropdown question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(3, 0); return false;"]')->click();
        sleep(1);
        
        // moving questions
        // move up the first question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(0, 0); return false;"]')->click();
        sleep(1);
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
        $this->assertEqual($quesNum, '1.'); // didn't move
        // move down the last question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(2, 0); return false;"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
        $this->assertEqual($quesNum, '3.'); // didn't move
        
        // move down the first question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(0, 0); return false;"]')->click();
        sleep(1);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
                return ($quesNum == '2.');
            }
        );
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($quesNum, '1.');
        
        // move up the last question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(2, 0); return false;"]')->click();
        sleep(1);
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
                return ($quesNum == '2.');
            }
        );
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
        $this->assertEqual($quesNum, '3.');
        
        // delete the middle question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(2, 0); return false;"]')->click();
        sleep(1);
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
                return ($quesNum == '2.');
            }
        );
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($quesNum, '1.');

        // adding question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        sleep(1);
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex4')->text();
        $this->assertEqual($quesNum, '3.');

        // delete all questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(1, 0); return false;"]')->click();
        sleep(1);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(0, 0); return false;"]')->click();
        sleep(1);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(4, 0); return false;"]')->click();
        sleep(1);
        $w->until(
            function($session) {
                $ques = $session->elements(PHPWebDriver_WebDriverBy::CLASS_NAME, 'MixevalMakeQuestion');
                return empty($ques);
            }
        );

        // adding question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex5')->text();
        $this->assertEqual($quesNum, '1.');

        // create template with one question
        $this->session->open($this->url.'mixevals/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalName')->sendKeys('test');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title')->sendKeys('test question');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The mixed evaluation was saved successfully.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'test')->click();
        $this->session->open(str_replace('view', 'edit', $this->session->url()));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($quesNum, '2.');
        $this->session->open(str_replace('edit', 'delete', $this->session->url()));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
    }

    public function testDeleteTemplate()
    {
        $this->session->open($this->url.'mixevals/index');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations > View > Final Project Evaluation');

        $_temp_url = explode('/', $this->session->url());
        $templateId = end($_temp_url);
        $this->session->open($this->url.'mixevals/delete/'.$templateId);

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
    }

    public function testSelfEvaluation()
    {
        $this->session->open($this->url.'mixevals/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalName')->sendKeys('With Self-Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'self_eval')->click();
        $selfType = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalMixevalQuestionTypeSelf');
        $this->assertTrue(!empty($selfType));
    }

    public function testAddPeerQuestions()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        // question numbers
        $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
        $this->assertEqual($ques1, '1.');
        $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($ques2, '2.');

        // question titles
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title')->sendKeys('Peer optional');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title')->sendKeys('Peer required');
        // set 1st question to optional
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Required')->click();
        // set marks
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "MixevalQuestion0Multiplier")->sendKeys('6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Multiplier')->sendKeys('4');
        // add dec
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(1);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(1);"]')->click();
    }

    public function testAddSelfQuestions()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(true);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypeSelf"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(true);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(true);"]')->click();

        // question numbers
        $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
        $this->assertEqual($ques1, '1.');
        $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex3')->text();
        $this->assertEqual($ques2, '2.');
        $ques3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex4')->text();
        $this->assertEqual($ques3, '3.');

        // question titles
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title')->sendKeys('Self 1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion3Title')->sendKeys('Self 2');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion4Title')->sendKeys('Self 3');
        // set mark
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "MixevalQuestion2Multiplier")->sendKeys('8');
        // add dec
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(2);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(2);"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        // wait for creation of template to finish
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function testViewSelfEvaluation()
    {
        $peer = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[3]/td[4]/div');
        $this->assertEqual($peer->text(), '2');
        $self = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[3]/td[5]/div');
        $this->assertEqual($self->text(), '3');
        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[3]/td[6]/div');
        $this->assertEqual($total->text(), '4');

        // view evaluation
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'With Self-Evaluation')->click();
        $this->mixeval = $this->session->url();
        // check self-evaluation field
        $self = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/dl/dd[6]');
        $this->assertEqual($self->text(), 'On');
        // check section headings
        $peer = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'peer-title');
        $this->assertEqual($peer->text(), 'Peer Evaluation Questions');
        $self = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'self-title');
        $this->assertEqual($self->text(), 'Self-Evaluation Questions');
        // check questions' order & numbering, question id = q_USERID_QUESTION#
        $peer1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_1');
        $this->assertEqual($peer1->text(), '1. Peer optional');
        $peer2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_2');
        $this->assertEqual($peer2->text(), "2. Peer required\n*");
        $self1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_3');
        $this->assertEqual($self1->text(), "1. Self 1\n*");
        $self2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_4');
        $this->assertEqual($self2->text(), "2. Self 2\n*");
        $self3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_5');
        $this->assertEqual($self3->text(), "3. Self 3\n*");
        // check total marks - should only include required peer evaluation questions
        //$mark = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'marks');
        //$this->assertEqual($mark->text(), 'Total Marks: 4');
    }

    public function testEditSelfEvaluation()
    {
        $this->session->open(str_replace('view', 'edit', $this->mixeval));
        $selfEval = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'self_eval');
        $this->assertTrue($selfEval->attribute('checked'));
        // check questions
        $peer1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title');
        $this->assertEqual($peer1->attribute('value'), 'Peer optional');
        $peer2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title');
        $this->assertEqual($peer2->attribute('value'), 'Peer required');
        $self1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title');
        $this->assertEqual($self1->attribute('value'), 'Self 1');
        $self2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion3Title');
        $this->assertEqual($self2->attribute('value'), 'Self 2');
        $self3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion4Title');
        $this->assertEqual($self3->attribute('value'), 'Self 3');
    }

    public function testEditReorderSelfEvaluation()
    {
        // test first peer evaluation question going up
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(0, 0); return false;"]')->click();
        $num = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
        $this->assertEqual($num, '1.');
        // test last peer evaluation question going down
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(1, 0); return false;"]')->click();
        $num = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($num, '2.');
        // test first self-evaluation question going up
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(2, 1); return false;"]')->click();
        $num = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
        $this->assertEqual($num, '1.');
        // test last self-evaluation question going down
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(4, 1); return false;"]')->click();
        $num = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex4')->text();
        $this->assertEqual($num, '3.');

        // test moving questions around
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(0, 0); return false;"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
                return ($quesNum == '2.');
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(2, 1); return false;"]')->click();
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
                return ($quesNum == '2.');
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(4, 1); return false;"]')->click();
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex4')->text();
                return ($quesNum == '2.');
            }
        );

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->open($this->mixeval);
        $peer1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_1');
        $this->assertEqual($peer1->text(), "1. Peer required\n*");
        $peer2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_2');
        $this->assertEqual($peer2->text(), "2. Peer optional");
        $self1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_3');
        $this->assertEqual($self1->text(), "1. Self 2\n*");
        $self2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_4');
        $this->assertEqual($self2->text(), "2. Self 3\n*");
        $self3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'q_1_5');
        $this->assertEqual($self3->text(), "3. Self 1\n*");

        // delete mixeval
        $this->session->open(str_replace('view', 'delete', $this->mixeval));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
    }
}
