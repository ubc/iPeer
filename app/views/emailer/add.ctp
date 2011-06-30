<?php $readonly = isset($readonly) ? $readonly : false;?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($this->data['CustomEmail']['id'])?'add':'edit/'.$this->data['CustomEmail']['id']) ?>">

      <?php echo empty($this->data['CustomEmail']['id']) ?
              $form->hidden('CustomEmail.user_id', array('value'=>$currentUser['id'])) :
              $form->hidden('CustomEmail.id', array('value'=>$this->data['CustomEmail']['id'])); ?>
      <?php //echo empty($params['data']['CustomEmail']['id']) ? $form->hidden('CustomEmail.creator_id', array('value'=>$currentUser['id'])) : $form->hidden('CustomEmail.updater_id', array('value'=>$currentUser['id'])); ?>
      <input type="hidden" name="assigned" id="assigned" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
  <td colspan="3" align="center">
    <?php
      echo $readonly ?
            ('View') :
            (empty($this->data['CustomEmail']['id'])?'Add':'Edit');
    ?> Custom Email Template</td>
  </tr>
  <tr class="tablecell2">
  	<td id="newemail_label">Custom Email Name:&nbsp;<font color="red">*</font></td>
  	<td>
        <?php echo $form->input('CustomEmail.name', array('size'=>'50','label'=>false,'readonly'=>$readonly));?>
        </td>
        <td>&nbsp;</td>
  </tr>

  <tr class="tablecell2">
    <td>Availability:</td>
    <td>
      <?php
        echo $form->input('CustomEmail.availability', array(
           'type' => 'radio',
           'options' => array('0' => ' - Private&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                              '1' => ' - Public&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                              '2' => ' - shared&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'),
           'default' => '0',
           'legend' => false,
           'disabled'=>$readonly
        ));
      ?>
    </td>
    <td>&nbsp;</td>
  </tr>

  <tr class="tablecell2">
    <td>Description:&nbsp;</td>
    <td><?php echo $form->textarea('CustomEmail.description', array('rows' => '5', 'style'=>'width:70%;', 'readonly'=>$readonly)) ?>  </td>
    <td>&nbsp;</td>
  </tr>

  <tr class="tablecell2">
    <td>Content:&nbsp;</td>
    <td><?php echo $form->textarea('CustomEmail.content', array('rows' => '30', 'style'=>'width:70%;', 'readonly'=>$readonly)) ?>  </td>
    <td>&nbsp;</td>
  </tr>

  <tr class="tablecell2">
    <td colspan="3" align="center"><?php if (!$readonly) echo $this->Form->submit() ?></td>
  </tr>
</table>
    </form>
	</td>
  </tr>
</table>