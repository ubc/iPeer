<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF"><tr><td>
    <?php
        // Determine tole from $this->params['data']['User']['role']
        $role = "";
        $isStudent = false;
        switch ($this->params['data']['User']['role']) {
            case 'S' : $role = "Student"; $isStudent = true; break;
            case 'A' : $role = "Admin"; break;
            case 'I' : $role = "Instructor"; break;
            default : exit;
        }
    ?>

    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['User']['id'])?'add':'edit') ?>" onSubmit="return validate()">
        <?php echo empty($params['data']['User']['id']) ? null : $html->hidden('User/id'); ?>
        <?php echo empty($params['data']['User']['role']) ? null: $html->hidden('User/role'); ?>
        <?php echo empty($params['data']['User']['id']) ? $html->hidden('User/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('User/updater_id', array('value'=>$rdAuth->id)); ?>
        <input type="hidden" name="required" id="required" value="newuser last_name" />
        <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader"><td colspan="3" align="center"><?php echo "Add $role" ?></td></tr>

        <!-- Course Selection for Students Only -->
        <?php if ($isStudent) { ?>
            <tr class="tablecell2">
                <td id="course_label">To Course:</td>
                <td width="440"><?php
                    // We need a coursesList, even if it's empty
                    if (empty($coursesList)) {
                        $coursesList = array();
                    }

                    $params = array('controller'=>'courses',
                                    'courseList'=>$coursesList,
                                    'courseId'=>$rdAuth->courseId);

                    if (empty($rdAuth->courseId)) {
                        $params['defaultOpt'] = 1;
                    }

                    echo $this->renderElement('courses/course_selection_box', $params);

                    // Tell the user where they can add more courses
                    if (!count($coursesList)) {
                        echo "<br /><span style='color:gray'><strong>";
                        echo "You have no courses yet.<br />We suggest you create some first, ";
                        echo "using the tab above labeled 'Courses'.";
                        echo "</strong></span>";
                    }
                ?></td>
                <td width="201" id="course_msg" class="error">&nbsp;</td>
            </tr>
        <?php } // if ($isStudent) ?>

        <!-- User Name / Student No. -->
        <tr class="tablecell2">
            <td width="186" id="newuser_label">
                <?php echo $isStudent ? "Student Number" : "Username"; ?>
            </td>
            <td width="437">
                <input type="text" name="newuser" id="newuser" class="validate required USERNAME_FORMAT newuser_msg Invalid_Username_Format." size="50"><br /><br />
                <?php echo $isStudent ? "" : "<u>Remember:</u> Usernames must be at least 6 characters long and contain only:<li>letters, digits, _ (underscore) or @ (at symbol) or . (period) </li>"; ?>
                <?php echo $ajax->observeField('newuser', array('update'=>'usernameErr', 'url'=>"checkDuplicateName/".$user_type, 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")); ?>
            <div id='usernameErr' class="error">
                <?php echo $this->renderElement('users/ajax_username_validate', array('controller'=>'users', 'data'=>null)); ?>
            </div></td>
            <td width="255" id="newuser_msg" class="error" >&nbsp;</td>
        </tr>

        <!-- PassWord -->
        <tr class="tablecell2"><td  colspan="3">
        A password will be automatically generated, and shown on the next page, after you click "Save".<br />
        <strong>Note:</strong> If using CWL logons, students should use CWL username/password for iPeer, instead of the generated one.
        </td></tr>

        <!-- Last Name -->
        <tr class="tablecell2">
            <td id="last_name_label">Last Name:</td>
            <td><?php echo $html->input('User/last_name', array('id'=>'last_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
            <td id="last_name_msg" class="error">&nbsp;</td>
        </tr>

        <!-- First Name -->
        <tr class="tablecell2">
            <td>First Name:</td>
            <td><?php echo $html->input('User/first_name', array('id'=>'first_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?></td>
            <td id="first_name_msg" class="error">&nbsp;</td>
        </tr>

        <!-- Title  -->
        <?php if (!$isStudent) { // hide for students ?>
            <tr class="tablecell2">
                <td>Title:</td>
                <td><?php echo $html->input('User/title', array('id'=>'title', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?></td>
                <td id="title_msg" class="error">&nbsp;</td>
            </tr>
        <?php } // if (!$isStudent) ?>

        <!-- Email  -->
        <tr class="tablecell2">
            <td>Email:</td>
            <td><?php echo $html->input('User/email', array('id'=>'email', 'size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.')) ?></td>
            <td id="email_msg" class="error">&nbsp;</td>
        </tr>

        <!-- Back / Save -->
        <tr class="tablecell2">
            <td colspan="3"><div align="center"><span class="error">
            <input type="button" value="Back" onClick="javascript:window.history.back()">
            <?php echo $html->submit('Save') ?>
            <br>

        </tr>
        </table>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
        </table>
    </form>
 <?php if ($isStudent) { ?>
    <br /> <br />
    <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Import Students From Text (.txt) or CSV File (.csv)</td>
          <td><div align="right"><a href="#import" onclick="$('import').style.display='block'; toggle(this);">[click here to start]</a> </div></td>
        </tr>
    </table>
  <div id="import" style="display: <?php echo isset($import_again) ? "block" : "none" ?>; background: #FFF;">
  <br>
  <?php echo $javascript->link('showhide')?>
  <table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td>
          <table width="95%"  border="0" cellspacing="2" cellpadding="8" align="center">
    <tr class="tableheader">
      <td width="50%">Instructions</td>
      <td width="50%">Import</td>
    </tr>
    <tr class="tablecell2">
      <td>
          <?php
          if (isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL) {
                  echo $this->renderElement('users/user_import_info_cwl');
          } else {
          echo $this->renderElement('users/user_import_info');
                  }?>
           </td>
     <td valign="top"><br>
<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" >
    <input type="hidden" name="required" value="file" />
    <h3>1) Please select a CSV file to import:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="file" name="file" value="Browse" /><br>
    <?php echo  $html->hidden('User/role'); ?>
    <?php
    if (empty($rdAuth->courseId)) {
        $params = array('controller'=>'users', 'courseList'=>$courseList, 'defaultOpt'=>1);
    } else {
        $params = array('controller'=>'users', 'courseList'=>$courseList);
    } ?>

    <br /><h3>2) Select the course to import into:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo $this->renderElement('courses/course_selection_box', $params); ?>
    <br>
    <br /><h3>3) Click the button bellow to Import the students:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo $html->submit('Import Student List') ?>
</form>
 </center>
 <br></td>
   </tr>
 </table>
 <?php } ?>
    </td></tr></table>
</td></tr></table>
