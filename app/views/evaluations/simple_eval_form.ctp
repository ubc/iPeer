<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<script type="text/javascript" language="javascript">

  function updateCount(allPoints,commentsRequired) {
    <?php
      $ids = array();
      foreach($groupMembers as $row) {
        $user = $row['User'];
        array_push($ids, $user['id']);
      }
      foreach ($ids as $id) {
        echo "floatPoints = document.getElementById('point".$id."');";
        echo "intPoints = Math.round(parseFloat(floatPoints.value));";
        echo "floatPoints.value = intPoints;";
      }
      echo "var totalPoints = 0;\n";
      echo "var emptyComments = 0;\n";
      foreach ($ids as $id) {
        // counting total points
        echo "if ((!isNaN(parseFloat(document.getElementById('point".$id."').value)))) {";
        echo "totalPoints += parseFloat(document.getElementById('point".$id."').value);}\n";
        // counting total number of comments
        echo "if(document.getElementById('comment".$id."').value.length == 0) {";
        echo "emptyComments++;}\n";
      }
      echo "var submitButton = document.getElementById('submit0');";
      echo "if (totalPoints == parseFloat(allPoints)) {";
      echo "  if (commentsRequired == 0) {";
      echo "    $('statusMsg').innerHTML = '".__('All points are allocated.', true)."';";
      
      echo "    submitButton.disabled = false;";
      echo "  }";
      echo "  else if (commentsRequired == 1 && emptyComments != 0) {";
      echo "    $('statusMsg').innerHTML = '".__('All points are allocated.<br />There are still', true)."<font color=red>' + emptyComments + '</font> ".__('comments to be filled.', true)." ';";
      echo "    submitButton.disabled = true;";
      echo "  }";
      echo "  else if (commentsRequired == 1 && emptyComments == 0) {";
      echo "    $('statusMsg').innerHTML = '".__('All points are allocated.<br />All comments are filled.', true)." ';";
      echo "    submitButton.disabled = false;";
      echo "  }";
      echo "}";

      echo "else if (totalPoints > parseFloat(allPoints)) {";
      echo "  diff = totalPoints - parseFloat(allPoints);";
      echo "  if (commentsRequired == 0) {";
      echo "    $('statusMsg').innerHTML = '".__('Too many points, need to unallocate', true)." <font color=red>' + diff + '</font> ".__('points.')." ';";
      echo "  }";
      echo "  else if (commentsRequired == 1 && emptyComments != 0) {";
      echo "    $('statusMsg').innerHTML = '".__('Too many points, need to unallocate', true)." <font color=red>' + diff + '</font> ".__('points.<br />There are still', true)." <font color=red>' + emptyComments + '</font> ".__('comments to be filled.', true)." ';";
      echo "  }";
      echo "  else if (commentsRequired == 1 && emptyComments == 0) {";
      echo "    $('statusMsg').innerHTML = '".__('Too many points, need to unallocate', true)." <font color=red>' + diff + '</font ".__('points.<br />All comments are filled.', true)." ';";
      echo "  }";
      echo "  submitButton.disabled = true;";
      echo "}";

      echo "else if (totalPoints < parseFloat(allPoints)) {";
      echo "  diff = parseFloat(allPoints) - totalPoints;";
      echo "  if (commentsRequired == 0) {";
      echo "    $('statusMsg').innerHTML = '".__('Please allocate', true)." <font color=green>' + diff + '</font> ".__('more points.', true)."';";
      echo "  }";
      echo "  else if (commentsRequired == 1 && emptyComments != 0) {";
      echo "    $('statusMsg').innerHTML = '".__('Please allocate', true)." <font color=green>' + diff + '</font>".__(' more points.<br />There are still', true)." <font color=red>' + emptyComments+ '</font>".__('comments to be filled.', true)."';";
      echo "  }";
      echo "  else if (commentsRequired == 1 && emptyComments == 0) {";
      echo "    $('statusMsg').innerHTML = '".__('Please allocate', true)." <font color=green>' + diff + '</font>".__(' more points.<br />All comments are filled.', true)."';";
      echo "  }";
      echo "  submitButton.disabled = true;";
      echo "}";

      echo "else {submitButton.disabled = true;}";

      echo "$('total').innerHTML = totalPoints;";
    ?>
  }

	function distribute() {
<?php
	echo "\n";
	for ($z = 0; $z < count($groupMembers); $z++) {
	  $user = $groupMembers[$z]['User'];
	  $id = $user['id'];
		echo "var v$z = parseInt($('score$id').innerHTML);";
		echo "\n";
	}
	for ($z = 0; $z < count($groupMembers); $z++)
	{
	  $user = $groupMembers[$z]['User'];
  	//TODO: if(!is_bool($groupMembers[$z]) && $groupMembers[$z]!==NULL){ //happens with groups of size1
  		$id = $user['id']; // this is a  null object for some reason
  		$string = "document.forms['evalForm'].elements['point$id'].value = Math.floor((v$z/(";
  		echo "\n";
  		for ($a = 0; $a < count($groupMembers); $a++) {
  			$plus = ( $a == 0 ? "" : "+" );
  			$string .= $plus . "v$a";
  		}

  		$total = $remaining;
  		$string .= "))*$total);";
  		echo $string;
  		echo "\n";
  		echo "var s$z = parseFloat(document.forms['evalForm'].elements['point$id'].value);";
  	//}
  }
	echo "\n";

	$t_string = "$('total').innerHTML = Math.round(";
		for ($b = 0; $b < count($groupMembers); $b++) {
			$plus = ( $b == 0 ? "" : "+" );
			$t_string .= $plus . "s$b";
		}

	$t_string .= ");";
	echo $t_string;
  $com_req = $event['Event']['com_req'];
  echo "updateCount($remaining, $com_req);";
?>
  }<?php

if ($event['Event']['id']==292) {
	$event['Event']['description']=$this->controller->SimpleEvaluation->field('description','id=50');
}

?>
	//-->
</script>

	<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeSimpleEvaluation/'.$event['Event']['id']) ?>">
      <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>"/>
      <input type="hidden" name="group_id" value="<?php echo $event['group_id']?>"/>
      <input type="hidden" name="course_id" value="<?php echo $courseId?>"/>
      <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $userId ?>"/>
      <input type="hidden" name="evaluateeCount" value="<?php echo $evaluateeCount?>"/>

      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center"><?php __('Evaluation Event Detail')?></td>
    </tr>
  <tr class="tablecell2">
    <td width="10%"><?php __('Evaluator')?>:</td>
    <td width="25%"><?php echo $fullName ?>
    </td>
    <td width="10%"><?php __('Evaluating')?>:</td>
    <td width="25%"><?php echo $event['group_name'] ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Event Name')?>:</td>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php __('Due Date')?>:</td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Description')?>:&nbsp;</td>
    <td colspan="3"><?php echo $event['Event']['description'] ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
      <b><?php __(' Instructions')?>:</b><br>
      1. <?php __("Rate your peer's relative performance by using the slider. [Weight 1-10]")?><br>
      2. <?php __('Click "Distribute" button to distribute points.')?><br>
      3. <?php __('Allocate any remaining point.')?><br>
      4. <?php __('Enter Comments')?> <?php echo  $event['Event']['com_req']? '<font color="red"> (Must) </font>' : '(Optional)' ;?> .<br>
      5: <font color="red"><blink><?php __('NOTE:')?></blink></font> <?php __('"Submit Evaluation" button will only be enabled when all points, and comments (if required), are filled!')?>
    </td>
  </tr>
</table>

    <div style="text-align:left; margin-left:3em;"><a href="#" onClick="javascript:$('penalty').toggle();return false;">( <?php __('Show/Hide late penalty policy')?> )</a></div>
    <div id ="penalty" style ="border:1px solid red; margin: 0.5em 0 0 3em; width: 450px; padding:0.5em; color:darkred; display:none">
    	   	
	<?php if(!empty($penalty)){
	  
	  if($penaltyType == -1){
      echo $penalty[0]['Penalty']['percent_penalty'].'% is deducted every day for '.$penaltyDays.' days. '.$penaltyFinal['Penalty']['percent_penalty'].'% is deducted afterwards.';	    
	  }
	  
	  if($penaltyType == -2){
      foreach($penalty as $day){  
        $mult = ($day['Penalty']['days_late']>1)?'s':'';
        echo $day['Penalty']['days_late'].' day'.$mult.' late: '.$day['Penalty']['percent_penalty'].'% deduction. </br>'; 
      }
      echo $penaltyFinal['Penalty']['percent_penalty'].'% is deducted afterwards.';	    
	  }	 
	 } else {
	   echo 'No penalty is specified for this evaluation.';	   
	 }
	
	?>    

  </div>

    </br>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader">
		<td width="30%"><?php __('Member(s)')?></td>
		<td width="20%"><?php __('Relative Weight')?></td>
		<td width="15%"><?php __('Mark')?></td>
		<td width="35%"><?php __('Comment')?>  <?php echo $event['Event']['com_req']? '<font color=red>*</font>' : __('(Optional)', true) ;?></td>
	</tr>
                   <?php $i = 0;
    foreach($groupMembers as $row): $user = $row['User'];
    ?>
    <tr class="tablecell">
        <td><?php echo $user['last_name'].' '.$user['first_name']?>
      <input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/></td>
      <td width="110"><table><tr>
        <td width="5">Min.</td>
        <td width="110">
        <div id="track<?php echo $user['id']?>" style="width:120px;background-color:#aaa;height:10px;">
          <div id="handle<?php echo $user['id']?>" style="width:10px;height:15px;background-color:#fa7e04;cursor:move;"> </div>
        </div>
        <div style="height:10px;padding-top:10px;" align="center" id="score<?php echo $user['id']?>"></div>&nbsp;&nbsp;
      </td>
      <td width="5">Max.</td>
      </tr></table>
                </td>
                 <td><input type="text" name="points[]" id="point<?php echo $user['id']?>" value="<?php echo empty($params['data']['Evaluation']['point'.$user['id']])? '' : $params['data']['Evaluation']['point'.$user['id']] ?>" size="5" onchange="updateCount(<?php echo $remaining?>,<?php echo $event['Event']['com_req']?>);">
    </td>
                 <td><input type="text" name="comments[]" id="comment<?php echo $user['id']?>" value="<?php echo empty($params['data']['Evaluation']['comment_'.$user['id']])? '' : $params['data']['Evaluation']['comment_'.$user['id']] ?>" size="50" onchange="updateCount(<?php echo $remaining?>,<?php echo $event['Event']['com_req']?>);">
      <script type="text/javascript" language="javascript">

            function onSlide(v){
                $(<?php echo "'score".$user['id']."'"?>).innerHTML=(v+1);
            }

            var defaultValue = 4;

            new Control.Slider(
                <?php echo "'handle".$user['id']."'"?>,
                <?php echo "'track".$user['id']."'"?>,
                {values:        [0,1,2,3,4,5,6,7,8,9],
                 range:         $R(0,9),
                 increment:     10,
                 sliderValue:   defaultValue,
                 onSlide:       onSlide,
                 onChange:      onSlide
                }
            );

            onSlide(defaultValue);
    </script></td>
	</tr>
	<?php $i++;?>
	<?php endforeach; ?>
	<tr class="tablecell">
		<td>

  </td>
		<td align="center"> <input type="button" name="distr" id="distr_button" value="<?php __('Distribute')?>" onClick="distribute()"/></td>
		<td align="center">
		  <table width="95%" border="0" align="center"><tr><td colspan="2"><?php __('Points Allocated/Total:')?></td></tr>
      	 <tr>
      	  <td align="right"><div id="total" style="padding-top: 5px;">0</div></td>
      	  <td align="left"><div id="remaining" style="padding-top: 5px;" >&nbsp;/&nbsp;<?php echo $remaining?></div></td>
      	 </tr>
      	</table>
    </td>
		<td align="center" id="statusMsg"></td>
	</tr>

  <tr class="tablecell2">
    <td colspan="4" align="center"><?php
      if (!isset($preview)) {
        echo $form->submit(__('Submit Evaluation', true), array('id' => 'submit0', 'disabled' => 'true', 'onClick' => "javascript:return confirm('".__('Once you submit the input, you cannot change them. Please review your input before submitting. Are you sure you want to submit?', true)."')"));
      }
      ?></td>
    </tr>
</table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
          <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
      </table>
    </form>
	</td>
  </tr>
</table>

