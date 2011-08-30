<div class="content-container">

<?php echo $this->element('evaltools/tools_menu', array());?>

<!--form id="searchForm" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="10" height="32"><?php echo $html->image('magnify.png', array('alt'=>__('Magnify Icon', true)));?></td>
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
	<table class="list-table">
	  <tr class="tableheader">
	    <td colspan="7"><?php __('Simple Evaluations')?></td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="5" align="right">
          <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Simple Evaluation', true), 'align'=>'middle','alt'=>__('add', true))); ?>&nbsp;<?php echo $html->link(__('Add Simple Evaluation', true), '/simpleevaluations/add'); ?>&nbsp;
          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="60%"><?php __('Name')?></th>
    	    <th width="50"><?php __('In Use')?></th>
    	    <th><?php __('Base Point Per Member')?></th>
    	  </tr>
      	<?php $i = '0';?>
    	  <?php foreach($simpleEvalData as $row): $eval = $row['SimpleEvaluation']; ?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
    		  <td align="left">
    	      <a href="<?php echo $this->webroot.$this->theme.'simpleevaluations/view/'.$eval['id']?>"><?php echo $eval['name']?></a>
    	    </td>
    		  <td align="center">
        	<?php echo $eval['event_count'] > 0 ?
                     $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check')) :
                     $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));?>
          </td>
    	    <td align="center">
    	      <?php echo $eval['point_per_member'] ?>
    	    </td>
    	  </tr>
    	  <?php $i++;?>
    	  <?php endforeach; ?>
        <?php if ($i == 0) :?>
      	<tr class="tablecell" align="center">
      	    <td colspan="4"><?php __('Record Not Found')?></td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
	  <tr class="tableheader">
	    <td colspan="7"><?php __('Rubrics')?></td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="8" align="right">
          <?php if (!empty($access['RUBRIC_RECORD'])) {   ?>
          <?php echo $html->image('icons/add.gif', array('alt'=>'Add Rubric', 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->link(__('Add Rubric', true), '/rubrics/add'); ?>
          <?php }?>          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="60%"><?php __('Name')?></th>
    	    <th width="50"><?php __('In Use')?></th>
    	    <th><?php __('Public')?></th>
    	    <th><?php __('LOM')?></th>
    	    <th><?php __('Criteria')?></th>
    	    <th><?php __('Total Marks')?></th>
    	  </tr>
      	<?php $i = '0';?>
    	  <?php foreach($rubricData as $row): $rubric = $row['Rubric']; ?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
    		  <td align="left"><?php echo $html->link($rubric['name'], '/rubrics/view/'.$rubric['id']) ?></td>
          <td align="center">
        	<?php echo $rubric['event_count'] > 0 ?
                     $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check')) :
                     $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));?>
          </td>
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
      	    <td colspan="7"><?php __('Record Not Found')?></td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
	  <tr class="tableheader">
	    <td colspan="7"><?php __('Mixed Evaluations')?></td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="8" align="right">
          <?php if (!empty($access['MIX_EVAL_RECORD'])) {   ?>
          <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Mix Evaluation', true), 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->link(__('Add Mix Evaluation', true), '/mixevals/add'); ?>
          <?php }?>
          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="60%"><?php __('Name')?></th>
    	    <th width="50"><?php __('In Use')?></th>
    	    <th><?php __('Public')?></th>
    	    <th><?php __('Lickert Scale Questions')?></th>
    	    <th><?php __('Prefill Questions')?></th>
    	    <th><?php __('Total Marks')?></th>
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
        	<?php echo $mixeval['event_count'] > 0 ?
                     $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check')) :
                     $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));?>
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
      	    <td colspan="8"><?php __('Record Not Found')?></td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
<?php if (!empty($access['SURVEY'])):?>
	  <tr class="tableheader">
	    <td colspan="7"><?php __('Surveys (Team Maker)')?></td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="6" align="right">
          <?php if (!empty($access['SURVEY_RECORD'])):?>
          <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Survey', true), 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->link(__('Add Survey', true), '/surveys/add'); ?>
          <?php endif;?>
          </td>
	      </tr>
    	  <tr class="panelContent">
    	    <th colspan="2" width="42%"><?php __('Name')?></th>
    	    <th width="45"><?php __('In Use')?></th>
    	    <th><?php __('Course')?></th>
    	    <th width="170"><?php __('Due Date')?></th>
          <th><?php __('Released?')?></th>
    	  </tr>
        <?php if(!empty($surveyData)):?>
    	  <?php foreach($surveyData as $row): $survey = $row['Survey'];?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
    		  <td align="left"><?php echo $html->link($survey['name'], '/surveys/questionssummary/'.$survey['id']) ?></td>
          <td align="center">
        	<?php echo $survey['event_count'] > 0 ?
                     $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check')) :
                     $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));?>
          </td>
    		  <td align="center"><?php echo $survey['course_id'] == '-1' ? 'N/A':$row['Course']['course']?></td>
          <td align="center">
            <?php
              if (!empty($survey['due_date'])) echo Toolkit::formatDate(date('Y-m-d H:i:s', strtotime($survey['due_date'])));
          ?></td>
          <td align="center">
      		<?php
                if(date('Y-m-d H:i:s',time()) > $survey['release_date_begin'])
      			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
      		else
      			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
      	 ?></td>
    	  </tr>
    	  <?php endforeach; ?>
        <?php else:?>
      	<tr class="tablecell" align="center">
      	    <td colspan="6"><?php __('Record Not Found')?></td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
<?php endif; ?>
<?php if (!empty($access['EMAIL_TEMPLATE'])):?>
	  <tr class="tableheader">
	    <td colspan="7"><?php __('Email Templates')?></td>
	  </tr>
	  <tr class="panelContent">
	    <td colspan="7"><table width="100%" bgcolor="#FFFFFF">
	      <tr class="panelContent">
	        <td colspan="6" align="right">
          <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Survey', true), 'align'=>'middle','alt'=>'add')); ?>&nbsp;<?php echo $html->link(__('Add Email Template', true), '/emailtemplates/add'); ?>
          
          </td>
	      </tr>
    	  <tr class="panelContent">
            <th colspan="2" width="20"><?php __('Name')?></th>
             <th width="20%"><?php __('Subject')?></th>
    	    <th width="60%"><?php __('Description')?></th>

    	  </tr>
        <?php if(!empty($emailTemplates)):?>
    	  <?php foreach($emailTemplates as $row): $emailTemplate = $row['EmailTemplate'];?>
    	  <tr class="tablecell">
    	    <td width="1%" bgcolor="#FFB66F">&nbsp;</td>
            <td align="left"><?php echo $html->link($emailTemplate['name'], '/emailtemplates/view/'.$emailTemplate['id']) ?></td>
            <td align="left"><?php echo $emailTemplate['subject'];?></td>
            <td align="center"><?php echo $emailTemplate['description'];?></td>
    	  </tr>
    	  <?php endforeach; ?>
        <?php else:?>
      	<tr class="tablecell" align="center">
      	    <td colspan="6"><?php __('Record Not Found')?></td>
        </tr>
        <?php endif;?>
    	  </table>
	  </td>
	  </tr>
<?php endif; ?>

  </table>

</div>
