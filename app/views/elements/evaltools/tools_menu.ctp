<?php
/**
 * Creates a link for Evaluation tools toolbar:
 *
 * @param $view              should be set to $this to access the view object
 * @param $url               where the tab should go to when clicked on
 * @param $name              name
 */
function generateLink ($view, $url, $name) {
    $url = $view->webroot . $view->theme . $url;
    $current = $view->params['controller'] ? "id='current'" : "";
    echo "<li $current><a href='$url'>$name</a></li>";
}
?>

<div id="evaltoolsnav">
    <ul>
        <!-- Sub menu for Evaluation Event Tools -->
        <?php if (User::hasPermission('controllers/evaltools')) { 
            generateLink(
                $this,
                'evaltools',
                'All My Tools'
            );
            echo "|";
        }?>
        <?php if (User::hasPermission('controllers/simpleevaluations')) {
            generateLink(
                $this,
                'simpleevaluations',
                'Simple Evaluations'
            );
            echo "|";
        }?>
        <?php if (User::hasPermission('controllers/rubrics')) {
            generateLink(
                $this,
                'rubrics',
                'Rubrics'
            );
            echo "|";
        }?>
        <?php if (User::hasPermission('controllers/mixevals')) {
            generateLink(
                $this,
                'mixevals',
                'Mixed Evaluations'
            );
            echo "|";
        }?>
        <?php if (User::hasPermission('controllers/surveys')) {
            generateLink(
                $this,
                'surveys',
                'Surveys'
            );
            echo "|";
        }?>
        <?php if (User::hasPermission('controllers/emailer')) {
            generateLink(
                $this,
                'emailer',
                'Emailer'
            );
            echo "|";
        }?>
        <?php if (User::hasPermission('controllers/emailtemplates')) {
            generateLink(
                $this,
                'emailtemplates',
                'Email Templates'
            );
        }?>
    </ul>
</div>
