<div id='StudentHome'>
<?php
$evalUpcoming = Toolkit::getUpcomingTableArray($html, $evals['upcoming']);
$surveyUpcoming = Toolkit::getUpcomingTableArray($html, $surveys['upcoming']);

$evalSubmitted = Toolkit::getNonUpcomingTableArray($html, $evals['submitted']);
$surveySubmitted = Toolkit::getNonUpcomingTableArray($html, $surveys['submitted']);
$evalExpired = Toolkit::getNonUpcomingTableArray($html, $evals['expired']);
// note that we have this section for completeness, but currently,
// surveys are removed once past the due date, so unless the student
// made a submission, it won't show up
$surveyExpired = Toolkit::getNonUpcomingTableArray($html, $surveys['expired']);

if ($numOverdue) {
    echo "<div class='eventSummary overdue'>$numOverdue Overdue Event(s)</div>";
}
if ($numDue) {
    echo "<div class='eventSummary pending'>$numDue Pending Event(s) Total</div>";
}
else {
    echo "<div class='eventSummary alldone'>No Event(s) Pending</div>";
}
?>


<section aria-labelledby='home-peer'>
<h2 id='home-peer'>Peer Evaluations</h2>
<h3 id='home-peer-due'>Due</h3>
<table class='standardtable' aria-labelledby='home-peer home-peer-due'>
    <tr>
       <th>Event</th>
       <th>Group</th>
       <th>Course</th>
       <th>Due Date</th>
       <th>Due In/<span class='red'>Late By</span></th>
    </tr>
    <?php echo $html->tableCells($evalUpcoming); ?>
    <?php if (empty($evalUpcoming)):?>
    <tr><td colspan="5" align="center"><b> No peer evaluations due at this time </b></td></tr>
    <?php endif; ?>
</table>

<h3 id='home-peer-submitted'>Submitted</h3>
<table class='standardtable' aria-labelledby='home-peer home-peer-submitted'>
    <tr>
        <th>Event</th>
        <th>Result <span class='orangered'>Available</span>/End</th>
        <th>Group</th>
        <th>Course</th>
        <th>Due Date</th>
        <th>Date Submitted</th>
    </tr>
    <?php echo $html->tableCells($evalSubmitted); ?>
    <?php if (empty($evalSubmitted)):?>
    <tr>
        <td colspan="6" align="center">No submitted evaluations available.</td>
    </tr>
    <?php endif; ?>
</table>

<?php if (!empty($evalExpired)):?>
<h3 id='home-peer-expired'>Expired With No Submission</h3>
<table class='standardtable' aria-labelledby='home-peer home-peer-expired'>
    <tr>
        <th>Event</th>
        <th>Result <span class='orangered'>Available</span>/End</th>
        <th>Group</th>
        <th>Course</th>
        <th>Due Date</th>
    </tr>
    <?php echo $html->tableCells($evalExpired); ?>
</table>
<?php endif; ?>
</section>

<section aria-labelledby='home-survey'>
<h2 id='home-survey'>Surveys</h2>
<h3 id='home-survey-due'>Due</h3>
<table class='standardtable' aria-labelledby='home-survey home-survey-due'>
    <tr>
       <th>Event</th>
       <th>Course</th>
       <th>Due Date</th>
       <th>Due In/<span class='red'>Late By</span></th>
    </tr>
    <?php echo $html->tableCells($surveyUpcoming); ?>
    <?php if (empty($surveyUpcoming)):?>
    <tr><td colspan="4" align="center"><b> No survey due at this time </b></td></tr>
    <?php endif; ?>
</table>

<h3 id='home-survey-submitted'>Submitted</h3>
<table class='standardtable' aria-labelledby='home-survey home-survey-submitted'>
    <tr>
        <th>Event</th>
        <th>Course</th>
        <th>Due Date</th>
        <th>Date Submitted</th>
    </tr>
    <?php echo $html->tableCells($surveySubmitted); ?>
    <?php if (empty($surveySubmitted)):?>
    <tr>
        <td colspan="4" align="center">No submitted surveys available.</td>
    </tr>
    <?php endif; ?>
</table>

<?php if (!empty($surveyExpired)):?>
<h3 id='home-survey-expired'>Expired With No Submission</h3>
<table class='standardtable' aria-labelledby='home-survey home-survey-expired'>
    <tr>
        <th>Event</th>
        <th>Course</th>
        <th>Due Date</th>
    </tr>
    <?php echo $html->tableCells($surveyExpired); ?>
</table>
<?php endif; ?>
</section>

</div>
