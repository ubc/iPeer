<?php
/**
 * Creates a user interface tab:
 *      $object and $access should always be set at $this and $access respectively.
 *      $accessVar is the key inside the sys_function function list.
 *      $activeControllers is an aeeay containg all the controllers for which this tab will be
 *        highlighted as active.
 *      $prefix and $postfix will be shown if passesed. Can be text or HTML.
 */
function generateTab (
  $object, $access, $accessVar, 
  $activeControllers, $name='') 
{
  if (!empty($access[$accessVar])) {
    // Get the variables
    $sysFunc = $access[$accessVar];
    if (empty($name))
    {
      $name = $sysFunc['function_name'];
    }
    $url = $object->webroot . $object->theme . $sysFunc['url_link'];
    // Generate the HTML
    $current = in_array($object->params['controller'], $activeControllers) ? "id='current'" : "";
    echo "<li $current><a href='$url'>$name</a></li>";
  }
}

?>

<?php
if (User::isLoggedIn())
{
  echo "<div id='navigationOuter' class='navigation'><ul>";
  if (!empty($access)) {
      //Home Tab
      generateTab($this, $access, 'HOME', array('home'));
      // Course Tab
      generateTab($this, $access, 'COURSE', 
        array('courses', 'events', 'groups', 'evaluations', 'surveygroups')
      );
      // Users Tab
      $isStudent = (User::get('role') == 'S');
      if (!$isStudent) {
        generateTab($this, $access, 'USERS' , array('users'));
      }
      //Evaluation Tools Tab
      generateTab($this, $access, 'EVAL_TOOL',
        array(
          'evaltools', 'simpleevaluations', 'rubrics', 
          'surveys', 'mixevals', 'emailer', 'emailtemplates'
        ),
        'Evaluation'
      );
      // Advanced Search Tab
      generateTab($this, $access, 'ADV_SEARCH', array('searchs'), 'Search');
      // System Parameters Tab
      generateTab($this, $access, 'SYS_PARA',  array('sysparameters'));
      // System Functions Tab
      generateTab($this, $access, 'SYS_FUNC', array('sysfunctions'));
  }

  echo "<li>";
  echo $this->Html->link(
    'Logout', 
    Router::url('/logout', true), 
    array('class' => 'miniLinks floatright')
  );
  echo "</li>";

  echo "<li>";
  echo $this->Html->link(
    User::get('full_name'), 
    '/users/editProfile', 
    array('class' => 'miniLinks floatright')
  );
  echo "</li>";

  echo "</ul>";
  echo "</div>";
}
?>

