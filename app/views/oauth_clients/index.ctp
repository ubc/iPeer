<div class="oauthClients index">

<div class="button-row">
    <ul>
        <li>
        <?php echo $html->link( __('Add Client', true), array('action' => 'add'), array('class' => 'add-button')); ?>
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
  echo "</tr>";
}
?>
    </tbody>
</table>
</div>

<script type="text/javascript">
/* Formatting function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<div class="userActionPanel"><ul>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/oauthclients/edit/'+aData[0]+'">Edit</a>';
    sOut += '</li>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/oauthclients/delete/'+aData[0]+'">Delete</a>';
    sOut += '</li>';

    sOut += '</ul></div>';

    return sOut;
}

jQuery(document).ready(function() {
    var oTable = jQuery('#table_id').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs": [
            { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] }
        ],
        "aaSorting" : [[1, 'asc']]
    });

    /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    jQuery('#table_id tbody td').live('click', function () {
        var nTr = jQuery(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'userActionPanel' );
        }
    } );
} );
</script>
