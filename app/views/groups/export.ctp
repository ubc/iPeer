<?php
	echo $html->script('groups');
?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url('export/'.$courseId) ?>">
      <table width="75%" border="0" align="center" cellpadding="10" cellspacing="2">
        <tr class="tableheader"> 
          <td colspan="2" align="center">Export As</td>
        </tr>	 	
        <tr class="tablecell2">
          <td width="30%">Export Filename:</td><td width="40%"><input type="text" name="file_name" value="<?php if(isset($file_name)) echo $file_name;?>" />.csv</td>
        </tr>
        <tr class="tableheader"> 
          <td colspan="2" align="center">Export Group Fields</td>
        </tr>
        <tr><td style="color:darkred; font-size:smaller"> (Please select at least one of the fields)</td></tr>
        <tr class="tablecell2">
          <td width="60%">Include Group Number(s):</td><td><input type="checkbox" name="include_group_numbers" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td width="60%">Include Group Name(s):</td><td><input type="checkbox" name="include_group_names" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Name(s):</td><td><input type="checkbox" name="include_student_name" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Id #:</td><td><input type="checkbox" name="include_student_id" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Email(s):</td><td><input type="checkbox" name="include_student_email" /></td>
        </tr>
        </table>
        <table width="75%" border="0" align="center" cellpadding="10" cellspacing="1">
        <tr class="tableheader">
          <td colspan="1" align="center">Group Selection</td>
        </tr>
        <tr class="tablecell2">
          <td align="center"> 		  
          <?php
	  	    echo $this->element("groups/group_list_chooser",
	   	    array('all' => $unassignedGroups, 'assigned'=>'',
	          'allName' =>  __('Available Groups', true), 'selectedName' => __('Participating Groups', true),
	          'itemName' => 'Group', 'listStrings' => array("Group #", "group_num"," - ","group_name")));   
		  ?>
		  </td>
		</tr>
        <tr class="tablecell2">
          <td colspan="3" align="center">
          <?php echo $this->Form->submit(ucfirst($this->action).__(' Group', true), array('div' => false,
			'onClick' => "processSubmit(document.getElementById('selected_groups'));")) ?>
		  </td>
        </tr>
      </table>
    </form>
  </td>
  </tr>
</table>
