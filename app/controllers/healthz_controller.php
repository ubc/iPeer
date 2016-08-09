<?php

/**
 * Health Check
 *
 * To generate a HTTP 200 response to indicate server is up and running.
 * Could return more info in the future
 */
class HealthzController extends Controller {
    var $uses = array();

    public function index() {
        $this->autoRender = false;
        return "OK";
    }
}
