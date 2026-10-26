<?php

App::import('Lib', 'caliper');
use caliper\CaliperSensor;

/**
 * Tests for CaliperSensor sending and retries.
 *
 * @package app.tests.cases.libs
 */
class CaliperSensorTestCase extends CakeTestCase
{
    function startTest($method)
    {
        // nothing listens on port 1, so sends fail with no response
        putenv('CALIPER_HOST=http://127.0.0.1:1');
        putenv('CALIPER_API_KEY=test_caliper_key');
    }

    function endTest($method)
    {
        putenv('CALIPER_HOST');
        putenv('CALIPER_API_KEY');
        CaliperSensor::isTest(false);
    }

    function testRetryDelay()
    {
        $this->assertEqual(1800, CaliperSensor::retryDelay(null));
        $this->assertEqual(1800, CaliperSensor::retryDelay(429));
        $this->assertEqual(1800, CaliperSensor::retryDelay(500));
        $this->assertEqual(1800, CaliperSensor::retryDelay(503));
        $this->assertEqual(3600, CaliperSensor::retryDelay(400));
        $this->assertEqual(3600, CaliperSensor::retryDelay(401));
        $this->assertEqual(3600, CaliperSensor::retryDelay(403));
    }

    function testSendEventFailureThrowsRetry()
    {
        CaliperSensor::isTest(false);
        try {
            CaliperSensor::_sendEvent('{}');
            $this->fail('expected DJRetryException');
        } catch (DJRetryException $e) {
            $this->assertPattern('/^Caliper Emit Error: no response/', $e->getMessage());
            $this->assertEqual(1800, $e->getDelay());
        }
    }
}
