<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td><script type="text/javascript" language="javascript">
<!--
  var total_course_count = <?php echo $course_count?>;
//-->
</script>
<?php echo $javascript->link('user')?>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['User']['id'])?'add':'edit') ?>" onSubmit="return validate()">
  <?php echo empty($params['data']['User']['id']) ? null : $html->hidden('User/id'); ?> <?php echo empty($params['data']['User']['role']) ? null: $html->hidden('User/role'); ?>
  <input type="hidden" name="required" id="required" value="last_name first_name" />
  <?php echo empty($params['data']['User']['id']) ? $html->hidden('User/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('User/updater_id', array('value'=>$rdAuth->id)); ?>
  <table width="95%" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td align="center" colspan="3"><?php echo empty($params['data']['User']['id'])?'Add':'Edit' ?> User </td>
      </tr>
    <tr class="tablecell2">
      <td width="130" id="username_label">Username:*</td>
      <td width="337"><?php echo $html->input('User/username', array('size'=>'50', 'disabled'=>'true')) ?></td>
      <td width="663" id="username_msg" class="error"></td>
    </tr>
    <tr class="tablecell2">
      <td width="130" id="last_name_label">Last Name:</td>
      <td width="337"><?php echo $html->input('User/last_name', array('id'=>'last_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
      <td width="663" id="last_name_msg" class="error"></td>
    </tr>
    <tr class="tablecell2">
      <td width="130" id="first_name_label">First Name:</td>
      <td><?php echo $html->input('User/first_name', array('id'=>'first_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?> </td>
      <td width="663" id="first_name_msg" class="error"></td>
    </tr class="tablecell2">
    <?php if ($params['data']['User']['role'] == 'S')  :?>
    <tr class="tablecell2">
      <td width="130" id="student_no_label">Student No.:</td>
      <td><?php echo $html->input('User/student_no', array('id'=>'student_no', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT student_no_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?> </td>
      <td width="663" id="student_no_msg" class="error"></td>
    </tr>
    <?php else: ?>
    <tr class="tablecell2">
      <td width="130" id="title_label">Title:</td>
      <td><?php echo $html->input('User/title', array('id'=>'title', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?> </td>
      <td width="663" id="title_msg" class="error"></td>
    </tr>
    <?php endif;?>
    <tr class="tablecell2">
      <td width="130" id="email_label">Email:</td>
      <td><?php echo $html->input('User/email', array('id'=>'email', 'size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.')) ?> </td>
      <td width="663" id="email_msg" class="error"></td>
    </tr>
    <?php if (($params['data']['User']['role'] == 'S') && (($rdAuth->role == 'I') || ($rdAuth->role == 'A'))):?>
    <tr class="tablecell2">
      <td width="130" id="courses_label">Courses</td>
      <td>
        <?php if (isset($enrolled_courses)):?>
         <table  width="100%" border="0" cellspacing="2" cellpadding="2">
         <?php foreach($enrolled_courses as $ec):?>
          	<tr>
              <td width="15">
                <?php if(User::canRemoveCourse($user, $ec['Course']['id'])):?>
                <a href="<?php echo $this->webroot . $this->themeWeb . $this->params['controller'] . '/removeFromCourse/'.$ec['Course']['id']. '/' .$params['data']['User']['id']?>"><?php echo $html->image('icons/x_small.gif',array('border'=>'0','alt'=>'Delete'), 'Are you sure you want to remove \"' . $ec['Course']['title'] . '\"?')?></a>
                <?php endif;?>
              </td>
              <td><a href="../../courses/view/<?php echo $ec['Course']['id']?>"><?php echo $ec['Course']['course']?></a></td>
            </tr>
          <?php endforeach;?>
          </table>
        <?php endif;?>
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
        <div id="adddelcourses">
        <?php
        $params2 = array('controller'=>'users', 'all_courses'=>$all_courses, 'count'=>0, 'user_id' => $user_id);
        echo $this->renderElement('users/ajax_user_courses', $params2);
        ?>
        </div>
      </td>
      <td>

        <input type="hidden" name="add" id="add" value="0">
        <a href=# onClick="addToCourse();"><?php echo $html->image('icons/add.gif', array('alt'=>'Add Additional Instructor', 'align'=>'middle', 'border'=>'0')); ?> - Add Another Course</a>
        <br><br>
        <a href=# onClick="removeFromCourse();"><?php echo $html->image('icons/delete.gif', array('alt'=>'Add Additional Instructor', 'align'=>'middle', 'border'=>'0')); ?> - Remove From Another Course</a>
        <?php echo $ajax->observeField('add', array('update'=>'adddelcourses', 'url'=>"/users/adddelcourse/$user_id", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');")) ?>

      </td>
    </tr>
    <?php endif;?>
<?php if (($params['data']['User']['role'] == 'S'))  {
         if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) :?>
          <tr class="tablecell2">
            <td id="password_label">Password:</td>
            <td colspan="2"><?php echo $html->link('Reset Password', '/users/resetPassword/'.$params['data']['User']['id'], '', 'Are you sure to reset the password of the current user?') ?> </td>
        	</tr>
<?php    endif; ?>
<?php  } else { ?>
          <tr class="tablecell2">
            <td id="password_label">Password:</td>
            <td colspan="2"><?php echo $html->link('Reset Password', '/users/resetPassword/'.$params['data']['User']['id'], '', 'Are you sure to reset the password of the current user?') ?> </td>
        	</tr>
<?php  }  ?>

    <tr class="tablecell2">
      <td align="center" colspan="3" id="password_label">	   <input type="button" value="Back" onClick="javascript:window.history.back()">

      <?php echo $html->submit('Save') ?>

	  </td>
      </tr>
  </table>
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
    <tr>
      <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
      <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
    </tr>
  </table>
</form></td>
</tr>
</table>
