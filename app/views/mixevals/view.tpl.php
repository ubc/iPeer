<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td>
	<?php
	if(!empty($data) && is_array($data)){
		$mixeval_name = $data['Mixeval']['name'];
		$scale_default = $data['Mixeval']['scale_max'];
		$prefill_question_max = $data['Mixeval']['prefill_question_max'];
		$question_default = $data['Mixeval']['lickert_question_max'];
		$mixeval_avail = $data['Mixeval']['availability'];
		$total_mark = $data['Mixeval']['total_marks'];
		if(!empty($data['Mixeval']['zero_mark']))
			$zero_mark = $data['Mixeval']['zero_mark'];
		else
			$zero_mark='off';
	}
	else{
		$mixeval_name = '';
		$scale_default = 5;
		$question_default = 3;
		$prefill_question_max = 3;
		$mixeval_avail = 'public';
		$total_mark = 5;
		$zero_mark = 'off';
	}
	?>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Mixeval']['id'])?'add':'edit') ?>" onSubmit="return validate()">
	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center">
	    <?php echo $html->hidden('Mixeval/user_id', array('value'=>$rdAuth->id)); ?>
      View Mix Evaluation
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="209" id="mixeval_name_label">Mix Evaluation Name:</td>
    <td width="301"><?php echo $data['Mixeval']['name']?></td>
    <td width="353" id="mixeval_name_msg" class="error" />
  </tr>

  <tr class="tablecell2">
    <td>Number of Likert Question:</td>
    <td><?php echo $data['Mixeval']['lickert_question_max']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Level of Scale:&nbsp;&nbsp;
		<?php echo $data['Mixeval']['scale_max']?>
		</td>
    <td>Number of Likert Question Aspects (Max 25) </td>
  </tr>
  <tr class="tablecell2">
    <td>Number of Pre-fill Text Question:</td>
    <td><?php echo $data['Mixeval']['prefill_question_max']?></td>
    <td>Number of Pre-fill Text Question Aspects (Max 10) </td>
  </tr>
  <tr class="tablecell2">
    <td>Mix Evaluation Availability:</td>
    <td><?php echo $data['Mixeval']['availability']?></td>
    <td>Public Allows Mixeval Sharing Amongst Instructors </td>
  </tr>
  <tr class="tablecell2">
    <td>Zero Mark: </td>
    <td><?php echo $data['Mixeval']['zero_mark']?></td>
    <td>No Marks Given for Level of Scale of 1</td>
  </tr>
  <tr class="tablecell2">
		<td colspan="3" align="center">
		<input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
	  </td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
</form><br>
	</td>
  </tr>
</table>

<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Mix Evaluation Preview </td>
	<td><div align="right"><a href="#rpreview" onclick="showhide('rpreview'); toggle(this);"><?php echo empty($data) ? '[+]' : '[-]'; ?></a></div></td>
  </tr>
</table>
<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;">' : 'style="display: block; background: #FFF;">'; ?>
<br />
<?php
$params = array('controller'=>'mixevals','data'=>$this->controller->MixevalHelper->compileViewData($data), 'scale_default'=>$scale_default, 'question_default'=>$question_default, 'mixeval_avail'=>$mixeval_avail, 'prefill_question_max'=>$prefill_question_max, 'zero_mark'=>$zero_mark, 'total_mark'=>$total_mark, 'evaluate'=>0);
echo $this->renderElement('mixevals/view_mixeval_details', $params);
?>
</div>


<script>
calculateMarks('<?php echo $scale_default?>','<?php echo $question_default?>','<?php echo $zero_mark?>');
</script>
