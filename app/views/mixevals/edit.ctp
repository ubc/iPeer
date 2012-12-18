<?php
$readonly = isset($readonly) ? $readonly : false;
$evaluate = isset($evaluate) ? $evaluate : false;
$url = $this->action == 'copy' ? 'add' : $this->action;

/*	if(!empty($data)){
    $mixeval_name = $data['Mixeval']['name'];
    $scale_default = $data['Mixeval']['scale_max'];
    $prefill_question_max = $data['Mixeval']['prefill_question_max'];
    $question_default = $data['Mixeval']['lickert_question_max'];
    $mixeval_avail = $data['Mixeval']['availability'];
    $total_mark = isset($data['Mixeval']['total_marks']) ? $data['Mixeval']['total_marks'] : "";
    if(!empty($data['Mixeval']['zero_mark']))
        $zero_mark = $data['Mixeval']['zero_mark'];
    else
        $zero_mark='off';
}
else{
    $mixeval_name = '';
    $scale_default = 5;
    $question_default = 3;
    $prefill_question_max = 3;
    $mixeval_avail = 'public';
    $total_mark = 5;
    $zero_mark = 'off';
}*/
?>

<?php echo $this->Form->create('Mixeval',
    array('id' => 'frm',
    'url' => array('action' => $url),
))?>
<?php echo $this->Form->input('id', array('type' => 'hidden'))?>
<input type="hidden" name="required" id="required" value="mixeval_name" />

<?php echo $this->Form->input('name', array('id' => 'name', 'size'=>'30',
    'class'=>'validate required TEXT_FORMAT mixeval_name_msg Invalid_Text._At_Least_One_Word_Is_Required.',
    'label' => __('Name', true), 'readonly' => $readonly));?>
<div id="mixeval_name_msg" class="error">&nbsp;</div>

<?php echo $this->Form->input('availability', array('id' => 'availability',
    'type' => 'radio',
    'options' => array('public'=>__('Public', true),'private'=>__('Private', true)),
    'label' => __('Availability', true),
    'default' => 'public',
    'disabled' => $readonly));?>
<div class="help-text"><?php __('Public Allows Mixed Evaluation Sharing Amongst Instructors')?></div>

<?php echo $this->Form->input('zero_mark', array('id' => 'zero_mark',
    'class'=>'self_enroll',
    'label' => false,
    'before' => $this->Form->label(__('Zero Mark', true)),
    'size' => 50,
    'disabled' => $readonly));?>
<div class="help-text"><?php __('No Marks Given for Level of Scale of 1')?></div>

<div style="text-align: center">
    <input type="button" name="Back" value="<?php echo __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
    <?php switch($action) {
      case 'Add Mixed Evaluation':
          echo$this->Form->submit(__('Save', true), array('id' => 'submit', 'Name' => 'submit', 'div' => false));
          break;
      case 'View Mixed Evaluation':
          echo $this->Form->button(__('Edit Mixed Evaluation', true),
              array('type' => 'button',
              'onClick' => 'javascript:location.href=\''.$this->Html->url('edit/'.$data['Mixeval']['id'], true).'\';'));
          break;
      default:
          echo $this->Form->submit(__('Save', true), array('id' => 'submit', 'Name' => 'submit', 'div' => false));
      }?>
</div>

<?php if(!empty($data)):?>
<h1 onclick="$('rpreview').toggle();" class="title">
  <span class="ipeer-icon"><?php __('Mixed Evaluation Editor')?></span>
</h1>

<div id="rpreview">
<?php echo $this->element('mixevals/ajax_mixeval_'.($this->action == 'view' ? 'view' : 'edit'), array('data' => $data, 'readonly' => $readonly, 'evaluate' => $evaluate)); ?>
</div>
<?php endif;?>

<?php echo $this->Form->end()?>
