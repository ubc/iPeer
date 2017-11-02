<?php if ($linkedWithCanvas && User::hasPermission('controllers/Courses/add')): ?>
    <div class="button-row">
        <ul>
            <li>
                <?php echo __('This course is linked with a Canvas course. You may compare the enrollment.', true); ?>
            </li>
            <li>
            <?php
                echo $html->link( __('Compare', true),
                    '/courses/syncCanvasEnrollment/'.$courseId, array('class' => 'compare-button'));
            ?>
            </li>
        </ul>
    </div>
<?php endif ?>

<table id="table_id">
    <thead>
        <tr>
<?php
$columns = array('id', 'username', 'full_name', 'email', 'student_no');
if (!empty($classList)) {
    foreach ($columns as $val) {
        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
            continue;
        }
        if ($val == 'username' && !User::hasPermission('functions/viewusername')) {
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
foreach ($classList as $person) {
    echo "<tr>";
    foreach ($columns as $val) {
        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
            continue;
        }
        if ($val == 'username' && !User::hasPermission('functions/viewusername')) {
            continue;
        }
        echo "<td>".$person[$val]."</td>";
    }
    echo "</tr>";
}
?>

    </tbody>
</table>

<script type="text/javascript">
/* Formatting function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );

    var sOut = '<a href="<?php echo $this->base; ?>/users/view/'+aData[0]+'">';
    sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">View';
    sOut += '</div></a>';

    sOut += '<a href="<?php echo $this->base; ?>/users/edit/'+aData[0]+'/<?php echo $courseId?>">';
    sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">Edit';
    sOut += '</div></a>';

    sOut += '<a href="<?php echo $this->base; ?>/emailer/write/U/'+aData[0]+'">';
    sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">Email';
    sOut += '</div></a>';

    sOut += '<a href="<?php echo $this->base; ?>/users/resetPassword/'+aData[0]+'/<?php echo $courseId?>" onclick="return resetConfirmed()">';
    sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">Reset Password';
    sOut += '</div></a>';

    <?php if (User::hasPermission('controllers/users/resetpasswordwithoutemail')) { ?>
        sOut += '<a href="<?php echo $this->base; ?>/users/resetPasswordWithoutEmail/'+aData[0]+'/<?php echo $courseId?>">';
        sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">Reset Password Without Email';
        sOut += '</div></a>';
    <?php } ?>

    sOut += '<a href="<?php echo $this->base; ?>/users/delete/'+aData[0]+'/<?php echo $courseId?>" onclick="return dropConfirmed(&quot;'+aData[2]+'&quot;)">';
    sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">Drop';
    sOut += '</div></a>';
    
    /*<?php
    if (User::hasPermission('controllers/Users/showEvents')) {
    ?>
       	sOut += '<a href="<?php echo $this->base; ?>/users/showEvents/'+aData[0]+'">';
	    sOut += '<div style="cursor: default; padding: 4px; font-weight: bold; color: black;">Show user\'s events';
	    sOut += '</div></a>';
	<?php } ?>*/

    return sOut;
}

function dropConfirmed(name)
{
    var confirmed = confirm("Are you sure you want to drop " + name + " from the course?");
    return confirmed;
}

function resetConfirmed()
{
    var confirmed = confirm("Resets student's password. Are you sure?");
    return confirmed;
}

jQuery(document).ready(function() {
	var elmnt = null;
    /*
     * Initialise DataTables, with no sorting on the 'details' column
     */
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
    jQuery('#table_id tbody').on('click', 'td', function (ev) {
        if (elmnt) {
        	elmnt.remove();
        	elmnt = null;
        }
        else {
	        var nTr = jQuery(this).parents('tr')[0];
	        elmnt = jQuery('<div>');
	        elmnt.html(fnFormatDetails(oTable, nTr));
	        elmnt.css({"background-color": "rgb(255, 255, 224)", "margin": "0px", "padding": "5px", "border": "1px solid", "position": "absolute"});
			elmnt.css("top", ev.pageY);
			elmnt.css("left", ev.pageX);
			elmnt.find("a").css({"text-decoration":"none", "color": "black"});
			elmnt.find("div").mouseover(function(){
				jQuery(this).css({"background-color": "rgb(173, 216, 230)"});
			});
			elmnt.find("div").mouseout(function(){
				jQuery(this).css({"background-color": "rgb(255, 255, 224)"});
			});
			jQuery(document.body).append(elmnt);
        }
        ev.stopPropagation();
    } );
    jQuery(document).on('click', function () {
    	if (elmnt) {
        	elmnt.remove();
        	elmnt = null;
        }
    });
} );
</script>