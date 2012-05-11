<?php
/**
 * Creates a user interface tab:
 *
 * @param $view              should be set to $this to access the view object
 * @param $url               where the tab should go to when clicked on
 * @param $activeControllers array containing all the controllers for which
 * @param $name              name
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

    if (User::hasPermission('controllers/courses')) {
        // Course Tab
        generateTab(
            $this,
            'courses',
            array('courses', 'events', 'groups', 'evaluations', 'surveygroups'),
            'Courses'
        );
    }

    // Users Tab
    if (User::hasPermission('controllers/users')) {
        generateTab($this, 'users', array('users'), 'Users');
    }

    //Evaluation Tools Tab
    if (User::hasPermission('controllers/evaltools')) {
        generateTab($this, 'evaltools',
            array(
                'evaltools', 'simpleevaluations', 'rubrics',
                'surveys', 'mixevals', 'emailer', 'emailtemplates'
            ),
            'Evaluation'
        );
    }

    // Advanced Search Tab
    if (User::hasPermission('controllers/searchs')) {
        generateTab($this, 'searchs', array('searchs'), 'Search');
    }

    // System Parameters Tab
    if (User::hasPermission('controllers/sysparameters')) {
        generateTab($this, 'sysparameters',
            array('sysparameters'),
            'Sys Parameters');
    }
    // System Functions Tab
    if (User::hasPermission('controllers/sysfunctions')) {
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

