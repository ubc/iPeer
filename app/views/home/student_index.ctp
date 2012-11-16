<?php
/*
Table for coming Peer Evaluations
*/
echo "<h3>Peer Evaluations Due</h3>";

echo"<div> 
        <table style='width:100%'> 
		  <tr class='tableheader'>
			<th width='30%'>Event</th>
            <th width='10%'>Group</th>
			<th width='20%'>Course</th>
			<th width='20%'>Due Date</th>
			<th width='20%'>Due In/Late By (red)</th>
		  </tr>";
		  
$i = 0;
$currentDate = strtotime('NOW');

foreach($data as $row) {
    isset($row['comingEvent'])? $comingUpEvent = $row['comingEvent']: $comingUpEvent = null;

 
if(!empty($row['comingEvent']['Event']['release_date_end'])) { 
    $releaseEndDate = strtotime( $row['comingEvent']['Event']['release_date_end']);
}
	
if (isset($comingUpEvent['Event']['id']) && $currentDate <= $releaseEndDate && !isset($row['eventSubmitted'])){
    echo "<tr class='tablecell'>";
	
if ($comingUpEvent['Event']['event_template_type_id'] == 1) {
    echo "<td> <a href ='" .$this->webroot.$this->theme. "evaluations/makeSimpleEvaluation/" 
    .$comingUpEvent['Event']['id'] . ";".  $comingUpEvent['Event']['group_id'] . "'>"
	.$comingUpEvent['Event']['title']."</a></td>"; 
} 
			
if ($comingUpEvent['Event']['event_template_type_id'] == 2) {
	echo "<td> <a href ='" .$this->webroot.$this->theme. "evaluations/makeRubricEvaluation/" 
	.$comingUpEvent['Event']['id'] . ";". $comingUpEvent['Event']['group_id'] . "'>"
	.$comingUpEvent['Event']['title']."</a></td>"; 
}
			
if ($comingUpEvent['Event']['event_template_type_id'] == 3) {
	echo"<td> <a href ='" .$this->webroot.$this->theme. "evaluations/makeSurveyEvaluation/" 
    .$comingUpEvent['Event']['id'] . "'>"
	.$comingUpEvent['Event']['title']."</a></td>"; 
}
			
if ($comingUpEvent['Event']['event_template_type_id'] == 4) {
	echo "<td> <a href ='" .$this->webroot.$this->theme. "evaluations/makeMixevalEvaluation/" 
	.$comingUpEvent['Event']['id'] . ";".  $comingUpEvent['Event']['group_id'] . "'>"
    .$comingUpEvent['Event']['title']."</a></td>"; 
}

//Display group name and course name for the Event			
echo "<td>".$comingUpEvent['Event']['group_name']."</td>";
echo "<td>" .$comingUpEvent['Event']['course']. "</td>";
	  

//Display due date for the coming event	  
echo "<td>" ;
$dueDate=$row['comingEvent']['Event']['due_date'];
$timeStamp = strtotime($dueDate);
echo date('F j, y g:i a', $timeStamp); 
echo "</td>";	  
	  
echo "<td>";	
if ($comingUpEvent['Event']['is_late']) {
//If the event is late, display in RED 
	echo "<font color=\"red\"><b>" . $comingUpEvent['Event']["days_to_due"] . "</b>".__(' day(s)', true)."!  ";
	
	if (isset($comingUpEvent['Event']['penalty'])){
	    echo ($comingUpEvent['Event']['penalty'].'% penalty');
	}
	
	echo "</font>";
			  } 
    else {
		 echo "<b>".$comingUpEvent['Event']['days_to_due']."</b>".__(' day(s)', true);
			  	
				if ($comingUpEvent['Event']['days_to_due'] <= 2) {
    				print "<b>!</b>";
    			}
			  
		 }
			  
	echo "</td>";
	echo "</tr>";
	$i++;
}
}

//Display no peer evaluations due
if($i == 0) {
    echo "<tr><td colspan='5' align='center'><b> No peer evaluations due at this time </b></td></tr>";
}

	echo "</table>";
	echo "</div>";
	
/*
Table for submitted peer evaluations 
*/
echo "<h3>Peer Evaluations Submitted</h3>";
	
echo"<div> 
    <table style='width:100%'> 
        <tr class='tableheader'>
		    <th width='30%'>Event</th>
            <th width='10%'>Group</th>
			<th width='20%'>Course</th>
			<th width='20%'>Due Date</th>
			<th width='20%'>Date Submitted</th>
		</tr>";
	  
$i = 0;
	
foreach($data as $row){
	if (isset($row['eventSubmitted']))  
	    $eventSubmitted = $row['eventSubmitted'];
    else
	    $eventSubmitted =null;
	
//Display if event is submitted, before result release end date and not survey
if (isset($eventSubmitted['Event']['id'])&&(($currentDate<strtotime($eventSubmitted['Event']['result_release_date_end'])&&$eventSubmitted['Event']['event_template_type_id'] != 3)|| ($currentDate<strtotime($eventSubmitted['Event']['release_date_end'])&&$eventSubmitted['Event']['event_template_type_id'] == 3))) {

    //Condition to check; if after result release begin date, display link to result view page: else just display event title.
    $isResultReleased = ($currentDate >= strtotime($eventSubmitted['Event']['result_release_date_begin']) &&
    $currentDate < strtotime($eventSubmitted['Event']['result_release_date_end']));
	
	echo "<tr class='tablecell'>";
			
    if ($eventSubmitted['Event']['event_template_type_id'] == 1){ 
        if ($isResultReleased && User::hasPermission('functions/viewstudentresults')){
            echo "<td><a href='". $this->webroot.$this->theme . "evaluations/studentViewEvaluationResult/".
            $eventSubmitted['Event']['id']. ";".$eventSubmitted['Event']['group_id']. "'>" .
            $eventSubmitted['Event']['title'] ."</a></td>";
	    }
	    else
       	    echo "<td>".$eventSubmitted['Event']['title']."</td>";
	}
	   
	   
	if ($eventSubmitted['Event']['event_template_type_id'] == 2) {
	    if ($isResultReleased && User::hasPermission('functions/viewstudentresults')) {
            echo "<td><a href='". $this->webroot.$this->theme . "evaluations/studentViewEvaluationResult/"
		    .$eventSubmitted['Event']['id']. ";" . $eventSubmitted['Event']['group_id']. "'>".
		    $eventSubmitted['Event']['title'] . "</a></td>";  
		}
	    else
	        echo "<td>".$eventSubmitted['Event']['title']."</td>";
	}
	 
	if ($eventSubmitted['Event']['event_template_type_id'] == 3) {
	    echo "<td><a href='". $this->webroot.$this->theme."evaluations/studentViewEvaluationResult/"
	    .$eventSubmitted['Event']['id']. ";".$userId . "'>" . $eventSubmitted['Event']['title'] . "</a></td>";
	}
	 
	if ($eventSubmitted['Event']['event_template_type_id'] == 4) {
	    if ($isResultReleased && User::hasPermission('functions/viewstudentresults')) {
        echo "<td><a href='". $this->webroot.$this->theme . "evaluations/studentViewEvaluationResult/"
		.$eventSubmitted['Event']['id'].";". $eventSubmitted['Event']['group_id']."'>".
		$eventSubmitted['Event']['title'] . "</a></td>";  
		}
	 else
	    echo "<td>".$eventSubmitted['Event']['title']."</td>";
	 } 
	 
	echo "</td>";
	 
	//Display the group name and the course name
	echo "<td>".$eventSubmitted['Event']['group_name']."</td>";
	echo "<td>".$eventSubmitted['Event']['course']."</td>" ;
	 
	//Display the due date
	echo "<td>";
	$due_date = $eventSubmitted['Event']['due_date'];
	$timeStamp = strtotime($due_date);
	echo date('F j, y g:i a', $timeStamp) . "</td>";
	 
	//Display the submitted date
	$submit_date = $eventSubmitted['Event']['date_submitted'];
	$timeStamp = strtotime($submit_date);
	echo "<td>". date('F j, y g:i a', $timeStamp) . "</td>";
	 
	echo "</tr>";
	 
	$i++;
}
}
	 
if ($i == 0) {
	echo  "<tr><td colspan='5' align='center'><b><br>'No peer Evaluations submitted.'</b></td></tr>";
}
	
	echo "</table></div>";
			
?>