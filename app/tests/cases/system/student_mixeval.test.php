<?php
App::import('Lib', 'system_base');

class studentMixeval extends SystemBaseTestCase
{
    protected $eventId = 0;
    protected $templateId = 0;

    public function startCase()
    {
        parent::startCase();
        echo "Start StudentMixeval system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testCreateEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Mixed Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('description for the evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventAutoRelease1')->click();

        //set due date and release date end to next month so that the event is opened.
        $this->selectDayOnCalendar('EventDueDate', date('j'), false);
        $this->selectDayOnCalendar('EventReleaseDateBegin', date('j'), false);
        $this->selectDayOnCalendar('EventReleaseDateEnd', '4');
        $this->selectDayOnCalendar('EventResultReleaseDateBegin', '5');
        $this->selectDayOnCalendar('EventResultReleaseDateEnd', '28');

        // add penalty
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();;
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="Penalty0PercentPenalty"] option[value="10"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="2"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');
    }

    public function testStudent()
    {
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();

        // check penalty note
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Show/Hide Late Penalty Policy')->click();
        $penalty = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'penalty')->text();
        $this->assertEqual($penalty, "From the due date to 1 days(s) late, 10% will be deducted.\n10% is deducted afterwards.");

        // help text
        $help = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/p');
        $this->assertEqual($help->text(), 'Please rate performance.');
        $this->assertEqual($help->attribute('class'), 'help green');
        $help = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[6]/p');
        $this->assertEqual($help->text(), 'Please give a paragraph answer.');
        $this->assertEqual($help->attribute('class'), 'help green');
        $help = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/p');
        $this->assertEqual($help->text(), 'Please rate performance.');
        $this->assertEqual($help->attribute('class'), 'help green');
        $help = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[12]/p');
        $this->assertEqual($help->text(), 'Please give a paragraph answer.');
        $this->assertEqual($help->attribute('class'), 'help green');

        // required questions
        $star = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered floatright"]');
        $this->assertEqual(count($star), 10);

        // Alex Student's evaluation
        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[5]/input')->click();

        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval4QuestionComment')->sendKeys('absolutely');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[6][EvaluationMixeval][5][question_comment]')->sendKeys('definitely');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval6QuestionComment')->sendKeys('very easy');

        // Matt Student's evaluation
        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval4QuestionComment')->sendKeys('absolutely');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval6QuestionComment')->sendKeys('very easy');

        // submit
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage")->text();
        $this->assertEqual($msg, 'Your answers have been saved. Please answer all the required questions before it can be considered submitted.');

        // answer the required question that was left unanswered
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('definitely');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }

    public function testReSubmit()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // check all required questions are answered before resubmitting
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $alert = $this->session->alert_text();
        $this->assertEqual($alert, 'Please fill in all required questions before resubmitting the evaluation.');
        $this->session->accept_alert();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('all the time');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval6QuestionComment')->clear();
        // submit
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');

        $this->secondStudent();
        $this->tutor();
    }

    public function testBasicResult()
    {
        $this->waitForLogoutLogin('root');
        $this->removeFromGroup();

        $this->session->open($this->url.'events/index/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $_temp_url = explode('/', $this->session->url());
        $this->eventId = end($_temp_url);

        // edit event's release date end to test final penalty
        // edit event's result release date begin to test student view of the results
        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->sendKeys(date('Y-m-d H:i:s'));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->sendKeys(date('Y-m-d H:i:s'));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        // make sure we sleep off 1 sec so the result is available after EventReleaseDateEnd
        sleep(1);

        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'MECH 328 - Mechanical Engineering Design Project > Mixed Evaluation > Results');

        //auto-release results message
        // $msg = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'autoRelease_msg');
        // $this->assertTrue(!empty($msg));

        // check lates
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '3 Late')->click();
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td[4]');
        $this->assertEqual($ed->text(), '1 day(s)');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[3]/td[4]');
        $this->assertEqual($alex->text(), '1 day(s)');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[4]/td[3]');
        $this->assertEqual($matt->text(), '(not submitted)');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[4]/td[4]');
        $this->assertEqual($matt->text(), '---');

        // view summary table
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1');
        $notSubmit = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[1]/th')->text();
        $this->assertEqual($notSubmit, 'Have not submitted their evaluations');
        $notComp = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($notComp, 'Matt Student (student)');
        $left = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[3]/tbody/tr[1]/th')->text();
        $this->assertEqual($left, 'Left the group, but had submitted or were evaluated');
        $left = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[3]/tbody/tr[2]/td')->text();
        $this->assertEqual($left, 'Tutor 1 (TA)');

        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[1]/th[2]')->text();
        $this->assertEqual($total, 'Total:( /3.00)');
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[2]/td[2]')->text();
        //$this->assertEqual($ed, '2.80 - 0.28 = 2.52 (84.00%)');
        $this->assertEqual($ed, '2.60 - 0.26 = 2.34 (78.00%)');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[3]/td[2]')->text();
        $this->assertEqual($alex, '2.40 - 0.24 = 2.16 (72.00%)');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[4]/td[2]')->text();
        //$this->assertEqual($matt, '1.87 - 0.19 = 1.68 (56.00%)');
        $this->assertEqual($matt, '2.20 - 0.22 = 1.98 (66.00%)');
        // $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[5]/td[2]')->text();
        // $this->assertEqual($avg, '2.12');
        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[6]/td[2]')->text();
        $this->assertEqual($avg, '2.16');
    }

    public function testDetailResult()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Detail')->click();

        // auto-release results messages
        // $msg = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'autoRelease_msg');
        // $this->assertTrue(!empty($msg));

        // view summary table
        $notSubmit = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[2]/tbody/tr[1]/th')->text();
        $this->assertEqual($notSubmit, 'Have not submitted their evaluations');
        $notComp = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($notComp, 'Matt Student (student)');
        $left = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[3]/tbody/tr[1]/th')->text();
        $this->assertEqual($left, 'Left the group, but had submitted or were evaluated');
        $left = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[3]/tbody/tr[2]/td')->text();
        $this->assertEqual($left, 'Tutor 1 (TA)');

        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[2]')->text();
        $this->assertEqual($header, '1 (/1.0)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[3]')->text();
        $this->assertEqual($header, '2 (/1.0)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[4]')->text();
        $this->assertEqual($header, '3 (/1.0)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[5]')->text();
        $this->assertEqual($header, 'Total (/3.00)');

        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[2]')->text();
        $this->assertEqual($ed, '0.80');
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[3]')->text();
        $this->assertEqual($ed, '1.00');
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[4]')->text();
        $this->assertEqual($ed, '0.80');
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[5]')->text();
        $this->assertEqual($ed, '2.60 - 0.26 = 2.34 (78.00%)');

        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[2]')->text();
        $this->assertEqual($alex, '0.80');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[3]')->text();
        $this->assertEqual($alex, '0.60');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[4]')->text();
        $this->assertEqual($alex, '1.00');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[5]')->text();
        $this->assertEqual($alex, '2.40 - 0.24 = 2.16 (72.00%)');

        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[2]')->text();
        $this->assertEqual($matt, '0.80');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[3]')->text();
        $this->assertEqual($matt, '0.80');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[4]')->text();
        $this->assertEqual($matt, '0.60');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[5]')->text();
        $this->assertEqual($matt, '2.20 - 0.22 = 1.98 (66.00%)');

        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[6]/td[2]')->text();
        $this->assertEqual($avg, '0.80');
        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[6]/td[3]')->text();
        $this->assertEqual($avg, '0.80');
        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[6]/td[4]')->text();
        $this->assertEqual($avg, '0.80');
        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[6]/td[5]')->text();
        $this->assertEqual($avg, '2.16');
    }

    public function testIndivDetailResults()
    {
        $final = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/b')->text();
        $this->assertEqual($final, 'Final Total: 2.60 - 0.26 = 2.34 (78%)  << Above Group Average >>');
        $penalty = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="panel1Content"]/font')->text();
        $this->assertEqual($penalty, '10%');

        $required = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered"]');
        $this->assertEqual(count($required), 20);
        // $unEnrolled = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'label[class="blue"]');
        // $this->assertEqual(count($unEnrolled), 17);
        // $this->assertEqual($unEnrolled[0]->text(), 'Tutor 1:');

        $likerts = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');

        $this->assertTrue($likerts[0]->attribute('disabled'));
        $this->assertEqual($likerts[0]->attribute('value'), 0.8);
        $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm5"]/ul[1]/div/li/label[7]');
        $this->assertEqual($ques1->text(), 'Grade: 0.80 / 1');

        $this->assertTrue($likerts[1]->attribute('disabled'));
        $this->assertTrue($likerts[1]->attribute('value'), 4);
        $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm5"]/ul[2]/div/li/label[7]');
        $this->assertEqual($ques2->text(), 'Grade: 1.00 / 1');
        $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm5"]/ul[2]/div/li/label[8]');
        $this->assertEqual($ques2->text(), '(Highest)');
/*
        //$this->assertTrue($likerts[3]->attribute('disabled'));
        $this->assertTrue($likerts[3]->attribute('value'), 4);
        $ques4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm6"]/ul[3]/div/li/label[7]');

        $this->assertEqual($ques4->text(), 'Grade: 1.00 / 1');
        $ques4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm6"]/ul[3]/div/li/label[8]');
        $this->assertEqual($ques4->text(), '(Highest)');

        //$this->assertTrue($likerts[4]->attribute('disabled'));
        $this->assertEqual($likerts[4]->attribute('value'), 0.8);
        $ques5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm6"]/ul[1]/div/li/label[7]');
        $this->assertEqual($ques5->text(), 'Grade: 0.80 / 1');

        //$this->assertTrue($likerts[5]->attribute('disabled'));
        $this->assertTrue($likerts[5]->attribute('value'), 4);
        $ques6 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm7"]/ul[2]/div[1]/li/label[7]');
        $this->assertEqual($ques6->text(), 'Grade: 1.00 / 1');
        $ques6 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm7"]/ul[2]/div[1]/li/label[8]');
        $this->assertEqual($ques6->text(), '(Highest)');
*/
        $comm1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm5"]/ul[4]/li')->text();
        $this->assertEqual(substr($comm1, -3), 'cat');
        $comm3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm5"]/ul[5]/li')->text();
        $this->assertEqual(substr($comm3, -3), 'dog');
        $comm5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm5"]/ul[6]/li')->text();
        $this->assertEqual(substr($comm5, -6), 'gerbil');
    }

    public function testStudentResults()
    {
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, '2.60 - (0.26)* = 2.34          ( )* : 10% late penalty. (78%)');

        $required = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered"]');
        $this->assertEqual(count($required), 5);

        $likerts = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        $testShuffle = $this->shuffled($likerts->attribute('value'));
        $this->assertTrue($testShuffle);
    }

    public function shuffled($mark)
    {
        $likerts = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        foreach ($likerts as $elm) {
            $this->assertTrue($elm->attribute('disabled'));
        }

        if ($mark == '0.8') {
            $this->assertEqual($likerts[0]->attribute('value'), 0.8);
            $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[6]');
            $this->assertEqual($ques1->text(), 'Grade: 0.80 / 1');

            $this->assertTrue($likerts[1]->attribute('value'), 1);
            $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div/li/label[6]');
            $this->assertEqual($ques2->text(), 'Grade: 1.00 / 1');
            $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div/li/label[7]');
            $this->assertEqual($ques2->text(), '(Highest)');

            $this->assertTrue($likerts[2]->attribute('value'), 1);
            $ques3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div/li/label[6]');
            $this->assertEqual($ques3->text(), 'Grade: 0.80 / 1');

            $comm1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[1]')->text();
            $this->assertEqual($comm1, 'cat');
            $comm3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[1]')->text();
            $this->assertEqual($comm3, 'dog');
            $comm5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[1]')->text();
            $this->assertEqual($comm5, 'gerbil');
            return true;
        } else if ($mark == '1') {
            $this->assertTrue($likerts[0]->attribute('value'), 1);
            $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[6]');
            $this->assertEqual($ques1->text(), 'Grade: 1.00 / 1');
            $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[1]/li/label[7]');
            $this->assertEqual($ques1->text(), '(Highest)');

            $this->assertEqual($likerts[1]->attribute('value'), 0.8);
            $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/div[2]/li/label[6]');
            $this->assertEqual($ques2->text(), 'Grade: 0.80 / 1');

            $this->assertTrue($likerts[2]->attribute('value'), 1);
            $ques3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[6]');
            $this->assertEqual($ques3->text(), 'Grade: 1.00 / 1');
            $ques3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[1]/li/label[7]');
            $this->assertEqual($ques3->text(), '(Highest)');

            $this->assertTrue($likerts[3]->attribute('value'), 1);
            $ques4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[6]');
            $this->assertEqual($ques4->text(), 'Grade: 1.00 / 1');
            $ques4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/div[2]/li/label[7]');
            $this->assertEqual($ques4->text(), '(Highest)');

            $this->assertTrue($likerts[4]->attribute('value'), 1);
            $ques5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[1]/li/label[6]');
            $this->assertEqual($ques5->text(), 'Grade: 1.00 / 1');
            $ques5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[1]/li/label[7]');
            $this->assertEqual($ques5->text(), '(Highest)');

            $this->assertEqual($likerts[5]->attribute('value'), 0.8);
            $ques6 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/div[2]/li/label[6]');
            $this->assertEqual($ques6->text(), 'Grade: 0.80 / 1');

            $comm1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[1]')->text();
            $this->assertEqual($comm1, 'red');
            $comm2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li[2]')->text();
            $this->assertEqual($comm2, 'cat');
            $comm3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[1]')->text();
            $this->assertEqual($comm3, 'blue');
            $comm4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li[2]')->text();
            $this->assertEqual($comm4, 'dog');
            $comm5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[1]')->text();
            $this->assertEqual($comm5, 'green');
            $comm6 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li[2]')->text();
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
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventEnableDetails0')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.60 - (0.26)* = 2.34          ( )* : 10% late penalty. (78%)');
        $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h3[1]')->text();
        $this->assertEqual($ques1, '1. Participated in Team Meetings *');
        $avg1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/li')->text();
        $this->assertEqual($avg1, 'Average: 0.80 / 1');
        $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h3[2]')->text();
        $this->assertEqual($ques2, '2. Was Helpful and co-operative *');
        $avg2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/li')->text();
        $this->assertEqual($avg2, 'Average: 1.00 / 1');
        $ques3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h3[3]')->text();
        $this->assertEqual($ques3, '3. Submitted work on time *');
        $avg3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/li')->text();
        $this->assertEqual($avg3, 'Average: 0.80 / 1');

        $required = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'span[class="required orangered"]');
        $this->assertEqual(count($required), 3);
    }

    public function testNotReleased()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventAutoRelease0')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventEnableDetails1')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), 'Not Released');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]');
        $this->assertEqual($header->text(), 'Comments/Grades Not Released Yet');
        $ques1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[1]/li');
        $this->assertEqual($ques1->text(), 'Grades Not Released Yet');
        $ques2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[2]/li');
        $this->assertEqual($ques2->text(), 'Grades Not Released Yet');
        $ques3 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[3]/li');
        $this->assertEqual($ques3->text(), 'Grades Not Released Yet');
        $ques4 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[4]/li');
        $this->assertEqual($ques4->text(), 'Comments Not Released Yet');
        $ques5 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[5]/li');
        $this->assertEqual($ques5->text(), 'Comments Not Released Yet');
        $ques6 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/ul[6]/li');
        $this->assertEqual($ques6->text(), 'Comments Not Released Yet');
    }

    public function testReviewed()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm"]/input[6]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $review = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="evalForm"]/input[6]');
                return ($review->attribute('value') == 'Mark Peer Evaluations as Not Reviewed');
            }
        );
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $review = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[6]/div')->text();
        $this->assertEqual($review, 'Reviewed');
    }

    public function testIndivGrades()
    {
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        // release grade for redshirt0001
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[2]/input')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $button = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[2]/input');
                return ($button->attribute('value') == 'Unrelease Grades');
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, '2.60 - (0.26)* = 2.34          ( )* : 10% late penalty. (78%)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font')->text();
        $this->assertEqual($header, 'Comments Not Released Yet');

        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]')->text();
        $this->assertEqual($header, 'Comments/Grades Not Released Yet');
    }

    public function testIndivComments()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[2]/input')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $button = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[2]/input');
                return ($button->attribute('value') == 'Release Grades');
            }
        );

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[3]/form/input[3]')->click();
        $w->until(
            function($session) {
                $button = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[3]/form/input[3]');
                return ($button->attribute('value') == 'Unrelease Comments');
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, 'Not Released');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font')->text();
        $this->assertEqual($header, 'Grades Not Released Yet');

        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]')->text();
        $this->assertEqual($header, 'Comments/Grades Not Released Yet');

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[3]/form/input[3]')->click();
        $w->until(
            function($session) {
                $button = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[5]/tbody/tr[2]/td[3]/form/input[3]');
                return ($button->attribute('value') == 'Release Comments');
            }
        );
    }

    public function testReleaseAllComments()
    {
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Release All Comments')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $comments = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[3]/div/div/table/tbody/tr[2]/td[8]/div');
                return ($comments->text() == 'Released');
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');

        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');

        $this->waitForLogoutLogin('redshirt0003');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Unrelease All Comments')->click();
        $w->until(
            function($session) {
                $comments = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[3]/div/div/table/tbody/tr[2]/td[8]/div');
                return ($comments->text() == 'Not Released');
            }
        );
    }

    public function testReleaseAllGrades()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Release All Grades')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $comments = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[3]/div/div/table/tbody/tr[2]/td[7]/div');
                return ($comments->text() == 'Released');
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.60 - (0.26)* = 2.34          ( )* : 10% late penalty. (78%)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Comments Not Released Yet');

        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.40 - (0.24)* = 2.16          ( )* : 10% late penalty. (72%)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Comments Not Released Yet');

        $this->waitForLogoutLogin('redshirt0003');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.20 - (0.22)* = 1.98          ( )* : 10% late penalty. (66%)');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="mixeval_result"]/h2/font');
        $this->assertEqual($header->text(), 'Comments Not Released Yet');

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Unrelease All Grades')->click();
        $w->until(
            function($session) {
                $comments = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[3]/div/div/table/tbody/tr[2]/td[7]/div');
                return ($comments->text() == 'Not Released');
            }
        );
    }

    public function testSavedAnswersNotSubmitted()
    {
        $this->waitForLogoutLogin('root');

        $w = new PHPWebDriver_WebDriverWait($this->session);
        // release all comments
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Release All Comments')->click();
        $w->until(
            function($session) {
                $comments = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[8]/div')->text();
                return $comments == 'Released';
            }
        );
        // release all grades
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Release All Grades')->click();
        $w->until(
            function($session) {
                $grades = $session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[7]/div')->text();
                return $grades == 'Released';
            }
        );

        // unsubmitted answers should not show up in student / instructor's results views
        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->sendKeys(date('Y-m-d H:i:s', strtotime('+1 day')));

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        // Matt Student submits
        $this->waitForLogoutLogin('redshirt0003');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[2]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '55EvaluationMixeval4QuestionComment')->sendKeys('right');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[5][EvaluationMixeval][5][question_comment]')->sendKeys('acute');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EvaluationMixeval5EvaluationMixeval6QuestionComment')->sendKeys('obtuse');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage")->text();
        $this->assertEqual($msg, 'Your answers have been saved. Please answer all the required questions before it can be considered submitted.');

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td')->text();
        $this->assertEqual($rating, 'Not Released');

        // Matt's evaluation for Ed is not counted yet - it's  not submitted
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1/Detail');
        $mark = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[5]')->text();
        $this->assertEqual($mark, '1.90 - 0.19 = 1.71 (57.00%)');
    }

    public function testSubmissionsAfterEvalReleased()
    {
        $this->waitForLogoutLogin('redshirt0003');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval4QuestionComment')->sendKeys('right');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[6][EvaluationMixeval][5][question_comment]')->sendKeys('acute');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval6QuestionComment')->sendKeys('obtuse');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');

        // check Matt Student's results
        $mixevals = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation');
        $mixevals[1]->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), '2.20 - (0.22)* = 1.98          ( )* : 10% late penalty. (66%)');

        // Ed Student's grades and comments should not be released
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $rating = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[2]/tbody/tr[2]/td');
        $this->assertEqual($rating->text(), 'Not Released');
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'font[color="red"]');
        $this->assertEqual($header->text(), 'Grades Not Released Yet');
    }

    public function testDeleteEvent()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'events/delete/'.$this->eventId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
        $this->assignToGroup();
    }

    public function testScoreDropdown()
    {
        // TODO - put scoredropdown question into sample mixed eval template and
        // adjust tests accordingly

        // add template
        $this->session->open($this->url.'mixevals/add');
        $name = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalName');
        $name->sendKeys('Final Project Evaluation');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypePeer"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();

        $question = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title');
        $question->sendKeys('Distributed Marks');

        $instructions = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Instructions');
        $instructions->sendKeys('Distribute the marks among your members.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        // wait for creation of template to finish
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $_temp_url = explode('/', $this->session->url());
        $templateId = end($_temp_url);

        // add event
        $this->session->open($this->url.'events/add/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Mixed Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('description for the evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventMixeval"] option[value="'.$templateId.'"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventAutoRelease1')->click();

        //set due date and release date end to next month so that the event is opened.
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->sendKeys(date('Y-m-d H:i:s'));
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->selectDayOnCalendar('EventReleaseDateBegin', date('j'), false);
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->selectDayOnCalendar('EventReleaseDateEnd', '4');
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '5')->click();
        $this->selectDayOnCalendar('EventResultReleaseDateBegin', '5');
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        $this->selectDayOnCalendar('EventResultReleaseDateEnd', '28');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="1"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        // do the evaluation
        $this->waitForLogoutLogin('tutor1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // check that the select field has options 0 to 30
        $marks = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option');
        $this->assertEqual(count($marks), 93);
        $selects = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select');
        $this->assertEqual(count($selects), 3);

        // do the evaluation
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $marks = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option');
        $this->assertEqual(count($marks), 42);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $alert = $this->session->alert_text();
        $this->assertEqual($alert, 'Please make sure that the total of the grades in the drop-downs equals 20 and then press "Submit" again.');
        $this->session->accept_alert();
        // given eleven marks each
        $eleven = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option[value="11"]');
        $eleven[0]->click();
        $eleven[1]->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $alert = $this->session->alert_text();
        $this->assertEqual($alert, 'Please make sure that the total of the grades in the drop-downs equals 20 and then press "Submit" again.');
        $this->session->accept_alert();
        // give ten marks each
        $ten = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EvaluationMixevalDropdown"] option[value="10"]');
        $ten[0]->click();
        $ten[1]->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        // delete the event and template
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'events/index/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        $this->session->open(str_replace('view', 'delete', $this->session->url()));
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->session->open($this->url.'mixevals/delete/'.$templateId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function testSelfEvaluation()
    {
        $this->waitForLogoutLogin('root');

        $this->session->open($this->url.'mixevals/add');
        // create simple evaluation with self-evaluation questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalName')->sendKeys('With Self-Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'self_eval')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(false);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title')->sendKeys('peer likert question');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(true);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Multiplier')->sendKeys('5');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionTypeSelf"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ(true);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title')->sendKeys('self likert question');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title')->sendKeys('self sentence question');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Multiplier')->sendKeys('7');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(1);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(1);"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The mixed evaluation was saved successfully.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'With Self-Evaluation')->click();
        $_temp_url = explode('/', $this->session->url());
        $this->templateId = end($_temp_url);
    }

    public function testSelfEvaluationEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Eval with Self-Eval');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="4"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[id="EventSelfEval1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventMixeval"] option[value="'.$this->templateId.'"]')->click();
        //set due date and release date end to next month so that the event is opened.
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->selectDayOnCalendar('EventReleaseDateBegin', date('j'), false);
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->selectDayOnCalendar('EventReleaseDateEnd', '4');
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '5')->click();
        $this->selectDayOnCalendar('EventResultReleaseDateBegin', '5');
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        $this->selectDayOnCalendar('EventResultReleaseDateEnd', '28');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="1"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Eval with Self-Eval')->click();
        $_temp_url = explode('/', $this->session->url());
        $this->eventId = end($_temp_url);
    }

    public function testAnswerSelfEval()
    {
        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Eval with Self-Eval')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[5]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="55EvaluationMixeval3QuestionComment"]')->sendKeys('I did great.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');

        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Eval with Self-Eval')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[1]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[1]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[5]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="66EvaluationMixeval3QuestionComment"]')->sendKeys('It was amazing.');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->waitForLogoutLogin('tutor1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Eval with Self-Eval')->click();
        $text = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="3535EvaluationMixeval3QuestionComment"]');
        $this->assertTrue(empty($text)); // no self-evaluation questions for tutors
    }

    public function testEvaluationResults()
    {
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1');
        $self = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[2]/td[1]');
        $this->assertEqual($self->text(), 'self likert question');
        $self = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[4]/tbody/tr[2]/td[2]');
        $this->assertEqual($self->text(), '7.00 / 7');
        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[5]/tbody/tr[1]/th[2]');
        $this->assertEqual($total->text(), 'Total:( /5.00)');
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[5]/tbody/tr[2]/td[2]');
        $this->assertEqual($ed->text(), '3.33 (66.70%)');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[5]/tbody/tr[3]/td[2]');
        $this->assertEqual($alex->text(), '4.17 (83.30%)');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[5]/tbody/tr[4]/td[2]');
        $this->assertEqual($matt->text(), '2.50 (50.00%)');
        $tut1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[5]/tbody/tr[5]/td[2]');
        $this->assertEqual($tut1->text(), 'N/A');
        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/table[5]/tbody/tr[6]/td[2]');
        $this->assertEqual($avg->text(), '3.33');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Detail')->click();
        $peer1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[1]/th[2]');
        $this->assertEqual($peer1->text(), '1 (/5.0)');
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[2]/td[2]');
        $this->assertEqual($ed->text(), '3.34');
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[3]/td[2]');
        $this->assertEqual($alex->text(), '4.17');
        $matt = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[4]/td[2]');
        $this->assertEqual($matt->text(), '2.50');
        $tut1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[5]/td[2]');
        $this->assertEqual($tut1->text(), 'N/A');
        $avg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table[4]/tbody/tr[6]/td[2]');
        $this->assertEqual($avg->text(), '3.33');

        $selfPanel = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'panelSelf');
        $this->assertTrue(!empty($selfPanel));
        $self1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div/div[1]/div[2]/div/div/h3[1]');
        $this->assertEqual($self1->text(), "1. self likert question *");
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="Ques100700"]');
        $this->assertTrue($ed->attribute('checked'));
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="Ques110700"]');
        $this->assertTrue($alex->attribute('checked'));
        $self2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div/div[1]/div[2]/div/div/h3[2]');
        $this->assertEqual($self2->text(), "2. self sentence question *");
        $ed = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div/div[1]/div[2]/div/div/ul[2]/li[1]');
        $this->assertEqual($ed->text(), "Ed Student:I did great.");
        $alex = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div/div[1]/div[2]/div/div/ul[2]/li[2]');
        $this->assertEqual($alex->text(), "Alex Student:It was amazing.");
    }

    public function testStudentSelfEvaluationResult()
    {
        $this->waitForLogoutLogin('root');

        $this->session->open($this->url.'events/edit/'.$this->eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->sendKeys(date('Y-m-d H:i:s'));
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->waitForLogoutLogin('redshirt0001');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Eval with Self-Eval')->click();
        $header = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[2]/h2');
        $this->assertEqual($header->text(), 'Self-Evaluation');
        $self1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[2]/h3[1]');
        $this->assertEqual($self1->text(), "1. self likert question *");
        $self1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="Ques105700"]');
        $this->assertTrue($self1->attribute('checked'));
        $self2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[2]/h3[2]');
        $this->assertEqual($self2->text(), "2. self sentence question *");
        $self2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/div[2]/ul[2]/li');
        $this->assertEqual($self2->text(), 'n/a');  // comment not released yet. so displayed as "n/a"

        $this->waitForLogoutLogin('root');
        // delete the event
        $this->session->open($this->url.'events/delete/'.$this->eventId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        // delete the template
        $this->session->open($this->url.'mixevals/delete/'.$this->templateId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function secondStudent()
    {
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '55EvaluationMixeval4QuestionComment')->sendKeys('cat');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[5][EvaluationMixeval][5][question_comment]')->sendKeys('dog');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EvaluationMixeval5EvaluationMixeval6QuestionComment')->sendKeys('gerbil');

        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[3]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[2]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval4QuestionComment')->sendKeys('abc');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('def');

        // submit
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }

    public function tutor()
    {
        $this->waitForLogoutLogin('tutor1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluation')->click();
        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[2]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[3]/table/tbody/tr[2]/td[5]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[4]/table/tbody/tr[2]/td[5]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '55EvaluationMixeval4QuestionComment')->sendKeys('red');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[5][EvaluationMixeval][5][question_comment]')->sendKeys('blue');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EvaluationMixeval5EvaluationMixeval6QuestionComment')->sendKeys('green');

        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[8]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[9]/table/tbody/tr[2]/td[4]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[10]/table/tbody/tr[2]/td[4]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '66EvaluationMixeval4QuestionComment')->sendKeys('one');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[6][EvaluationMixeval][5][question_comment]')->sendKeys('two');

        // Likert questions
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[14]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[15]/table/tbody/tr[2]/td[2]/input')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '/html/body/div[1]/div[3]/table/tbody/tr/td/form/div[16]/table/tbody/tr[2]/td[2]/input')->click();
        // short and long answers
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval4QuestionComment')->sendKeys('right');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::NAME, 'data[7][EvaluationMixeval][5][question_comment]')->sendKeys('left');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, '77EvaluationMixeval6QuestionComment')->sendKeys('forward');

        // submit
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }

    public function removeFromGroup()
    {
        $this->session->open($this->url.'groups/edit/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="5"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="6"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="7"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="<< Remove "]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Group"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function assignToGroup()
    {
        $this->session->open($this->url.'groups/edit/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="35"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Group"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }
}
