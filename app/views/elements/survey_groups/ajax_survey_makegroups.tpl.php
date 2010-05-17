<!-- elements::ajax_survey_makegroups end -->
<div id="ajax_update">
<form name="frm" id="frm" method="POST" action="<?php echo $html->url('maketmgroups') ?>">
<input name="survey_id" id="survey_id" type="hidden" value="<?=$survey_id?>">
  <table width="65%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td align="center">Team Making - Step One</td>
    </tr>
    <tr class="tablecell2">
      <td>
  			<?php
  			echo $student_count.' students were specified for this survey, '.$student_response_count.' students responded<br />';
  			?>
			</td>
		</tr>
		<tr class="tablecell2">
			<td>Group Configuration:
  			<select name="group_config" id="group_config">
  			<?php
      		$num_students = $student_count;
      		for($i = 2; $i <= $num_students / 2; $i++) {
      		  echo '<option value="' . $i . '">';
      		  $teams = array_pad(array(),$i,0);
      		  $maxie=0;
      		  for($j = 0; $j < $num_students; $j++)  $maxie=max(++$teams[$j % $i],$maxie);

      		  $counts = array_pad(array(),$maxie+1,0);
      		  for($j = 0; $j < $i; $j++) $counts[$teams[$j]]++;

      		  $output = array();
      		  foreach($counts as $size => $number) if($number!=0){
      		    array_push($output, "$number teams of $size");
      		  }

      		  rsort($output);
      		  echo join(', ' , $output);
      		  echo '</option>'."\n";
      		}
  			?>
  			</select>
      </td>
    </tr><?php if (!isset($questions)||count($questions)<1): ?>
    <tr class="tablecell2"><td>
       <?php echo "There must be at least one question."; ?>
       </td>
    </tr>
    <?php endif;
          $question_count = 1;
          for ($i=1; $i <= count($questions); $i++):
          if ($questions[$i]['Question']['type'] == 'M' || $questions[$i]['Question']['type'] == 'C'): ?>
    <tr class="tablecell2">
    	<td width="40%" colspan="2"><b>Question<?=$question_count;?>: <?=$questions[$i]['Question']['prompt'];?></b><br />
      <table>
        <tr>
          <td align="center">
          <?= $html->image('/survey/correlate.gif',array('alt'=>'correlate')); ?>
          <td bgcolor="#00ff00"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="-5"></td>
          <td bgcolor="#30ff30"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="-4"></td>
          <td bgcolor="#60ff60"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="-3"></td>
          <td bgcolor="#90ff90"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="-2"></td>
          <td bgcolor="#c0ffc0"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="-1"></td>
          <td bgcolor="#ffffff"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="0" checked></td>
          <td bgcolor="#ffcfcf"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="1"></td>
          <td bgcolor="#ff9f9f"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="2"></td>
          <td bgcolor="#ff6f6f"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="3"></td>
          <td bgcolor="#ff3f3f"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="4"></td>
          <td bgcolor="#ff0f0f"><input type="radio" name="weight_<?= $questions[$i]['Question']['id'];?>" value="5"></td>
          <td align="center"><?= $html->image('/survey/differentiate.gif',array('alt'=>'differentiate')); ?></td>
        </tr>
        <tr><td>Gather<br />Similar</td><td colspan="11" align="center">Ignore</td>
          <td align="center">Gather<br />Dissimilar</td></tr>
      </table>
      </td>
    </tr>
    <?php $question_count++; endif; endfor; ?>
    <tr class="tablecell2"><td><b>Note: It may take up to 10mins to create groups.</b></td></tr>
    <tr class="tablecell2">
      <td>
        <div align="center"><?php
         if (!isset($questions)||count($questions)<1)
           echo $html->submit('Next',array('onClick'=>'Element.show(\'loading\');','disabled'=>'true'));
         else
           echo $html->submit('Next',array('onClick'=>'Element.show(\'loading\');')); ?>
           <input type="button" name="Cancel" value="Cancel" onClick="parent.location='javascript:history.go(-1)'">
         </div>
      </td>
    </tr>
  </table>
  <table width="65%" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
    <tr>
      <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
      <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
    </tr>
  </table>
</form>
</div>
<!-- elements::ajax_survey_makegroups end -->