<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url('export/'.$eventId) ?>">
      <input type="hidden" name="assigned" id="assigned"/>
      <table width="85%" border="0" align="center" cellpadding="4" cellspacing="2">
      	<tr><td style="color:darkred; font-size:smaller"> *Please check at least one of similarly coloured fields</td></tr>
        <tr class="tableheader"> 
          <td colspan="3" align="center">Export Evaluation Results</td>
        </tr>
        <tr class="tablecell2">
          <td width="30%">Export Filename:</td><td width="40%"><input type="text" name="file_name" value="<?php if(isset($file_name)) echo $file_name;?>" /></td><td width="30%"></td>
        </tr>
        <tr class="tablecell2">
          <td width="30%">Export File Type:</td><td width="40%"><select name="export_type" class="export_type">
			<option value="excel">excel</option>
			<option value="csv">csv</option>          
            </select>
          </td>
          <td></td>
        </tr>                  
        <tr class="tablecell2">
          <td width="30%">Event Name:</td><td width="40%"><select name="eval_type" class= "eval_type">
          	<?php 
          	  if($fromEvent) {
				echo '<option value='.$selectedEvent['Event']['event_template_type_id'].'>'.$selectedEvent['Event']['title'].'</option>';
          	  } else {
          	      foreach($events as $e) {
          	  	  //.json_encode
          	    	        echo "<option value= '".json_encode($e)."'>".$e["Event"]["title"]."</option>";          	  	  
          	    }
          	  }
          	?>
          	</select></td> <td></td>
        </tr>        
        
        <tr class="tablesubheader">
          <td colspan="3" align="center">Header</td>
        </tr>
        <tr class="tablecell2">
          <td>Include Course Name:&nbsp;<font color="red">*</td><td><input type="checkbox" name="include_course" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Event Name:&nbsp;<font color="red">*</td><td><input type="checkbox" name="include_eval_event_names" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Date of Export:</td><td><input type="checkbox" name="include_date" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Instructor Name:</td><td><input type="checkbox" name="include_instructors" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Evaluation Type:</td><td><input type="checkbox" name="include_eval_event_type" checked /></td><td></td>
        </tr>        
        <tr class="tablesubheader">
          <td colspan="3" align="center">Body</td>
        </tr>
        <tr class="tablecell2">
          <td>Include Group Names:</td><td><input type="checkbox" name="include_group_names" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Name:&nbsp;<font color="Green">*</td><td><input type="checkbox" name="include_student_name" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Id:&nbsp;<font color="Green">*</td><td><input type="checkbox" name="include_student_id" checked /></td><td></td>
        </tr>
        <tr class="tablecell2">
          <td>Include Student Email:</td><td><input type="checkbox" name="include_student_email" checked /></td><td></td>
        </tr>
 		<?php //Simple Evaluation?>
 		    <tr class="tablecell2 simple">
          		    <td>Include Evaluator Comments:&nbsp;<font color="orange">*</td><td><input class = "csv" type="checkbox" name="simple_evaluator_comment" checked /></td><td></td>
        	      </tr>
        	      <tr class="tablecell2 simple">
          		    <td>Include Simple Evaluation Grade Table:&nbsp;<font color="orange">*</td><td><input class = "csv" type="checkbox" name="simple_eval_grade_table" checked /></td><td></td>
        	      </tr>
	
 		<?php // Rubrics Evaluation?>
 		    <tr class="tablecell2 rubrics">
		            <td>Include Rubrics Criteria Marks:&nbsp;<font color="orange">*</td><td><input class = "csv" type="checkbox" name="rubric_criteria_marks" checked /></td><td></td>
		          <tr class="tablecell2 rubrics">
		            <td>Include Rubrics General Comments:&nbsp;<font color="orange">*</td><td><input class = "csv" type="checkbox" name="rubric_general_comments" checked /></td><td></td>
		          </tr>

 		 <?php // Mix Evaluation?>
		    <tr class="tablecell2 mix">
		            <td>Include Comments Table:&nbsp;<font color="orange">*</td><td><input type="checkbox" class = "csv" name="include_mixeval_question_comment" checked /></td><td></td>
		          </tr>
		          <tr class="tablecell2 mix">
		            <td>Include Grades Table:&nbsp;<font color="orange">*</td><td><input type="checkbox" class = "csv" name="include_mixeval_grades" checked /></td><td></td>
		          </tr>

        <tr class="tablecell2">
          <td>Include Final Marks:</td><td><input type="checkbox" name="include_final_marks" checked /></td><td></td>
        </tr>
        
        <tr class="tablecell2">
          <td colspan="3" align="center"><?php echo $this->Form->submit('Export', array('onClick'=>'return checkSubmit()')) ?></td>
        </tr>
      </table>
    </form>
  </td>
  </tr>
</table>
<?php echo $html->script('export');
?>
<style type="text/css">
.cssDisabled { color: #aaa; }
</style>