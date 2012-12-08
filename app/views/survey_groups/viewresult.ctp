<form name="surveyForm">
    <?php echo __('Survey Event', true) ?>:
    <?php echo $this->Form->select('survey_select', $surveysList, $eventId, array('onChange' => 'go();', 'empty' => false));?>
    <?php echo (!empty($eventId)? '('.$html->link(__('View Summary', true), '/evaluations/viewSurveySummary/'.$eventId).')' : '');?>
    <script type="text/javascript">
        function go() {
            location = '<?php echo $html->url('/surveygroups/viewresult/'.$courseId)?>/'+document.surveyForm.survey_select.options[document.surveyForm.survey_select.selectedIndex].value
        }
    </script>
</form><br>
<table id="table_id">
    <thead>
        <tr>
            <!-- column headings -->
<?php
$columns = array('id', 'username', 'full_name', 'email', 'student_no', 'submitted');
if (!empty($view)) {
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
foreach ($view as $person) {
    echo "<tr>";
    foreach ($columns as $val) {
        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
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

// creates the drop down section to links to View Student and View Result
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<div class="userActionPanel"><ul>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/users/view/'+aData[0]+'">View Student</a>';
    sOut += '</li>';

    if (aData[4] != 'Not Submitted') {
        sOut += '<li>';
        sOut += '<a href="<?php echo $this->base; ?>/evaluations/viewEvaluationResults/'+<?php echo $eventId?>+'/'+aData[0]+'">View Result</a>';
        sOut += '</li>';
    }

    sOut += '</ul></div>';

    return sOut;
}

// customizing the table - hiding some fields, and jQuery for opening and closing drop down menu
jQuery(document).ready(function() {

    var oTable= jQuery('#table_id').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs" : [
            {"bSearchable": false, "bVisible": false, "bSortable": false, "aTargets": [0] },
        ],
        "aaSorting" :[[1, 'asc']]
    });

    jQuery('#table_id tbody td').live('click', function () {
        var nTr = jQuery(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            oTable.fnClose( nTr);
        }
        else
        {
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'userActionPanel' );
        }
    });
});

</script>
