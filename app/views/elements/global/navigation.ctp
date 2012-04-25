<?php
// TODO temporary solution until access control is worked out, we're 
// hardcoding what is accessible by different roles

/**
 * Creates a user interface tab:
 *
 * @param $object - should be set to $this to access the view object
 * @param $url - where the tab should go to when clicked on
 * @param $activeControllers - array containing all the controllers for which 
 * this tab will be highlighted as active.
 */

function generateTab ($view, $url, $activeControllers, $name) {
  $url = $view->webroot . $view->theme . $url;
  // Generate the HTML
  $current = in_array($view->params['controller'], $activeControllers) ? 
    "id='current'" : "";
  echo "<li $current><a href='$url'>$name</a></li>";
}

if (User::isLoggedIn()) {
  echo "<div id='navigationOuter' class='navigation'><ul>";

  //Home Tab
  generateTab($this, 'home', array('home'), 'Home');

  if ($issuperadmin || $isadmin || $isinstructor) {
    // Course Tab
    generateTab(
      $this, 
      'courses', 
      array('courses', 'events', 'groups', 'evaluations', 'surveygroups'),
      'Courses'
    );

    // Users Tab
    generateTab($this, 'users' , array('users'), 'Users');

    //Evaluation Tools Tab
    generateTab($this, 'evaltools',
      array(
        'evaltools', 'simpleevaluations', 'rubrics', 
        'surveys', 'mixevals', 'emailer', 'emailtemplates'
      ),
      'Evaluation'
    );

    // Advanced Search Tab
    generateTab($this, 'searchs', array('searchs'), 'Search');
  }

  if ($issuperadmin) {
    // System Parameters Tab
    generateTab($this, 'sysparameters', array('sysparameters'), 
      'Sys Parameters');
    // System Functions Tab
    generateTab($this, 'sysfunctions', array('sysfunctions'), 'Sys Functions');
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

