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
	    <th>ID</th>
	    <th>View</th>
	    <th>Email<br>Group</th>
	    <th>Group</th>
	    <th>Completion Rate</th>
	    <th>Status</th>
	    <th>Reviewed</th>
	    <th>Released Comments</th>
	    <th>Released Grades</th>
	  </tr>
  	<?php $i = 0;?>
	  <?php
   if (isset($data['Evaluation']['assignedGroups'])) {
	  foreach($data['Evaluation']['assignedGroups'] as $row): $group = $row['Group'];
	    if (isset($group['id'])) {?>
  	  <tr class="tablecell">
  	    <td align="center">
  		  <?php echo $group['id'] ?>
  	    </td>
  	    <td align="center">
  		    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/viewEvaluationResults/'.$data['Event']['id'].';'.$group['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    </td>
  		  <td>
  		    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/view/'.$group['id']?>"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    </td>
  		  <td>
  	      <a title="Group Submission Detail" href="<?php echo $this->webroot.$this->themeWeb;?>evaluations/viewGroupSubmissionDetails/<?=$data['Event']['id']?>;<?=$group['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo 'Group '.$group['group_num'].' - '.$group['group_name'] ?></a> &nbsp;
  	    </td>
        <td align="center"><?php echo '<b>'.$group['num_completed'].'</b> / <b>'.$group['num_members'].'</b> completed' ?></td>
  	    <td>
  	      <?php
  	      $imageFile = '';
  	      $imageText = '';

  	      if ($group['complete_status']) {
  	        $imageFile = '/icons/green_check.gif';
  	        $imageText = 'Completed';
  	      } else {
  	        $imageFile = '/icons/red_x.gif';
  	        $imageText = 'Not Completed';
  	      }
  	      echo $html->image($imageFile, array('alt'=>$imageText, 'title'=>$imageText));?>
  	    </td>
  	    <td>
  	      <?php
  	      $imageFile = '';
  	      $imageText = '';

  	      if ($group['marked'] == "reviewed") {
  	        $imageFile = '/icons/green_check.gif';
  	        $imageText = 'marked';
  	      } else if ($group['marked'] == "to review") {
  	        $imageFile = '/icons/yellow_x.gif';
  	        $imageText = 'Ready to Review';
  	      } else {
  	        $imageFile = '/icons/red_x.gif';
  	        $imageText = 'Not Ready to Review';
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
