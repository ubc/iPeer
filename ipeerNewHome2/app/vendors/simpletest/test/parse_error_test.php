<?php
// $Id: parse_error_test.php 449 2011-02-21 22:52:09Z compass $
require_once('../unit_tester.php');
require_once('../reporter.php');

$test = &new TestSuite('This should fail');
$test->addFile('test_with_parse_error.php');
$test->run(new HtmlReporter());
?>