<form name="surveyForm">
    <?php echo __('Survey: ', true) ?>
    <select name="survey_select" onChange="go()">
        <?php
        for ($i=0; $i < count($surveysList); $i++) {
            if ($surveysList[$i]['id'] == $eventId) {
                echo '<option selected="selected" value="/surveygroups/viewresult/'.$surveysList[$i]['id'].'">'.$surveysList[$i]['title'].'</option>';        
            } else {
                echo '<option value="/surveygroups/viewresult/'.$surveysList[$i]['id'].'">'.$surveysList[$i]['title'].'</option>';
            }
        }
        ?>
    </select>
    <?php 
        if (!empty($survey_id)) {
            echo '('.$html->link(__('View Summary', true), '/evaluations/viewSurveySummary/'.$survey_id).')';
        }
    ?>
    <script type="text/javascript">
        function go() {
            location = document.surveyForm.survey_select.options[document.surveyForm.survey_select.selectedIndex].value
        }
    </script>
</form><br>
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
        echo "</tr>";
    } ?>  
    </tbody>
</table>

<script type="text/javascript">

function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<div class="userActionPanel"><ul>';
    
    sOut += '<li>';
    sOut += '<a href="/users/view/'+aData[0]+'">View Student</a>';
    sOut += '</li>';
    
    if (aData[3] != 'Not Submitted') {
        sOut += '<li>';
        sOut += '<a href="/evaluations/viewEvaluationResults/'+aData[4]+'/'+aData[0]+'">View Result</a>';
        sOut += '</li>';
    }
    
    sOut += '</ul></div>';
    
    return sOut;
}

jQuery(document).ready(function() {

    var oTable= jQuery('#table_id').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs" : [
            {"bSearchable": false, "bVisible": false, "bSortable": false, "aTargets": [0] },
            {"bSearchable": false, "bVisible": false, "bSortable": false, "aTargets": [4] }
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