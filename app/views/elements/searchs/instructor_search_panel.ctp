<form name="frm" id="frm" method="POST" action="">
<input type="hidden" id="search_type" name="search_type" value="<?php echo $display?>"/>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td><div align="left" id="criteria_panel">
        <table width="60%" border="0" align="center" cellpadding="4" cellspacing="2">

          <tr class="searchtable3">
            <td height="25" colspan="3" align="center"><?php __('Instructor Search Panel')?></td>
          </tr>
         <tr class="tablecell2">
            <td width="186" id="course_label"><?php __('Course')?>:</td>
            <td width="437"><?php
                $params = array('controller'=>'courses', 'coursesList'=>$coursesList, 'courseId'=>null, 'defaultOpt'=>'A','sticky_course_id'=>$sticky['course_id']);
                echo $this->element('courses/course_selection_box', $params);
                ?></td>
            <td width="201" id="course_msg" class="error">&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="100" id="instructorname_label"><?php __('Name')?>:</td>
            <td width="200">
              <input type="text" name="instructorname" id="instructorname" class="validate null DATE_FORMAT instructorname_msg Invalid_Date_Format." value="<?php echo $sticky['instructor_name'] ?>" size="50"/>
            </td>
            <td width="100" id="instructorname_msg" class="error" >&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td id="email_label"><?php __('Email')?>:</td>
            <td><input type="text" name="email" id="email" class="validate null DATE_FORMAT email_msg Invalid_Date_Format." value="<?php echo $sticky['email']?>" size="50"></td>
            <td id="email_msg" class="error">&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td colspan="3"><div align="center"><?php echo $form->submit('Search',array('url'=>'/searchs/searchInstructor')) ?>
        	  <input type="reset" name="Reset" value="Reset">
            </div></td>
          </tr>
        </table>
        <table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'left','alt'=>'left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'right','alt'=>'right'))?></td>
          </tr>
        </table>
        </div>
      </td>
    </tr>
  </table>
</form>
<?php
$params = array('controller'=>'searchs', 'data'=>$data, 'display'=>'search');
echo $this->element('users/ajax_user_list', $params);
?>
