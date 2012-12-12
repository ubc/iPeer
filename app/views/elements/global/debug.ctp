<!-- Debugging output -->
<?php
// only show this section if we have debug on and we're in a controller
if(!Configure::read('debug') == 0 &&
  isset($this->params['controller']))
{
    $branch = `git rev-parse --abbrev-ref HEAD`;
    if (!$branch) {
        $branch = "Unknown";
    }
    $commit = `git describe --tags --always`;
    if (!$commit) {
        $commit = "Unknown";
    }
    $hasUserModel = class_exists('User');
?>

<div id='debugsection'>
  <!-- Render a few debuging elements -->
  <div style="text-align:center;">Version: <?php echo IPEER_VERSION?> - Branch: <?php echo $branch?> - Commit/Tag: <?php echo $commit;?></div>
  <table>
  <tr>
    <td>
        <a href="#user-data">User ID: 
        <?php 
            echo $hasUserModel && User::isLoggedIn() ? User::get('id') : "none";
        ?>
        </a>
      <input type="button" onclick="jQuery('#user-data').toggle();" value="show/hide" />
    </td>
    <td><a href="#role-data">
        Role: <?php echo $hasUserModel ? count(User::getRoleArray()) : "no user"; ?>
        </a>
        <input type="button" onclick="jQuery('#role-data').toggle();" value="show/hide" />
    </td>
    <td><a href="#permission-data">
Permission: <?php echo $hasUserModel ? count(User::getPermissions()) : "no user"; ?></a>
      <input type="button" onclick="jQuery('#permission-data').toggle();" value="show/hide" />
    </td>
    <td><a href="#actions-data">Actions: <?php echo empty($action) ? 0 : count($action); ?></a>
      <input type="button" onclick="jQuery('#actions-data').toggle();" value="show/hide" />
    </td>
    <td><a href="#params-data">Params: <?php echo empty($this->params) ? 0 : count($this->params); ?></a>
      <input type="button" onclick="jQuery('#params-data').toggle();" value="show/hide" />
    </td>
    <td><a href="#SQL-data">SQL Log:</a>
      <input type="button" onclick="jQuery('#SQL-data').toggle();" value="show/hide" />
    </td>
    <td><a href="#email-data">Email:</a>
      <input type="button" onclick="jQuery('#email-data').toggle();" value="show/hide" />
    </td>
  </tr>
  </table>

  <!-- The actual debugging data -->
  <h5>$user variable</h5>
  <pre style="background-color:#E9FFFF;" id="user-data" >
<?php
  // echo prevent the use of the short form conditional operator for some reason
  if ($hasUserModel && User::isLoggedIn())
  { // escape html so they're not interpreted by the browser
    echo htmlspecialchars(print_r(User::getInstance(), true));
  }
  else
  {
    print_r(__("(Empty)", true));
  }
    ?>
  </pre>

  <h5>Roles</h5>
  <pre style="background-color:#E9FFFF;" id="role-data" >
<?php
  // echo prevent the use of the short form conditional operator for some reason
  if ($hasUserModel && User::isLoggedIn()) {
      // escape html so they're not interpreted by the browser
      echo htmlspecialchars(print_r(User::getRoleArray(), true));
  } else {
      print_r(__("(Empty)", true));
  }
    ?>
  </pre>

  <h5>Permissions</h5>
  <pre style="background-color:#E9FFFF;display: none;" id="permission-data" >
<?php
    $perms = $hasUserModel ? User::getPermissions() : array();
    if (empty($perms)) {
      print_r(__("(Empty)", true));
    } else {
      echo htmlspecialchars(print_r($perms, true));
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

  <h5>$email variable</h5>
  <pre style="background-color: #E9FFFF;" id="email-data" >
  <?php echo $this->Session->flash('email'); ?>
  </pre>
</div>
<?php
} // end if(!constant('DEBUG') == 0)
?>

