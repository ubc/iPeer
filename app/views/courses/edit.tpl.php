<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
<script type="text/javascript" language="javascript">
<!--
  var total_instructor_count = <?php echo $instructor_count?>;
//-->
</script>
<?php echo $javascript->link('course')?>
<form name="frm" id="frm" method="post" action="<?php echo $html->url(empty($params['data']['Course']['id'])?'add':'edit') ?>">
	<input type="hidden" name="required" id="required" value="course" />
  <?php echo empty($params['data']['Course']['id']) ? null : $html->hidden('Course/id'); ?>
  <?php echo empty($params['data']['Course']['id']) ? $html->hidden('Course/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Course/updater_id', array('value'=>$rdAuth->id)); ?>
  <?php $course = $params['data']['Course']; ?>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  <tr class="tableheader">
    <td colspan="3" align="center">
        <?php echo empty($course['id'])?'Add':'Edit' ?> Course
    </td>
  </tr>
  <tr class="tablecell2">
    <td width="254" id="course_label">Course:<font color="red">*</font></td>
    <td width="405">
    <input type="text" name="course" id="course" class="input" value="<?php echo empty($course['course'])? '' : $course['course'] ?>" size="50">
    <div id="courseErr">
    <?php
      $fieldValue = isset($course['course'])? $course['course'] : '';
      $params = array('controller'=>'courses', 'data'=>null, 'fieldvalue'=>$fieldValue);
      echo $this->renderElement('courses/ajax_course_validate', $params);
    ?>
    </div>
  </td>
  <td width="243">eg. APSC 201 001 <?php echo $ajax->observeField('course', array('update'=>'courseErr', 'url'=>"/courses/checkDuplicateName", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?></td>
  </tr>
  <tr class="tablecell2">
    <td>Title:&nbsp;</td>
    <td><?php echo $html->input('Course/title', array('size'=>'50','class'=>'input')) ?> </td>
    <td> eg. Intro to APSCI </td>
  </tr>
  <tr class="tablecell2">
    <td valign="top">Instructor(s):</td>
    <td>
	<?php
	if (isset($instructor_data)) {
		echo '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
		for( $i=0; $i < count($instructor_data); $i++ ){
			echo '<tr>';
			echo '<td width="15"><a href='.$this->webroot.$this->themeWeb.$this->params['controller'].'/deleteInstructor/'.$instructor_data[$i]['User']['id'].'/'.$course['id'].'>'.$html->image('icons/x_small.gif',array('border'=>'0','alt'=>'Delete'), 'Are you sure to delete instructor \"'.$instructor_data[$i]['User']['last_name'].', '.$instructor_data[$i]['User']['first_name'] .'\"?').'</a></td><td>';
			echo '<a href=../../users/view/'.$instructor_data[$i]['User']['id'].'>';
			echo $instructor_data[$i]['User']['last_name'].', '.$instructor_data[$i]['User']['first_name'].'<br>';
			echo '</a>';
			echo '</td></tr>';
		}
	}
	echo '</table>';
	  ?>
	<div id="adddelinstructors">
	<?php
    $params2 = array('controller'=>'courses', 'instructor'=>$instructor, 'count'=>0);
    echo $this->renderElement('courses/ajax_course_instructors', $params2);
    ?>
	</div>
	</td>
    <td valign="top">
	<input type="hidden" name="add" id="add" value="0">
	<a href=# onClick="addInstructor();"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Additional Instructor', 'align'=>'middle', 'border'=>'0')); ?> - Add Additional Instructor</a>
	<br><br>
	<a href=# onClick="removeInstructor();"><?php echo $html->image('icons/delete.gif', array('alt'=>'Add Additional Instructor', 'align'=>'middle', 'border'=>'0')); ?> - Remove Additional Instructor</a>
	<?php echo $ajax->observeField('add', array('update'=>'adddelinstructors', 'url'=>"/courses/adddelinstructor", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>	</td>
  </tr>
  <tr class="tablecell2">
    <td>Status:</td>
    <td>
		<input type="radio" name="data[Course][record_status]" value="A" <?php if( $course['record_status'] == "A" ) echo "checked";?>> - Active&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="record_status" id="record_status" value="I" <?php if( $course['record_status'] == "I" ) echo "checked";?>> - Inactive<br>
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
    <td>Homepage:&nbsp;</td>
    <td><?php echo $html->input('Course/homepage', array('size'=>'50','class'=>'input')) ?> </td>
    <td> eg. http://mycoursehome.com </td>
  </tr>
  <tr class="tablecell2">
    <td colspan="3" align="center"><?php echo $html->submit('Update Course') ?>
	<input type="button" name="Back" value="Back" onClick="parent.location='<?php echo $this->webroot.$this->themeWeb.$this->params['controller']; ?>'"></td>
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
