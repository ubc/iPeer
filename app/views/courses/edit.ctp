<?php $readonly = isset($readonly) ? $readonly : false;?>
<?php echo $html->script('course')?>

<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr><td>
  <?php echo $this->Form->create('Course', 
                                 array('id' => 'frm',
                                       'url' => array('action' => $this->action),
                                       'inputDefaults' => array('div' => false,
                                                                'before' => '<td width="200px">',
                                                                'after' => '</td>',
                                                                'between' => '</td><td>')))?>

  <input type="hidden" name="required" id="required" value="course" />

  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center"><?php echo ucfirst($this->action)?> <?php __('Course')?></td>
  </tr>

  <tr class="tablecell2">
      <td><?php __('Course')?>:&nbsp;<font color="red">*</font></td>
    <?php echo $this->Form->input(__('course', true), array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly, 'label' => false, 'format' => array('before','input','after', 'error'))) ?>
    <?php
      $this->Js->get('#CourseCourse');
      $this->Js->event('blur', $this->Js->request(array('action' => 'checkDuplicateName', 'course_id' => $course_id), 
                                                  array('update' => '#courseErr',
                                                        'before' => 'Element.show("loading");',
                                                        'complete' => 'Element.hide("loading");',
                                                        'data' => '$(this).serialize()',
                                                        'dataExpression' => true)));
    ?>
    <td width="243"><?php __('eg. APSC 201 001')?> <div id="courseErr" class='error-message'></div></td>
  </tr>

  <tr class="tablecell2">
    <td width="200px"><?php __('Title')?>:&nbsp;<font color="red">*</font></td>
    <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly, 'label' => false, 'format' => array('before','input','after', 'error'))) ?>
    <td><?php __('eg. Intro to APSCI')?> </td>
  </tr>

  <?php if('add' != $this->action):?>
  <tr class="tablecell2">
    <td valign="top"><?php __('Instructor(s)')?>:</td>
    <td>
      <?php if (isset($data['Instructor'])):?>
        <?php foreach($data['Instructor'] as $i):?>
        <div><?php echo $this->element('courses/edit_instructor', array('instructor' => $i, 'course_id' => $data['Course']['id']));?></div>
        <?php endforeach;?>
      <?php endif;?>
	<div id="add-div">
  <?php echo $this->Form->select('instructors', $instructors_rest);?>
	<?php echo $this->Js->link($html->image('icons/add.gif', array('alt'=>__('Add Instructor',true), 'valign'=>'middle', 'border'=>'0')).__(' Add Instructor', true),
                             array('action' => 'addInstructor'),
                             array('escape' => false,
                                   'success' => '$("add-div").insert({before: "<div>"+response.responseText+"</div>"});$$("option[value="+$F("CourseInstructors")+"]").invoke("remove")',
                                   'error' => 'alert("Communication error!")',
                                   'dataExpression' => true,
                                   'evalScripts' => true,
                                   'data' => '{instructor_id:$F("CourseInstructors"), course_id:'.$course_id.'}'))?>

  <div>
	</td>
    <td></td>
  </tr>
  <?php endif;?>

  <tr class="tablecell2">
    <td><?php __('Status')?>:</td>
    <td>
    <?php echo $this->Form->select('record_status', array('A' => __('Active',true), 'I' => __('Inactive', true)), null, array('empty' => false))?>
	</td>
    <td>&nbsp;</td>
  </tr>
  <!--<?php if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) { ?>
  <tr class="tablecell2">
    <td><?php __('Enable Student Self Enrollment')?>: </td>
    <td><input type="checkbox" name="self_enroll" value="on" <?php if( $course['self_enroll'] == "on" ) echo " checked";?>>
	</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td><?php __('Password for Self Enroll')?>: </td>
    <td><?php echo $html->input(__('Course/password', true), array('size'=>'50','class'=>'input')) ?></td>
    <td>&nbsp;</td>
  </tr>
<?php }?>-->

  <tr class="tablecell2">
      <td><?php __('Homepage')?>:</td>
  
    <?php echo $this->Form->input(__('homepage', true), array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly, 'label' => false, 'format' => array('before','input','after', 'error'))) ?>
    <td><?php __('eg.')?> http://mycoursehome.com </td>
  </tr>

  <tr class="tablecell2">
    <td colspan="3" align="center"><?php echo $this->Form->submit(ucfirst($this->action).__(' Course', true), array('div' => false)) ?>
	<input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
	</td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
  <tr>
    <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
    <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
  </tr>
</table>
</form>
	</td>
  </tr>
</table>
