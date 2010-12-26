<?php $readonly = isset($readonly) ? $readonly : false;?>
<?php $username_msg = $readonly ? '' : '<br /><u>Remember:</u> Usernames must be at least 6 characters long and contain only:<li>letters, digits, _ (underscore) or @ (at symbol) or . (period) </li>';?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <?php echo $this->Form->create('User',
                                   array('id' => 'frm',
                                         'url' => array('action' => $this->action),
                                         'inputDefaults' => array('div' => false,
                                                                  'before' => '<td width="200px">',
                                                                  'after' => '</td>',
                                                                  'between' => '</td><td>')))?>
      <input type="hidden" name="required" id="required" value="username" />
      <table width="75%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader"><td colspan="3" align="center"><?php echo ucfirst($this->action)?> User</td></tr>

        <!-- User Name -->
        <tr class="tablecell2">
          <?php echo $this->Form->input('username', array('id' => 'username', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT username_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'after' => $username_msg,
                                                          'error' => array('unique' => __('Duplicate Username found. Please change the username.', true)),
                                                          'readonly' => $readonly));?>
          <?php echo $readonly ? '' : $ajax->observeField('username', array('update'=>'usernameErr', 'url'=>'checkDuplicateName/', 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")); ?>
          <td width="255" id="username_msg" class="error" ><div id='usernameErr' class="error"></div></td>
        </tr>

        <?php if(!$readonly && !$isEdit):?>
        <!-- Password -->
          <tr class="tablecell2"><td  colspan="3">
          A password will be automatically generated, and shown on the next page, after you click "Save".<br />
          <strong>Note:</strong> If using CWL logons, students should use CWL username/password for iPeer, instead of the generated one.
          </td></tr>
        <?php endif;?>

        <!-- First Name -->
        <tr class="tablecell2">
            <?php echo $this->Form->input('first_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                              'readonly' => $readonly)) ?>
            <td id="first_name_msg" class="error">&nbsp;</td>
        </tr>

        <!-- Last Name -->
        <tr class="tablecell2">
            <?php echo $this->Form->input('last_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                             'readonly' => $readonly))?>
            <td id="last_name_msg" class="error">&nbsp;</td>
        </tr>


        <!-- Email  -->
        <tr class="tablecell2">
            <?php echo $this->Form->input('email', array('size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.',
                                                         'after' => '',
                                                         'readonly' => $readonly)) ?>
            <td id="email_msg" class="error">&nbsp;</td>
        </tr>

        <tr class="tablecell2">
          <?php echo $this->Form->input('Role.Role', array('disabled' => $readonly));?>
           <td id="role_msg" class="error">&nbsp;</td>
        </tr>

        <script language="JavaScript" type="text/javascript">
        function updateFields() {
          var options = $$('select#RoleRole option');
          var student_field_action = "hide";
          var nonstudent_field_action = "hide";

          $F('RoleRole').each(function(selected){
            options.each(function(option) {
              if(option.value == selected) {
                if(option.text == 'student') {
                  student_field_action = "show";
                } else {
                  nonstudent_field_action = "show";
                }
              }
            });
          });
          $$('tr.student_field').invoke(student_field_action);
          $$('tr.nonstudent_field').invoke(nonstudent_field_action);
        }
        $('RoleRole').observe('change', updateFields);
        </script>

        <!-- Title  -->
        <tr class="tablecell2 nonstudent_field" style="display:none;">
          <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.',
                                                       'readonly' => $readonly)) ?>
          <td id="title_msg" class="error">&nbsp;</td>
        </tr>

        <!-- student no-->
        <tr class="tablecell2 student_field" style="display:none;">
          <?php echo $this->Form->input('student_no', array('size'=>'50', 'class'=>'validate none',
                                                            'readonly' => $readonly)) ?>
          <td id="student_no_msg" class="error">&nbsp;</td>
        </tr>

        <!-- student courses-->
        <?php if ($isStudent) : ?>
          <tr class="tablecell2"> <td width="130" id="courses_label">This student's<br />Courses:</td>
          <td colspan=2><?php
            // Render the course list, with check box selections
            echo $this->element("list/checkBoxList", array(
                "eachName" => "Course",
                "setName" => "Courses",
                "verbIn" => "add",
                "verbOut" => "remove",
                "list" => $simpleCoursesList,
                "readOnly" => $readonly,
                "selection" => $simpleEnrolledList)); ?>
          </td></tr>
        <?php endif; ?>

        <?php if($readonly):?>
        <tr class="tablecell2">
          <?php echo $this->Form->input('creator', array('size'=>'50', 'class'=>'validate none',
                                                         'readonly' => $readonly)) ?>
          <td></td>
        </tr>

        <tr class="tablecell2">
          <?php echo $this->Form->input('updater', array('size'=>'50', 'class'=>'validate none',
                                                         'readonly' => $readonly)) ?>
          <td></td>
        </tr>

        <tr class="tablecell2">
          <?php echo $this->Form->input('created', array('type' => 'text',
                                                         'size'=>'50', 'class'=>'validate none',
                                                         'readonly' => $readonly)) ?>
          <td></td>
        </tr>
        <tr class="tablecell2">
          <?php echo $this->Form->input('modified', array('type' => 'text',
                                                         'size'=>'50', 'class'=>'validate none',
                                                         'readonly' => $readonly)) ?>
          <td></td>
        </tr>
        <?php endif;?>

        <!-- Back / Save -->
        <tr class="tablecell2">
            <td colspan="3" align="center">
            <input type="button" value="Back" onClick="javascript:window.location='../index'";>
            <?php if (!$readonly) echo $this->Form->submit('Save', array('div' => false));?>
            </td>
        </tr>
        </table>

        <table width="75%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
        </table>
<?php echo $this->Form->end();?>
    </td></tr></table>
</td></tr>
</table>

<script language="JavaScript" type="text/javascript">
updateFields();
</script>
