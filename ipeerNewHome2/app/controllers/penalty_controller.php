<?php

class PenaltyController extends AppController
{
  var $name = 'Penalty';
  
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $Sanitize;
  
  function __construct(){    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy . ' ' . strtoupper($this->direction);
    $this->pageTitle = 'Penalty';
    parent::__construct();
  }
  
  function index() {
  	
  }
  
}
?>