<?php

class AjaxListComponent extends Object {

    // Will hold a reference to the controller
    var $controller = null;
    var $controllerName = null;
    var $webroot = null;

    var $listName = null;
    var $sessionVariableName = null;

    var $model = null;
    var $columns = null;
    var $actions = null;
    var $joinFilters = null;

    var $fields = null;

    var $customModelFindFunction = null;
    var $customModelCountFunction = null;
    var $recursive = 0;

    // Initialize other componenets
    var $components = array('Session');

    // On start up , just remember the contoller, so we can get it's name later
    function startup($controller) {
        $this->controller = $controller;
        $this->controllerName = strtolower($controller->name);
        $this->webroot = $controller->webroot;
        $this->sessionVariableName = $this->controllerName . "-ajaxListState";
    }

    // Constructs the state for this ajaxList
    function getState() {
        // Read the state out of the session
        $state = $this->Session->read($this->sessionVariableName);
        // Got it okay?
        if (!$state) {
            // No state is session? Well, set it up then!
            $state->sortBy = $this->sortBy;
            $state->sortAsc = true;
            $state->pageSize = 15;
            $state->pageShown = 1;
            $state->searchValue = "";
            $state->searchBy = $this->searchBy;
            $this->Session->write($this->sessionVariableName, $state);
        }
        // Return the session or constructed state
        return $state;
    }

    // Get data function for the ajaxList
    // Example call:
    //  $this->AjaxList->getListByState($state, $this->User, "User.id, User.username");
    // Remember:
    //  if you use custom functions, they must be take parameters just like:
    //  findAll   ($conditions, $fields, $order, $limit, $page, $recursive, $state, $joinTable, $groupBy);
    //  findCount ($conditions, $state);

    function getListByState()
    {
        // Get the session state, or create a new session state
        $state = $this->getState();

        // Set up sorting and pagination
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

        // Add in any join conditions

        if (!empty($state->joinFilterSelections)) {
            foreach ($state->joinFilterSelections as $filter => $value) {


                 // Find the joinFilter object relating to this one
                 $joinFilter = null;
                 foreach ($this->joinFilters as $index => $thisJoinFilter) {
                    if ($thisJoinFilter['id'] == $filter) {
                        $joinFilter = $thisJoinFilter;
                        break;
                    }
                 }

                // Make sure we found this filter
                 if ($joinFilter == null) {
                    continue;
                 }

                 // Check to make sure we should be considering this value at this time
                 if (!empty($joinFilter['dependsMap']) && !empty($joinFilter['dependsValues'])) {
                    if (!empty($state->mapFilterSelections)) {
                        if (!in_array($state->mapFilterSelections->$joinFilter['dependsMap'],
                            $joinFilter['dependsValues'])) {
                                // do not concider at this time
                            continue;
                        }
                    } else {
                        continue;
                    }
                 }

                 if (!empty($filter) && !empty($value)) {
                    //var_dump($filter . " - " . $value);
                    $conditions .= mysql_real_escape_string($filter) . "='" .
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

        // The default functions for searhing
        $customModelFindFunction = "findAll";

        // Put in the custom functions is they were suppplied
        if (!empty($this->customModelFindFunction)) {
            $customModelFindFunction = $this->customModelFindFunction;
        }
        if (!empty($this->customModelCountFunction)) {
            $customModelCountFunction = $this->customModelCountFunction;
        }

        // Figure out the table joins, if any
        $joinTable = "";
        if (!empty($this->joinFilters)) {
            for ($i = 0; $i < sizeof($this->joinFilters); $i++) {
                $joinFilter = $this->joinFilters[$i];
                $joinTable .= " LEFT JOIN " . $joinFilter['joinTable'] . " as " . $joinFilter['joinModel'];
                $joinTable .= " on " . $this->model->name . ".id" . "=" . $joinFilter['joinModel'] . "." . $joinFilter['foreignKey'];
            }
        }

        // Get the group By // we always group by this models's id, to make
        // sure there is only 1 result per entry even when using inner join.
        $groupBy = " GROUP by " . $this->model->name . ".id";

        // Do the database quiries to return the data
        // Group by needs to be "hacked" onto the conditions, since Cake php 1.1 has no direct support
        //  for it. However, in findCound, the group by must be absent.
        $data = $this->model->$customModelFindFunction($conditions . $groupBy,
                         $this->fields, $order, $limit, $page, $this->recursive, array($joinTable));

        // Counts a a bit more difficult with grouped, joint tables.
        if (isset($customModelCountFunction)) {
            $count = $this->model->$customModelCountFunction
                ($conditions, $groupBy, $this->recursive, array($joinTable));
        } else {
            $count = $this->betterCount
                ($conditions, $groupBy, $this->recursive, array($joinTable));

        }

        // Package up the list, and return it to called
        $result = array ("entries" =>   $data,
                         "count"   =>   $count,
                         "state" =>     $state,
                         "timeStamp" => time());

        return $result;
    }


    // Find all can't handle joint talbes that and GROUP By well,
    //  so this custom function is its replacement
    function betterCount ($conditions, $groupBy, $recursive, $joinTable) {

        /*
        select count(*) from (select count(*) FROM `users` AS `User`
        LEFT JOIN user_enrols as UserEnrol on User.id=UserEnrol.user_id WHERE 1=1
        GROUP by `User`.`id` ORDER BY `User`.`id`) as a
        */

        // Initial Statement
        $sql  = "SELECT count(*) as count FROM ( SELECT count(*) FROM " . $this->model->table . " as ";
        $sql .= $this->model->name . " ";

        // Add table joins
        for ($i = 0; $i < sizeof($joinTable); $i++) {
            $sql .= $joinTable[$i] . " ";
        }

        // add on conditions
        $sql .= "WHERE " . $conditions;

        // add on the group statement
        $sql .= $groupBy;

        // And finish up the statement

        $sql .= " ) as aTableForCount";

        list($data) = $this->model->findBySql($sql);

        return isset($data[0]['count']) ? $data[0]['count'] : 0;
    }


    // Returns the search results to JSON, or redirects to the list again, that will be rendered with the
    //  new state
    function asyncGet($pageForRedirect = "index") {

        // Grab the next state the browser sent over, and save it
        $state = json_decode($_POST['json']);

        // If not state was sent: fetch it from session.
        if ($state == null) {
            $state = $this->getState();
        } else {
            // Or if state was send, save it to session
            $this->Session->write($this->sessionVariableName, $state);
        }

        // Get the user data for the state.
        $listData = $this->getListByState();

        // Return a different page, depending if we were asked for full page
        //   or just the data.
        if ($_POST['fullPage'] == "true") {
            // Render the index page for a full page  (for ie 6)
            $this->controller->redirect($this->controllerName . "/" . $pageForRedirect);
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

    function setUp ($model, $columns, $actions, $joinFilters, $sortBy, $searchBy,
                    $listName = null,
                    $recursive = 0,
                    $customModelFindFunction = null,
                    $customModelCountFunction = null)
    {
        // Set up the basics
        $this->model = $model;
        $this->columns = $columns;
        $this->actions = $actions;
        $this->joinFilters = $joinFilters;
        $this->sortBy = $sortBy;
        $this->searchBy = $searchBy;

        // Setup up the custom variables
        $this->customModelFindFunction = $customModelFindFunction;
        $this->customModelCountFunction = $customModelCountFunction;

        // Generate the fields
        $this->fields = array();
        for ($i = 0; $i < sizeof($this->columns); $i++) {
            array_push($this->fields, $columns[$i][0]);
        }


        // If this list has a name, name it!
        if (!empty($listName))  {
            $this->listName = $listName;
            $this->sessionVariableName = $this->controllerName . "-ajaxListState" . "-" . $listName;
        }
    }


    function getParamsForList() {
                // Collect the parameters
        return array(
            "webroot"     => $this->webroot,
            "controller"  => $this->controllerName,
            "columns"     => $this->columns,
            "actions"     => $this->actions,
            "joinFilters" => $this->joinFilters,
            "data"        => $this->getListByState());
    }

}



?>