<div class="MixevalAdd">
<?php 
/* Unfortunately, if we want the nice responsive and user friendly question 
 * editing all on a single page, there's quite a bit of unwieldy complexity 
 * involved. The easier, more clean way, is how surveys implement question 
 * editing, involving multiple pages. However, since we want Mixeval to replace 
 * all the other evaluations eventually, it's probably best that we deal with 
 * some complexity in order to make it easy to use.
 *
 * Most of the complexity is hidden away in the mixeval element questions_editor
 */

/* Create the Form */
echo $form->create('Mixeval');
echo $html->tag('h3', _('Info'));
echo $form->input('name');
echo $form->input(
    'availability', 
    array(
        'type' => 'radio',
        'default' => 'private',
        'options' => array('public' => _('Public'), 'private' => _('Private'))
    )
);
echo $html->div("help-text", 
    _('Public Allows Mixed Evaluation Sharing Amongst Instructors'));
echo $this->Form->input('zero_mark');
echo $html->div("help-text", 
    _('No Marks Given for Level of Scale of 1'));


// Question section
echo $html->tag('h3', _('Questions'));
$addQButton = $form->button(_('Add'), 
    array('type' => 'button', 'onclick' => "insertQ();"));
echo $form->input('MixevalQuestionType', 
    array('after' => $addQButton));
echo $this->element('mixevals/questions_editor', 
    array('qTypes' => $mixevalQuestionTypes));

// Submit
echo $html->div('center', 
    $form->button(_('Back'), array('onclick' => 
        "javascript:(history.length > 1) ? history.back() : window.close();'")).
    $form->button(_('Save'))
);

echo $this->Form->end();

debug($this->data);

// TODO Persistence
// TODO Save
// TODO Paragraph
// TODO Likert

?>
</div>













<!--
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
-->
