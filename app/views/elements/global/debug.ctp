<!-- Debugging output -->
<?php 
// only show this section if we have debug on and we're in a controller
if(!Configure::read('debug') == 0 &&
  isset($this->params['controller'])) 
{ 
?>

<div id='debugsection'>
  <!-- Render a few debuging elements -->
  <table>
  <tr>
    <td>User ID: <?php echo User::isLoggedIn() ? User::get('id') : "none" ?>
      <input type="button" onclick="$('#user-data').toggle();" value="show/hide" />
    </td>
    <td>Courses: <?php echo empty($coursesList) ? 0 : count($coursesList); ?>
      <input type="button" onclick="$('#coursesList-data').toggle();" value="show/hide" />
    </td>
    <td>Actions: <?php echo empty($action) ? 0 : count($action); ?>
      <input type="button" onclick="$('#access-data').toggle();" value="show/hide" />
    </td>
    <td>Access: <?php echo empty($access) ? 0 : count($access); ?>
      <input type="button" onclick="$('#actions-data').toggle();" value="show/hide" />
    </td>
    <td>Params: <?php echo empty($this->params) ? 0 : count($this->params); ?>
      <input type="button" onclick="$('#params-data').toggle();" value="show/hide" />
    </td>
    <td>SQL Log:
      <input type="button" onclick="$('#SQL-data').toggle();" value="show/hide" />
    </td>
    <td>Allowed By: 
      <input type="button" onclick="$('#allowedBy-data').toggle();" value="show/hide" />
    </td>
  </tr>
  </table>

  <!-- The actual debugging data -->
  <h5>$user variable</h5>
  <pre style="background-color:#E9FFFF;" id="user-data" >
<?php 
    User::isLoggedIn() ?
      print_r(User::getInstance()) :
      print_r(__("(Empty)", true));
    ?>
  </pre>

  <h5>$coursesList variable</h5>
  <pre style="background-color:#FFE9FF;" id="coursesList-data" >
<?php 
    empty($courseList) ?
      print_r(__("(Empty)", true)) :
      print_r($courseList);
    ?>
  </pre>

  <h5>$actions variable</h5>
  <pre style="background-color:#FFFFE9;" id="actions-data" >
<?php 
    empty($action) ?
      print_r(__("(Empty)", true)) :
      print_r($action);
    ?>
  </pre>

  <h5>$access variable</h5>
  <pre style="background-color:#E9FFFF;" id="access-data" >
<?php 
    empty($access) ?
      print_r(__("(Empty)", true)) :
      print_r($access);
    ?>
  </pre>

  <h5>$this->params variable</h5>
  <pre style="background-color:#FFE9FF;" id="params-data" >
<?php 
    empty($this->params) ?
      print_r(__("(Empty)", true)) :
      print_r($this->params);
    ?>
  </pre>

  <h5>SQL data</h5>
  <div style="background-color: #FFFFE9;" id="SQL-data" >
    <?php
    if ($this->params['controller'] != 'install')
    {
      $dataSource = ConnectionManager::getDataSource('default');
      empty($dataSource) ?
        print_r(__("(No SQL Data)", true)) :
        $dataSource->showLog();
    }
    else
    {
      print_r(__("(No SQL Data)", true));
    }
    ?>
  </div>

  <h5>$allowedBy variable</h5>
  <pre style="background-color: #E9FFFF;" id="allowedBy-data" >
<?php 
    empty($allowedBy) ?
      print_r(__("(Empty)", true)) :
      print_r($allowedBy);
    ?>
  </pre>
</div>
<?php 
} // end if(!constant('DEBUG') == 0) 
?>

