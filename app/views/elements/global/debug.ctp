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
  // echo prevent the use of the short form conditional operator for some reason
  if (User::isLoggedIn())
  { // escape html so they're not interpreted by the browser
    echo htmlspecialchars(print_r(User::getInstance(), true));
  }
  else
  {
    print_r(__("(Empty)", true));
  }
    ?>
  </pre>

  <h5>$coursesList variable</h5>
  <pre style="background-color:#FFE9FF;" id="coursesList-data" >
<?php 
    if (empty($courseList))
    {
      print_r(__("(Empty)", true));
    }
    else
    {
      echo htmlspecialchars(print_r($courseList, true));
    }
    ?>
  </pre>

  <h5>$actions variable</h5>
  <pre style="background-color:#FFFFE9;" id="actions-data" >
<?php 
    if (empty($action))
    {
      print_r(__("(Empty)", true));
    }
    else
    {
      echo htmlspecialchars(print_r($action, true));
    }
    ?>
  </pre>

  <h5>$access variable</h5>
  <pre style="background-color:#E9FFFF;" id="access-data" >
<?php 
    if (empty($access))
    {
      print_r(__("(Empty)", true));
    }
    else
    {
      echo htmlspecialchars(print_r($access, true));
    }
    ?>
  </pre>

  <h5>$this->params variable</h5>
  <pre style="background-color:#FFE9FF;" id="params-data" >
<?php 
    if (empty($this->params))
    {
      print_r(__("(Empty)", true));
    }
    else
    {
      echo htmlspecialchars(print_r($this->params, true));
    }
    ?>
  </pre>

  <h5>SQL data</h5>
  <div style="background-color: #FFFFE9;" id="SQL-data" >
    <?php
    if ($this->params['controller'] != 'install')
    {
      $dataSource = ConnectionManager::getDataSource('default');
      if (empty($dataSource))
      {
        print_r(__("(No SQL Data)", true));
      }
      else
      {
        $dataSource->showLog();
      }
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
    if (empty($allowedBy))
    {
      print_r(__("(Empty)", true));
    }
    else
    {
      echo htmlspecialchars(print_r($allowedBy, true));
    }
    ?>
  </pre>
</div>
<?php 
} // end if(!constant('DEBUG') == 0) 
?>

