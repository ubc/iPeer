<!-- elements::ajax_event_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right"><?php echo $pagination->show('Show ',null,'event_table')?></div></td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <?php if($this->Auth->user('role') == 'A' || $this->Auth->user('role') == 'I'):?>
	    <th>Actions</th>
	    <?php endif;?>
	    <th><?php echo $pagination->sortLink('Title',array('title','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Due Date',array('due_date','desc'))?></th>
	    <th>Released?</th>
	    <th><?php echo $pagination->sortLink('Self Evaluation',array('self_eval','desc'))?></th>
	    <th>Groups</th>
	  </tr>
  	<?php $i = 0;?>
	  <?php
	  if (!empty($data)) {
  	  foreach($data as $row): $event = $row['Event']; ?>
  	  <tr class="tablecell">
  	    <td align="center">
  		    <a href="<?php echo $this->webroot.$this->theme.'events/view/'.$event['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    <?php if($this->Auth->user('role') == 'A' || $this->Auth->user('role') == 'I'):?>
  	      <a href="<?php echo $this->webroot.$this->theme.'events/edit/'.$event['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
  	      <a href="<?php echo $this->webroot.$this->theme.'events/delete/'.$event['id']?>" onclick="return confirm('Are you sure you want to delete event &ldquo;<?php echo $event['title']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
  	    <?php endif;?>
  	    </td>
  		  <td>
  	      <?php echo $event['title'] ?>
  	    </td>
        <td align="center"><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['due_date']))) ?> </td>
  	    <td>
  	      <?php echo $data[$i][0]['is_released']==1? 'YES' : 'NO' ?>
  	    </td>
  	    <td>
  	      <?php echo $event['self_eval']==1? 'YES' : 'NO' ?>
  	    </td>
  	    <td>
  	    <a title="View Groups" href="<?php echo $this->webroot.$this->theme;?>events/viewGroups/<?php echo $event['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;View Groups</a> &nbsp;
		
  	    </td>
  	  </tr>
  	  <?php $i++;?>
  	  <?php endforeach;
  	}?>
    <?php if ($i == 0) :?>
  	<tr class="tablecell" align="center">
  	    <td colspan="7">Record Not Found</td>
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
<!-- elements::ajax_event_list end -->
