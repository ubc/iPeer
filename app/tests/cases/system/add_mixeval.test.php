<?php
require_once('system_base.php');

class AddMixEvalTestCase extends SystemBaseTestCase
{   
    public function startCase()
    {
        $this->getUrl();
        echo "Start AddMixeval system test.\n";
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
        
        // likert question
        $this->addLikert();
        
        // sentence question
        $this->addSentence();
        
        // paragraph question
        $this->addParagraph();
        
        // score dropdown question
        $this->addScoreDropdown();
        
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        // wait for creation of template to finish
        $w = new PHPWebDriver_WebDriverWait($session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The mixed evaluation was saved successfully.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Default Mix Evaluation')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual(substr($msg, 0, 30), 'This evaluation is now in use,');
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Default Mix Evaluation cannot be edited now that submissions have been made. Please make a copy.');
        
        $this->session->open($this->url.'evaltools');
        $eval = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation');
        $this->assertTrue(!empty($eval));
        
        $this->waitForLogoutLogin('instructor1');
        $this->session->open($this->url.'mixevals/index');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $url = $this->session->url();
        $this->session->open(str_replace('view', 'delete', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to delete this evaluation');
        $this->session->open(str_replace('view', 'edit', $url));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Error: You do not have permission to edit this evaluation');
        $this->session->open($this->url.'evaltools');
        $eval = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation');
        $this->assertTrue(empty($eval));
        $this->session->open(str_replace('view', 'copy', $url));
        // copying public template
        $this->copyTemplate();
        $this->waitForLogoutLogin('root');
        
        $this->session->open($url);
        // view the template
        $this->viewTemplate();
        
        $this->session->open(str_replace('view', 'edit', $url));
        // edit the template
        $this->editTemplate();
        
        $this->session->open($this->url.'mixevals/index');        
        // delete the template
        $this->deleteTemplate();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
    }
    
    public function addLikert() 
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
    
    public function addSentence()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        
        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title');
        $question->sendKeys('Which part of the project was their greatest contribution?');
        
        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Instructions');
        $instructions->sendKeys('Choose one of the following: Research, Report, Presentation.');
        
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Required')->click();
    }
    
    public function addParagraph()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        
        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title');
        $question->sendKeys('What have they done well? How can they improve?');
        
        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Instructions');
        $instructions->sendKeys('Please give constructive comments.');
    }
    
    public function addScoreDropdown()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();

        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion3Title');
        $question->sendKeys('Distributed Marks');
        
        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion3Instructions');
        $instructions->sendKeys('Distribute the marks among your members.');
    }
    
    public function deleteTemplate()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations > View > Final Project Evaluation');
        
        $templateId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'mixevals/delete/'.$templateId);
    }
    
    public function viewTemplate()
    {
        $avail = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "html/body/div[1]/div[3]/dl/dd[2]")->text();
        $this->assertEqual($avail, 'Public');
        $zero = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "html/body/div[1]/div[3]/dl/dd[4]")->text();
        $this->assertEqual($zero, 'On');
        $req = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered floatright"]'));
        $this->assertEqual($req, 3);
        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'marks')->text();
        $this->assertEqual($total, 'Total Marks: 18');
    }
    
    public function editTemplate()
    {
        // delete the score dropdown question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(3); return false;"]')->click();
    
        // moving questions
        // move up the first question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(0); return false;"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
        $this->assertEqual($quesNum, '1.'); // didn't move
        // move down the last question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(2); return false;"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
        $this->assertEqual($quesNum, '3.'); // didn't move
        // move down the first question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="downQ(0); return false;"]')->click();
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
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="upQ(2); return false;"]')->click();
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex2')->text();
                return ($quesNum == '2.');
            }
        );
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
        $this->assertEqual($quesNum, '3.');
        // delete the middle question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(2); return false;"]')->click();
        $w->until(
            function($session) {
                $quesNum = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex0')->text();
                return ($quesNum == '2.');
            }
        );
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($quesNum, '1.');
        
        // adding question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex4')->text();
        $this->assertEqual($quesNum, '3.');

        // delete all questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(1); return false;"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(0); return false;"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="removeQ(4); return false;"]')->click();
        $w->until(
            function($session) {
                $ques = $session->elements(PHPWebDriver_WebDriverBy::CLASS_NAME, 'MixevalMakeQuestion');
                return empty($ques);
            }
        );
        
        // adding question
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex5')->text();
        $this->assertEqual($quesNum, '1.');
        
        // create template with one question
        $this->session->open($this->url.'mixevals/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
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
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        $quesNum = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'questionIndex1')->text();
        $this->assertEqual($quesNum, '2.');
        $this->session->open(str_replace('edit', 'delete', $this->session->url()));
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
    }
    
    public function copyTemplate()
    {
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
        $w = new PHPWebDriver_WebDriverWait($session);
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
    }
}