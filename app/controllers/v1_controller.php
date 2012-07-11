<?php
class V1Controller extends Controller {
    public $uses = array();
    
    public function users() {
        $this->set('test', 'test');
    }
}
