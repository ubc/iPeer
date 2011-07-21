    <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> <?php __('Import Students From Text (.txt) or CSV File (.csv)')?></td>
          <td><div align="right"><a href="#import" onclick="$('import').style.display='block'; toggle(this);"><?php __('[click here to start]')?></a> </div></td>
        </tr>
    </table>
  <div id="import" style="display: <?php echo isset($import_again) ? "block" : "none" ?>; background: #FFF;">
  <br>
  <?php echo $html->script('showhide')?>
  <table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td>
          <table width="95%"  border="0" cellspacing="2" cellpadding="8" align="center">
    <tr class="tableheader">
      <td width="50%"><?php __('Instructions')?></td>
      <td width="50%"><?php __('Import')?></td>
    </tr>
    <tr class="tablecell2">
      <td>
          <?php
          if (isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL) {
                  echo $this->element('users/user_import_info_cwl');
          } else {
              echo $this->element('users/user_import_info');
          }?>
           </td>
     <td valign="top"><br>
<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" onSubmit="return import_validate();">
    <input type="hidden" name="required" value="course_id"/>
    <h3 id="file_label">1) <?php __('Please select a CSV file to import:')?></h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="file" name="file" value="Browse" class="required"/><br>
    <?php echo $this->Form->hidden('User/role'); ?>
    <br /><h3 id="course_id_label">2) <?php __('Select the course to import into:')?></h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php $course_params['id_prefix'] = 'import';?>
    <?php echo $this->element('courses/course_selection_box', $course_params); ?>
    <br>
    <br /><h3>3) <?php __('Click the button bellow to Import the students:')?></h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo $this->Form->submit(__('Import Student List', true)) ?>
</form>
 <br></td>
   </tr>
 </table>
