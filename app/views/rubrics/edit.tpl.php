<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Rubric']['id']) || (!empty($copy) && $copy) ? 'add':'edit') ?>">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td>
	<?php
	$this->data = $this->params['data'];
	if(!empty($this->data)){
		$rubric_name = $this->data['Rubric']['name'];
		$lom_default = $this->data['Rubric']['lom_max'];
		$criteria_default = $this->data['Rubric']['criteria'];
		$rubric_avail = $this->data['Rubric']['availability'];
		$rubric_type = $this->data['Rubric']['template'];
		if(!empty($this->data['Rubric']['zero_mark']))
			$zero_mark = $this->data['Rubric']['zero_mark'];
		else
			$zero_mark='off';
	}
	else{
		$rubric_name = '';
		$lom_default = 5;
		$criteria_default = 3;
		$rubric_avail = 'public';
		$rubric_type = 'horizontal';
		$zero_mark = 'off';
	}
	if (!empty($copy) && $copy) {
	  $params['data']['Rubric']['id'] = null;
	}
	?>

    <?php echo empty($params['data']['Rubric']['id']) ? null : $html->hidden('Rubric/id'); ?>
    <?php echo empty($params['data']['Rubric']['id']) ? null : $html->hidden('Rubric/id'); ?>
    <?php echo empty($params['data']['Rubric']['id']) ? $html->hidden('Rubric/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Rubric/updater_id', array('value'=>$rdAuth->id)); ?>
    <br/>
	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center">
      <?php echo empty($params['data']['Rubric']['id'])?'Add':'Edit' ?> Rubric
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="209">Rubric Name:<font color="red">*</font></td>
    <td width="301"><?php echo $html->input('Rubric/name', array('size'=>'30','class'=>'input','value'=>$rubric_name)) ?></td>
    <td width="353">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td>Level of Mastery:</td>
    <td><?php echo $html->selectTag('Rubric/lom_max', array('2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10'), $lom_default, array('style'=>'width:50px;','id'=>'LOM'),'',false) ?></td>
    <td>aka LOM, Evaluation Range (Max 10) </td>
  </tr>
  <tr class="tablecell2">
    <td>Number of Criteria:</td>
    <td><?php echo $html->selectTag('Rubric/criteria', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17',
									'18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25'), $criteria_default,
									array('style'=>'width:50px;','id'=>'criteria'),'',false) ?></td>
    <td>Number of Evaluation Aspects (Max 25) </td>
  </tr>
  <tr class="tablecell2">
    <td>Rubric Availability:<font color="red">*</font></td>
    <td><?php echo $html->selectTag('Rubric/availability', array('public'=>'public','private'=>'private'), $rubric_avail, array('style'=>'width:100px;'),'',false) ?></td>
    <td>Public Allows Rubric Sharing Amongst Instructors </td>
  </tr>
  <tr class="tablecell2">
    <td>Zero Mark:
  <input type="hidden" name="data[Rubric][template]" value="horizontal"/></td>
    <td><?php echo $html->checkbox('Rubric/zero_mark', array('size'=>'50','class'=>'self_enroll', 'id'=>'zero_mark',  'checked'=>$zero_mark)) ?></td>
    <td>No Marks Given  for Level of Mastery of 1</td>
  </tr>
  <tr class="tablecell2">
  		<td colspan="3" align="center">
        <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
		<?php echo $html->submit('Preview', array('Name'=>'preview')) ?>
		<?php
		$actionname = empty($params['data']['Rubric']['id'])?'Add Rubric':'Edit Rubric';
		echo $html->submit($actionname, array('id' => 'submit-rubric')) ?>
		</td>
    </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
<br>
	</td>
  </tr>
</table>
<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Rubric Preview </td>
	<td><div align="right"><a href="#rpreview" onclick="$('rpreview').toggle(); toggle1(this);"><?php echo empty($this->data) ? '[-]' : '[-]'; ?></a></div></td>
  </tr>
</table>
<div id="rpreview" <?php echo empty($this->data) ? 'style="display: none; background: #FFF;">' : 'style="display: block; background: #FFF;"'; ?>>
<br>
<?php
  $params = array('controller'=>'rubrics','data'=>$this->controller->RubricHelper->compileViewData($this->data));
  echo $this->renderElement('rubrics/ajax_rubric_edit', $params);
?>
</div>
</form>

<script type="text/javascript">
$('LOM').observe('change', function(event){
  $('submit-rubric').disable();
})
$('criteria').observe('change', function(event){
  $('submit-rubric').disable();
})
</script>
