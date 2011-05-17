<!-- elements::ajax_user_list end -->
<div id="ajax_update">
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td align="left">
<!--          <?php if (!empty($data)): //TODO: fill href with mailto list ?>
		      <a href="#"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>'View'))?></a>
          Email To All Listed Users
        <?php endif;?>-->
        </td>
        <td align="right">
          <b>Page Size: </b>
                <input type="radio" id="15" onclick="window.location= window.location+'/limit:15';" /><label for="15">15</label>
                <input type="radio" id="30" onclick="window.location= window.location+'/limit:30';" /><label for="30">30</label>
                <input type="radio" id="90" onclick="window.location= window.location+'/limit:90';" /><label for="90">90</label>
                <input type="radio" id="270" onclick="window.location= window.location+'/limit:270';" /><label for="270">270</label>
        </td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <?php if($currentUser['role'] == 'A' || $currentUser['role'] == 'I'):?>
	    <th>Actions</th>
	    <?php endif;?>
	    <th><?php echo $this->Paginator->sort('Username','username')?></th>
	    <th><?php echo $this->Paginator->sort('Role','role')?></th>
	    <th><?php echo $this->Paginator->sort('First Name','first_name')?></th>
	    <th><?php echo $this->Paginator->sort('Last Name','last_name')?></th>
	    <th><?php echo $this->Paginator->sort('Email','email')?></th>
<!--	    <th><?php //echo $pagination->sortLink('Created By',array('created','desc'))?></th> -->
<!--	    <th><?php //echo $pagination->sortLink('Last Updated By',array('modified','desc'))?></th> -->
	  </tr>
	  
	  <?php foreach($data as $row): $user = $row['User']; ?>
	  <tr class="tablecell">
	    <td align="center">  
		    <a href="<?php echo '/users/view/'.$user['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>

	    <?php if($currentUser['role'] == 'A' || $currentUser['role'] != 'I' && ($currentUser['role'] == 'A' || $currentUser['role'] == 'I')):?>
	      <a href="<?php echo '/users/edit/'.$user['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>

   	    <?php if($currentUser['role'] == 'A'):?>
          <a href="<?php echo '/users/delete/'.$user['id'] ?>" onclick="return confirm('Are you sure you want to delete user &ldquo;<?php echo $user['username']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
  	   	<?php endif;?>

	      <a href="<?php echo '/users/resetPassword/'.$user['id']?>" onclick="return confirm('Are you sure you want to reset password for user &ldquo;<?php echo $user['username']?>&rdquo;?')"><?php echo $html->image('icons/key_email.gif',array('border'=>'0','alt'=>'Reset password'))?></a>

	    <?php endif;?>
	    </td>
		  <td><?php echo $user['username'] ?></td>
		  <td><?php echo User::getRoleText($user['role'])?></td>
	    <td><?php echo $user['first_name'] ?></td>
	    <td><?php echo $user['last_name'] ?></td>
	   
	    <td><a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></td>
 </tr>
	  
	  
	  <?php endforeach; ?>
	 
    <?php if (0 == count($data)) :?>
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
    <td width="33%" align="left"><?php //Display page infomation
	echo '<br/>'.$this->Paginator->counter(array(
		'format' => 'Page %page% of %pages%, showing %current% records out of %count% total<br/>'
	));?></td>
    <td colspan="2" align="right">
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
</div>
<!-- elements::ajax_user_list end -->
