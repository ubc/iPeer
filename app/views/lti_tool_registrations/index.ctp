<div class="ltiToolRegistrations index">

    <div class="button-row">
        <ul>
            <li><?php echo $this->Html->link(__('Add Tool Registration', true), array('action'=>'add'), array('class'=>'add-button')); ?></li>
        </ul>
    </div>

    <table id="table_id">
<?php if (!empty($headings)): ?>
        <thead>
            <tr>
<?php     foreach ($headings as $heading): ?>
                <th><?php echo $heading; ?></th>
<?php     endforeach; ?>
            </tr>
        </thead>
<?php endif; ?>
<?php if (!empty($rows)): ?>
        <tbody>
<?php     foreach ($rows as $id => $row): ?>
            <tr>
<?php         foreach ($row as $key => $value): ?>
<?php             if (!in_array($key, array('id', 'Issuer'))): ?>
                <td><pre style="font-family:monospace;font-size:smaller;"><?php echo $value; ?></pre></td>
<?php             else: ?>
                <td><?php echo $value; ?></td>
<?php             endif; ?>
<?php         endforeach; ?>
                <td>
                    <ul class='tableActions'>
                        <li><?php echo $this->Html->link(__('Edit', true), array('action'=>'edit', $id)); ?></li>
                        <li><?php echo $this->Html->link(__('Delete', true), array('action'=>'delete', $id), null, sprintf(__('Are you sure you want to delete %s?', true), $row['Issuer'])); ?></li>
                    </ul>
                </td>
            </tr>
<?php     endforeach; ?>
        </tbody>
<?php endif; ?>
    </table>

</div>

<script type="text/javascript">
jQuery(document).ready(function() {
    var oTable = jQuery('#table_id').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs": [
            {"aTargets": [ 2 ], "bSearchable": false, "bSortable": false}
        ],
        "aaSorting" : [[1, 'asc']]
    });
});
</script>
