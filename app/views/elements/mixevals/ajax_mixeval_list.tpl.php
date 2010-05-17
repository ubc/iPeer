<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
<table width="95%" border="0" cellspacing="2" cellpadding="4">
	  <tr>
	    <td colspan="5">
      <?php
      echo $this->renderElement('evaltools/tools_menu', array());
      ?>
	    </td>
	    <td align="right" colspan="2"><div align="right"><?php echo $pagination->show('Show ',null,'mixeval_table')?></div></td>
	  </tr>
<tr class="tableheader">
	<?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	<th width="15%">Actions</th>
	<?php endif;?>
	<th width="45%"><?php echo $pagination->sortLink('Name',array('name','desc'))?></th>
	<!--th width="15%">Owner</th-->
	<th width="7%">In Use</th>
	<th width="4%"><?php echo $pagination->sortLink('Public',array('availability','desc'))?></th>
	<th width="4%"><?php echo $pagination->sortLink('Total Marks',array('total_marks','desc'))?></th>
	<th width="25%"><?php echo $pagination->sortLink('Created',array('created','desc'))?></th>
	<!--th width="14%"><?php echo $pagination->sortLink('Last Updated',array('modified','desc'))?></th-->
  </tr>
    <?php $i = '0';?>
    <?php
    foreach ($data as $row): $mixeval = $row['Mixeval']; ?>
    <tr class="tablecell">
      <td align="center">
      <a href="<?php echo $this->webroot.$this->themeWeb.'mixevals/view/'.$mixeval['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
      <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
        <?php if ($rdAuth->id == $mixeval['creator_id'] or ($rdAuth->role=='A')): ?>
          <a href="<?php echo $this->webroot.$this->themeWeb.'mixevals/edit/'.$mixeval['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
	      <?php else: ?>
	        <?php echo $html->image('icons/editdisabled.gif',array('border'=>'0','alt'=>'Edit'))?>
	      <?php endif; ?>
	      <?php if ($rdAuth->id == $mixeval['creator_id'] or ($rdAuth->role=='A')): ?>
          <a href="<?php echo $this->webroot.$this->themeWeb.'mixevals/delete/'.$mixeval['id']?>" onclick="return confirm('All associating events and evaluation data will be deleted as well.\n Are you sure you want to delete mixeval &ldquo;<?=$mixeval['name'] ?>&rdquo;? ')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
	      <?php else: ?>
	        <?php echo $html->image('icons/deletedisabled.gif',array('border'=>'0','alt'=>'Delete'))?>
	      <?php endif; ?>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'mixevals/copy/'.$mixeval['id']?>"><?php echo $html->image('icons/copy.gif',array('border'=>'0','alt'=>'Copy'))?></a>

      <?php endif;?>
      </td>
      <td align="left">
      <?php echo $html->link($mixeval['name'], '/mixevals/view/'.$mixeval['id']) ?>
      </td>
      <!--td>
    	<?php echo $sysContainer->getUserInfo($mixeval['creator_id']) ?>
      </td-->
      <td align="center">
    	<?php
    		if($sysContainer->checkEvaluationToolInUse('4', $mixeval['id']))
    			echo $html->image('icons/green_check.gif',array('border'=>'0','green_check'));
    		else
    			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
    	 ?>
      </td>
      <td align="center">
    	<?php

    	if( $mixeval['availability'] == "public" )
    		echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
    	else
    		echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
    	?>
      </td>
      <td align="center">
    	<?php echo $mixeval['total_marks'] ?>
      </td>
      <td align="center">
        <?php
        $params = array('controller'=>'mixevals', 'userId'=>$mixeval['creator_id']);
        echo $this->renderElement('users/user_info', $params);
        ?><br/>
        <?php echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($mixeval['created']))) ?>
      </td>
      <!--td align="center">
        <?php
        $params = array('controller'=>'mixevals', 'userId'=>$mixeval['updater_id']);
        echo $this->renderElement('users/user_info', $params);
        ?><br/>
        <?php
            if (!empty($mixeval['modified'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($mixeval['modified'])));
        ?>
      </td-->
    </tr>
    <?php $i++;?>
    <?php endforeach; ?>
    <?php if ($i == 0) :?>
  	<tr class="tablecell" align="center">
  	    <td colspan="8">Record Not Found</td>
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
        <?php echo $pagination->prev('Prev')?> <?php echo $pagination->numbers()?> <?php echo $pagination->next('Next')?>
<?php endif;?>
      </td>
    </tr>
  </table>
  </div>
</div>
<!-- elements::ajax_user_list end -->
