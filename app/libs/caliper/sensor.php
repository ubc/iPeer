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
        $curlError = curl_error($client);
        curl_close($client);
        $responseCode = $responseText ? $responseInfo['http_code'] : null;
        if ($responseCode < 200 || $responseCode >= 300) {
            $error = 'Caliper Emit Error: '. ($responseCode ? $responseCode : 'no response ('. $curlError .')')
                . (self::isTransientError($responseCode) ? ' (transient)' : ' (rejected by LRS, check API key or payload)');
            \CakeLog::write('error', $error);
            if ( self::$isTest ) {
                throw new \Exception($error .'\n'.$envelopeJson);
            }
            // let the job queue retry; after max attempts the job is kept as failed
            // (failed_at set) and can be replayed by clearing failed_at/attempts.
            // A summary of the envelope is logged by SendCaliperEventsJob when the
            // last attempt fails here (not when the worker marks a job failed for
            // an abandoned lock; that only sets the job's error).
            $exception = new \DJRetryException($error);
            $exception->setDelay(self::retryDelay($responseCode));
            throw $exception;
        }
        return true;
    }

    /**
     * Seconds to wait before retrying a failed send
     *
     * @param int|null $responseCode HTTP response code, null if there was no response
     */
    public static function retryDelay($responseCode) {
        return self::isTransientError($responseCode) ? 1800 : 3600;
    }

    /**
     * Whether a failed send is likely to succeed later without changes
     * (outage, timeout, rate limit) rather than rejected (bad API key, payload, WAF)
     *
     * @param int|null $responseCode HTTP response code, null if there was no response
     */
    public static function isTransientError($responseCode) {
        return is_null($responseCode) || $responseCode == 408 || $responseCode == 429 || $responseCode >= 500;
    }

    /**
     * Summarizes an envelope for logging: ids, types and times only, so no
     * actor or other user data (names, URLs with query strings) ends up in logs
     *
     * @param string $envelopeJson
     * @return string JSON
     */
    public static function summarizeEnvelope($envelopeJson) {
        $envelope = json_decode($envelopeJson, true);
        $events = array();
        foreach ((array) ($envelope['data'] ?? array()) as $event) {
            $object = $event['object'] ?? null;
            $events[] = array(
                'id' => $event['id'] ?? null,
                'type' => $event['type'] ?? null,
                'action' => $event['action'] ?? null,
                'eventTime' => $event['eventTime'] ?? null,
                'objectType' => is_array($object) ? ($object['type'] ?? null) : null,
            );
        }
        return json_encode(array(
            'sendTime' => $envelope['sendTime'] ?? null,
            'events' => $events,
        ));
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
