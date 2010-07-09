<!-- elements::ajax_simpleeval_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	    <th width="13%">Actions</th>
	    <?php endif;?>
	    <th width="20%"><?php echo $pagination->sortLink('Name',array('name','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Description',array('description','desc'))?></th>
	    <th width="20%"><?php echo $pagination->sortLink('Base Point/Member',array('point_per_member','desc'))?></th>
	    <th width="23%"><?php echo $pagination->sortLink('Created',array('created','desc'))?></th>
	    <!--th><?php echo $pagination->sortLink('Last Updated By',array('modified','desc'))?></th-->
	  </tr>
  	<?php $i = '0';?>
	  <?php
	  if (!empty($data)) {
  	  foreach($data as $row): $simpleeval = $row['SimpleEvaluation']; ?>
  	  <tr class="tablecell">
  	    <td align="center">
  		    <a href="<?php echo $this->webroot.$this->themeWeb.'simpleevaluations/view/'.$simpleeval['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
  	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
  	      <?php if ($rdAuth->id == $simpleeval['creator_id'] or ($rdAuth->role=='A')): ?>
  	        <a href="<?php echo $this->webroot.$this->themeWeb.'simpleevaluations/edit/'.$simpleeval['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
  	      <?php else: ?>
  	        <?php echo $html->image('icons/editdisabled.gif',array('border'=>'0','alt'=>'Edit'))?>
  	      <?php endif; ?>
  	      <?php if ($rdAuth->id == $simpleeval['creator_id'] or ($rdAuth->role=='A')): ?>
  	      <a href="<?php echo $this->webroot.$this->themeWeb.'simpleevaluations/delete/'.$simpleeval['id']?>" onclick="return confirm('All associating events and evaluation data will be deleted as well.\n Are you sure you want to delete this evaluation &ldquo;<?php echo $simpleeval['name']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
  	      <?php else: ?>
  	        <?php echo $html->image('icons/deletedisabled.gif',array('border'=>'0','alt'=>'Delete'))?>
  	      <?php endif; ?>
  	      <a href="<?php echo $this->webroot.$this->themeWeb.'simpleevaluations/copy/'.$simpleeval['id']?>"><?php echo $html->image('icons/copy.gif',array('border'=>'0','alt'=>'Copy'))?></a>
  	    <?php endif;?>
  	    </td>
  		  <td align="left">
  		    <a href="<?php echo $this->webroot.$this->themeWeb.'simpleevaluations/view/'.$simpleeval['id']?>"><?php echo $simpleeval['name'] ?></a>

  	    </td>
  		  <td align="left">
  	      <?php echo $simpleeval['description'] ?>
  	    </td>
  	    <td align="center">
  	      <?php echo $simpleeval['point_per_member'] ?>
  	    </td>
        <td align="center">
          <?php
          $params = array('controller'=>'simpleevaluations', 'userId'=>$simpleeval['creator_id']);
          echo $this->renderElement('users/user_info', $params);
          ?><br/>
          <?php echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($simpleeval['created']))) ?>
        </td>
        <!--td align="center">
          <?php
          $params = array('controller'=>'simpleevaluations', 'userId'=>$simpleeval['updater_id']);
          echo $this->renderElement('users/user_info', $params);
          ?><br/>
          <?php
              if (!empty($simpleeval['modified'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($simpleeval['modified'])));
          ?>
        </td-->
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
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
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
<!-- elements::ajax_simpleeval_list end -->
