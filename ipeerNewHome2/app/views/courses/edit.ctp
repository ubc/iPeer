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
    <td colspan="3" align="center"><?php echo ucfirst($this->action)?> Course</td>
  </tr>

  <tr class="tablecell2">
    <?php echo $this->Form->input('course', array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly)) ?>
    <?php
      $this->Js->get('#CourseCourse');
      $this->Js->event('blur', $this->Js->request(array('action' => 'checkDuplicateName', 'course_id' => $course_id), 
                                                  array('update' => '#courseErr',
                                                        'before' => 'Element.show("loading");',
                                                        'complete' => 'Element.hide("loading");',
                                                        'data' => '$(this).serialize()',
                                                        'dataExpression' => true)));
    ?>
    <td width="243">eg. APSC 201 001 <div id="courseErr" class='error-message'></div></td>
  </tr>

  <tr class="tablecell2">
    <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly)) ?>
    <td> eg. Intro to APSCI </td>
  </tr>

  <?php if('add' != $this->action):?>
  <tr class="tablecell2">
    <td valign="top">Instructor(s):</td>
    <td>
      <?php if (isset($data['Instructor'])):?>
        <?php foreach($data['Instructor'] as $i):?>
        <div><?php echo $this->element('courses/edit_instructor', array('instructor' => $i, 'course_id' => $data['Course']['id']));?></div>
        <?php endforeach;?>
      <?php endif;?>
	<div id="add-div">
  <?php echo $this->Form->select('instructors', $instructors_rest);?>
	<?php echo $this->Js->link($html->image('icons/add.gif', array('alt'=>'Add Instructor', 'valign'=>'middle', 'border'=>'0')).' Add Instructor',
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
    <td>Status:</td>
    <td>
    <?php echo $this->Form->select('record_status', array('A' => 'Active', 'I' => 'Inactive'), null, array('empty' => false))?>
	</td>
    <td>&nbsp;</td>
  </tr>
  <!--<?php if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) { ?>
  <tr class="tablecell2">
    <td> Enable Student Self Enrollment: </td>
    <td><input type="checkbox" name="self_enroll" value="on" <?php if( $course['self_enroll'] == "on" ) echo " checked";?>>
	</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td> Password for Self Enroll: </td>
    <td><?php echo $html->input('Course/password', array('size'=>'50','class'=>'input')) ?></td>
    <td>&nbsp;</td>
  </tr>
<?php }?>-->

  <tr class="tablecell2">
    <?php echo $this->Form->input('homepage', array('size'=>'50', 'class'=>'input',
                                                  'readonly' => $readonly)) ?>
    <td> eg. http://mycoursehome.com </td>
  </tr>

  <tr class="tablecell2">
    <td colspan="3" align="center"><?php echo $this->Form->submit(ucfirst($this->action).' Course', array('div' => false)) ?>
	<input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
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
