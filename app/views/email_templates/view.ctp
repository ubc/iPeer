<?php $readonly = isset($readonly) ? $readonly : false;?>
<div>
<?php
    echo $form->create('Emailtemplate');
    echo empty($this->data['EmailTemplate']['id']) ?
        $form->hidden('EmailTemplate.user_id', array('value'=>$currentUser['id'])) :
        $form->hidden('EmailTemplate.id', array('value'=>$this->data['EmailTemplate']['id']));
    echo $form->input('EmailTemplate.name', array('readonly' => $readonly));
    echo $form->input('EmailTemplate.subject', array('readonly' => $readonly));
    echo $form->input('EmailTemplate.availability', array(
                                'readonly' => $readonly, 
                                'type' => 'radio',
                                'options' => array('0' => 'private', '1' => 'public'), 
                                'disabled' => $readonly
    ));
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
    echo $form->input('EmailTemplate.content', array('readonly' => $readonly));
    if(!$readonly):
        echo $form->submit();
    endif;
    echo $form->end();
?>
</div>