<?php
require_once CAKE_TEST_LIB . 'reporter' . DS . 'cake_base_reporter.php';

/**
 * CakeXmlReporter the CakePHP test reporter to output xml format
 *
 * @uses    CakeBaseReporter
 * @package Tests.Reporter
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class CakeXmlReporter extends CakeBaseReporter
{
    protected $pre;

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
        parent::__contructor($charset, $params);
    }
    /* }}} */


    /**
     * paintMethodStart
     *
     * @param mixed $test_name
     *
     * @access public
     * @return void
     */
    public function paintMethodStart($test_name)
    {
        $this->pre = microtime();
        parent::paintMethodStart($test_name);
    }


    /**
     * paintMethodEnd
     *
     * @param mixed $test_name
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

}
