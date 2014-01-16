<div class="button-row">
<ul>
    <li>
    <?php echo $this->Html->link(__('Add Faculty', true), array('action' => 'add'), array('class' => 'add-button')); ?>
    </li>
</ul>
</div>

<table id="table_id">
    <thead>
        <tr>
<?php
if (!empty($faculties)) {
  $sample = $faculties[0];
  foreach ($sample as $key => $val) {
    echo "<th>$key</th>";
  }
  echo "<th>Actions</th>";
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($faculties as $faculty) {
    $id = $faculty['id'];
    echo "<tr>";
    foreach ($faculty as $val) {
        echo "<td>$val</td>";
    }
    // add actions
    echo "<td>";
    echo "<ul class='tableActions'>";
    echo "<li>";
    echo $this->Html->link(
        __('View', true),
        array('action' => 'view', $id)
    );
    echo "</li>";
    echo "<li>";
    echo $this->Html->link(
        __('Edit', true),
        array('action' => 'edit', $id)
    );
    echo "</li>";
    echo "<li>";
    echo $this->Html->link(
        __('Delete', true),
        array(
            'action' => 'delete',
            $id),
        null,
        sprintf(__('Are you sure you want to delete %s?', true),
        $faculty['Name'])
    );
    echo "</li>";
    echo "</ul>";
    echo "</td>";
    echo "</tr>";
}
?>

    </tbody>
</table>


<script type="text/javascript">
jQuery(document).ready(
    function() {
        var oTable = jQuery('#table_id').dataTable({
            "sPaginationType" : "full_numbers",
            "aoColumnDefs": [
                {"aTargets": [ 0 ], "bSearchable": false, "bVisible": false, "bSortable": false},
                {"aTargets": [ 2 ], "bSearchable": false, "bSortable": false}
            ],
            "aaSorting" : [[1, 'asc']]
        });
    }
);
</script>
