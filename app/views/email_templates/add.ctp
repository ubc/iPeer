<?php 
  echo $html->script('emailtemplates.js');
  $readonly = isset($readonly) ? $readonly : false;
?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($this->data['EmailTemplate']['id'])?'add':'edit/'.$this->data['EmailTemplate']['id']) ?>">

      <?php echo empty($this->data['EmailTemplate']['id']) ?
              $form->hidden('EmailTemplate.user_id', array('value'=>$currentUser['id'])) :
              $form->hidden('EmailTemplate.id', array('value'=>$this->data['EmailTemplate']['id'])); ?>
      <?php //echo empty($params['data']['EmailTemplate']['id']) ? $form->hidden('EmailTemplate.creator_id', array('value'=>$currentUser['id'])) : $form->hidden('EmailTemplate.updater_id', array('value'=>$currentUser['id'])); ?>
      <input type="hidden" name="assigned" id="assigned" />
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
  <td colspan="3" align="center">
    <?php
      echo $readonly ?
            __('View') :
            (empty($this->data['EmailTemplate']['id'])?'Add':'Edit');
    ?> <?php __('Custom Email Template')?></td>
  </tr>
  <tr class="tablecell2">
  	<td width="20%"><?php __('Name')?>:</td>
  	<td>
        <?php echo $form->input('EmailTemplate.name', array('size'=>'50','label'=>false,'readonly'=>$readonly));?>
        </td>
        <td width="20%">&nbsp;</td>
  </tr>
  <tr class="tablecell2">
  	<td width="20%">Subject:</td>
  	<td>
        <?php echo $form->input('EmailTemplate.subject', array('size'=>'50','label'=>false,'readonly'=>$readonly));?>
        </td>
        <td width="20%">&nbsp;</td>
  </tr>

  <tr class="tablecell2">
    <td><?php __('Availability')?>:</td>
    <td>
      <?php
        echo $form->input('EmailTemplate.availability', array(
           'type' => 'radio',
           'options' => array('0' => ' - '.__('Private',true).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                              '1' => ' - '.__('Public',true).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                              //'2' => ' - shared&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                             ),
           'default' => '0',
           'legend' => false,
           'disabled'=>$readonly
        ));
      ?>
    </td>
    <td><?php __('Public Allows Template Sharing Amongst Instructors')?></td>
  </tr>

  <tr class="tablecell2">
    <td><?php __('Description')?>:&nbsp;</td>
    <td><?php echo $form->textarea('EmailTemplate.description', array('rows' => '5', 'style'=>'width:90%;', 'readonly'=>$readonly)) ?>  </td>
    <td>&nbsp;</td>
  </tr>

  <?php if(!$readonly): ?>
  <tr class="tablecell2">
    <td><?php __('Insert Merge Field')?>:&nbsp;</td>
    <td><?php echo $form->input('Email.merge', array(
                'type' => 'select',
                'id' => 'merge',
                'name' => 'merge',
                'options' => $mergeList,
                'empty' => __('-- Select Merge Field --', true),
                'label' => false,
                'onChange' => "insertAtCursor(document.frm.emailContent, this.value)"
              ));?>  </td>
    <td>&nbsp;</td>
  </tr>
  <?php endif; ?>
  
  <tr class="tablecell2">
    <td><?php __('Content')?>:&nbsp;</td>
    <td><?php echo $form->textarea('EmailTemplate.content', array(
        'id' => 'emailContent',
        'rows' => '30',
        'style'=>'width:90%;',
        'readonly'=>$readonly)) ?>  </td>
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