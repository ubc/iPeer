<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url('export_all/') ?>">
      <input type="hidden" name="assigned" id="assigned"/>
      <table width="85%" border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="3" align="center"><?php __('Export Evaluation Results')?></td>
        </tr>
        <tr class="tablecell2">
          <td width="30%"><?php __('Export Filename')?>:</td><td width="40%"><input type="text" name="file_name" value="<?php if(isset($file_name)) echo $file_name;?>" />.csv</td><td width="30%"></td>
        </tr>
        <tr class="tablesubheader">
          <td colspan="3" align="center"><?php __('Header')?></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Course Name:')?></td><td><input type="checkbox" name="include_course" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Date of Export:')?></td><td><input type="checkbox" name="include_date" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Instructor Name:')?></td><td><input type="checkbox" name="include_instructor" checked /></td><td></td>
        </tr>
        <tr class="tablesubheader">
          <td colspan="3" align="center"><?php __('Body')?></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Evaluation Event Names:')?></td><td><input type="checkbox" name="include_eval_event_names" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Evaluation Type:')?></td><td><input type="checkbox" name="include_eval_event_type" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Group Names:')?></td><td><input type="checkbox" name="include_group_names" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Group Status:')?></td><td><input type="checkbox" name="include_group_status" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Student First Name:')?></td><td><input type="checkbox" name="include_student_first" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Student Last Name:')?></td><td><input type="checkbox" name="include_student_last" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Student Id:')?></td><td><input type="checkbox" name="include_student_id" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Student Email:')?></td><td><input type="checkbox" name="include_student_email" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Criteria Legend:')?></td><td><input type="checkbox" name="include_criteria_legend" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Criteria Marks:')?></td><td><input type="checkbox" name="include_criteria_marks" checked /></td><td></td>
        <tr class="tablecell2">
          <td><?php __('Include General Comments:')?></td><td><input type="checkbox" name="include_general_comments" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Criteria Comments:')?></td><td><input type="checkbox" name="include_criteria_comments" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td><?php __('Include Final Marks:')?></td><td><input type="checkbox" name="include_final_marks" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td colspan="3" align="center"><?php echo $html->submit(__('Export', true)) ?></td>
        </tr>
      </table>
    </form>
  </td>
  </tr>
</table>