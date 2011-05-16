<!-- elements::ajax_evaluation_list start -->
<div id="ajax_update">

<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right" id="page-numbers"><?php echo $pagination->show('Show ',null,'eval_table')?></div></td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <th><?php echo $pagination->sortLink('ID',array('id','desc'))?></th>
	   <?php ?> 
<?php if($currentUser['role'] == 'A' || $currentUser['role'] == 'I'):?>
	        <th>View</th>
	    <?php endif;?>
	    <?php if (isset($display) && $display == 'search') { ?>
	    <th>Course</th>
	    <?php } ?>
<!--	    <th>Email <br>All Groups</th>-->
	    <th><?php echo $pagination->sortLink('Evaluation Event Title',array('title','desc'))?></th>
	    <th>Event Type</th>
	    <th><?php echo $pagination->sortLink('Due Date',array('due_date','desc'))?></th>
	    <th>Released?</th>
	    <th><?php echo $pagination->sortLink('Self Evaluation',array('self_eval','desc'))?></th>
	  </tr>
  	<?php $i = 0;?>
	  <?php
	  if (isset($data) && !empty($data)) {
  	  foreach($data as $row): 
        $evaluation = $row['Event'];
        $template_type = $row['EventTemplateType'];
  	  if (isset($evaluation['id'])) {?>
  	  <tr class="tablecell">
  	    <td align="center">
  		  <?php echo $evaluation['id'] ?>
  	    </td>
  	    <td align="center">
  	    <?php if($currentUser['role'] == 'A' || $currentUser['role'] == 'I'):?>
  		    <a href="<?php echo $this->webroot.$this->theme.'evaluations/view/'.$evaluation['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    <?php endif;?>
  	    </td>
  	    <?php
  	    if (isset($display) && $display == 'search') { ?>
  		  <td>
  		    <?php 
  	
  		//    echo $sysContainer->getCourseName($evaluation['course_id']); 
  		  echo $names[$i]; ?>
  	    </td>
  	    <?php } ?>
<!--  		  <td>
  		    <a href="<?php echo $this->webroot.$this->theme.'evaluations/view/'.$evaluation['id']?>"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    </td>-->
  		  <td>
  	      <?php echo $evaluation['title'] ?>
  	    </td>
  		  <td>
  	      <?php echo $template_type['type_name'] ?>
  	    </td>
  	    <td align="center"><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($evaluation['due_date']))) ?> </td>
  	    <td>
  	      <?php echo $data[$i][0]['is_released']==1? 'YES' : 'NO' ?>
  	    </td>
  	    <td>
  	      <?php echo $evaluation['self_eval']==1? 'YES' : 'NO' ?>
  	    </td>
  	  </tr>
  	  <?php $i++;?>
  	  <?php
  	  }
  	  endforeach;
  	} ?>
  </table>
    <table width="95%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'left','alt'=>'left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'right','alt'=>'right'))?></td>
      </tr>
    </table>
  <?php $pagination->loadingId = 'loading2';?>

<table width="95%"  border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="33%" align="left"><?php echo $pagination->result('Results: ')?></td>
    <td width="33%"></td>
    <td width="33%" align="right">
<?php if($pagination->set($paging)):?>
		<?php echo $pagination->prev('Prev')?>
		<?php echo $pagination->numbers()?>
		<?php echo $pagination->next('Next')?>
<?php endif;?>
	</td>
  </tr>
</table>
</div>
<!-- elements::ajax_evaluation_list end -->
