<!-- elements::ajax_user_list end -->
<?php echo $javascript->link('search.js');?>
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
            <?php
                echo $form->input('radio', $this->Paginator->pageSize($this->params["paging"]["User"]["options"]["limit"]));
            ?>
        </td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <?php if($currentUser['role'] == 'A' || $currentUser['role'] == 'I'):?>
	    <th>Actions</th>
	    <?php endif;?>
	    <th><?php echo $this->Paginator->sort(__('Username', true),'username')?></th>
	    <th><?php echo $this->Paginator->sort(__('Role', true),'role')?></th>
	    <th><?php echo $this->Paginator->sort(__('First Name', true),'first_name')?></th>
	    <th><?php echo $this->Paginator->sort(__('Last Name', true),'last_name')?></th>
	    <th><?php echo $this->Paginator->sort(__('Email', true),'email')?></th>
<!--	    <th><?php //echo $pagination->sortLink('Created By',array('created','desc'))?></th> -->
<!--	    <th><?php //echo $pagination->sortLink('Last Updated By',array('modified','desc'))?></th> -->
	  </tr>
	  
	  <?php foreach($data as $row): $user = $row['User']; ?>
	  <tr class="tablecell">
	    <td align="center">
            <?php
                  echo $html->link(
                            $html->image("icons/view.gif", array("border"=>"0","alt"=>"View")),
                            "/users/view/".$user['id'],
                            array('escape'=>false, 'title'=>'View '.$user['full_name'])
                  );
            ?>
	    <?php if($currentUser['role'] == 'A' || $currentUser['role'] != 'I' && ($currentUser['role'] == 'A' || $currentUser['role'] == 'I')):
                   echo $html->link(
                                    $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit')),
                                    "/users/edit/".$user['id'],
                                    array('escape'=>false, 'title'=>'Edit '.$user['full_name'])
                   );
            ?>
   	    <?php if($currentUser['role'] == 'A'):
                  echo $html->link(
                                    $html->image('icons/delete.gif',array('border'=>'0','alt'=>__('Delete', true), 'title'=>__('Delete ', true).$user['full_name'])),
                                    "/users/delete/".$user['id'],
                                    array('onClick' => "return confirm('".__('Are you sure you want to delete user', true)."&ldquo;".$user["username"]."&rdquo;?')",'escape'=>false)
                  );
                  endif;
            ?>
            <?php
                  echo $html->link(
                                    $html->image('icons/key_email.gif',array('border'=>'0','alt'=>'Reset password', 'title'=>__('Reset ', true).$user['full_name'].__("'s password", true))),
                                    "/users/resetPassword/".$user['id'],
                                    array('onClick' => "return confirm('".__('Are you sure you want to reset password for user', true)." &ldquo;".$user["username"]."&rdquo;?')",'escape'=>false)
                  );
                  endif;
            ?>
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
  	    <td colspan="9"><?php __('Record Not Found')?></td>
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
		'format' => __('Page %page% of %pages%, showing %current% records out of %count% total<br/>', true)
	));?></td>
    <td colspan="2" align="right">
<?php
	//Prev page link
        echo $this->Paginator->prev(__('Prev ', true),null,' ',array('class' => 'disabled'));
	//Shows the page numbers
	echo $this->Paginator->numbers();
	//Next page link
        echo $this->Paginator->next(__(' Next', true),null,' ',array('class' => 'disabled'));
?>
	</td>
  </tr>
</table>
	</div>
</div>
<!-- elements::ajax_user_list end -->
