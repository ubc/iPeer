<div id='groupsimport'>
<div id='title'><?php __('The group CSV file was processed.')?></div><br>
<div id='button_text'><?php __('Click OK to return to the list of groups, or look below 
for the results of the import.')?></div><br><center>
<input type="button" id='back' value="OK" onClick="window.location='<?php echo $this->webroot . "groups/index/". $courseId ?>'";>
</center>
<?php
if (!empty($invalid)) {
    echo '<h2>'._t('Invalid Entries').'</h2>';
    echo '<ul><li>'._t('There are more or less than 2 columns.').'</li></ul>';
    echo '<table class="standardtable"><tr><th>'._t('Entry').'</th></tr>';
    foreach ($invalid as $entry) {
        echo '<tr><td>'.implode(', ', $entry).' ('.count($entry).' columns)</td></tr>';
    }
    echo '</table>';
}
if (!empty($groupSuccess)) {
    echo '<h2>'._t('Groups Created').'</h2>';
    echo '<table class="standardtable"><tr><th>'._t('Name').'</th></tr>';
    foreach ($groupSuccess as $group) {
        echo '<tr><td>'.$group.'</td></tr>';
    }
    echo '</table>';
}
if (!empty($groupFailure)) {
    echo '<h2>'._t('Groups that could not be created.').'</h2>';
    echo '<table class="standardtable"><tr><th>'._t('Name').'</th></tr>';
    foreach ($groupFailure as $group) {
        echo '<tr><td>'.$group.'</td></tr>';
    }
    echo '</table>';
}
if (!empty($memSuccess)) {
    echo '<h2>'._t('Students Placed').'</h2>';
    echo '<table class="standardtable"><tr><th>'._t('Student').'</th>';
    echo '<th>'._t('Group').'</th></tr>';
    foreach ($memSuccess as $groupName => $group) {
        foreach ($group as $member) {
            echo '<tr><td>'.$member.'</td>';
            echo '<td>'.$groupName.'</td></tr>';
        }
    }
    echo '</table>';
}
if (!empty($memFailure)) {
    echo '<h2>'._t('Students Not Placed').'</h2>';
    echo '<h3>'._t('Possible Reasons:').'</h3>';
    echo '<ul>';
    echo '<li>'._t('The student identifier does not exist in the system. Please add them first.').'</li>';
    echo '<li>'._t('The student is not enrolled in the course. Please enrol them first.').'</li>';
    echo '<li>'._t('The group was unable to be created or does not exist.').'</li>';
    echo '</ul>';
    echo '<table class="standardtable"><tr><th>'._t('Student').'</th>';
    echo '<th>'._t('Group').'</th></tr>';
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