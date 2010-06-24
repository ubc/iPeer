<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $javascript->link('course')?>
<script type="text/javascript" language="javascript">
<!--
  var total_instructor_count = <?php echo $instructor_count?>;
//-->
</script>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Course']['id'])?'add':'edit') ?>">
    <input type="hidden" name="required" id="required" value="course" />
    <?php echo empty($params['data']['Course']['id']) ? null : $html->hidden('Course/id'); ?>
    <?php echo empty($params['data']['Course']['id']) ? $html->hidden('Course/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Course/updater_id', array('value'=>$rdAuth->id)); ?>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center"><?php echo empty($params['data']['Course']['id'])?'Add':'Edit' ?> Course</td>
  </tr>
  <tr class="tablecell2">
    <td width="254" id="course_label">Course:<font color="red">*</font></td>
    <td width="405">
  <input type="text" name="course" id="course" class="input" value="<?php echo empty($params['data']['Course']['course'])? '' : $params['data']['Course']['course'] ?>" size="50">
  <div id="courseErr">
  <?php
    $fieldValue = isset($this->params['form']['course'])? $this->params['form']['course'] : '';
    $params = array('controller'=>'courses', 'data'=>null, 'fieldvalue'=>$fieldValue);
    echo $this->renderElement('courses/ajax_course_validate', $params);
  ?>
  </div>
  </td>
  <td width="243">
  eg. APSC 201 001<?php echo $ajax->observeField('course', array('update'=>'courseErr', 'url'=>"/courses/checkDuplicateName", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?></td>

  </tr>
  <tr class="tablecell2">
    <td>Title:&nbsp;</td>
    <td><?php echo $html->input('Course/title', array('size'=>'50','class'=>'input')) ?>  </td>
    <td> eg. Intro to APSCI </td>
  </tr>
  <tr class="tablecell2">
    <td valign="top">Instructor(s):</td>
    <td>
  <div id="adddelinstructors">
  <?php
    $params = array('controller'=>'courses', 'instructor'=>$instructor, 'count'=>1);
    echo $this->renderElement('courses/ajax_course_instructors', $params);
    ?>
  </div>
    </td>
    <td valign="top">
  <input type="hidden" name="add" id="add" value="1">
  <a href=# onclick="addInstructor();"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Additional Instructor', 'align'=>'middle', 'border'=>'0')); ?> - Add Additional Instructor</a>
  <br><br>
  <a href=# onclick="removeInstructor();"><?php echo $html->image('icons/delete.gif', array('alt'=>'Add Additional Instructor', 'align'=>'middle', 'border'=>'0')); ?> - Remove Additional Instructor</a>
  <?php echo $ajax->observeField('add', array('update'=>'adddelinstructors', 'url'=>"/courses/adddelinstructor", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>

  </td>
  </tr>
  <tr class="tablecell2">
    <td>Status:</td>
    <td>
      <input type="radio" name="data[Course][record_status]" value="A" checked  > - Active&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" name="data[Course][record_status]" value="I"> - Inactive<br>
    </td>
    <td>&nbsp;</td>
  </tr>
<!--  <?php if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) { ?>
  <tr class="tablecell2">
    <td> Enable Student Self Enrollment: </td>
    <td><?php echo $html->checkbox('Course/self_enroll') ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tablecell2">
    <td> Password for Self Enroll: </td>
    <td><?php echo $html->input('Course/password', array('size'=>'50','class'=>'input')) ?></td>
    <td>&nbsp;</td>
  </tr>
  <?php }?>-->
  <tr class="tablecell2">
    <td>Homepage:&nbsp;</td>
    <td><?php echo $html->input('Course/homepage', array('size'=>'50','class'=>'input')) ?> </td>
    <td> eg. http://mycoursehome.com </td>
  </tr>
  <tr class="tablecell2">
    <td colspan="3" align="center"><?php echo $html->submit('Add Course') ?>
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
