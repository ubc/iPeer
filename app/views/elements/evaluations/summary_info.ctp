<h3><?php __('Summary')?>
  (<?php echo $this->Html->link(__('Basic', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['Group']['id']."/Basic")?> |
    <?php echo $html->link(__('Detail', true), "/evaluations/viewEvaluationResults/".$event['Event']['id']."/".$event['Group']['id']."/Detail")?> )</h3>

<!-- Users who haven't done the evaluation yet table -->
<table class="standardtable">
<?php
if (!empty($inCompletedMembers)) {
    echo $html->tableHeaders(array(__('These people have not yet submitted their evaluations', true)), null, array('class' => 'red'));
    $incompletedMembersArr = array();
    $users = array();
    foreach ($inCompletedMembers as $row) {
        $user = $row['User'];
        array_push($incompletedMembersArr, $user['first_name'] . " " . $user['last_name']);
        $users[] = array($user['first_name'] . " " . $user['last_name'] . ($row['Role'][0]['id'] == 4 ? ' (TA)' : ' (student)'));
    }
    echo $html->tableCells($users);
}
?>
</table>

