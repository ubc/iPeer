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
    private $env = array();

    function startTest($method)
    {
        foreach (array('CALIPER_HOST', 'CALIPER_API_KEY') as $name) {
            $this->env[$name] = getenv($name);
        }
        // nothing listens on port 1, so sends fail with no response
        putenv('CALIPER_HOST=http://127.0.0.1:1');
        putenv('CALIPER_API_KEY=test_caliper_key');
        $this->resetSensor();
    }

    function endTest($method)
    {
        foreach ($this->env as $name => $value) {
            putenv($value === false ? $name : "$name=$value");
        }
        $this->resetSensor();
        CaliperSensor::isTest(false);
    }

    // CaliperSensor caches the host and sensor on first use, which may have
    // happened in an earlier test in the same run
    private function resetSensor()
    {
        foreach (array('options', 'sensor') as $name) {
            $property = new ReflectionProperty('caliper\CaliperSensor', $name);
            $property->setAccessible(true);
            $property->setValue(null, null);
        }
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
            $this->assertPattern('/^Caliper Emit Error: no response .* \(transient\)$/', $e->getMessage());
            $this->assertEqual(1800, $e->getDelay());
        }
    }
}
