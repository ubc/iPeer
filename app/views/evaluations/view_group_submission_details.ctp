<h2>Group Details</h2>
<table class='standardtable'>
    <tr>
        <th><?php __('Group Number')?>:</th>
        <th><?php __('Group Name')?>:</th>
    </tr>
    <tr>
        <td><?php echo $group['Group']['group_num']?></td>
        <td><?php echo $group['Group']['group_name']?></td>
    </tr>
</table>


<?php
// data processing to get information on the group members into format
// fit for consumption by the html helper's tableCells
$memberSubmissions = array();
foreach($members as $member) {
    $userinfo = $member['Member'];
    $tmp = array();
    $tmp[] = $userinfo['full_name'];
    $tmp[] = $userinfo['student_no'];
    // date submitted
    if ($member['users']['submitted']) {
        $tmp[] = Toolkit::formatDate(
            date("Y-m-d H:i:s", strtotime($member['users']['date_submitted'])));
    }
    else {
        $tmp[] = __("(not submitted)", true);
    }
    // late by
    if (isset($member['users']['time_diff'])) {
        $tmp[] = $member['users']['time_diff']. __(' day(s)', true);
    }
    else {
        $tmp[] = $member['users']['submitted'] ?  __("(on time)", true) : "---";
    }
    $memberSubmissions[] = $tmp;
}
?>

<h3>Group Members</h3>

<table class='standardtable'>
    <tr>
  	    <th><?php __('Name')?></th>
  	    <th><?php __('Student Number')?></th>
  	    <th><?php __('Date Submitted')?></th>
  	    <th><?php __('Late By')?></th>
	</tr>
    <?php
    echo $html->tableCells($memberSubmissions);
    ?>
</table>
