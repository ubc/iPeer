<?php
require_once APP_DIR . DS . 'vendors' . DS . 'simpletest' . DS . 'xml.php';

/**
 * CakeXmlReporter the CakePHP test reporter to output xml format
 *
 * @uses    XmlReporter
 * @package Tests.Reporter
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class CakeXmlReporter extends XmlReporter
{
    protected $pre;

    /**
     * Time the test runs started.
     *
     * @var integer
     * @access protected
     */
    protected $_timeStart = 0;

    /**
     * Time the test runs ended
     *
     * @var integer
     * @access protected
     */
    protected $_timeEnd = 0;

    /**
     * Duration of all test methods.
     *
     * @var integer
     * @access protected
     */
    protected $_timeDuration = 0;

    /**
     * Array of request parameters.  Usually parsed GET params.
     *
     * @var array
     */
    public $params = array();

    /**
     * Character set for the output of test reporting.
     *
     * @var string
     * @access protected
     */
    protected $_characterSet;

    /* public __construct($charset = 'utf-8', $params = array()) {{{ */
    /**
     * __construct constructor function
     *
     * @param bool $charset charset
     * @param bool $params  extra parameters
     *
     * @access public
     * @return void
     */
    public function __construct($charset = 'utf-8', $params = array())
    {
        parent::__construct();
        $this->_characterSet = $charset;
        $this->params = $params;
    }
    /* }}} */


    /* public paintMethodStart($test_name) {{{ */
    /**
     * paintMethodStart
     *
     * @param mixed $test_name the name of the test going to run
     *
     * @access public
     * @return void
     */
    public function paintMethodStart($test_name)
    {
        $this->pre = microtime();
        parent::paintMethodStart($test_name);
    }
    /* }}} */

    /* public paintMethodEnd($test_name) {{{ */
    /**
     * paintMethodEnd
     *
     * @param mixed $test_name the name of the test going to end
     *
     * @access public
     * @return void
     */
    function paintMethodEnd($test_name)
    {
        $post = microtime();
        if ($this->pre != null) {
            $duration = $post - $this->pre;
            // how can post time be less than pre?  assuming zero if this happens..
            if ($post < $this->pre) {
                $duration = 0;
            }
            print $this->_getIndent(1);
            print "<time>$duration</time>\n";
        }
        parent::paintMethodEnd($test_name);
        $this->pre = null;
    }
    /* }}} */

    /**
     * Retrieves a list of test cases from the active Manager class,
     * displaying it in the correct format for the reporter subclass
     *
     * @return mixed
     */
    function testCaseList()
    {
        $testList = TestManager::getTestCaseList();
        return $testList;
    }

    /**
     * Get the baseUrl if one is available.
     *
     * @return string The base url for the request.
     */
    function baseUrl()
    {
        if (!empty($_SERVER['PHP_SELF'])) {
            return $_SERVER['PHP_SELF'];
        }

        return '';
    }
}
