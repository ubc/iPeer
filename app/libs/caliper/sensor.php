<?php
namespace caliper;

use IMSGlobal\Caliper\Options;
use IMSGlobal\Caliper\Sensor;
use caliper\ResourceIRI;
use caliper\CaliperEvent;
use IMSGlobal\Caliper\Client;

use IMSGlobal\Caliper\request\HttpRequestor;

\App::import('Lib', 'jobs');
\App::import('Component', 'CakeDjjob.cake_job', array(
    'file' => 'cake_djjob.php',
));

class CaliperSensor {
    private static $options = null;
    private static $sensor = null;
    private static $isTest = false;
    private static $sendTestEvents = false;
    private static $tempStore = array();

    public static function isTest($isTest) {
        self::$isTest = $isTest;
    }

    public static function sendTestEvents($sendTestEvents) {
        self::$sendTestEvents = $sendTestEvents;
    }

    public static function getEnvelopes() {
        $envelopes = self::$tempStore;
        self::$tempStore = array();
        return $envelopes;
    }

    private static function getOptions() {
        if ( self::$options == null ) {
            self::$options = (new Options())
                ->setApiKey("Bearer " . getenv('CALIPER_API_KEY'))
                ->setHost(getenv('CALIPER_HOST'));
        }
        return self::$options;
    }

    private static function getSensor() {
        if ( self::$sensor == null ) {
            self::$sensor = new Sensor(ResourceIRI::getBaseUrl());
            self::$sensor->registerClient('default_client', new Client('remote_lrs', self::getOptions()));
        }
        return self::$sensor;
    }

    public static function caliperEnabled() {
        return (is_string(getenv('CALIPER_HOST')) && is_string(getenv('CALIPER_API_KEY')));
    }

    public static function sendEvent(&$events, $course = NULL, $group = NULL) {
        if (!self::caliperEnabled()) {
            return;
        }
        if (!is_array($events)) {
            $events = [$events];
        }

        $roles = $course ? CaliperEvent::generateRolesForCourse($course) : NULL;
        foreach ($events as $event) {
            CaliperEvent::addDefaults($event, $course, $roles, $group);
        }

        $event_chunks = array_chunk($events, 5);

        $CakeDjjobComponent = new \CakeDjjobComponent();
        $CakeDjjobComponent->initialize();

        foreach ($event_chunks as $event_chunk) {
            $requestor = new HttpRequestor(self::getOptions());
            $envelope = $requestor->createEnvelope(self::getSensor(), $event_chunk);
            $envelopeJson = $requestor->serializeData($envelope);


            // used for unit tests
            if ( self::$isTest ) {
                self::$tempStore[] = $envelopeJson;
                if (self::$sendTestEvents) {
                    self::_sendEvent($envelopeJson);
                }
            } else {
                $CakeDjjobComponent->enqueue(new \SendCaliperEventsJob($envelopeJson));
            }
        }
    }

    public static function _sendEvent($envelopeJson) {
        if (!is_string($envelopeJson)) {
            throw new \InvalidArgumentException(__METHOD__ . ': string expected');
        }
        if (!self::caliperEnabled()) {
            return false;
        }

        // Requires curl extension
        // based off of https://github.com/IMSGlobal/caliper-php/blob/master/src/request/HttpRequestor.php#L75
        $client = curl_init(self::getOptions()->getHost());
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' .self::getOptions()->getApiKey()
        ];
        curl_setopt_array($client, [
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT_MS => self::getOptions()->getConnectionTimeout(),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => 'Caliper (PHP curl extension)',
            CURLOPT_HEADER => true, // CURLOPT_HEADER required to return response text
            CURLOPT_RETURNTRANSFER => true, // CURLOPT_RETURNTRANSFER required to return response text
            CURLOPT_POSTFIELDS => $envelopeJson,
        ]);

        $responseText = curl_exec($client);
        $responseInfo = curl_getinfo($client);
        curl_close($client);
        $responseCode = $responseText ? $responseInfo['http_code'] : null;
        if ($responseCode != 200) {
            \CakeLog::write('error', 'Caliper Emit Error: '. $responseCode);
            \CakeLog::write('error', 'Caliper Envelope JSON: '. $envelopeJson);
            if ( self::$isTest ) {
                throw new \Exception('Caliper Emit Error: '. $responseCode .'\n'.$envelopeJson);
            }
        }
        return true;
    }

    public static function iso8601_duration($seconds) {
        if (!is_integer($seconds)) {
            throw new \InvalidArgumentException(__METHOD__ . ': integer expected');
        }

        $hours = floor($seconds / 3600);
        $seconds = $seconds % 3600;
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;

        $output = 'PT';
        if ($hours > 0) {
            $output .= $hours . "H";
        }
        if ($hours > 0 || $minutes > 0) {
            $output .= $minutes."M";
        }
        $output .= $seconds ."S";

        return $output;
    }
}
