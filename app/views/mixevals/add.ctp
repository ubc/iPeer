<body onunload="window.opener.document.getElementById('eval_dropdown').onchange()">
<?php //isset($data)?print_r($data):true;
	if (empty($layout)) $layout=''; ?>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Mixeval']['id'])?'add':'edit').'/'.$layout ?>" onSubmit="return validate()">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td><?php echo $html->script('showhide')?>
	<?php
	if(!empty($data)){
	  //print_r($data);
		$mixeval_name = $data['Mixeval']['name'];
		$scale_default = $data['Mixeval']['scale_max'];
		$prefill_question_max = $data['Mixeval']['prefill_question_max'];
		//$question_default = $data['Mixeval']['total_marks'];
		$question_default = $data['Mixeval']['lickert_question_max'];
		$mixeval_avail = $data['Mixeval']['availability'];
		if(!empty($data['Mixeval']['zero_mark']))
			//$zero_mark = $this->data['Mixeval']['zero_mark'];
      $zero_mark = 'on';
		else
			$zero_mark='off';
	}
	else{
		$mixeval_name = '';
		$scale_default = 5;
		$question_default = 3;
		$prefill_question_max = 3;
		$mixeval_avail = 'public';
		$zero_mark = 'off';
	}

	?>
	<input type="hidden" name="required" id="required" value="mixeval_name" />
      <?php echo empty($params['data']['Mixeval']['id']) ? null : $html->hidden('Mixeval/id'); ?>
      <?php echo empty($params['data']['Mixeval']['id']) ? $html->hidden('Mixeval/creator_id', array('value'=>$this->Auth->user('id'))) : $html->hidden('Mixeval/updater_id', array('value'=>$this->Auth->user('id'))); ?>

	  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center">
	    <?php echo $html->hidden('Mixeval/user_id', array('value'=>$this->Auth->user('id'))); ?>
      <?php echo empty($params['data']['Mixeval']['id'])?'Add':'Edit' ?> <?php __('Mix Evaluation')?>
    </td>
    </tr>
  <tr class="tablecell2">
    <td width="209" id="mixeval_name_label"><?php __('Mix Evaluation Name')?>:<font color="red">*</font></td>
    <td width="301"><?php echo $html->input('Mixeval/name', array('size'=>'30','class'=>'validate required TEXT_FORMAT mixeval_name_msg Invalid_Text._At_Least_One_Word_Is_Required.','value'=>$mixeval_name, 'id'=>'mixeval_name')) ?></td>
    <td width="353" id="mixeval_name_msg" class="error" />
  </tr>

  <tr class="tablecell2">
    <td><?php __('Number of Lickert Question')?>:</td>
    <td>

    <?php if (empty($data)) { ?>
        <?php echo $html->selectTag('Mixeval/lickert_question_max', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17',
									'18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25'), $question_default,
									array('style'=>'width:50px;','id'=>'lickert_question_max'),'',false) ?>

        <?php } else { ?>
              <input type="hidden" id="lickert_question_max" value="<?php echo $question_default?>" name="data[Mixeval][lickert_question_max]">
              <?php echo "<b>&nbsp;&nbsp;$question_default&nbsp;&nbsp;</b>";?>
        <?php } // ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php __('Level of Scale')?>:
		&nbsp;&nbsp;
		<?php echo $html->selectTag('Mixeval/scale_max', array('2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10'), $scale_default, array('style'=>'width:50px;','id'=>'LOM'),'',false) ?>
		</td>
    <td><!--Number of Lickert Question Aspects (Max 25)--> </td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Number of Pre-fill Text Question')?>:</td>
    <td><?php echo $html->selectTag('Mixeval/prefill_question_max', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8',
									'9'=>'9','10'=>'10'), $prefill_question_max, array('style'=>'width:50px;','id'=>'LOM'),'',false) ?></td>
    <td><!--Number of Pre-fill Text Question Aspects (Max 10)--> </td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Mixed Evaluation Availability')?>:<font color="red">*</font></td>
    <td><?php echo $html->selectTag('Mixeval/availability', array('public'=>'public','private'=>'private'), $mixeval_avail, array('style'=>'width:100px;'),'',false) ?></td>
    <td><?php __('Public allows Mixed Evaluations sharing amongst instructors')?> </td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Zero Mark')?>: </td>
    <td><?php echo $html->checkbox('Mixeval/zero_mark', array('size'=>'50','class'=>'self_enroll', 'id'=>'zero_mark',  'checked'=>$zero_mark)) ?></td>
    <td><?php __('No marks given for Level of Scale of 1')?></td>
  </tr>
  <tr class="tablecell2">
  		<td colspan="3" align="center">
        <input type="button" name="Back" value=<?php __("Back", true)?> onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        &nbsp;&nbsp;
		<?php
		if(!empty($data)){
		  echo $html->submit(__('Add Mixed Evaluation', true));
		} else {
		  echo $html->submit(__('Next', true), array('Name'=>'next'));
		} ?>
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
<?php if(!empty($data)){ ?>
<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> <?php __('Mix Evaluation Preview')?> </td>
	<td><div align="right"><a href="#rpreview" onclick="showhide('rpreview'); toggle(this);"><?php echo empty($data) ? '[+]' : '[-]'; ?></a></div></td>
  </tr>
</table>
<?php } ?>
<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;"' : 'style="display: block; background: #FFF;"'; ?>>
<br>
<?php
$params = array('controller'=>'mixevals',
                'data'=>isset($data)?$data:null,
                'scale_default'=>$scale_default,
                'question_default'=>$question_default,
                'mixeval_avail'=>$mixeval_avail,
                'prefill_question_max'=>$prefill_question_max,
                'zero_mark'=>$zero_mark);
echo $this->element('mixevals/ajax_mixeval_details', $params); ?>

</div>
</form>
</body>
