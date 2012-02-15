<!-- elements::ajax_evaluation_result_list end -->
<div id="ajax_update">
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right" id="page-numbers"><?php echo $pagination->show('Show ',null,'eval_table')?>
        </div></td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <th><?php __('View')?></th>
<!--	    <th>Email<br>Group</th>-->
            <th><?php __('Event')?></th>
	    <th><?php __('Group')?></th>
	    <th><?php __('Completion Rate')?></th>
	    <th><?php __('Status')?></th>
	    <th><?php __('Reviewed')?></th>
	    <th><?php __('Released Comments')?></th>
	    <th><?php __('Released Grades')?></th>
	  </tr>
  	<?php $i = 0;?>
	  <?php
   if (isset($data['Evaluation']['assignedGroups'])) {
	  foreach($data['Evaluation']['assignedGroups'] as $row): $group = $row['Group'];
	    if (isset($group['id'])) {?>
  	  <tr class="tablecell">
  	    <td align="center">
  		    <a href="<?php echo $this->webroot.$this->theme.'evaluations/viewEvaluationResults/'.$group['event_id'].'/'.$group['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>__('View', true),'title'=>__('View Evaluation Result', true)))?></a>
  	    </td>
<!--  		  <td>
  		    <a href="<?php echo $this->webroot.$this->theme.'evaluations/view/'.$group['id']?>"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    </td>-->
            <td>
              <a title=<?php __('Event Title')?> href="<?php echo $this->webroot.$this->theme;?>events/view/<?php echo $group['event_id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $group['event_title'] ?></a> &nbsp;
            </td>
  		  <td>
  	      <a title=<?php __('Group Submission Detail')?> href="<?php echo $this->webroot.$this->theme;?>evaluations/viewGroupSubmissionDetails/<?php echo $group['event_id']?>/<?php echo $group['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo __('Group ', true).$group['group_num'].' - '.$group['group_name'] ?></a> &nbsp;
  	    </td>
        <td align="center"><?php echo '<b>'.$group['num_completed'].'</b> / <b>'.$group['num_members'].'</b>'. __('completed', true) ?></td>
  	    <td>
  	      <?php
  	      $imageFile = '';
  	      $imageText = '';

  	      if ($group['complete_status']) {
  	        $imageFile = '/icons/green_check.gif';
  	        $imageText = __('Completed', true);
  	      } else {
  	        $imageFile = '/icons/red_x.gif';
  	        $imageText = __('Not Completed', true);
  	      }
  	      echo $html->image($imageFile, array('alt'=>$imageText, 'title'=>$imageText));?>
  	    </td>
  	    <td>
  	      <?php
  	      $imageFile = '';
  	      $imageText = '';

  	      if ($group['marked'] == "reviewed") {
  	        $imageFile = '/icons/green_check.gif';
  	        $imageText = __('marked', true);
  	      } else if ($group['marked'] == "to review") {
  	        $imageFile = '/icons/yellow_x.gif';
  	        $imageText = __('Ready to Review', true);
  	      } else {
  	        $imageFile = '/icons/red_x.gif';
  	        $imageText = __('Not Ready to Review', true);
  	      }
  	      echo $html->image($imageFile, array('alt'=>$imageText, 'title'=>$imageText));?>

  	    </td>
  	    <td>
  	      <?php echo $group['comment_release_status']?>
  	    </td>
  	    <td>
  	      <?php echo $group['grade_release_status']?>
  	    </td>
  	  </tr>
  	  <?php $i++;?>
  	<?php
  	  } endforeach;
  	}?>
  </table>
</div>
<!-- elements::ajax_evaluation_result_list end -->
