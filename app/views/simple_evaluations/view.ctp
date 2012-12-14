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

<table class="standardtable">
  <tr>
    <th onclick="$('rpreview').toggle();">
        <?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?><?php __(' Simple Evaluation Preview')?>
    </th>
  </tr>
</table>

<div id="rpreview" style="display: none;">
<?php
echo $this->element('evaluations/simple_eval_form', array(
    'event' => array(
        'Event' => array(
            'id' => 0,
            'title' => 'Preview Event',
            'due_date' => Toolkit::formatDate(time()+(5*24*60*60)),
            'description' => 'Preview for simple evaluation event.',
            'com_req' => true,
        ),
        'Group' => array(
            'id' => 0,
            'group_name' => 'Demo Group',
        ),
    ),
    'groupMembers' => array(
        array(
            'User' => array(
                'id' => 1,
                'first_name' => 'Demo',
                'last_name'  => 'Student1',
            ),
        ),
        array(
            'User' => array(
                'id' => 2,
                'first_name' => 'Demo',
                'last_name'  => 'Student2',
            ),
        ),
        array(
            'User' => array(
                'id' => 3,
                'first_name' => 'Demo',
                'last_name'  => 'Student3',
            ),
        ),
    ),
    'courseId' => 0,
    'userId' => 0,
    'evaluateeCount' => 2,
    'fullName' => User::get('full_name'),
    'remaining' => $data['SimpleEvaluation']['point_per_member'] * 3,
    'preview' => true,
));
?>
</div>
