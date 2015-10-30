<?php
/**
 * Creates a link for Evaluation tools toolbar:
 *
 * @param $view              should be set to $this to access the view object
 * @param $url               where the tab should go to when clicked on
 * @param $name              name
 */
function generateLink ($view, $url, $name) {
    $current = $view->params['controller'] == $url ? "class='current'" : "";
    $url = $view->webroot . $view->theme . $url;
    echo "<li $current><a href='$url'>$name</a></li>";
}
?>

<div class="evaltoolsnav">
    <ul>
        <!-- Sub menu for Evaluation Event Tools -->
<?php 
if (User::hasPermission('controllers/evaltools')) { 
    generateLink($this, 'evaltools', __('All My Tools',true));
}
if (User::hasPermission('controllers/simpleevaluations')) {
    generateLink($this, 'simpleevaluations', __('Simple Evaluations',true));
}
if (User::hasPermission('controllers/rubrics')) {
    generateLink($this, 'rubrics', __('Rubrics',true));
}
if (User::hasPermission('controllers/mixevals')) {
    generateLink($this, 'mixevals', __('Mixed Evaluations',true));
}
if (User::hasPermission('controllers/surveys')) {
    generateLink($this, 'surveys', __('Surveys',true));
}
if (User::hasPermission('controllers/emailer')) {
    generateLink($this, 'emailer', __('Emailer',true));
}
if (User::hasPermission('controllers/emailtemplates')) {
    generateLink($this, 'emailtemplates', __('Email Templates',true));
}
?>
    </ul>
</div>
