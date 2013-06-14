<?php
require_once('PHPWebDriver/WebDriverSession.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PHPWebDriver/WebDriverContainer.php');

class SystemWebDriverSession extends PHPWebDriver_WebDriverSession {
    public function elementWithWait($using, $value) {
        $selector = array('selector' => $using, 'value' => $value);
        $w = new PHPWebDriver_WebDriverWait($this, 30, 0.5, $selector);
        $w->until(
            function($session, $selector) {
                return $session->element($selector['selector'], $selector['value']);
            }
        );
        return $this->element($using, $value);
    }

    public function elementsWithWait($using, $value) {
        $selector = array('selector' => $using, 'value' => $value);
        $w = new PHPWebDriver_WebDriverWait($this, 30, 0.5, $selector);
        $w->until(
            function($session, $selector) {
                return count($session->elements($selector['selector'], $selector['value']));
            }
        );
        return $this->elements($using, $value);
    }
}