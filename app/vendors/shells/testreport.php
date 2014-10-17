<?php
App::import('Shell', 'testsuite');

/**
 * TestReportShell
 *
 * @uses TestSuiteShell
 * @package Test
 * @author  Pan Luo <pan.luo@ubc.ca>
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class TestReportShell extends TestSuiteShell
{
    /**
     * Initialization method installs Simpletest and loads all plugins
     *
     * @return void
     * @access public
     */
    public function initialize()
    {
        require_once APPLIBS . 'test_suite' . DS . 'reporters' . DS . 'cake_xml_reporter.php';
        parent::initialize();
    }

    /**
     * Executes the tests depending on our settings
     *
     * @return void
     * @access private
     */
    public function __run()
    {
        $Reporter = new CakeXmlReporter('utf-8', array(
            'app' => $this->Manager->appTest,
            'plugin' => $this->Manager->pluginTest,
            'group' => ($this->type === 'group'),
            'codeCoverage' => $this->doCoverage
        ));

        if ($this->doCoverage) {
            if (!extension_loaded('xdebug')) {
                $this->out(__('You must install Xdebug to use the CakePHP(tm) Code Coverage Analyzation. Download it from http://www.xdebug.org/docs/install', true));
                $this->_stop(0);
            }
        }

        ob_start();

        try {
            if ($this->type == 'all') {
                $result = $this->Manager->runAllTests($Reporter);
            } else if ($this->type == 'group') {
                $ucFirstGroup = ucfirst($this->file);
                if ($this->doCoverage) {
                    require_once CAKE . 'tests' . DS . 'lib' . DS . 'code_coverage_manager.php';
                    CodeCoverageManager::init($ucFirstGroup, $Reporter);
                    CodeCoverageManager::start();
                }
                $result = $this->Manager->runGroupTest($ucFirstGroup, $Reporter);
            } else {

                $folder = $folder = $this->__findFolderByCategory($this->category);
                $case = $this->__getFileName($folder, $this->isPluginTest);

                if ($this->doCoverage) {
                    require_once CAKE . 'tests' . DS . 'lib' . DS . 'code_coverage_manager.php';
                    CodeCoverageManager::init($case, $Reporter);
                    CodeCoverageManager::start();
                }
                $result = $this->Manager->runTestCase($case, $Reporter);
            }
        } catch (Exception $e) {
            ob_get_clean();
            $this->out('Tests failed to run. ' . $e->getMessage());
            $this->_stop(1);
        }
        $xml = ob_get_clean();

        $xmlD = new DOMDocument();
        $xmlD->loadXML($xml);

        $xslt = new XSLTProcessor();
        $XSL = new DOMDocument();
        $XSL->load(dirname(__FILE__).DS.'xsl'.DS.'to-junit.xsl');
        $xslt->importStylesheet($XSL);
        $out = $xslt->transformToXML($xmlD);
        $time = time();

        file_put_contents(ROOT . DS . "build" . DS . "logs" . DS . "junit-$time.xml", $out);
        echo "Done.\n";
    }

    /**
     * tries to install simpletest and exits gracefully if it is not there
     *
     * @return void
     * @access private
     */
    public function __installSimpleTest()
    {
        /*if (!App::import('Vendor', 'simpletest' . DS . 'reporter')) {
            $this->err(__('Sorry, Simpletest could not be found. Download it from http://simpletest.org and install it to your vendors directory.', true));
            exit;
        }*/
    }
}
