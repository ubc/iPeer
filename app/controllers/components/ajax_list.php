<?php

// We use json encode and json decode here...
// Make sure they're defined :-)

// This code will substitute in json_encode and json_decode implemented in PHP.
// the problem with these functions is that they're slower than PHP 5.2's c implementationss
if (!function_exists('json_decode') ) {
    /**
     * json_decode
     *
     * @param mixed $content content
     * @param bool  $assoc   assoc
     *
     * @access public
     * @return void
     */
    function json_decode($content, $assoc=false)
    {
        require_once 'vendors/JSON.php';
        if ($assoc ) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }

}

if (!function_exists('json_encode') ) {
    /**
     * json_encode
     *
     * @param mixed $content
     *
     * @access public
     * @return void
     */
    function json_encode($content)
    {
        require_once 'vendors/JSON.php';
        $json = new Services_JSON;
        return $json->encode($content);
    }

}

/**
 * AjaxListComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class AjaxListComponent extends CakeObject
{

    // Will hold a reference to the controller
    public $controller = null;
    public $controllerName = null;
    public $webroot = null;

    public $listName = null;
    public $sessionVariableName = null;

    public $model = null;
    public $columns = null;
    public $actions = null;
    public $joinFilters = null;
    public $extraFilters = null;

    public $fields = null;

    public $customModelFindFunction = null;
    public $customModelCountFunction = null;
    public $recursive = 0;
    public $postProcessFunction = null;

    public $conditions = array();

    // Initialize other componenets
    public $components = array('Session', 'Output');

    /**
     * startup
     * On start up , just remember the contoller, so we can get it's name later
     *
     * @param mixed $controller
     *
     * @access public
     * @return void
     */
    function startup($controller)
    {
        $this->controller = $controller;
        $this->controllerName = strtolower($controller->name);
        $this->webroot = $controller->webroot;
        $this->sessionVariableName = $this->controllerName . "-ajaxListState";
    }


    /**
     * getState
     * Constructs the state for this ajaxList
     *
     *
     * @access public
     * @return void
     */
    function getState()
    {
        // Read the state out of the session
        $state = $this->Session->read($this->sessionVariableName);
        // Got it okay?
        if (!$state) {
            // No state is session? Well, set it up then!
            $state = new CakeObject();
            $state->sortBy = $this->sortBy;
            $state->sortAsc = true;
            $state->pageSize = 20;
            $state->pageShown = 1;
            $state->searchValue = "";
            $state->searchBy = $this->searchBy;
            $this->Session->write($this->sessionVariableName, $state);
        }
        // Return the session or constructed state

        return $state;
    }


    /**
     * checkState
     * Do a quick state check to fix any error inside
     *
     * @param mixed $state
     *
     * @access public
     * @return void
     */
    function checkState($state)
    {
        if (empty($state->sortBy)) {
            $state->sortBy = $this->sortBy;
        }
        if (empty($state->searchBy)) {
            $state->searchBy = $this->searchBy;
        }

        // Ensure that search objects are strings
        if (!is_string($state->sortBy)) {
            $state->sortBy = "";
        }
        if (!is_string($state->searchValue)) {
            $state->searchValue = "";
        }

        return $state;
    }

    /**
     * formatDates
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function formatDates($data)
    {
        // Process data if it's there
        if (!empty($data) && is_array($data)) {
            // For each column defined
            foreach ($this->columns as $column) {
                // Is this column maked as a date?
                if ($column[3] == "date") {
                    $split = explode(".", $column[0], 2);
                    $model = $split[0];
                    $col = $split[1];
                    foreach ($data as $key => $entry) {
                        $date = strtotime($entry[$model][$col]);
                        $date = Toolkit::formatDate($date);
                        $data[$key][$model][$col] = $date;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * isSpecialValue
     * Special values start with a '!'
     *
     * @param mixed $value
     *
     * @access public
     * @return void
     */
    function isSpecialValue($value)
    {
        return strlen($value)>3  &&  $value[0]=='!'  &&  $value[1]=='!'  &&  $value[2]=='!';
    }



    /**
     * getListByState
     * Get data function for the ajaxList
     * Example call:
     *  $this->AjaxList->getListByState($state, $this->User, "User.id, User.username");
     * Remember:
     *  if you use custom functions, they must be take parameters just like:
     *
     *
     * @access public
     * @return void
     */
    function getListByState()
    {
        // Get the session state, or create a new session state
        $state = $this->checkState($this->getState());

        // Set up sorting and pagination
        $order = $state->sortBy . " " .  ($state->sortAsc ? "ASC" : "DESC");
        $limit = $state->pageSize;
        $page = $state->pageShown;

        // Start with no tables, and noconditions
        //$tables = "";  //unused
        $conditions = $this->conditions;

        // Add the main table

        // Add in the map filter conditions

        if (!empty($state->mapFilterSelections)) {
            foreach ($state->mapFilterSelections as $column => $value) {
                if (!empty($column) && null !== $value && "" != $value) {
                    $conditions[$column] = $value;
                }
            }
        }

        // Add in any join filter conditions
        if (!empty($state->joinFilterSelections)) {
            foreach ($state->joinFilterSelections as $filter => $value) {
                // Find the joinFilter object relating to this one
                $joinFilter = null;
                foreach ($this->joinFilters as $thisJoinFilter) {
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
                    // Keywords starting with !!! are a special case
                    if (!$this->isSpecialValue($value)) {
                        $conditions[$filter] = $value;
                    } else {
                        // note: no quotes around special value
                        $conditions[$filter] = substr($value, 3);
                    }
                }
            }
        }

        // Add in any extra Filters - by array or by string.
        if (!empty($this->extraFilters)) {
            if (is_array($this->extraFilters)) {
                /*foreach ($this->extraFilters as $column => $value) {
                    $conditions[$column] = $value;
                }*/
                $conditions = array_merge($conditions, $this->extraFilters);
            } else if (is_string($this->extraFilters)) {
                $conditions[] = $this->extraFilters;
            }
        }

        // Add in the search conditions
        if (!empty($state->searchBy) && !empty($state->searchValue)) {
            $conditions[$state->searchBy . " LIKE"] = '%' .  $state->searchValue . '%';
        }

        // The default functions for searhing
        $customModelFindFunction = "find";

        // Put in the custom functions is they were suppplied
        if (!empty($this->customModelFindFunction)) {
            $customModelFindFunction = $this->customModelFindFunction;
        }
        if (!empty($this->customModelCountFunction)) {
            $customModelCountFunction = $this->customModelCountFunction;
        }

        // Figure out the table joins, if any
        $joinTable = "";
        // comment out by compass. use association and default join format
/*        if (!empty($this->joinFilters)) {
            for ($i = 0; $i < sizeof($this->joinFilters); $i++) {
                $joinFilter = $this->joinFilters[$i];
                if (!empty($joinFilter)) {
                    // Set up the default values
                    $localKey   = !empty($joinFilter['localKey'])   ? $joinFilter['localKey']   : "id";
                    $foreignKey = !empty($joinFilter['foreignKey']) ? $joinFilter['foreignKey'] : "id";
                    $localModel = !empty($joinFilter['localModel']) ? $joinFilter['localModel'] : $this->model->name;

                    $joinTable .= " LEFT JOIN `$joinFilter[joinTable]` as `$joinFilter[joinModel]`";
                    $joinTable .= " on `$localModel`.`$localKey`=`$joinFilter[joinModel]`.`$foreignKey`";
                }
            }
}*/



        // Get the group By // we always group by this models's id, to make
        // sure there is only 1 result per entry even when using inner join.
        $groupBy = array($this->model->name . ".id");

        // Do the database quiries to return the data
        // Group by needs to be "hacked" onto the conditions, since Cake php 1.1 has no direct support
        //  for it. However, in findCound, the group by must be absent.
        $data = $this->model->$customModelFindFunction('all', array('conditions' => $conditions,
            'group'      => $groupBy,
            'fields'     => $this->fields,
            'order'      => $order,
            'limit'      => $limit,
            'page'       => $page,
            'recursive'  => $this->recursive,
            'joins'      => array($joinTable)));

        // Counts a bit more difficult with grouped, joint tables.
        if (isset($customModelCountFunction)) {
            $count = $this->model->$customModelCountFunction
                ($conditions, $groupBy, $this->recursive, array($joinTable));
        } else {
            //$count = $this->betterCount
            //    ($conditions, $groupBy, array($joinTable));
            $count = $this->model->$customModelFindFunction('count', array('conditions' => $conditions,
            ));
        }

        // Format the dates as given in the iPeer database
        $data = $this->formatDates($data);

        // Post-process Data, if asked
        if (!empty($this->postProcessFunction)) {
            $function = $this->postProcessFunction;
            $data = $this->controller->$function($data);
        }

        // Package up the list, and return it to caller
        $result = array ("entries" =>   $data,
            "count"   =>   $count,
            "state" =>     $state,
            "timeStamp" => time());

        return $result;
    }



    /**
     * betterCount
     * Find all can't handle joint tables that and GROUP By well,
     *  so this custom function is its replacement
     *
     * @param mixed $conditions conditions
     * @param mixed $groupBy    group by
     * @param mixed $joinTable  join table
     *
     * @access public
     * @return void
     */
    function betterCount ($conditions, $groupBy, $joinTable)
    {

        $table = $this->model->table;
        $name = $this->model->name;

        // Initial Statement
        $sql  = "SELECT count(*) as count FROM ( SELECT count(*) FROM `$table` as `$name` ";

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

        list($data) = $this->model->query($sql);

        return isset($data[0]['count']) ? $data[0]['count'] : 0;
    }



    /**
     * asyncGet
     * Returns the search results to JSON, or redirects to the list again, that will be rendered with the
     * new state
     *
     * @param bool   $pageForRedirect page for redirect
     * @param string $parameters      parameters
     *
     * @access public
     * @return void
     */
    function asyncGet($pageForRedirect = "index", $parameters = "")
    {

        // remove the escape backslashs if magic quotes is OK. Otherwise,
        // json_decode will not work properly
        //$json = get_magic_quotes_gpc() ? stripslashes($_POST['json']) : $_POST['json'];  //unused
        // Grab the next state the browser sent over, and save it
        $state = json_decode(str_replace("\\", "", $_POST['json']));

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
            $redirect = $this->controllerName . "/" . $pageForRedirect .
                (!empty($parameters) ? ("/" . $parameters) : "");
            $this->controller->redirect($redirect);
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


    /**
     * setStateVariable
     * Set a state variable manually
     *
     * @param mixed $variableName variable name
     * @param mixed $value        value
     *
     * @access public
     * @return void
     */
    function setStateVariable($variableName, $value)
    {
        $state = $this->getState();
        $state->$variableName = $value;
        $this->Session->write($this->sessionVariableName, $state);
    }


    /**
     * clearState
     * Clears the state completelly.
     *
     * @access public
     * @return void
     */
    function clearState()
    {
        $this->Session->write($this->sessionVariableName, null);
    }


    /**
     * quickSetUp
     * Create a non-operational version of the stucture
     *
     * @access public
     * @return void
     */
    function quickSetUp()
    {
        $this->setUp(null, null, null, null, null);
    }


    /**
     * setUp
     * Sets up the basic info about the list
     *
     * @param mixed $model                    model
     * @param mixed $columns                  columns
     * @param mixed $actions                  actions
     * @param mixed $sortBy                   sortBy
     * @param mixed $searchBy                 searchBy
     * @param array $joinFilters              joinFilters
     * @param array $extraFilters             extra filters
     * @param int   $recursive                recursive
     * @param bool  $postProcess              post process
     * @param bool  $listName                 list name
     * @param bool  $customModelFindFunction  constom model find function
     * @param bool  $customModelCountFunction custom model count function
     * @param bool  $conditions               condition
     *
     * @access public
     * @return void
     */
    function setUp($model, $columns, $actions, $sortBy, $searchBy, $joinFilters = null, $extraFilters = null, $recursive = 0, $postProcess = null, $listName = null, $customModelFindFunction = null, $customModelCountFunction = null, $conditions = array())
    {
        // Set up the basics
        $this->model = $model;
        $this->columns = empty($columns) ? array() : $columns;
        $this->actions = $actions;
        $this->joinFilters = $joinFilters;
        $this->extraFilters = $extraFilters;
        $this->sortBy = $sortBy;
        $this->searchBy = $searchBy;
        $this->recursive = $recursive;
        $this->postProcessFunction = $postProcess;

        // Setup up the custom variables
        $this->customModelFindFunction = $customModelFindFunction;
        $this->customModelCountFunction = $customModelCountFunction;

        $this->conditions = $conditions;

        // Generate the fields
        $this->fields = array();
        for ($i = 0; $i < sizeof($this->columns); $i++) {
            $columnName = $this->columns[$i][0];
            // Leave out emptry and "!"-flagged colums out of queries
            if (!empty($columnName) && $columnName[0] != "!") {
                array_push($this->fields, $columnName);
            }
        }

        // If this list has a name, name it!
        if (!empty($listName)) {
            $this->listName = $listName;
            $this->sessionVariableName = $this->controllerName . "-ajaxListState" . "-" . $listName;
        }
    }



    /**
     * getParamsForList
     *
     * @access public
     * @return void
     */
    function getParamsForList()
    {
        // Collect the parameters
        return array(
            'webroot'     => $this->webroot,
            'controller'  => $this->controllerName,
            'columns'     => $this->columns,
            'actions'     => $this->actions,
            'joinFilters' => $this->joinFilters,
            'data'        => $this->getListByState()
        );
    }
}
