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
<?php echo $html->script('showhide')?>

<?php echo $this->Form->create('Mixeval',
                               array('id' => 'frm',
                                     'url' => array('action' => $url),
                                     'inputDefaults' => array('div' => false,
                                                              'before' => '<td width="200px">',
                                                              'after' => '</td>',
                                                              'between' => '</td><td>')))?>
<?php echo $this->Form->input('id', array('type' => 'hidden'))?>
<input type="hidden" name="required" id="required" value="mixeval_name" />

<div class="content-container">
  <table class="form-table">
    <tr class="tableheader">
      <td colspan="3" align="center"><?php echo __($action)?></td>
    </tr>

    <tr class="tablecell2">
      <?php echo $this->Form->input('name', array('id' => 'name', 'size'=>'30', 
                                                  'class'=>'validate required TEXT_FORMAT mixeval_name_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                  'label' => __('Name', true), 'readonly' => $readonly));?>
      <td id="mixeval_name_msg" class="error">&nbsp;</td>
    </tr>


    <tr class="tablecell2">
      <td><?php __('Availability')?>:</td>
      <?php echo $this->Form->input('availability', array('id' => 'availability',
                                                          'type' => 'radio',
                                                          'legend' => false,
                                                          'options' => array('public'=>__('Public', true),'private'=>__('Private', true)),
                                                          'label' => false,
      													  'default' => 'public',	     			
                                                          'before' => '<td>',
                                                          'after' => '</td>',
                                                          'between' => '',
                                                          'separator' => '&nbsp;',
                                                          'disabled' => $readonly));?>
      <td><?php __('Public Allows Mixed Evaluation Sharing Amongst Instructors')?></td>
    </tr>

    <tr class="tablecell2">
      <td><?php __('Zero Mark')?></td>
      <td>
      <?php echo $this->Form->checkbox('zero_mark', array('id' => 'zero_mark', 'class'=>'self_enroll',
                                                       'size' => 50,
                                                       'disabled' => $readonly));?>
      </td>
      <td><?php __('No Marks Given for Level of Scale of 1')?></td>
    </tr>

    <?php if('view' == $this->action):?>
      <?php echo $this->element('creator_block', 
                                array('data' => $data['Mixeval'],
                                     ));?>
    <?php endif; ?>

    <tr class="tablecell2">
    	<td colspan="3" align="center">
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
      </td>
    </tr>
  </table>

  
</div>

<?php if(!empty($data)):?>
<div class='title'>
  <span class="text"><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> <?php __('Mixed Evaluation Editor')?></span>
	<span class="controls"><a href="#rpreview" onclick="$('rpreview').toggle(); toggle1(this);"><?php echo empty($data) ? '[+]' : '[-]'; ?></a></span>
</div>

<div class="content-container">

<div id="rpreview" <?php echo empty($data) ? 'style="display: none; background: #FFF;">' : 'style="display: block; background: #FFF;"'; ?>>
<?php echo $this->element('mixevals/ajax_mixeval_'.($this->action == 'view' ? 'view' : 'edit'), array('data' => $data, 'readonly' => $readonly, 'evaluate' => $evaluate)); ?>
</div>

</div>
<?php endif;?>

<?php echo $this->Form->end()?>
<?php /*

<script type="text/javascript">
$('scale-max').observe('change', function(event){
  $('submit').disable();
})
</script>

*/?>
