<?php $readonly = isset($readonly) ? $readonly : false;?>
<table width="100%">
    <tr class="tableheader"><td align="center">
    <?php
        echo $readonly ?
        __('View') :
        (empty($this->data['EmailTemplate']['id'])?'Add':'Edit');
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
echo $this->Form->input('EmailTemplate.name', array('readonly' => $readonly));
echo $this->Form->input('EmailTemplate.subject', array('readonly' => $readonly));
echo $this->Form->input(
    'EmailTemplate.availability',array(
        'type' => 'radio',
        'readonly' => $readonly,
        'options' => array(
            '0' => __('Private',true).'<br>',
            '1' => __('Public',true).'  <font size="2">(Allows Template Sharing Among Instructors)</font>',
        )
    )
);
echo $form->input('EmailTemplate.description', array('readonly' => $readonly));
if(!$readonly):
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
endif;
echo $form->input(
    'EmailTemplate.content', array(
        'id' => 'emailContent',
        'readonly' => $readonly
    )
);
if(!$readonly):
    echo $this->Form->submit();
endif;
echo $this->Form->end();
?>
</div>