<?php
// $Id: simpletest_test.php 1505 2007-04-30 23:39:59Z lastcraft $
require_once(dirname(__FILE__) . '/../autorun.php');
require_once(dirname(__FILE__) . '/../simpletest.php');

SimpleTest::ignore('ShouldNeverBeRunEither');

class ShouldNeverBeRun extends UnitTestCase
{
    public function testWithNoChanceOfSuccess()
    {
        $this->fail('Should be ignored');
    }
}

class ShouldNeverBeRunEither extends ShouldNeverBeRun
{
}

class TestOfStackTrace extends UnitTestCase
{
    public function testCanFindAssertInTrace()
    {
        $trace = new SimpleStackTrace(array('assert'));
        $this->assertEqual(
                $trace->traceMethod(array(array(
                        'file' => '/my_test.php',
                        'line' => 24,
                        'function' => 'assertSomething'))),
                ' at [/my_test.php line 24]'
        );
    }
}

class DummyResource
{
}

class TestOfContext extends UnitTestCase
{
    public function testCurrentContextIsUnique()
    {
        $this->assertReference(
                SimpleTest::getContext(),
                SimpleTest::getContext()
        );
    }

    public function testContextHoldsCurrentTestCase()
    {
        $context = &SimpleTest::getContext();
        $this->assertReference($this, $context->getTest());
    }

    public function testResourceIsSingleInstanceWithContext()
    {
        $context = new SimpleTestContext();
        $this->assertReference(
                $context->get('DummyResource'),
                $context->get('DummyResource')
        );
    }

    public function testClearingContextResetsResources()
    {
        $context = new SimpleTestContext();
        $resource = &$context->get('DummyResource');
        $context->clear();
        $this->assertClone($resource, $context->get('DummyResource'));
    }
}
