<!-- elements::ajax_evaluation_list start -->
<?php echo $javascript->link('search.js');?>
<div id="ajax_update">

	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right" id="page-numbers">
            <b><?php __('Page Size')?>: </b>
            <?php                
                echo $form->input('radio', $this->Paginator->pageSize($this->params["paging"]["Event"]["options"]["limit"]));
            ?>
        </div></td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <th><?php echo $this->Paginator->sort('ID','Event.id')?></th>
	   <?php ?> 
<?php if($currentUser['role'] == 'A' || $currentUser['role'] == 'I'):?>
	        <th>View</th>
	    <?php endif;?>
	    <?php if (isset($display) && $display == 'search') { ?>
	    <th>Course</th>
	    <?php } ?>
<!--	    <th>Email <br>All Groups</th>-->
	    <th><?php echo $this->Paginator->sort(__('Evaluation Event Title', true),'title')?></th>
	    <th>Event Type</th>
	    <th><?php echo $this->Paginator->sort(__('Due Date', true),'due_date')?></th>
	    <th>Released?</th>
	    <th><?php echo $this->Paginator->sort(__('Self Evaluation', true),'self_eval')?></th>
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
  		    <a href="<?php echo $this->webroot.$this->theme.'evaluations/view/'.$evaluation['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>__('View', true), 'title'=>__('View ', true).$evaluation['title']))?></a>
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
    <td width="33%" align="left"><?php //Display page infomation
	echo '<br/>'.$this->Paginator->counter(array(
		'format' => __('Page %page% of %pages%, showing %current% records out of %count% total<br/>', true)
	));?></td>
    <td width="33%"></td>
    <td width="33%" align="right">
<?php	
	//Prev page link
        echo $this->Paginator->prev('Prev ',null,' ',array('class' => 'disabled'));
	//Shows the page numbers
	echo $this->Paginator->numbers();
	//Next page link
        echo $this->Paginator->next(' Next',null,' ',array('class' => 'disabled'));
?>
	</td>
  </tr>
</table>
</div>
<!-- elements::ajax_evaluation_list end -->
