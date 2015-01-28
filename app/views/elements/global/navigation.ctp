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
  // Generate the HTML
  $current = ($view->params['url']['url'] == $url) ?
    "id='current'" : "";
  $url = $view->webroot . $view->theme . $url;
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
    if (User::hasPermission('functions/user/index')) {
        generateTab($this, 'users', array('users'), 'Users');
    }

    //Evaluation Tools Tab
    // Combo condition: user has permission to view evaltools (system instructor)
    // OR user is instructor in at least one course (and system student/tutor otherwise)
    if (User::hasPermission('controllers/evaltools') || User::isInstructor()) {
        generateTab($this, 'evaltools',
            array(
                'evaltools', 'simpleevaluations', 'rubrics',
                'surveys', 'mixevals', 'emailer', 'emailtemplates'
            ),
            'Evaluation'
        );
    }

    // Advanced Search Tab
    /*if (User::hasPermission('controllers/searchs')) {
        generateTab($this, 'searchs', array('searchs'), 'Search');
    }*/

    // Admin Tab
    if (User::hasPermission('adminpage')) {
        generateTab($this, 'pages/admin',
            array('pages/admin'),
            'Admin');
    }

    echo "<li>";
    echo $this->Html->link(
        'Logout',
        Router::url('/logout', true),
        array('class' => 'miniLinks')
    );
    echo "</li>";

    echo "<li>";
    echo $this->Html->link(
        User::get('full_name'),
        '/users/editProfile',
        array('class' => 'miniLinks')
    );
    echo "</li>";

    echo "</ul>";
    echo "</div>";
}
?>

