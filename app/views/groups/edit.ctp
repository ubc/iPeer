<?php echo $html->script('groups')?>
<?php $readonly = isset($readonly) ? $readonly : false;?>

<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
  <td>    
  <?php echo $this->Form->create('Group', 
                                 array('id' => 'frm',
                                       'url' => array('action' => $this->action.'/'.$course_id),
                                       'inputDefaults' => array('div' => false,
                                                                'before' => '<td width="200px">',
                                                                'after' => '</td>',
                                                                'between' => '</td><td>')))?>
  <?php echo $this->Form->input('course_id', array('type' => 'hidden'));?>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="4" align="center"><?php echo ucfirst($this->action)?> Group</td>
  </tr>

  <tr class="tablecell2">
    <?php echo $this->Form->input('group_num', array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly)) ?>
  </tr>

  <tr class="tablecell2">
    <?php echo $this->Form->input('group_name', array('size'=>'50', 'class'=>'input',
                                                      'readonly' => $readonly)) ?>
  </tr>

  <tr class="tablecell2">
    <td>Status:</td>
    <td><?php echo $this->Form->select('record_status', array('A' => 'Active', 'I' => 'Inactive'), null, array('empty' => false,
                                                                                                               'disabled' => $readonly))?></td>
  </tr>

  <tr class="tablecell2">
    <td>Members:</td>
    <td>
    <?php if($readonly):?>
      <?php if(!empty($members)):?>
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <?php foreach($members as $id => $name):?>
      <tr>
        <td width="15"><?php echo $html->image('icons/email_icon.gif',array('border'=>'0','alt'=>'Email'))?></td>
        <td><?php echo $this->Html->link($name, '/users/view/'.$id)?></td>
      </tr>
      <?php endforeach;?>
    </table>
      <?php else:?>
        No members in this group.
      <?php endif;?>
    <?php else:?>
    <?php echo $this->element("groups/group_list_chooser",
                array('all' => $user_data,
                      'allName' =>  "Filtered Students", 'selectedName' => 'Students in Group'));
    ?>
    <?php endif;?>
    </td>
  </tr>

  <?php if(!$readonly):?>
  <tr class="tablecell2">
    <td colspan="2" align="center"><?php echo $this->Form->submit(ucfirst($this->action).' Group', array('div' => false,
                                                                                                         'onClick' => "processSubmit(document.getElementById('selected_groups'))")) ?>
    </td>
  </tr>
  <?php endif;?>
  </table>

  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
  </table>


  <?php if($readonly):?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td>
      <?php echo $this->Html->link('Edit this Group', '/groups/edit/'.$data['Group']['id']); ?> | 
      <?php echo $html->link('Back to Group Listing', '/groups/index/'.$data['Group']['course_id']); ?>
    </td>
  </tr>
  </table>
  <?php endif;?>

	</td>
</tr>
</table>
<?php
    echo $this->Form->hidden('Group.id', array('value' => $group_id));
    echo $this->Form->end();
?>
