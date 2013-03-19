<center style="font-size:150%"><?php __('The group CSV file was processed.')?></center><br />
<center><?php __('Click OK to return
to the list of groups, or look below for the results of the import.')?>
<br /> <br />
<input type="button" name="Okay" value="OK" onClick="window.location='<?php echo $this->webroot . "groups/index/". $courseId ?>'";>
</center>
<?php
if (!empty($invalid)) {
    echo '<h2>'._t('Invalid Entries').'</h2>';
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
    echo '<h2>'._t('Students Not Placed.').'</h2>';
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