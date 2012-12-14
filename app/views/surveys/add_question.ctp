<?php if(!isset($responses)) {
    $responses = array();
}
?>
<?php echo $this->Form->create('Question',
    array('id' => 'frm',
    'url' => '/surveys/'.$this->action.'/'.(isset($question_id)?$question_id.'/':'').$survey_id,
))?>
<?php echo isset($question_id) ? $this->Form->input('id', array('type' => 'hidden', 'value' => $question_id)) :
                                 $this->Form->input('Survey.id', array('type' => 'hidden', 'value' => $survey_id))?>
<?php echo $this->Form->input('template_id', array(
    'label' => __('Load Existing Question', true),
    'empty' => __('(Select Question to Load Its Details)', true),
    'after' => $this->Form->submit('Load Question', array('name'=>'loadq','div' => false)
)))?>
<div class="help-text"><?php __('Select from the list to load an existing question as your question template.')?></div>

<?php echo $this->Form->input('prompt', array('size'=>'50','class'=>'input',
    'label' => __('Question', true).' <font color="red">*</font>')) ?>
<div class="help-text"><?php __('E.g. What grade do you expect to earn in this class?')?></div>

<?php echo $this->Form->input('master', array('label' => __('Master Question?', true),
    'type' => 'select',
    'options' => array('no' => __('No', true), 'yes' => __('Yes', true))))?>
<div class="help-text"><?php __('Master question can be used as a template of a new question. ')?></div>

<?php echo $this->Form->input('type', array('label' => __('Question Type:', true).' <font color="red">*</font>',
    'options' => array(
        'M' => __('Multiple Choice (Single Answer)', true),
        'C' => __('Choose Any Of... (Multiple Answers)', true),
        'S' => __('Single Line Text Input', true),
        'L' => __('Long Answer Text Input', true))))?>
<div id="possible-answers">
<div class="input select">
    <?php echo $this->Form->label('response', __('Possible Question Answers', true).'<font color="red">*</font>')?>
    <div id="answers">
        <?php foreach($responses as $k => $r):?>
          <div><?php echo $this->Form->input('Response.'.$k.'.response', array(
            'size'=>'25','class'=>'answers', 'type' => 'text',
            'value' => $r['response'], 'id' => false, 'label' => false, 'div' => false,
            ));?>
          <?php echo isset($r['id']) ? $this->Form->input('Response.'.$k.'.id', array('type' => 'hidden', 'value' => $r['id'])) : '';?>
          <?php echo $html->link( __('Remove', true), '#', array('class' => 'delete-button', 'onclick' => 'removeAnswer(this);')); ?>
          </div>
          <?php endforeach;?>
            <?php echo $html->link( __('Add Answer', true), '#', array('class' => 'add-button', 'id' => 'add-button')); ?>
        </div>
</div>
<div class="help-text">
    <?php __(' Do not include an option for "I choose not to answer this question." It will be inserted automatically.')?>
</div>
</div>

<div align="center">
    <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
    <?php echo $this->Form->submit(Inflector::humanize(Inflector::underscore($this->action)), array('div' => false))?>
</div>
<?php echo $this->Form->end()?>


<script>
$("add-button").observe('click', function(event){
  var index = $$('input[class=answers]').length;

  // add element
  this.insert({before: '<?php echo $this->Form->input('Response.\'+index+\'.response',
      array('size'=>'25','class'=>'answers',
      'type' => 'text',
      'id' => false,
      'label' => false,
      'div' => false
  ));?> <?php echo $html->link( __('Remove', true), '#', array('class' => 'delete-button', 'onclick' => 'removeAnswer(this);')); ?><br/>'});
});

$("QuestionType").observe('change', function(event) {
    updatePossibleAnswersStatus();
});

function removeAnswer(element) {
  $(element).up().remove();

  // reorder the names
  var fields = $$('input[class=answers]');

  for(var i=0; i<fields.length; i++) {
    fields[i].name='data[Response]['+i+'][response]';
  }
}

function updatePossibleAnswersStatus() {
    switch($('QuestionType').getValue()) {
    case 'M':
    case 'C':
        $('possible-answers').show();
        break;
    case 'S':
    case 'L':
        $('possible-answers').hide();
    }
}
updatePossibleAnswersStatus();
</script>
