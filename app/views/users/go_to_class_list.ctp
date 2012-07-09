<table id="table_id">
    <thead>
        <tr>
<?php
if (!empty($classList)) {
  $sample = $classList[0];
  foreach ($sample as $key => $val) {
    echo "<th>$key</th>";
  }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($classList as $person) {
  echo "<tr>";
  foreach ($person as $val) {
    echo "<td>$val</td>";
  }
  echo "</tr>";
}
?>

    </tbody>
</table>

<script type="text/javascript">
/* Formating function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<div class="userActionPanel"><ul>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/users/view/'+aData[0]+'">View</a>'; 
    sOut += '</li>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/users/edit/'+aData[0]+'">Edit</a>'; 
    sOut += '</li>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/emailer/write/U/'+aData[0]+'">Email</a>'; 
    sOut += '</li>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/users/resetPassword/'+aData[0]+'">Reset Password</a>'; 
    sOut += '</li>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/users/delete/'+aData[0]+'">Delete</a>'; 
    sOut += '</li>';

    sOut += '</ul></div>';
     
    return sOut;
}
 
jQuery(document).ready(function() {
    /*
     * Initialse DataTables, with no sorting on the 'details' column
     */
    var oTable = jQuery('#table_id').dataTable( {
      "sPaginationType" : "full_numbers",
      "aoColumnDefs": [
        { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] },
        { "bSearchable": false, "bVisible": false, "bSortable": false, "aTargets": [ 1 ] }
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
