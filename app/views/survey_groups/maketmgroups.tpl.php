<form name="frm" id="frm" method="POST" action="<?php echo $html->url('savegroups') ?>">
<input type="hidden" name="filename" id="filename" value="<?php if (!empty($filename)) echo $filename; ?>" >
<input type="hidden" name="survey_id" id="survey_id" value="<?php if (!empty($survey_id)) echo $survey_id; ?>" >
<table width="100%" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td align="center">Teams Summary</td>
          </tr>
          <tr class="tablecell2">
            <td>Click on any user name to view their answers to this survey. To adjust weightings and create a new set of teams, just go <a href="javascript:history.go(-1);">back</a>.<br/><br/>To edit these teams, first save them below.<br/><br/><b>Note:</b> Higher 'Match Score' is better. <br/><br/>
            <table style="border-collapse:collapse;border-top:hidden;">
            <tr>
      			<?php
      			echo '<th>Team Name</th>';
    			  for ($j=0; $j < (count($scores[0])-2); $j++)
    			    echo '<th width="40"><a href="'.$this->webroot.$this->themeWeb.'evaluations/viewSurveySummary/'.$survey_id.'" onclick="wopen(this.href, \'popup\', 650, 500); return false;">Q'.($j+1).'</a></th>';
      			echo '<th>Match Score</th>';
      			echo '<th colspan="40">Team Members</th></tr>';
      			for ($i=0; $i < count($teams); $i++) {
      			  $team = $teams[$i];
      			  echo '<tr class="tablecell" style="border-top:solid #cccccc;">';
      			  echo '<td width="100">Team #'.($i+1).'</td>';
      			  for ($j=0; $j < (count($scores[0])-2); $j++)
      			    echo isset($scores[$i]['q_'.$j])?'<td>'.$scores[$i]['q_'.$j].'</td>':'<td>-</td>';
      			  echo '<td width="50">'.(isset($scores[$i]['percent'])? $scores[$i]['percent']:'-').'</td>';
      			  for ($j=0; $j < count($team);$j++) {
      			?>
      			<td width="70"><a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/viewEvaluationResults/'.$event_id.'/'.$team['member_'.$j]['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $team['member_'.$j]['student_no']?></a></td>
      			<?php
      			  }
      			  echo '</tr>';
      			}
      			echo '</table>';
          //  echo $scores;
      			?>
      			</td>
          </tr>
          <tr class="tablecell2"><td>Group Set Name: <input type="text" name="team_set_name" id="team_set_name" size="35"/></td></tr>
          <tr class="tablecell2">
            <td><div align="center"><?php echo $html->submit('Save Groups') ?>
			<input type="button" name="Cancel" value="Cancel" onClick="javascript:history.go(-1);" />
			</div></td>
          </tr>
      </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table></td>
  </tr>
</table></form>
