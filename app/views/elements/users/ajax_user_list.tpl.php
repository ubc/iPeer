<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td align="left">
<!--          <?php if (!empty($data)): //TODO: fill href with mailto list ?>
		      <a href="#"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>'View'))?></a>
          Email To All Listed Users
        <?php endif;?>-->
        </td>
        <td align="right">
          <?php echo $pagination->show('Show ',null,'user_table')?>
        </td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	    <th>Actions</th>
	    <?php endif;?>
	    <th><?php echo $pagination->sortLink('Username',array('username','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Role',array('role','desc'))?></th>
	    <th><?php echo $pagination->sortLink('First Name',array('first_name','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Last Name',array('last_name','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Email',array('email','desc'))?></th>
<!--	    <th><?php //echo $pagination->sortLink('Created By',array('created','desc'))?></th> -->
<!--	    <th><?php //echo $pagination->sortLink('Last Updated By',array('modified','desc'))?></th> -->
	  </tr>
  	<?php $i = '0';?>
	  <?php foreach($data as $row): $user = $row['User']; ?>
	  <?php 
	  	$userEnrol = $row['UserEnrol'];
	  	$userEnrolId = 0;
	  	foreach($row['UserEnrol'] as $enrol)
	  	{
	  		if($enrol['course_id'] == $course_id)
	  			$userEnrolId = $enrol['id'];
	  	}
	  ?>
	  <tr class="tablecell">
	    <td align="center">
		    <a href="<?php echo $this->webroot.$this->themeWeb.'users/view/'.$user['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
	    <?php if($rdAuth->role == 'A' || $user['role'] != 'I' && ($rdAuth->role == 'A' || $rdAuth->role == 'I')):?>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'users/edit/'.$user['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
	    <?php if($user['role'] == 'S'):?>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'users/delete/'.$userEnrolId.'/'. $user['role']?>" onclick="return confirm('Are you sure you want to delete user &ldquo;<?php echo $user['username']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
	   	<?php else:?>
	   	 <a href="<?php echo $this->webroot.$this->themeWeb.'users/delete/'.$user['id'].'/'. $user['role']?>" onclick="return confirm('Are you sure you want to delete user &ldquo;<?php echo $user['username']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
	   	<?php endif;?>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'users/resetPassword/'.$user['id']?>" onclick="return confirm('Are you sure you want to reset password for user &ldquo;<?php echo $user['username']?>&rdquo;?')"><?php echo $html->image('icons/key_email.gif',array('border'=>'0','alt'=>'Reset password'))?></a>
	   	
	    <?php endif;?>
	    </td>
		  <td>
	      <?php echo $user['username'] ?>
	    </td>
		  <td>
	      <?php if ($user['role']=='A') {
	              echo 'Administrator';
	            } else if ($user['role']=='I') {
	              echo 'Instructor';
	            } else if ($user['role']=='S') {
	              echo 'Student';
	            }?>
	    </td>
	    <td>
	      <?php echo $user['first_name'] ?>
	    </td>
	    <td>
	      <?php echo $user['last_name'] ?>
	    </td>
	    <td>
	      <a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a>
	    </td>
<!--      <td align="center">
        <?php
        $params = array('controller'=>'users', 'userId'=>$user['creator_id']);
        echo $this->renderElement('users/user_info', $params);
        ?><br/>
        <?php echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($user['created']))) ?>
      </td>
      <td align="center">
        <?php
        $params = array('controller'=>'users', 'userId'=>$user['updater_id']);
        echo $this->renderElement('users/user_info', $params);
        ?><br/>
        <?php
            if (!empty($user['modified'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($user['modified'])));
        ?>
      </td>
-->
	  </tr>
	  <?php $i++;?>
	  <?php endforeach; ?>
    <?php if ($i == 0) :?>
  	<tr class="tablecell" align="center">
  	    <td colspan="9">Record Not Found</td>
    </tr>
    <?php endif;?>
  </table>
    <table width="95%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'left','alt'=>'left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'right','alt'=>'right'))?></td>
      </tr>
    </table>
  <?php $pagination->loadingId = 'loading2';?>
	<div id="page-numbers">
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
</div>
<!-- elements::ajax_user_list end -->
