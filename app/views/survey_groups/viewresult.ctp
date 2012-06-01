<table id="table_id">
    <thead>
        <tr>
            <?php if(!empty($view)) {
                $sample = $view[0];
                foreach ($sample as $key => $val) {
                    echo "<th>$key</th>";
                }
            } ?>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($view as $student) {
        echo "<tr>";
        foreach ($student as $val) {
            echo "<td>$val</td>";
        }
    } ?>  
    </tbody>
</table>

<script type="text/javascript">

jQuery(document).ready(function() {

    var oTable= jQuery('#table_id').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs" : [
            {"bSearchable": false, "bVisible": false, "bSortable": false, "aTargets": [0] }
        ],
        "aaSorting" :[[1, 'asc']]
    });
});

</script>