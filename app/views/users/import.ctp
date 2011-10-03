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
          <td valign="top">
            <?php echo $this->Form->create(null, array('action' => 'importFile',
                                                       'type' => 'file',
                                                       'onSubmit' => 'return import_validate();',
                                                       'id' => 'importfrm',
                                                       'name' => 'importfrm'));?>

              <input type="hidden" name="required" value="course_id"/>
              <?php echo $this->Form->hidden('User/role'); ?>
              <ol>
              <li><h3><?php __('Please select a CSV file to import:')?></h3><?php echo $this->Form->file('file', array('name' => 'file', 'class' => 'required')); ?></li>
              <li><h3><?php __('Select the course to import into:')?></h3><?php echo $this->element('courses/course_selection_box', $courseParams); ?></li>
              <li><h3><?php __('Click the button bellow to Import the students:')?></h3><?php echo $this->Form->submit(__('Import Student List', true))?></li>
              </ol>
              <?php echo $this->Form->end();?>
          </td>
        </tr>
        </table>
      </td>
    </tr>
</table>
