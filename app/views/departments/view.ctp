<h2><?php echo $department; ?></h2>

<p>
Faculty: <?php echo $faculty;?>
</p>

<h3>Courses</h3>
<table id="table_id">
    <thead>
        <tr>
<?php
$columns = array('id', 'course', 'title', 'student_count', 'record_status');
if (!empty($courses)) {
    foreach ($columns as $val) {
        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
            continue;
        }
        echo "<th>".Inflector::humanize($val)."</th>";
    }
}
?>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($courses as $course) {
    echo "<tr>";
    foreach ($columns as $val) {
        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
            continue;
        }
        echo "<td>".$this->Html->link($course['Course'][$val], '/courses/view/'.$course['Course']['id'])."</td>";
    }
    echo "</tr>";
}
?>
    </tbody>
</table>

<script type="text/javascript">

jQuery(document).ready(function() {
    var oTable = jQuery('#table_id').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs": [{
            "bSearchable": false,
            "bVisible": false,
            "bSortable":false,
            "aTargets": [ 0 ]
        }],
        "aaSorting" : [[1, 'asc']]
    });
});

</script>

