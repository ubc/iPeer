<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
    <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2">
      <tr>
        <td><div align="right"><?php echo $pagination->show('Show ',null,'group_table')?></div></td>
      </tr>
    </table>
    <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2" bgcolor="#FFFFFF">
      <tr class="tableheader">
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	    <th width="10%">Actions</th>
	    <?php endif;?>
	    <th width="12%"><?php echo $pagination->sortLink('Group Number',array('group_num','desc'))?></th>
	    <th width="42%"><?php echo $pagination->sortLink('Group Name (members)',array('group_name','desc'))?></th>
	    <th width="18%"><?php echo $pagination->sortLink('Created',array('created','desc'))?></th>
	    <th width="18%"><?php echo $pagination->sortLink('Last Updated',array('modified','desc'))?></th>
	  </tr>
      <?php $i = '0';?>
	  <?php foreach($data as $row): $group = $row['Group']; ?>
	  <tr class="tablecell">
        <td><div align="center">
		<a href="<?php echo $this->webroot.$this->themeWeb.'groups/view/'.$group['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
		<a href="<?php echo $this->webroot.$this->themeWeb.'groups/edit/'.$group['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
	    <a href="<?php echo $this->webroot.$this->themeWeb.'groups/delete/'.$group['id']?>" onclick="return confirm('Are you sure you want to delete group &ldquo;<?=$group['group_name']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
	    <?php endif;?>
		</div></td>
        <td align="center"><?php echo $group['group_num'] ?></td>
        <td><?php echo $group['group_name'].' ('.$group['member_count'].')' ?></td>
        <td align="center"><?php
        $params = array('controller'=>'groups', 'userId'=>$group['creator_id']);
        echo $this->renderElement('users/user_info', $params);
        ?><br/>
        <?php echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($group['created']))) ?></td>
        <td align="center"><?php
        $params = array('controller'=>'groups', 'userId'=>$group['updater_id']);
        echo $this->renderElement('users/user_info', $params);
        ?><br/>
        <?php
            if (!empty($group['modified'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($group['modified'])));
        ?></td>
      </tr>
	  <?php $i++;?>
    <?php endforeach; ?>
    </table>
    <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
      </tr>
    </table>

    <?php $pagination->loadingId = 'loading2';?>
    <?php if($pagination->set($paging)):?>
	<div id="page-numbers">
<table width="95%"  border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td width="33%" align="left"><?php echo $pagination->result('Results: ')?></td>
        <td width="33%"></td>
        <td width="33%" align="right"> <?php echo $pagination->prev('Prev')?> <?php echo $pagination->numbers()?> <?php echo $pagination->next('Next')?> </td>
      </tr>
    </table>
	</div>
<?php endif;?>
    </div>
<!-- elements::ajax_user_list end -->
