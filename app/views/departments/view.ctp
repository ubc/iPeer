<h2><?php echo $department; ?></h2>

<p>
Faculty: <?php echo $faculty;?>
</p>

<h3>Courses</h3>
<table id="table_id">
    <thead>
        <tr>
<?php
if (!empty($courses)) {
    $sample = $courses[0];
    foreach ($sample as $key => $val) {
        echo "<th>$key</th>";
    }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($courses as $course) {
    $id = $course['id'];
    echo "<tr>";
    foreach ($course as $val) {
        echo "<td>";
        echo $this->Html->link(
            $val, 
            array('action' => 'view', 'controller' => 'courses', $id)
        );
        echo "</td>";
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

