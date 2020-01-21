<?php
App::import('Lib', 'system_base');
App::import('Lib', 'Lti13Bootstrap');

/**
 * Simpletest compatibility.
 */
if (!function_exists('split')) {
    function split($pattern, $string, $limit = -1) {
        return preg_split('@'.$pattern.'@', $string, $limit);
    }
}

/**
 * To bypass `vendors/webdriver/PHPWebDriver/WebDriverBase.php::__call()` -> count() PHP error
 *
 * @link https://www.php.net/manual/en/function.set-error-handler
 */
function error_handler($errno, $errstr, $errfile, $errline) {
    $args = compact('errno', 'errstr', 'errfile', 'errline');
    $error = array(
        'errno'   => E_WARNING,
        'errstr'  => "count(): Parameter must be an array or an object that implements Countable",
        'errfile' => '/var/www/html/vendors/webdriver/PHPWebDriver/WebDriverBase.php',
        'errline' => 242,
    );
    return $args + $error == $error;
}
set_error_handler('error_handler');

/**
 * Usage:
 * `cake/console/cake -app app testsuite app case system/lti13_login`
 *
 * @link https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#web-testing-testing-views
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Lti13LoginTestCase extends SystemBaseTestCase
{
    private $saveScreenshot = true;

    public function startCase()
    {
        parent::startCase();
        echo "Start LTI 1.3 Login system test.", PHP_EOL;
        $this->getSession()->open($this->url);
    }

    /**
     * Override SimpleTestCase::error()
     *
     * @see app/vendors/simpletest/test_case.php
     */
    public function error($severity, $message, $file, $line) {
        if (!error_handler(constant($severity), $message, $file, $line)) {
            parent::error($severity, $message, $file, $line);
        }
    }

    /**
     * @see vendors/webdriver/README.md -> Screenshotting
     */
    public function saveScreenshot()
    {
        if ($this->saveScreenshot) {
            $img = $this->session->screenshot();
            $data = base64_decode($img);
            $u = explode(".", microtime(1))[1];
            $filename = sprintf('%s/app/tmp/tests/%s%s.png', ROOT, date('YmdHis'), $u);
            printf("Screenshot: %s%s", $filename, PHP_EOL);
            return file_put_contents($filename, $data);
        }
    }

    public function login()
    {
        $this->session->deleteAllCookies();
        $login = PageFactory::initElements($this->session, 'Login');
        $this->saveScreenshot();
        return $login->login('root', 'ipeeripeer');
    }

    public function testLogin()
    {
        $this->login();
        $this->assertEqual($this->session->url(), $this->url);

        // Make sure we are landed on home page
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->saveScreenshot();
        $this->assertEqual($title, 'Home');
    }
}
