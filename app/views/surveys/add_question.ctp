<?php echo $this->Form->create('Question', 
                               array('id' => 'frm',
                                     'url' => '/surveys/'.$this->action.'/'.(isset($question_id)?$question_id.'/':'').$survey_id,
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '<td>',
                                                              'after' => '</td>',
                                                              'between' => '</td><td>')))?>
<?php echo isset($question_id) ? $this->Form->input('id', array('type' => 'hidden', 'value' => $question_id)) : 
                                 $this->Form->input('Survey.id', array('type' => 'hidden', 'value' => $survey_id))?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader"><td colspan="3" align="center"><?php echo Inflector::humanize(Inflector::underscore($this->action))?></td></tr>

          <tr class="tablecell2">
            <?php echo $this->Form->input('template_id', array('label' => 'Load Existing Question:',
                                                               'empty' => '(Select Question to Load Its Details)',
                                                               'after' => $this->Form->submit('Load Question', array('Name'=>'loadq',
                                                                                                                     'div' => false)).'</td>'))?>
            <td width="33%">Select from the list to load an existing question as your question template.</td>
          </tr>

          <tr class="tablecell2">
            <?php echo $this->Form->input('prompt', array('size'=>'50','class'=>'input', 'style'=>'width:85%;',
                                                          'label' => 'Question: <font color="red">*</font>')) ?>
            <td>E.g. What grade do you expect to earn in this class?</td>
          </tr>

          <tr class="tablecell2">
            <?php echo $this->Form->input('master', array('label' => 'Master Question?',
                                                         'type' => 'select', 
                                                          'options' => array('no' => 'No', 'yes' => 'Yes')))?>
            <td> Master question can be used as a template of a new question. </td>
          </tr>

          <tr class="tablecell2">
          <?php echo $this->Form->input('type', array('label' => 'Question Type:  <font color="red">*</font>',
                                             //           'type' => 'radio', 
                                             //           'separator' => '<br />',
                                                        'options' => array('M' => 'Multiple Choice (Single Answer)', 
                                                                           'C' => 'Choose Any Of... (Multiple Answers)',
                                                                           'S' => 'Single Line Text Input',
                                                                           'L' => 'Long Answer Text Input')))?>
            <td>&nbsp;</td>
          </tr>

          <tr class="tablecell2">
            <td valign="top">Possible Question Answers: <font color="red">*</font></td>
            <td valign="top">
              <div id="answers">
                <?php foreach($responses as $k => $r):?>
                
                <div><?php echo $this->Form->input('Response.'.$k.'.response', 
                                                   array('size'=>'25','class'=>'answers', 'style'=>'width:75%;', 
                                                         'type' => 'text',
                                                         'value' => $r['response'],
                                                         'id' => false,
                                                         'before' => '', 
                                                         'after' => '', 'label' => '',
                                                         'between' => ''));?> 
                <?php echo isset($r['id']) ? $this->Form->input('Response.'.$k.'.id', array('type' => 'hidden', 'value' => $r['id'])) : '';?>
                <a href="#" onClick="removeAnswer(this);"><?php echo $html->image('icons/delete.gif', array('alt'=>'Remove Answer', 'valign'=>'middle', 'border'=>'0'))?>Remove</a>
                </div> 
                <?php endforeach;?>
              </div> 
              <a href="#" id="add-button"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Answer', 'valign'=>'middle', 'border'=>'0')); ?> - Add Answer</a>
            </td>
            <td valign="top">'Multiple Choice' and 'Choose Any Of...' Questions Only<br><br>
              Do not include an option for "I choose not to answer this
              question." It will be inserted automatically.<br><br>
            </td>
          </tr>

          <tr class="tablecell2">
            <td colspan="3">
            <div align="center">
                <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
                <?php echo $this->Form->submit(Inflector::humanize(Inflector::underscore($this->action)), array('div' => false))?>
            </div>
            </td>
          </tr>
      </table>

        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
          </tr>
        </table>

    </td>
  </tr>
</table>
<?php echo $this->Form->end()?>


<script>
$("add-button").observe('click', function(event){
  var index = $$('input[class=answers]').length;

  // add element
  $('answers').insert({bottom: '<div><?php echo $this->Form->input('Response.\'+index+\'.response', 
                                                                   array('size'=>'25','class'=>'answers', 'style'=>'width:75%;', 
                                                                         'type' => 'text',
                                                                         'id' => false,
                                                                         'value'=>'', 'before' => '', 
                                                                         'after' => '', 'label' => '',
                                                                         'between' => ''));?> <a href="#" onClick="removeAnswer(this);"><?php echo $html->image('icons/delete.gif', array('alt'=>'Remove Answer', 'valign'=>'middle', 'border'=>'0'))?>Remove</a></div>'});
});

function removeAnswer(element) {
  $(element).up().remove();

  // reorder the names
  var fields = $$('input[class=answers]');

  for(var i=0; i<fields.length; i++) {
    fields[i].name='data[Response]['+i+'][response]';
  }
}
</script>
