<?php
App::import('Lib', 'system_base');

class importEventsTestCase extends SystemBaseTestCase
{
    protected $courseId;
    protected $eventId;

    public function startCase()
    {
        parent::startCase();
        echo "Start importEvents system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testImportSuccess()
    {
        $this->session->open($this->url.'events/import/1');

        $file = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'ImportEventsFile');
        $file->sendKeys(dirname(__FILE__).'/files/import-test-success.csv');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='submit'] input")->click();

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, "4 event(s) were imported successfully.");

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "option[value='Event.Title']")->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "searchInputField")->sendKeys('test-event-1');

        // link to event should exist
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'test-event-1')->click();

        // link to group should exist
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, '1')->click();
    }


    public function testImportFailure()
    {
        // bad data in file
        $this->session->open($this->url.'events/import/1');
        $file = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'ImportEventsFile');
        $file->sendKeys(dirname(__FILE__).'/files/import-test-fail.csv');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='submit'] input")->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, "Import Failed\nEvent \"test-event-4\" (on row 4), has an invalid field: \"Type\": Please select a template type.");

        // wrong extension
        $this->session->open($this->url.'events/import/1');
        $file = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'ImportEventsFile');
        $file->sendKeys(dirname(__FILE__).'/files/docx.docx');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='submit'] input")->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, "extension is not allowed.\nFileUpload::processFile() - Unable to save temp file to file system.");

    }
}
