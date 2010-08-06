<?php

class AjaxListComponent extends Object {

    // Will hold a reference to the controller
    var $controllerName = null;

    var $listName = null;
    var $sessionVariableName = null;
    var $model = null;
    var $fields = null;
    var $customModelFindFunction = null;
    var $customModelCountFunction = null;
    var $recursive = 0;

    // Initialize other componenets
    var $components = array('Session');

    // On start up , just remember the contoller, so we can get it's name later
    function startup($controller) {
        $this->controllerName = $controller->name;
        $this->sessionVariableName = $this->controllerName . "-ajaxListState";
    }

    // Constructs the state for this ajaxList
    function getState() {
        $state = $this->Session->read($this->sessionVariableName);
        if (!$state) {
            $state->sortBy = $this->sortBy;
            $state->sortAsc = true;
            $state->pageSize = 15;
            $state->pageShown = 1;
            $state->searchValue = "";
            $state->searchBy = $this->searchBy;
            $this->Session->write($this->sessionVariableName, $state);
        }

        return $state;
    }

    // Get data function for the ajaxList
    // Example call:
    //  $this->AjaxList->getListByState($state, $this->User, "User.id, User.username");
    // Remember:
    //  if you use custom functions, they must be take parameters just like:
    //  findAll   ($conditions, $fields, $order, $limit, $page, $recursive);
    //  findCount ($conditions);

    function getListByState()
    {
        $state = $this->getState();

        $order = $state->sortBy . " " .  ($state->sortAsc ? "ASC" : "DESC");
        $limit = $state->pageSize;
        $page = $state->pageShown;


        // Ensure that search objects are strings
        if (!is_string($state->sortBy)) {
            $state->sortBy = "";
        }
        if (!is_string($state->searchValue)) {
            $state->searchValue = "";
        }

        // Start with no conditions
        $conditions = "";

        // Add in the map filter conditions
        if (!empty($state->mapFilterSelections)) {
            foreach ($state->mapFilterSelections as $column => $value) {
                if (!empty($column) && !empty($value)) {
                    $conditions .=  mysql_real_escape_string($column) . "='" .
                                    mysql_real_escape_string($value) . "' and ";
                }
            }
        }

        // Add in the search conditions
        if (!empty($state->searchBy) && !empty($state->searchValue)) {
            $conditions .= mysql_real_escape_string($state->searchBy) .
                " like '%" .  mysql_real_escape_string($state->searchValue) . "%'";
        } else {
            // Because the last statement appended an "and" we need to specify a dummy condition
            $conditions .= " 1=1 ";
        }

        $customModelFindFunction = "findAll";
        $customModelCountFunction = "findCount";
        // Put in the default count and find functions
        if (!empty($this->customModelFindFunction)) {
            $customModelFindFunction = $this->customModelFindFunction;
        }

        if (!empty($this->customModelCountFunction)) {
            $customModelCountFunction = $this->customModelCountFunction;
        }

        $data = $this->model->$customModelFindFunction($conditions,
                $this->fields, $order, $limit, $page, $this->recursive);
        $count =$this->model->$customModelCountFunction($conditions);

        $result = array ("count"   =>   $count,
                         "entries" =>   $data,
                         "state" =>     $state,
                         "timeStamp" => time());

        return $result;
    }


    // Returns the search results to JSON, or redirects to the list again, that will be rendered with the
    //  new state
    function asyncGet($pageForRedirect = "index") {

        // Grab the next state the browser sent over, and save it
        $state = json_decode($_POST['json']);
        $this->Session->write($this->sessionVariableName, $state);

        $listData = $this->getListByState();

        if ($_POST['fullPage'] == "true") {
            // Render the index page for a full page  (for ie 6)
            $this->redirect($this->controllerName . "/" . $pageForRedirect);

        } else {
            // Send the reponse in JSON only ( for non-ie 6)
            // Don't cache these, please, from : http://php.net/manual/en/function.header.php
            header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
            header("expires: 0"); // HTTP/1.1
            header("Content-type: application/json");
            echo json_encode($listData); // Send it away!!
            exit;  // Nothing more to do.
        }
    }



    function setUp ($model, $fields, $sortBy, $searchBy,
                    $listName = null,
                    $recursive = 0,
                    $customModelFindFunction = null,
                    $customModelCountFunction = null)
    {
        $this->model = $model;
        $this->fields = $fields;
        $this->sortBy = $sortBy;
        $this->searchBy = $searchBy;
        $this->customModelFindFunction = $customModelFindFunction;
        $this->customModelCountFunction = $customModelCountFunction;


        // If this list has a name, name it!
        if (!empty($listName))  {
            $this->listName = $listName;
            $this->sessionVariableName = $this->controllerName . "-ajaxListState" . "-" . $listName;
        }
    }

}



?>