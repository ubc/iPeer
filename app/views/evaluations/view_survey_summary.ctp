<?php
// Survey Summary data
$questionIndex = 1;
$colspan = 4; // total number of columns for when we want only 1 cell
$questionsTable = array();
foreach ($questions as $question) {
    $tmp = array();
    if (isset($question['total_response'])) {
        // Processing a multiple choice response
        // header
        $totalResponses = $question['total_response'];
        $tmp['header'][] = "$questionIndex. " . $question['prompt'] . ' (' .
            $totalResponses . ' '. __('responses', true) . ')';

        // responses
        foreach ($question['Response'] as $response) {
            $cells = array();
            // processing a multiple choice question's response
            $count = $response['count'];
            $percent = $totalResponses > 0 ?
                $percent = round($count / $totalResponses * 100) : 0;
            $cells[] = $response['response'];
            $cells[] = $count;
            $cells[] = $percent == 0 ? "-" : "$percent%";
            $cells[] = '<div class="graph"><div class="bar" style="width:'.$percent.'%;"></div></div>';
            $tmp['cells'][] = $cells;
        }
    } else {
        // Processing a single or multi-line text response
        // header
        $totalResponses = count($question['Response']);
        $tmp['header'][] = "$questionIndex. " . $question['prompt'] . ' (' .
            $totalResponses . ' '. __('responses', true) . ')';

        // responses
        $responders = "";
        foreach ($question['Response'] as $response) {
            $responders .= $response['user_name']
                . ', ';
        }
        $responders = trim($responders, ", ");
        $cells = array();
        $cells[] = __('Responders', true) . ":";
        $cells[] = array($responders, array('colspan' => $colspan - 1));
        $tmp['cells'][] = $cells;
    }

    $questionIndex++;
    $questionsTable[] = $tmp;
}

// Individual Response data
$headers = array('id', 'username', 'full_name', 'email', 'student_no', 'submitted');
$displayHeaders = array();
// display email only if the user has access to it
foreach ($headers as $key => $header) {
    if ($header == 'email' &&
        !User::hasPermission('functions/viewemailaddresses')
    ) {
        unset($headers[$key]);
        continue;
    }
    $displayHeaders[] = Inflector::humanize($header);
}

$displayCells = array();
foreach ($students as $student) {
    $tmp = array();
    foreach ($headers as $header) {
        if ($header == 'email' &&
            !User::hasPermission('functions/viewemailaddresses')
        ) {
            continue;
        }
        // link to view user information
        else if ($header == 'username') {
            $tmp[] = $html->link(
                $student[$header],
                '/users/view/'.$student['id'],
                array('target' => '_blank')
            );
        }
        // link to view this user's submission if available
        else if ($header == 'submitted') {
            $tmp[] = $student[$header] ?
                $html->link(__('Result', true),
                    "/evaluations/viewEvaluationResults/$eventId/" .
                    $student['id'],
                    array('target' => '_blank')
                ) :
                __('Not Submitted', true);
        }
        else {
            $tmp[] = $student[$header];
        }
    }
    $displayCells[] = $tmp;
}
?>

<h2>Survey Summary</h2>

<table class='standardtable leftalignedtable'>
<?php
foreach ($questionsTable as $question) {
    echo $html->tableHeaders($question['header'], null,
        array('colspan' => $colspan));
    echo $html->tableCells($question['cells']);
}
?>
</table>

<h2>Individual Responses</h2>

<table id="individualResponses">
    <thead>
    <?php
    echo $html->tableHeaders($displayHeaders);
    ?>
    </thead>
    <tbody>
    <?php
    echo $html->tableCells($displayCells);
    ?>
    </tbody>
</table>

<script type="text/javascript">
// enable all the nice sorting, search pagination, etc, hide the user id field
jQuery(document).ready(function() {
    var oTable= jQuery('#individualResponses').dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs" : [
            {"bSearchable": false, "bVisible": false, "bSortable": false, "aTargets": [0] }
        ],
        "aaSorting" :[[1, 'asc']]
    });
});
</script>

