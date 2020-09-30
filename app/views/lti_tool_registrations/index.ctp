<div class="ltiToolRegistrations index">

    <div class="button-row">
        <ul>
            <li><?php echo $this->Html->link(__('Add Tool Registration', true), array('action'=>'add'), array('class'=>'add-button')); ?></li>
        </ul>
    </div>

    <table id="table_id">
<?php if (!empty($registrations)): ?>
        <thead>
            <tr>
                <th>Issuer</th>
                <th>Client ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
<?php     foreach ($registrations as $registration): ?>
            <tr>
                <td><?php echo $registration['iss'] ?></td>
                <td><?php echo $registration['client_id'] ?></td>
                <td>
                    <ul class='tableActions'>
                        <li><?php echo $this->Html->link(__('Edit', true), array('action'=>'edit', $registration['id'])); ?></li>
                        <li><?php echo $this->Html->link(__('Delete', true), array('action'=>'delete', $registration['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $registration['iss'])); ?></li>
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
