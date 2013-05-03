<?php
require_once('system_base.php');

class studentMixeval extends SystemBaseTestCase
{
    protected $eventId = 0;
    
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
    
    public function testCreateEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Mixed Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('description for the evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventAutoRelease1')->click();

        //set due date and release date end to next month so that the event is opened.
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '5')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        
        // add penalty
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();;
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="Penalty0PercentPenalty"] option[value="10"]')->click();
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="1"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="2"]')->click();

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');
    }
    
    public function testStudent()
    {
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        
        // check penalty note
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Show/Hide Late Penalty Policy')->click();
        $penalty = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'penalty')->text();
        $this->assertEqual($penalty, "1 day late: 10% deduction.\n10% is deducted afterwards.");
        
        // help text
        $help = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/p');
        $this->assertEqual($help->text(), 'Please rate performance.');
        $this->assertEqual($help->attribute('class'), 'help green');
        $help = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[6]/p');
        $this->assertEqual($help->text(), 'Please give a paragraph answer.');
        $this->assertEqual($help->attribute('class'), 'help green');
        $help = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/p');
        $this->assertEqual($help->text(), 'Please rate performance.');
        $this->assertEqual($help->attribute('class'), 'help green');
        $help = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[12]/p');
        $this->assertEqual($help->text(), 'Please give a paragraph answer.');
        $this->assertEqual($help->attribute('class'), 'help green');
        
        // required questions
        $star = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered floatright"]');
        $this->assertEqual(count($star), 10);

        // Alex Student's evaluation
        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[5]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval4QuestionComment')->sendKeys('absolutely');
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[6][EvaluationMixeval][5][question_comment]')->sendKeys('definitely');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval6QuestionComment')->sendKeys('very easy');
        
        // Matt Student's evaluation
        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval4QuestionComment')->sendKeys('absolutely');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval6QuestionComment')->sendKeys('very easy');
        
        // submit
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::ID, "flashMessage")->text();
        $this->assertEqual($msg, 'Your answers have been saved. Please answer all the required questions before it can be considered submitted.');
        
        // answer the required question that was left unanswered
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('definitely');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }
    
    public function testReSubmit()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // check all required questions are answered before resubmitting
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->clear();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $alert = $this->session->alert_text();
        $this->assertEqual($alert, 'Please fill in all required questions before resubmitting the evaluation.');
        $this->session->accept_alert();
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('all the time');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval6QuestionComment')->clear();
        // submit
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');

        $this->secondStudent();
        $this->tutor();
    }
    
    public function testBasicResult()
    {
        $this->waitForLogoutLogin('root');
        $this->removeFromGroup();
        
        $this->session->open($this->url.'events/index/1');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $this->eventId = end(explode('/', $this->session->url()));

        // edit event's release date end to test final penalty
        // edit event's result release date begin to test student view of the results
        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ui-datepicker-div"]/div[3]/button[1]')->click(); // today
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ui-datepicker-div"]/div[3]/button[1]')->click(); // today
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;     
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'MECH 328 - Mechanical Engineering Design Project > Mixed Evaluation > Results');

        //auto-release results message
        $msg = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'autoRelease_msg');
        $this->assertTrue(!empty($msg));

        // check lates
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '3 Late')->click();
        $ed = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td[4]');
        $this->assertEqual($ed->text(), '1 day(s)');
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[3]/td[4]');
        $this->assertEqual($alex->text(), '1 day(s)');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[4]/td[3]');
        $this->assertEqual($matt->text(), '(not submitted)');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[4]/td[4]');
        $this->assertEqual($matt->text(), '---');
        
        // view summary table
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1');
        $notSubmit = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[1]/th')->text();
        $this->assertEqual($notSubmit, 'Have not submitted their evaluations');
        $notComp = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($notComp, 'Matt Student (student)');
        $left = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[3]/tbody/tr[1]/th')->text();
        $this->assertEqual($left, 'Left the group, but had submitted or were evaluated');
        $left = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[3]/tbody/tr[2]/td')->text();
        $this->assertEqual($left, 'Tutor 1 (TA)');
        
        $total = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[1]/th[2]')->text();
        $this->assertEqual($total, 'Total:( /3.00)');
        $ed = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[2]/td[2]')->text();
        $this->assertEqual($ed, '2.80 - 0.28 = 2.52 (84.00%)');
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[3]/td[2]')->text();
        $this->assertEqual($alex, '2.40 - 0.24 = 2.16 (72.00%)');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[4]/td[2]')->text();
        $this->assertEqual($matt, '1.87 - 0.19 = 1.68 (56.00%)');
        $avg = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[5]/td[2]')->text();
        $this->assertEqual($avg, '2.12');
    }
    
    public function testDetailResult()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Detail')->click();
    
        // auto-release results messages
        $msg = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'autoRelease_msg');
        $this->assertTrue(!empty($msg));
        
        // view summary table
        $notSubmit = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[2]/tbody/tr[1]/th')->text();
        $this->assertEqual($notSubmit, 'Have not submitted their evaluations');
        $notComp = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($notComp, 'Matt Student (student)');
        $left = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[3]/tbody/tr[1]/th')->text();
        $this->assertEqual($left, 'Left the group, but had submitted or were evaluated');
        $left = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[3]/tbody/tr[2]/td')->text();
        $this->assertEqual($left, 'Tutor 1 (TA)');
        
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[2]')->text();
        $this->assertEqual($header, '1 (/1.0)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[3]')->text();
        $this->assertEqual($header, '2 (/1.0)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[4]')->text();
        $this->assertEqual($header, '3 (/1.0)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[5]')->text();
        $this->assertEqual($header, 'Total (/3.00)');
        
        $ed = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[2]')->text();
        $this->assertEqual($ed, '0.90');
        $ed = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[3]')->text();
        $this->assertEqual($ed, '1.00');
        $ed = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[4]')->text();
        $this->assertEqual($ed, '0.90');
        $ed = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[5]')->text();
        $this->assertEqual($ed, '2.80 - 0.28 = 2.52 (84.00%)');
        
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[2]')->text();
        $this->assertEqual($alex, '0.80');
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[3]')->text();
        $this->assertEqual($alex, '0.70');
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[4]')->text();
        $this->assertEqual($alex, '0.90');
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[5]')->text();
        $this->assertEqual($alex, '2.40 - 0.24 = 2.16 (72.00%)');

        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[2]')->text();
        $this->assertEqual($matt, '0.67');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[3]')->text();
        $this->assertEqual($matt, '0.67');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[4]')->text();
        $this->assertEqual($matt, '0.53');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[5]')->text();
        $this->assertEqual($matt, '1.87 - 0.19 = 1.68 (56.00%)');

        $avg = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[5]/td[2]')->text();
        $this->assertEqual($avg, '0.79');
        $avg = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[5]/td[3]')->text();
        $this->assertEqual($avg, '0.79');
        $avg = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[5]/td[4]')->text();
        $this->assertEqual($avg, '0.78');
        $avg = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[5]/td[5]')->text();
        $this->assertEqual($avg, '2.12');
    }
    
    public function testIndivDetailResults()
    {
        $final = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/b')->text();
        $this->assertEqual($final, 'Final Total: 2.80 - 0.28 = 2.52 (84%)  << Above Group Average >>');
        $penalty = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/font')->text();
        $this->assertEqual($penalty, '10%');
        
        $required = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered"]');
        $this->assertEqual(count($required), 15);
        $unEnrolled = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'label[class="blue"]');
        $this->assertEqual(count($unEnrolled), 17);
        $this->assertEqual($unEnrolled[0]->text(), 'Tutor 1:');
        
        $likerts = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        
        $this->assertTrue($likerts[0]->attribute('disabled'));
        $this->assertEqual($likerts[0]->attribute('value'), 3);
        $ques1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[7]');
        $this->assertEqual($ques1->text(), 'Grade: 0.80 / 1');
        
        $this->assertTrue($likerts[1]->attribute('disabled'));
        $this->assertTrue($likerts[1]->attribute('value'), 4);
        $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[2]/li/label[7]');
        $this->assertEqual($ques2->text(), 'Grade: 1.00 / 1');
        $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[2]/li/label[8]');
        $this->assertEqual($ques2->text(), '(Highest)');

        $this->assertTrue($likerts[2]->attribute('disabled'));
        $this->assertTrue($likerts[2]->attribute('value'), 4);
        $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[7]');
        $this->assertEqual($ques3->text(), 'Grade: 1.00 / 1');
        $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[8]');
        $this->assertEqual($ques3->text(), '(Highest)');

        $this->assertTrue($likerts[3]->attribute('disabled'));
        $this->assertTrue($likerts[3]->attribute('value'), 4);
        $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[7]');
        $this->assertEqual($ques4->text(), 'Grade: 1.00 / 1');
        $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[8]');
        $this->assertEqual($ques4->text(), '(Highest)');
        
        $this->assertTrue($likerts[4]->attribute('disabled'));
        $this->assertEqual($likerts[4]->attribute('value'), 3);
        $ques5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[1]/li/label[7]');
        $this->assertEqual($ques5->text(), 'Grade: 0.80 / 1');
        
        $this->assertTrue($likerts[5]->attribute('disabled'));
        $this->assertTrue($likerts[5]->attribute('value'), 4);
        $ques6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[2]/li/label[7]');
        $this->assertEqual($ques6->text(), 'Grade: 1.00 / 1');
        $ques6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[2]/li/label[8]');
        $this->assertEqual($ques6->text(), '(Highest)');
        
        $comm1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[1]')->text();
        $this->assertEqual(substr($comm1, -3), 'cat');
        $comm2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[2]')->text();
        $this->assertEqual(substr($comm2, -3), 'red');
        $comm3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[1]')->text();
        $this->assertEqual(substr($comm3, -3), 'dog');
        $comm4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[2]')->text();
        $this->assertEqual(substr($comm4, -4), 'blue');
        $comm5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[1]')->text();
        $this->assertEqual(substr($comm5, -6), 'gerbil');
        $comm6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[2]')->text();
        $this->assertEqual(substr($comm6, -5), 'green');
    }
    
    public function testStudentResults()
    {
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, '2.80 - (0.28)* = 2.52          ( )* : 10% late penalty. (84%)');

        $required = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered"]');
        $this->assertEqual(count($required), 5);
        
        $likerts = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        $testShuffle = $this->shuffled($likerts->attribute('value'));
        $this->assertTrue($testShuffle);
    }
    
    public function shuffled($mark)
    {
        $likerts = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        $this->assertTrue($likerts[0]->attribute('disabled'));
        $this->assertTrue($likerts[1]->attribute('disabled'));
        $this->assertTrue($likerts[2]->attribute('disabled'));
        $this->assertTrue($likerts[3]->attribute('disabled'));
        $this->assertTrue($likerts[4]->attribute('disabled'));
        $this->assertTrue($likerts[5]->attribute('disabled'));

        if ($mark == '3') {
            $this->assertEqual($likerts[0]->attribute('value'), 3);
            $ques1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[6]');
            $this->assertEqual($ques1->text(), 'Grade: 0.80 / 1');
        
            $this->assertTrue($likerts[1]->attribute('value'), 4);
            $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[2]/li/label[6]');
            $this->assertEqual($ques2->text(), 'Grade: 1.00 / 1');
            $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[2]/li/label[7]');
            $this->assertEqual($ques2->text(), '(Highest)');

            $this->assertTrue($likerts[2]->attribute('value'), 4);
            $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[6]');
            $this->assertEqual($ques3->text(), 'Grade: 1.00 / 1');
            $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[7]');
            $this->assertEqual($ques3->text(), '(Highest)');

            $this->assertTrue($likerts[3]->attribute('value'), 4);
            $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[6]');
            $this->assertEqual($ques4->text(), 'Grade: 1.00 / 1');
            $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[7]');
            $this->assertEqual($ques4->text(), '(Highest)');
        
            $this->assertEqual($likerts[4]->attribute('value'), 3);
            $ques5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[1]/li/label[6]');
            $this->assertEqual($ques5->text(), 'Grade: 0.80 / 1');
        
            $this->assertTrue($likerts[5]->attribute('value'), 4);
            $ques6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[2]/li/label[6]');
            $this->assertEqual($ques6->text(), 'Grade: 1.00 / 1');
            $ques6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[2]/li/label[7]');
            $this->assertEqual($ques6->text(), '(Highest)');
        
            $comm1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[1]')->text();
            $this->assertEqual($comm1, 'cat');
            $comm2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[2]')->text();
            $this->assertEqual($comm2, 'red');
            $comm3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[1]')->text();
            $this->assertEqual($comm3, 'dog');
            $comm4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[2]')->text();
            $this->assertEqual($comm4, 'blue');
            $comm5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[1]')->text();
            $this->assertEqual($comm5, 'gerbil');
            $comm6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[2]')->text();
            $this->assertEqual($comm6, 'green');
            return true;
        } else if ($mark == '4') {
            $this->assertTrue($likerts[0]->attribute('value'), 4);
            $ques1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[6]');
            $this->assertEqual($ques1->text(), 'Grade: 1.00 / 1');
            $ques1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[7]');
            $this->assertEqual($ques1->text(), '(Highest)');

            $this->assertEqual($likerts[1]->attribute('value'), 3);
            $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[2]/li/label[6]');
            $this->assertEqual($ques2->text(), 'Grade: 0.80 / 1');
            
            $this->assertTrue($likerts[2]->attribute('value'), 4);
            $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[6]');
            $this->assertEqual($ques3->text(), 'Grade: 1.00 / 1');
            $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[7]');
            $this->assertEqual($ques3->text(), '(Highest)');

            $this->assertTrue($likerts[3]->attribute('value'), 4);
            $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[6]');
            $this->assertEqual($ques4->text(), 'Grade: 1.00 / 1');
            $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[7]');
            $this->assertEqual($ques4->text(), '(Highest)');

            $this->assertTrue($likerts[4]->attribute('value'), 4);
            $ques5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[1]/li/label[6]');
            $this->assertEqual($ques5->text(), 'Grade: 1.00 / 1');
            $ques5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[1]/li/label[7]');
            $this->assertEqual($ques5->text(), '(Highest)');
            
            $this->assertEqual($likerts[5]->attribute('value'), 3);
            $ques6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[2]/li/label[6]');
            $this->assertEqual($ques6->text(), 'Grade: 0.80 / 1');

            $comm1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[1]')->text();
            $this->assertEqual($comm1, 'red');
            $comm2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[2]')->text();
            $this->assertEqual($comm2, 'cat');
            $comm3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[1]')->text();
            $this->assertEqual($comm3, 'blue');
            $comm4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[2]')->text();
            $this->assertEqual($comm4, 'dog');
            $comm5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[1]')->text();
            $this->assertEqual($comm5, 'green');
            $comm6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[2]')->text();
            $this->assertEqual($comm6, 'gerbil');
            return true;
        } else {
            return false;
        }
    }
    
    public function testBasic()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventEnableDetails0')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.80 - (0.28)* = 2.52          ( )* : 10% late penalty. (84%)');
        $ques1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h3[1]')->text();
        $this->assertEqual($ques1, '1. Participated in Team Meetings *');
        $avg1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/li')->text();
        $this->assertEqual($avg1, 'Average: 0.9 / 1');
        $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h3[2]')->text();
        $this->assertEqual($ques2, '2. Was Helpful and co-operative *');
        $avg2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/li')->text();
        $this->assertEqual($avg2, 'Average: 1 / 1');
        $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h3[3]')->text();
        $this->assertEqual($ques3, '3. Submitted work on time *');
        $avg3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/li')->text();
        $this->assertEqual($avg3, 'Average: 0.9 / 1');
        
        $required = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered"]');
        $this->assertEqual(count($required), 3);
    }
    
    public function testNotReleased()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventAutoRelease0')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventEnableDetails1')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), 'Not Released');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]');
        $this->assertEqual($header->text(), 'Comments/Grades Not Released Yet');
        $ques1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/li');
        $this->assertEqual($ques1->text(), 'Grades Not Released Yet');
        $ques2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/li');
        $this->assertEqual($ques2->text(), 'Grades Not Released Yet');
        $ques3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/li');
        $this->assertEqual($ques3->text(), 'Grades Not Released Yet');
        $ques4 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li');
        $this->assertEqual($ques4->text(), 'Comments Not Released Yet');
        $ques5 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li');
        $this->assertEqual($ques5->text(), 'Comments Not Released Yet');
        $ques6 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li');
        $this->assertEqual($ques6->text(), 'Comments Not Released Yet');
    }
    
    public function testReviewed()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm"]/input[6]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $review = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm"]/input[6]');
                return ($review->attribute('value') == 'Mark Peer Evaluations as Not Reviewed');
            }
        );
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $review = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[6]/div')->text();
        $this->assertEqual($review, 'Reviewed');
    }
    
    public function testIndivGrades()
    {
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[1]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $button = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[1]');
                return ($button->attribute('value') == 'Unrelease Grades');
            }
        );
        
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, '2.80 - (0.28)* = 2.52          ( )* : 10% late penalty. (84%)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font')->text();
        $this->assertEqual($header, 'Comments Not Released Yet');
        
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]')->text();
        $this->assertEqual($header, 'Comments/Grades Not Released Yet');
    }
    
    public function testIndivComments()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[1]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $button = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[1]');
                return ($button->attribute('value') == 'Release Grades');
            }
        );
        
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[2]')->click();
        $w->until(
            function($session) {
                $button = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[2]');
                return ($button->attribute('value') == 'Unrelease Comments');
            }
        );
        
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, 'Not Released');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font')->text();
        $this->assertEqual($header, 'Grades Not Released Yet');

        $this->waitForLogoutLogin('redshirt0002');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]')->text();
        $this->assertEqual($header, 'Comments/Grades Not Released Yet');

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[2]')->click();
        $w->until(
            function($session) {
                $button = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/input[2]');
                return ($button->attribute('value') == 'Release Comments');
            }
        );
    }
    
    public function testReleaseAllComments()
    {
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Release All Comments')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $comments = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[8]/div');
                return ($comments->text() == 'Some Released');
            }
        );
        
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');
        
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');
        
        $this->waitForLogoutLogin('redshirt0003');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');
        
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Unrelease All Comments')->click();
        $w->until(
            function($session) {
                $comments = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[8]/div');
                return ($comments->text() == 'Not Released');
            }
        );
    }
    
    public function testReleaseAllGrades()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Release All Grades')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $comments = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[7]/div');
                return ($comments->text() == 'Some Released');
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.80 - (0.28)* = 2.52          ( )* : 10% late penalty. (84%)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Comments Not Released Yet');
        
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.40 - (0.24)* = 2.16          ( )* : 10% late penalty. (72%)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Comments Not Released Yet');
        
        $this->waitForLogoutLogin('redshirt0003');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '1.87 - (0.19)* = 1.68          ( )* : 10% late penalty. (56%)');
        $header = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Comments Not Released Yet');
        
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Unrelease All Grades')->click();
        $w->until(
            function($session) {
                $comments = $session->element(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[7]/div');
                return ($comments->text() == 'Not Released');
            }
        );
    }
    
    public function testDeleteEvent()
    {
        $this->session->open($this->url.'events/delete/'.$this->eventId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
        $this->assignToGroup();
    }
    
    public function testScoreDropdown()
    {
        // TODO - put scoredropdown question into sample mixed eval template and
        // adjust tests accordingly
        
        // add template
        $this->session->open($this->url.'mixevals/add');
        $name = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalName');
        $name->sendKeys('Final Project Evaluation');
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();

        $question = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title');
        $question->sendKeys('Distributed Marks');
        
        $instructions = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Instructions');
        $instructions->sendKeys('Distribute the marks among your members.');

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $session = $this->session;
        // wait for creation of template to finish
        $w = new PHPWebDriver_WebDriverWait($session);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $templateId = end(explode('/', $this->session->url()));

        // add event
        $this->session->open($this->url.'events/add/1');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Mixed Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('description for the evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventMixeval"] option[value="'.$templateId.'"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventAutoRelease1')->click();

        //set due date and release date end to next month so that the event is opened.
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '5')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="1"]')->click();

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        // do the evaluation
        $this->waitForLogoutLogin('tutor1');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // check that the select field has options 0 to 30
        $marks = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option');
        $this->assertEqual(count($marks), 93);
        $selects = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select');
        $this->assertEqual(count($selects), 3);
        
        // do the evaluation
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $marks = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option');
        $this->assertEqual(count($marks), 42);
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $alert = $this->session->alert_text();
        $this->assertEqual($alert, 'Please make sure that the total of the grades in the drop-downs equals 20 and then press "Submit" again.');
        $this->session->accept_alert();
        // given eleven marks each
        $eleven = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option[value="11"]');
        $eleven[0]->click();
        $eleven[1]->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $alert = $this->session->alert_text();
        $this->assertEqual($alert, 'Please make sure that the total of the grades in the drop-downs equals 20 and then press "Submit" again.');
        $this->session->accept_alert();
        // give ten marks each
        $ten = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option[value="10"]');
        $ten[0]->click();
        $ten[1]->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        // delete the event and template
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'events/index/1');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $this->session->open(str_replace('view', 'delete', $this->session->url()));
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->session->open($this->url.'mixevals/delete/'.$templateId);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }
    
    public function secondStudent()
    {
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '55EvaluationMixeval4QuestionComment')->sendKeys('cat');
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[5][EvaluationMixeval][5][question_comment]')->sendKeys('dog');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EvaluationMixeval5EvaluationMixeval6QuestionComment')->sendKeys('gerbil');
        
        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[2]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval4QuestionComment')->sendKeys('abc');
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('def');
        // submit
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }
    
    public function tutor()
    {
        $this->waitForLogoutLogin('tutor1');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[5]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '55EvaluationMixeval4QuestionComment')->sendKeys('red');
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[5][EvaluationMixeval][5][question_comment]')->sendKeys('blue');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EvaluationMixeval5EvaluationMixeval6QuestionComment')->sendKeys('green');

        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval4QuestionComment')->sendKeys('one');
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[6][EvaluationMixeval][5][question_comment]')->sendKeys('two');
        
        // Likert questions
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[14]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[15]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[16]/table/tbody/tr[2]/td[2]/input')->click();
        // short and long answers
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval4QuestionComment')->sendKeys('right');
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('left');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval6QuestionComment')->sendKeys('forward');
        
        // submit
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }

    public function removeFromGroup()
    {
        $this->session->open($this->url.'groups/edit/1');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="5"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="6"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="7"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="<< Remove "]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Group"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }
    
    public function assignToGroup()
    {
        $this->session->open($this->url.'groups/edit/1');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="35"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Group"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }
}