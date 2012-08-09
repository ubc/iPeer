<div class="oauthClients index">

<div class="button-row">
    <ul>
        <li>
        <?php echo $html->image('icons/add.gif', array('valign'=>'middle'))?>
        <?php echo $html->link( __('Add Client', true), 
                                 array('action' => 'add')); ?>
        </li>
    </ul>
</div>

<table id="table_id">
    <thead>
        <tr>
<?php
if (!empty($clientCreds)) {
  $sample = $clientCreds[0];
  foreach ($sample as $key => $val) {
    echo "<th>$key</th>";
  }
  echo "<th></th>";
  echo "<th></th>";
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($clientCreds as $client) {
  echo "<tr>";
  $id = 0;
  foreach ($client as $key => $val) {
    echo "<td>$val</td>";
    if ($key == 'id') {
        $id = $val;
    }
  }
  echo "<td>". 
      $this->Html->link(__('Edit', true), array('action' => 'edit', $id)) .
      "</td>";
  echo "<td>". 
      $this->Html->link(__('Delete', true), array('action' => 'delete', $id)) .
      "</td>";
  echo "</tr>";
}
?>
    </tbody>
</table>
</div>

<script type="text/javascript">
var oTable = jQuery('#table_id').dataTable( {
      "sPaginationType" : "full_numbers",
      "aoColumnDefs": [
        { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] },
        { "bSearchable": false, "bSortable":false, "aTargets": [ 5 ] },
        { "bSearchable": false, "bSortable":false, "aTargets": [ 6 ] }
        ],
        "aaSorting" : [[1, 'asc']]
    });

</script>
