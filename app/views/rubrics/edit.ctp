<?php
$readonly = isset($readonly) ? $readonly : false;
$evaluate = isset($evaluate) ? $evaluate : false;
$url = $this->action == 'copy' ? 'add' : $this->action;
?>

<?php echo $this->Form->create('Rubric',
                               array('id' => 'frm',
                                     'url' => array('action' => $url),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '<td width="200px">',
                                                              'after' => '</td>',
                                                              'between' => '</td><td>')))?>
<?php echo $this->Form->input('id', array('type' => 'hidden'))?>
<?php echo $this->Form->input('template', array('type' => 'hidden', 'value' => 'horizontal'))?>

<div class="content-container">
  <table class="form-table">
    <tr class="tableheader">
      <td colspan="3" align="center"><?php echo __($action)?></td>
    </tr>

    <tr class="tablecell2">
      <?php echo $this->Form->input('name', array('id' => 'name', 'size'=>'30', 
                                                  'class'=>'validate required TEXT_FORMAT username_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                  'readonly' => $readonly, 'label' => __('Name')));?>
      <td>&nbsp;</td>
    </tr>

    <tr class="tablecell2">
      <?php echo $this->Form->input('lom_max', array('id' => 'LOM', 'class'=>'validate required TEXT_FORMAT username_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                     'options' => array_combine(range(2,10), range(2,10)),
                                                     'default' => 5,
                                                     'label' => __('Level of Mastery:', true),
                                                     'style'=>'width:50px;',
                                                     'disabled' => $readonly));?>
      <td><?php __('aka LOM, Evaluation Range (Max 10)')?></td>
    </tr>

    <tr class="tablecell2">
      <?php echo $this->Form->input('criteria', array('id' => 'criteria', 'class'=>'validate required TEXT_FORMAT username_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                      'options' => array_combine(range(1,25), range(1,25)),
                                                      'default' => 3,
                                                      'label' => __('Number of Criteria:', true),
                                                      'style'=>'width:50px;',
                                                      'disabled' => $readonly));?>
      <td><?php __('Number of Evaluation Aspects (Max 25)')?></td>
    </tr>

    <tr class="tablecell2">
      <td><?php __('Availability')?>:</td>
      <?php echo $this->Form->input('availability', array('id' => 'availability',
                                                          'type' => 'radio',
                                                          'legend' => false,
                                                          'options' => array('public'=>__('Public', true),'private'=>__('Private', true)),
                                                          'label' => false,
                                                          'before' => '<td>',
                                                          'after' => '</td>',
                                                          'between' => '',
                                                          'separator' => '&nbsp;',
                                                          'disabled' => $readonly));?>
      <td><?php __('Public Allows Rubric Sharing Amongst Instructors')?></td>
    </tr>

    <tr class="tablecell2">
      <td><?php __('Zero Mark')?></td>
      <td>
      <?php echo $this->Form->checkbox('zero_mark', array('id' => 'zero_mark', 'class'=>'self_enroll',
                                                       'size' => 50,
                                                       'disabled' => $readonly));?>
      </td>
      <td><?php __('No Marks Given for Level of Mastery of 1')?></td>
    </tr>

    <?php if('view' == $this->action):?>
      <?php echo $this->element('creator_block', 
                                array('data' => $data['Rubric'],
                                     ));?>
    <?php endif; ?>

    <tr class="tablecell2">
    	<td colspan="3" align="center">
        <input type="button" name="Back" value="<?php echo __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
        <?php switch($action) {
          case 'Add Rubric':
            echo $this->Form->submit(__('Next', true), array('Name'=>'preview', 'div' => false)); 
            break;
          case 'View Rubric':
            echo $this->Form->button(__('Edit Rubric', true), array('type' => 'button', 
                                                                    'onClick' => 'javascript:location.href=\''.$this->Html->url('edit/'.$data['Rubric']['id'], true).'\';'));
            break;
          default:
            echo $this->Form->submit(__('Preview (Update Format)', true), array('Name'=>'preview', 'div' => false));
            echo $this->Form->submit(__('Save', true), array('id' => 'submit-rubric', 'Name' => 'submit', 'div' => false));
          }?>
      </td>
    </tr>
  </table>

<table class="list-bottom">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
  
</div>

<?php if(!empty($data)):?>
<div class='title'>
  <span class="text"><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> <?php __('Rubric Preview<')?>/span>
	<span class="controls"><a href="#rpreview" onclick="$('rpreview').toggle(); toggle1(this);"><?php echo empty($this->data) ? '[-]' : '[-]'; ?></a></span>
</div>


<div class="content-container">

<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;">' : 'style="display: block; background: #FFF;"'; ?>>
<?php echo $this->element('rubrics/ajax_rubric_'.($this->action == 'view' ? 'view' : 'edit'), array('data' => $data, 'readonly' => $readonly, 'evaluate' => $evaluate)); ?>
</div>

</div>
<?php endif;?>

<?php echo $this->Form->end()?>

<script type="text/javascript">
$('LOM').observe('change', function(event){
  $('submit-rubric').disable();
})
$('criteria').observe('change', function(event){
  $('submit-rubric').disable();
})
</script>
