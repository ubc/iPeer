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

    function testSummarizeEnvelope()
    {
        $envelope = json_encode(array(
            'sensor' => 'http://test.ipeer.com',
            'sendTime' => '2026-10-26T18:00:00.000Z',
            'data' => array(
                array(
                    'id' => 'urn:uuid:1',
                    'type' => 'NavigationEvent',
                    'action' => 'NavigatedTo',
                    'eventTime' => '2026-10-26T17:59:59.000Z',
                    'actor' => array('id' => 'http://www.ubc.ca/student1', 'name' => 'Jane Student'),
                    'object' => array('id' => 'http://test.ipeer.com/users?q=Jane', 'type' => 'WebPage'),
                ),
                // missing fields and a non-entity object
                array('type' => 'SessionEvent', 'object' => 'http://test.ipeer.com'),
            ),
        ));
        $summary = CaliperSensor::summarizeEnvelope($envelope);

        $this->assertEqual(array(
            'sendTime' => '2026-10-26T18:00:00.000Z',
            'events' => array(
                array('id' => 'urn:uuid:1', 'type' => 'NavigationEvent', 'action' => 'NavigatedTo',
                    'eventTime' => '2026-10-26T17:59:59.000Z', 'objectType' => 'WebPage'),
                array('id' => null, 'type' => 'SessionEvent', 'action' => null,
                    'eventTime' => null, 'objectType' => null),
            ),
        ), json_decode($summary, true));
        $this->assertNoPattern('/Jane|student1/', $summary);

        $empty = array('sendTime' => null, 'events' => array());
        $this->assertEqual($empty, json_decode(CaliperSensor::summarizeEnvelope('not json'), true));
        $this->assertEqual($empty, json_decode(CaliperSensor::summarizeEnvelope('{}'), true));
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
