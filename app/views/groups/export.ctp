<?php
	echo $html->script('groups');
?>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url('export/'.$courseId) ?>">
      <table width="75%" border="0" align="center" cellpadding="10" cellspacing="2">
	 	<tr><td style="color:darkred; font-size:smaller"> *Please check at least one of similarly coloured fields</td></tr>
        <tr class="tableheader"> 
          <td colspan="2" align="center">Export Groups</td>
        </tr>	 	
        <tr class="tablecell2">
          <td width="30%">Export Filename:</td><td width="40%"><input type="text" name="file_name" value="<?php if(isset($file_name)) echo $file_name;?>" />.csv</td>
        </tr>
        <tr class="tableheader"> 
          <td colspan="2" align="center">Export Group Fields</td>
        </tr>
        <tr class="tablecell2">
          <td width="60%">Include Group Names:</td><td><input type="checkbox" name="include_group_names" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Studenta Name:&nbsp;<font color="Red">*</td><td><input type="checkbox" name="include_student_name" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Id:&nbsp;<font color="Red">*</td><td><input type="checkbox" name="include_student_id" checked /></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Email:</td><td><input type="checkbox" name="include_student_email" checked /></td>
        </tr>
        </table>
        <table width="75%" border="0" align="center" cellpadding="10" cellspacing="1">
        <tr class="tableheader">
          <td colspan="1" align="center">Export Groups</td>
        </tr>
        <tr class="tablecell2">
          <td align="center"> 		  
          <?php
	  	    echo $this->element("groups/group_list_chooser",
	   	    array('all' => $unassignedGroups, 'assigned'=>'',
	          'allName' =>  __('Avaliable Groups', true), 'selectedName' => __('Participating Groups', true),
	          'itemName' => 'Group', 'listStrings' => array("Group #", "group_num"," - ","group_name")));   
		  ?>
		  </td>
		</tr>
        <tr class="tablecell2">
          <td colspan="3" align="center">
          <?php echo $this->Form->submit(ucfirst($this->action).__(' Event', true), array('div' => false,
			'onClick' => "processSubmit(document.getElementById('selected_groups'));")) ?>
		  </td>
        </tr>
      </table>
    </form>
  </td>
  </tr>
</table>