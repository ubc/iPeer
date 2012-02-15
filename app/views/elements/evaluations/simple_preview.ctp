
<?php
  $groupMembers[0]['User']['id'] = 1;
  $groupMembers[0]['User']['last_name'] = 'User1';
  $groupMembers[0]['User']['first_name'] = 'Test';
  $groupMembers[1]['User']['id'] = 2;
  $groupMembers[1]['User']['last_name'] = 'User2';
  $groupMembers[1]['User']['first_name'] = 'Test';
  $groupMembers[2]['User']['id'] = 3;
  $groupMembers[2]['User']['last_name'] = 'User3';
  $groupMembers[2]['User']['first_name'] = 'Test';
  $remaining = '0';
?>

<script type="text/javascript" language="javascript">
<!--
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
  }
	echo "\n";

	$t_string = "$('total').innerHTML = Math.round(";
		for ($b = 0; $b < count($groupMembers); $b++) {
			$plus = ( $b == 0 ? "" : "+" );
			$t_string .= $plus . "s$b";
		}

	$t_string .= ");";

	echo $t_string;
?>
	}
	//-->
</script>
	<?php echo empty($params['data']['Evaluation']['id']) ? null : $html->hidden('Evaluation/id'); ?>
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('makeSimpleEvaluation') ?>">
      <input type="hidden" name="course_id" value="<?php echo $course_id?>"/>
      <input type="hidden" name="data[Evaluation][evaluator_id]" value="<?php echo $current_user['id']?>"/>


<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center"><?php __('Evaluation Event Detail')?></td>
    </tr>
  <tr class="tablecell2">
    <td width="10%"><?php __('Evaluator')?>:</td>
    <td width="25%"><?php echo $current_user['full_name'] ?></td>
    <td width="10%"><?php __('Evaluating')?>:</td>
    <td width="25%"><?php echo __('Test Group', true)?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Event Name')?>:</td>
    <td><?php echo __('Simple Evaluation Preview', true) ?></td>
    <td><?php __('Due Date')?>:</td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime('0000-00-00'))) ?></td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Description')?>:&nbsp;</td>
    <td colspan="3"><?php echo __('Preview for simple evaluation tool.', true) ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td colspan="3"><?php echo $html->image('icons/instructions.gif',array('alt'=>'instructions'));?>
      <b><?php __('Instructions')?>:</b><br>
      1. <?php __("Rate your peer's relative performance by using the slider. [Weight 1-10]")?><br>
      2. <?php __('Click "Distribute" button to distribute points.')?><br>
      3. <?php __('Allocate any remaining point.')?><br>
      4. <?php __('Enter Comments')?> <?php echo  1? '<font color="red">'.__('Must', true).'</font>' : __('(Optional)', true) ;?> .<br>
    </td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
	<tr class="tableheader">
		<td width="30%"><?php __('Member(s)')?></td>
		<td width="20%"><?php __('Relative Weight')?></td>
		<td width="15%"><?php __('Mark')?></td>
		<td width="35%"><?php __('Comment')?>  <?php echo  1? '<font color=red>*</font>' : __('(Optional)', true) ;?></td>
	</tr>


    <?php $i = 0;
    foreach($groupMembers as $row): $user = $row['User']; ?>
    <tr class="tablecell">
        <td><?php echo $user['last_name'].' '.$user['first_name']?>
      <input type="hidden" name="memberIDs[]" value="<?php echo $user['id']?>"/></td>
      <td width="110"><table><tr>
        <td width="5"><?php __('Min.')?></td>
        <td width="110">
        <div id="track<?php echo $user['id']?>" style="width:120px;background-color:#aaa;height:10px;">
          <div id="handle<?php echo $user['id']?>" style="width:10px;height:15px;background-color:#fa7e04;cursor:move;"> </div>
        </div>
        <div style="height:10px;padding-top:10px;" align="center" id="score<?php echo $user['id']?>"></div>&nbsp;&nbsp;
      </td>
      <td width="5"><?php __('Max.')?></td>
      </tr></table>
        </td>
        <td><input type="text" name="points[]" id="point<?php echo $user['id']?>" value="<?php echo empty($params['data']['Evaluation']['point'.$user['id']])? '' : $params['data']['Evaluation']['point'.$user['id']] ?>" size="5" onkeyup="updateCount('total', 'remaining');">
    </td>
        <td><input type="text" name="comments[]" id="comment<?php echo $user['id']?>" value="<?php echo empty($params['data']['Evaluation']['comment_'.$user['id']])? '' : $params['data']['Evaluation']['comment_'.$user['id']] ?>" style="width:96%">
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
		<td align="center"> <input type="button" name="distr" id="distr_button" value="Distribute" onClick="distribute()"/></td>
		<td align="center">
		  <table width="95%" border="0" align="center"><tr><td colspan="2"><?php __('Points Allocated/Total')?>:</td></tr>
      	 <tr>
      	  <td align="right"><div id="total" style="padding-top: 5px;">0</div></td>
      	  <td align="left"><div id="remaining" style="padding-top: 5px;" >&nbsp;/&nbsp;<?php echo $remaining?></div></td>
      	 </tr>
      	</table>
    </td>
		<td>&nbsp;</td>
	</tr>

  <tr class="tablecell2">
    <td colspan="4" align="center"><?php
      if (!isset($preview)) {
        echo $this->Form->submit(__('Submit Evaluation', true), array("disabled"=>'true') );
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
