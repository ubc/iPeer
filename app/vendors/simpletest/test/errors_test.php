<?php
require_once(dirname(__FILE__) . '/../autorun.php');
require_once(dirname(__FILE__) . '/../errors.php');
require_once(dirname(__FILE__) . '/../expectation.php');
require_once(dirname(__FILE__) . '/../test_case.php');
Mock::generate('SimpleTestCase');
Mock::generate('SimpleExpectation');
SimpleTest::ignore('MockSimpleTestCase');

class TestOfErrorQueue extends UnitTestCase
{
    public function setUp()
    {
        $context = &SimpleTest::getContext();
        $queue = &$context->get('SimpleErrorQueue');
        $queue->clear();
    }

    public function tearDown()
    {
        $context = &SimpleTest::getContext();
        $queue = &$context->get('SimpleErrorQueue');
        $queue->clear();
    }

    public function testOrder()
    {
        $context = &SimpleTest::getContext();
        $queue = &$context->get('SimpleErrorQueue');
        $queue->add(1024, 'Ouch', 'here.php', 100);
        $queue->add(512, 'Yuk', 'there.php', 101);
        $this->assertEqual(
                $queue->extract(),
                array(1024, 'Ouch', 'here.php', 100)
        );
        $this->assertEqual(
                $queue->extract(),
                array(512, 'Yuk', 'there.php', 101)
        );
        $this->assertFalse($queue->extract());
    }

    public function testAssertNoErrorsGivesTrueWhenNoErrors()
    {
        $test = new MockSimpleTestCase();
        $test->expectOnce('assert', array(
                new IdenticalExpectation(new TrueExpectation()),
                true,
                'Should be no errors'));
        $test->setReturnValue('assert', true);
        $queue = new SimpleErrorQueue();
        $queue->setTestCase($test);
        $this->assertTrue($queue->assertNoErrors('%s'));
    }

    public function testAssertNoErrorsIssuesFailWhenErrors()
    {
        $test = new MockSimpleTestCase();
        $test->expectOnce('assert', array(
                new IdenticalExpectation(new TrueExpectation()),
                false,
                'Should be no errors'));
        $test->setReturnValue('assert', false);
        $queue = new SimpleErrorQueue();
        $queue->setTestCase($test);
        $queue->add(1024, 'Ouch', 'here.php', 100);
        $this->assertFalse($queue->assertNoErrors('%s'));
    }

    public function testAssertErrorFailsWhenNoError()
    {
        $test = new MockSimpleTestCase();
        $test->expectOnce('fail', array('Expected error not found'));
        $test->setReturnValue('assert', false);
        $queue = new SimpleErrorQueue();
        $queue->setTestCase($test);
        $this->assertFalse($queue->assertError(false, '%s'));
    }

    public function testAssertErrorFailsWhenErrorDoesntMatchTheExpectation()
    {
        $test = new MockSimpleTestCase();
        $test->expectOnce('assert', array(
                new IdenticalExpectation(new FailedExpectation()),
                'B',
                'Expected PHP error [B] severity [E_USER_NOTICE] in [b.php] line [100]'));
        $test->setReturnValue('assert', false);
        $queue = new SimpleErrorQueue();
        $queue->setTestCase($test);
        $queue->add(1024, 'B', 'b.php', 100);
        $this->assertFalse($queue->assertError(new FailedExpectation(), '%s'));
    }

    public function testExpectationMatchCancelsIncomingError()
    {
        $test = new MockSimpleTestCase();
        $test->expectOnce('assert', array(
                new IdenticalExpectation(new AnythingExpectation()),
                'B',
                'a message'));
        $test->setReturnValue('assert', true);
        $test->expectNever('error');
        $queue = new SimpleErrorQueue();
        $queue->setTestCase($test);
        $queue->expectError(new AnythingExpectation(), 'a message');
        $queue->add(1024, 'B', 'b.php', 100);
    }
}

class TestOfErrorTrap extends UnitTestCase
{
    public $_old;

    public function setUp()
    {
        $this->_old = error_reporting(E_ALL);
        set_error_handler('SimpleTestErrorHandler');
    }

    public function tearDown()
    {
        restore_error_handler();
        error_reporting($this->_old);
    }

    public function testQueueStartsEmpty()
    {
        $context = &SimpleTest::getContext();
        $queue = &$context->get('SimpleErrorQueue');
        $this->assertFalse($queue->extract());
    }

    public function testTrappedErrorPlacedInQueue()
    {
        trigger_error('Ouch!');
        $context = &SimpleTest::getContext();
        $queue = &$context->get('SimpleErrorQueue');
        list($severity, $message, $file, $line) = $queue->extract();
        $this->assertEqual($message, 'Ouch!');
        $this->assertEqual($file, __FILE__);
        $this->assertFalse($queue->extract());
    }

    public function testErrorsAreSwallowedByMatchingExpectation()
    {
        $this->expectError('Ouch!');
        trigger_error('Ouch!');
    }

    public function testErrorsAreSwallowedInOrder()
    {
        $this->expectError('a');
        $this->expectError('b');
        trigger_error('a');
        trigger_error('b');
    }

    public function testAnyErrorCanBeSwallowed()
    {
        $this->expectError();
        trigger_error('Ouch!');
    }

    public function testErrorCanBeSwallowedByPatternMatching()
    {
        $this->expectError(new PatternExpectation('/ouch/i'));
        trigger_error('Ouch!');
    }

    public function testErrorWithPercentsPassesWithNoSprintfError()
    {
        $this->expectError("%");
        trigger_error('%');
    }
}

class TestOfErrors extends UnitTestCase
{
    public $_old;

    public function setUp()
    {
        $this->_old = error_reporting(E_ALL);
    }

    public function tearDown()
    {
        error_reporting($this->_old);
    }

    public function testDefaultWhenAllReported()
    {
        error_reporting(E_ALL);
        trigger_error('Ouch!');
        $this->assertError('Ouch!');
    }

    public function testNoticeWhenReported()
    {
        error_reporting(E_ALL);
        trigger_error('Ouch!', E_USER_NOTICE);
        $this->assertError('Ouch!');
    }

    public function testWarningWhenReported()
    {
        error_reporting(E_ALL);
        trigger_error('Ouch!', E_USER_WARNING);
        $this->assertError('Ouch!');
    }

    public function testErrorWhenReported()
    {
        error_reporting(E_ALL);
        trigger_error('Ouch!', E_USER_ERROR);
        $this->assertError('Ouch!');
    }

    public function testNoNoticeWhenNotReported()
    {
        error_reporting(0);
        trigger_error('Ouch!', E_USER_NOTICE);
    }

    public function testNoWarningWhenNotReported()
    {
        error_reporting(0);
        trigger_error('Ouch!', E_USER_WARNING);
    }

    public function testNoticeSuppressedWhenReported()
    {
        error_reporting(E_ALL);
        @trigger_error('Ouch!', E_USER_NOTICE);
    }

    public function testWarningSuppressedWhenReported()
    {
        error_reporting(E_ALL);
        @trigger_error('Ouch!', E_USER_WARNING);
    }

    public function testErrorWithPercentsReportedWithNoSprintfError()
    {
        trigger_error('%');
        $this->assertError('%');
    }
}

class TestOfPHP52RecoverableErrors extends UnitTestCase
{
    public function skip()
    {
        $this->skipIf(
                version_compare(phpversion(), '5.2', '<'),
                'E_RECOVERABLE_ERROR not tested for PHP below 5.2'
        );
    }

    public function testError()
    {
        eval('
            class RecoverableErrorTestingStub {
                function ouch(RecoverableErrorTestingStub $obj) {
                }
            }
        ');

        $stub = new RecoverableErrorTestingStub();
        $this->expectError(new PatternExpectation('/must be an instance of RecoverableErrorTestingStub/i'));
        $stub->ouch(new stdClass());
    }
}

class TestOfErrorsExcludingPHP52AndAbove extends UnitTestCase
{
    public function skip()
    {
        $this->skipIf(
                version_compare(phpversion(), '5.2', '>='),
                'E_USER_ERROR not tested for PHP 5.2 and above'
        );
    }

    public function testNoErrorWhenNotReported()
    {
        error_reporting(0);
        trigger_error('Ouch!', E_USER_ERROR);
    }

    public function testErrorSuppressedWhenReported()
    {
        error_reporting(E_ALL);
        @trigger_error('Ouch!', E_USER_ERROR);
    }
}

SimpleTest::ignore('TestOfNotEnoughErrors');
/**
 * This test is ignored as it is used by {@link TestRunnerForLeftOverAndNotEnoughErrors}
 * to verify that it fails as expected.
 *
 * @ignore
 */
class TestOfNotEnoughErrors extends UnitTestCase
{
    public function testExpectTwoErrorsThrowOne()
    {
        $this->expectError('Error 1');
        trigger_error('Error 1');
        $this->expectError('Error 2');
    }
}

SimpleTest::ignore('TestOfLeftOverErrors');
/**
 * This test is ignored as it is used by {@link TestRunnerForLeftOverAndNotEnoughErrors}
 * to verify that it fails as expected.
 *
 * @ignore
 */
class TestOfLeftOverErrors extends UnitTestCase
{
    public function testExpectOneErrorGetTwo()
    {
        $this->expectError('Error 1');
        trigger_error('Error 1');
        trigger_error('Error 2');
    }
}

class TestRunnerForLeftOverAndNotEnoughErrors extends UnitTestCase
{
    public function testRunLeftOverErrorsTestCase()
    {
        $test = new TestOfLeftOverErrors();
        $this->assertFalse($test->run(new SimpleReporter()));
    }

    public function testRunNotEnoughErrors()
    {
        $test = new TestOfNotEnoughErrors();
        $this->assertFalse($test->run(new SimpleReporter()));
    }
}

// TODO: Add stacked error handler test
