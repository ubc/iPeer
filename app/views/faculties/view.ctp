<h2><?php echo $faculty; ?></h2>

<h3><?php $this->Vocabulary->translate('Department');?></h3>

<table id="departments">
    <thead>
        <tr>
<?php
if (!empty($departments)) {
  $sample = $departments[0];
  foreach ($sample as $key => $val) {
    echo "<th>$key</th>";
  }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($departments as $department) {
    $id = $department['id'];
    echo "<tr>";
    foreach ($department as $val) {
        echo "<td>";
        echo $this->Html->link(
            $val,
            array('action' => 'view', 'controller' => 'departments', $id)
        );
        echo "</td>";
    }
    echo "</tr>";
}
?>
</table>

<h3><?php __('Admins/Instructors');?></h3>

<table id="userfaculty">
    <thead>
        <tr>
<?php
if (!empty($userfaculty)) {
  $sample = $userfaculty[0];
  foreach ($sample as $key => $val) {
    echo "<th>$key</th>";
  }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($userfaculty as $user) {
    $id = $user['id'];
    echo "<tr>";
    foreach ($user as $val) {
        echo "<td>";
        echo $this->Html->link(
            $val,
            array('action' => 'view', 'controller' => 'users', $id)
        );
        echo "</td>";
    }
    echo "</tr>";
}
?>
</table>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#departments').dataTable({
        "aoColumnDefs": [{
            "bSearchable": false,
                "bVisible": false,
                "bSortable":false,
                "aTargets": [ 0 ]
        }],
        "aaSorting" : [[1, 'asc']]
    });
    jQuery('#userfaculty').dataTable({
        "aoColumnDefs": [{
            "bSearchable": false,
                "bVisible": false,
                "bSortable":false,
                "aTargets": [ 0 ]
        }],
        "aaSorting" : [[1, 'asc']]
    });
} );
</script>
