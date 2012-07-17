<?php
class V1Controller extends Controller {
    public $uses = array();
    public $layout = "blank_layout";
    
    public function users() {
        $this->set('test', 'test');
    }
}
