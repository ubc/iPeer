<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($data['SimpleEvaluation']['id'])?'add':'edit') ?>" onSubmit="return validate()">
<?php echo empty($data['SimpleEvaluation']['id']) ? null : $this->Form->hidden('id'); ?>
<input type="hidden" name="required" id="required" value="point_per_member" />

<table class="standardtable">
    <tr><th colspan="2" align="center"><?php __('View Simple Evaluation')?></th></tr>
    <tr>
        <td width="254" id="newtitle_label"><?php __('Evaluation Name')?>:</td>
        <td align="left"><?php echo $data['SimpleEvaluation']['name']?></td>
    </tr>
    <tr>
        <td id="description_label"><?php __('Description')?>:</td>
        <td><?php echo $data['SimpleEvaluation']['description']  ?></td>
    </tr>
    <tr>
        <td id="point_per_member_label"><?php __('Base Point Per Member')?>:</td>
        <td><?php echo $data['SimpleEvaluation']['point_per_member']?></td>
    </tr>
    <tr>
        <td id="availability"><?php __('Availability')?>:</td>
        <td><?php echo $data['SimpleEvaluation']['availability']?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <?php if (!empty($popUp) && $popUp) { ?>
        <input type="button" name="Close" value="<?php __('Close')?>" onClick="window.close()">
        <?php } else { ?>
        <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php }?>
    </td>
</table>

</form>

<br />

<h1 onclick="$('rpreview').toggle();" class="title" id="simplePreview">
    <span class="ipeer-icon"><?php __(' Simple Evaluation Preview')?></span>
</h1>

<div id="rpreview">
    <?php echo $this->element('evaluations/simple_eval_form', Toolkit::getSimpleEvalDemoData($data['SimpleEvaluation']['point_per_member'] * 3));?>
</div>
