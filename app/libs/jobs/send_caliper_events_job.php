<?php
App::import('Lib', 'CakeDjjob.cake_job', array(
    'file' => 'cake_djjob' . DS . 'libs' . DS . 'jobs' . DS . 'cake_job.php',
));
App::import('Lib', 'caliper');
use caliper\CaliperSensor;

class SendCaliperEventsJob extends CakeJob {

    var $envelopeJson = null;
    function __construct($envelopeJson) {
        $this->envelopeJson = $envelopeJson;
    }

    function perform() {
        $this->out('Start sending Caliper envelope');
        $success = CaliperSensor::_sendEvent($this->envelopeJson);
        if ($success) {
            $this->out('Finished sending Caliper envelope');
        } else {
            $this->out("Issue sending Caliper envelope:\n".var_export($this->envelopeJson, TRUE));
        }
    }

}