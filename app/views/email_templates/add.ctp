<table width="100%">
    <tr class="tableheader"><td align="center">
    <?php
        echo (empty($this->data['EmailTemplate']['id'])?'Add':'Edit');
        __(' Custom Email Template')
    ?>
    </td></tr>
</table>
<div>
<?php
echo $this->Form->create('Emailtemplate');
echo empty($this->data['EmailTemplate']['id']) ?
    $form->hidden('EmailTemplate.user_id', array('value'=>$currentUser['id'])) :
    $form->hidden('EmailTemplate.id', array('value'=>$this->data['EmailTemplate']['id']));
echo $this->Form->input('EmailTemplate.name');
echo $this->Form->input('EmailTemplate.subject');
echo $this->Form->input(
    'EmailTemplate.availability',array(
        'type' => 'radio',
        'options' => array(
            '0' => __('Private',true).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            '1' => __('Public',true).'  <font size="2">(Allows Template Sharing Among Instructors)</font>',
        )
    )
);
echo $form->input('EmailTemplate.description');
echo $form->input(
    'Email.merge', array(
        'type' => 'select',
        'id' => 'merge',
        'name' => 'merge',
        'options' => $mergeList,
        'empty' => __('-- Select Merge Field --', true),
        'label' => 'Insert Merge Field'
    )
);
echo $form->input('EmailTemplate.content', array('id' => 'emailContent'));
echo $this->Form->submit();
echo $this->Form->end();
?>
</div>