<?php
$readonly = isset($readonly) ? $readonly : false;
$evaluate = isset($evaluate) ? $evaluate : false;
$url = $this->action == 'copy' ? 'add' : $this->action;
?>

<?php echo $this->Form->create('Mixeval', array('url' => array('action' => $url)))?>
<?php echo $this->Form->input('id', array('type' => 'hidden'))?>
<input type="hidden" name="required" id="required" value="mixeval_name" />

<?php echo $this->Form->input('name', array('id' => 'name', 'size'=>'30',
    'label' => __('Name', true), 'readonly' => $readonly));?>

<?php echo $this->Form->input('availability', array('id' => 'availability',
    'type' => 'radio',
    'options' => array('public'=>__('Public', true),'private'=>__('Private', true)),
    'label' => __('Availability', true),
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
