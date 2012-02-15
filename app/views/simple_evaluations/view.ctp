<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($data['SimpleEvaluation']['id'])?'add':'edit') ?>" onSubmit="return validate()">
      <?php echo empty($data['SimpleEvaluation']['id']) ? null : $this->Form->hidden('id'); ?>
      <input type="hidden" name="required" id="required" value="point_per_member" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="3" align="center"><?php __('View Simple Evaluation')?></td>
        </tr>
        <tr class="tablecell2">
	        <td width="254" id="newtitle_label"><?php __('Evaluation Name')?>:</td>
	        <td width="405" align="left">
        	<?php echo $data['SimpleEvaluation']['name']  ?>
          </td>
          <td width="243" id="newtitle_msg" class="error"/>
        </tr>
        <tr class="tablecell2">
        	<td id="description_label"><?php __('Description')?>:</td>
        	<td align="left"><?php echo $data['SimpleEvaluation']['description']  ?></td>
        	<td id="description_msg" class="error"/>
        </tr>
        <tr class="tablecell2">
        	<td id="point_per_member_label"><?php __('Base Point Per Member')?>:</td>
        	<td align="left"><?php echo $data['SimpleEvaluation']['point_per_member']
        	?>
        	</td>
        	<td id="point_per_member_msg" class="error"/>
        </tr>
        <tr class="tablecell2">
          <td colspan="3" align="center">
            <?php if (!empty($popUp) && $popUp) { ?>
            <input type="button" name="Close" value="<?php __('Close')?>" onClick="window.close()">
            <?php } else { ?>
      	    <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
            <?php }?>

      	</td>
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



<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?><?php __(' Simple Evaluation Preview')?> </td>
  <td><div align="right"><a href="#rpreview" onclick="showhide('rpreview'); toggle(this);"><?php echo empty($data) ? '[+]' : '[-]'; ?></a></div></td>
  </tr>
</table>
<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;">' : 'style="display: block; background: #FFF;">'; ?>
<br />

<br />
<?php
//$params = array('controller'=>'simpleevaluations','data'=>$this->controller->EvaluationHelper->formatEventObj($data), 'evaluate'=>0);
echo $this->element('evaluations/simple_preview', array('current_user' => $user,
                                                        'course_id' => 0));
?>
</div>
