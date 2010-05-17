<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<!--form id="searchForm" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?></td>
    <td width="35"> <b>Search:</b> </td>
    <td width="35"><select name="select" id="select2">
      <option value="name" >Name</option>
      <option value="description" >Description</option>
      <option value="point_per_member" >Base Point Per Member</option>
    </select></td>
    <td width="35"><input type="text" name="livesearch2" id="livesearch" size="30"></td>
    <td width="80%" align="right">&nbsp;</td>
  </tr>
</table>
</form-->
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr>
	    <td colspan="5">
      <?php
      echo $this->renderElement('evaltools/tools_menu', array());
      ?>
	    </td>
	    <td align="right" colspan="2">&nbsp;	</td>
	  </tr>
	  <tr class="tableheader">
	    <td colspan="7">Simple Evaluations</td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="5" align="right">
          <?php echo $html->image('icons/add.gif', array('alt'=>'Add Simple Evaluation', 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->linkTo('Add Simple Evaluation', '/simpleevaluations/add'); ?>&nbsp;
          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="60%">Name</th>
    	    <th width="50">In Use</th>
    	    <th>Base Point Per Member</th>
    	  </tr>
      	<?php $i = '0';?>
    	  <?php foreach($simpleEvalData as $row): $simpleeval = $row['SimpleEvaluation']; ?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
    		  <td align="left">
    	      <a href="<?php echo $this->webroot.$this->themeWeb.'simpleevaluations/view/'.$simpleeval['id']?>"><?php echo $simpleeval['name']?></a>
    	    </td>
    		  <td align="center"><?php
        		if($sysContainer->checkEvaluationToolInUse('1', $simpleeval['id']))
        			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
        		else
        			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
        	 ?></td>
    	    <td align="center">
    	      <?php echo $simpleeval['point_per_member'] ?>
    	    </td>
    	  </tr>
    	  <?php $i++;?>
    	  <?php endforeach; ?>
        <?php if ($i == 0) :?>
      	<tr class="tablecell" align="center">
      	    <td colspan="4">Record Not Found</td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
	  <tr class="tableheader">
	    <td colspan="7">Rubrics</td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="8" align="right">
          <?php if (!empty($access['RUBRIC_RECORD'])) {   ?>
          <?php echo $html->image('icons/add.gif', array('alt'=>'Add Rubric', 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->linkTo('Add Rubric', '/rubrics/add'); ?>
          <?php }?>          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="60%">Name</th>
    	    <th width="50">In Use</th>
    	    <th>Public</th>
    	    <th>LOM</th>
    	    <th>Criteria</th>
    	    <th>Total Marks</th>
    	  </tr>
      	<?php $i = '0';?>
    	  <?php foreach($rubricData as $row): $rubric = $row['Rubric']; ?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
    		  <td align="left"><?php echo $html->link($rubric['name'], '/rubrics/view/'.$rubric['id']) ?></td>
          <td align="center"><?php
        		if($sysContainer->checkEvaluationToolInUse('2', $rubric['id']))
        			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
        		else
        			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
        	 ?></td>
          <td align="center"><?php
        	if( $rubric['availability'] == "public" )
        		echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
        	else
        		echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
        	?></td>
          <td align="center"><?php echo $rubric['lom_max'] ?></td>
          <td align="center"><?php echo $rubric['criteria'] ?></td>
          <td align="center"><?php echo $rubric['total_marks'] ?></td>
    	  </tr>
    	  <?php $i++;?>
    	  <?php endforeach; ?>
        <?php if ($i == 0) :?>
      	<tr class="tablecell" align="center">
      	    <td colspan="7">Record Not Found</td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
	  <tr class="tableheader">
	    <td colspan="7">Mixed Evaluations</td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="8" align="right">
          <?php if (!empty($access['MIX_EVAL_RECORD'])) {   ?>
          <?php echo $html->image('icons/add.gif', array('alt'=>'Add Mix Evaluation', 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->linkTo('Add Mix Evaluation', '/mixevals/add'); ?>
          <?php }?>
          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="60%">Name</th>
    	    <th width="50">In Use</th>
    	    <th>Public</th>
    	    <th>Lickert Scale Questions</th>
    	    <th>Prefill Questions</th>
    	    <th>Total Marks</th>
    	  </tr>
        <?php $i = '0';?>
        <?php
        foreach ($mixevalData as $row): $mixeval = $row['Mixeval']; ?>
        <tr class="tablecell">
          <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
          <td align="left">
          <?php echo $html->link($mixeval['name'], '/mixevals/view/'.$mixeval['id']) ?>
          </td>
          <td align="center">
        	<?php
        		if($sysContainer->checkEvaluationToolInUse('4', $mixeval['id']))
        			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
        		else
        			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
        	 ?>
          </td>
          <td align="center">
        	<?php

        	if( $mixeval['availability'] == "public" )
        		echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
        	else
        		echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
        	?>
          </td>
          <td align="center">
        	<?php echo $mixeval['lickert_question_max'] ?>
          </td>
          <td align="center">
        	<?php echo $mixeval['prefill_question_max'] ?>
          </td>
          <td align="center">
        	<?php echo $mixeval['total_marks'] ?>
          </td>
        </tr>
        <?php $i++;?>
        <?php endforeach; ?>
        <?php if ($i == 0) :?>
      	<tr class="tablecell" align="center">
      	    <td colspan="8">Record Not Found</td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
<?php if (!empty($access['SURVEY'])) {   ?>
	  <tr class="tableheader">
	    <td colspan="7">Team Maker</td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="6" align="right">
        <?php if (!empty($access['SURVEY_RECORD'])) {   ?>
        <?php echo $html->image('icons/add.gif', array('alt'=>'Add Survey', 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->linkTo('Add Survey', '/surveys/add'); ?>
        <?php }?>          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="42%">Name</th>
    	    <th width="45">In Use</th>
    	    <th>Course</th>
    	    <th width="170">Due Date</th>
          <th>Released?</th>
    	  </tr>
      	<?php $i = '0';?>
    	  <?php foreach($surveyData as $row): $survey = $row['Survey']; ?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
    		  <td align="left"><?php echo $html->link($survey['name'], '/surveys/questionssummary/'.$survey['id']) ?></td>
          <td align="center"><?php
        		if($sysContainer->checkEvaluationToolInUse('3', $survey['id']))
        			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
        		else
        			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
        	 ?></td>
    		  <td align="center"><?php echo $survey['course_id'] == '-1' ? 'N/A':$sysContainer->getCourseName($survey['course_id']) ?></td>
          <td align="center">
            <?php
              if (!empty($survey['due_date'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($survey['due_date'])));
          ?></td>
          <td align="center">
      		<?php
      		if($survey['released'])
      			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
      		else
      			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
      	 ?></td>
    	  </tr>
    	  <?php $i++;?>
    	  <?php endforeach; ?>
        <?php if ($i == 0) :?>
      	<tr class="tablecell" align="center">
      	    <td colspan="6">Record Not Found</td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
<?php } ?>
  </table>
</td>
</tr>
</table>