<div id='groupsimport'>
<div id='title'><?php __('The group CSV file was processed.')?></div><br>
<div id='button_text'><?php __('Click OK to return to the list of groups, or look below
for the results of the import.')?></div><br><center>
<input type="button" id='back' value="OK" onClick="window.location='<?php echo $this->webroot . "groups/index/". $courseId ?>'";>
</center>
<?php
if (!empty($invalid)) {
    echo '<h2>'.__('Invalid Entries', true).'</h2>';
    echo '<ul><li>'.__('There are more or less than 2 columns.', true).'</li></ul>';
    echo '<table class="standardtable"><tr><th>'.__('Entry', true).'</th></tr>';
    foreach ($invalid as $entry) {
        echo '<tr><td>'.implode(', ', $entry).' ('.count($entry).' columns)</td></tr>';
    }
    echo '</table>';
}
if (!empty($groupSuccess)) {
    echo '<h2>'.__('Groups Created', true).'</h2>';
    echo '<table class="standardtable"><tr><th>'.__('Name', true).'</th></tr>';
    foreach ($groupSuccess as $group) {
        echo '<tr><td>'.$group.'</td></tr>';
    }
    echo '</table>';
}
if (!empty($groupFailure)) {
    echo '<h2>'.__('Groups that could not be created.', true).'</h2>';
    echo '<table class="standardtable"><tr><th>'.__('Name', true).'</th></tr>';
    foreach ($groupFailure as $group) {
        echo '<tr><td>'.$group.'</td></tr>';
    }
    echo '</table>';
}
if (!empty($memSuccess)) {
    echo '<h2>'.__('Students Placed', true).'</h2>';
    echo '<table class="standardtable"><tr><th>'.__('Student', true).'</th>';
    echo '<th>'.__('Group', true).'</th></tr>';
    foreach ($memSuccess as $groupName => $group) {
        foreach ($group as $member) {
            echo '<tr><td>'.$member.'</td>';
            echo '<td>'.$groupName.'</td></tr>';
        }
    }
    echo '</table>';
}
if (!empty($memFailure)) {
    echo '<h2>'.__('Students Not Placed', true).'</h2>';
    echo '<h3>'.__('Possible Reasons:', true).'</h3>';
    echo '<ul>';
    echo '<li>'.__('The student identifier does not exist in the system. Please add them first.', true).'</li>';
    echo '<li>'.__('The student is not enrolled in the course. Please enrol them first.', true).'</li>';
    echo '<li>'.__('The group was unable to be created or does not exist.', true).'</li>';
    echo '</ul>';
    echo '<table class="standardtable"><tr><th>'.__('Student', true).'</th>';
    echo '<th>'.__('Group', true).'</th></tr>';
    foreach ($memFailure as $groupName => $group) {
        foreach ($group as $member) {
            echo '<tr><td>'.$member.'</td>';
            echo '<td>'.$groupName.'</td></tr>';
        }
    }
    echo '</table>';
}
?>
</div>
