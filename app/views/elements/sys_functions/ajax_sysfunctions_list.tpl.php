<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right"><?php echo $pagination->show('Show ',null,'function_table')?></div></td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <th><?php echo $pagination->sortLink('ID',array('id','desc'))?></th>
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	    <th>Actions</th>
	    <?php endif;?>
	    <th><?php echo $pagination->sortLink('Function Code',array('function_code','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Function Name',array('function_name','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Parent Id',array('parent_id','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Controller Name',array('controller_name','desc'))?></th>
	    <th><?php echo $pagination->sortLink('URL Link',array('url_link','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Permission Type',array('permission_type','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Created',array('created','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Last Updated',array('modified','desc'))?></th>
	  </tr>
  	<?php $i = '0';?>
	  <?php if (isset($data)&&!empty($data))
	        foreach ($data as $row): $sysfunction = $row['SysFunction']; ?>
	  <tr class="tablecell">
	    <td align="center">
		<?php echo $sysfunction['id'] ?>
	    </td>
	    <td align="center">
		    <a href="<?php echo $this->webroot.$this->themeWeb.'sysfunctions/view/'.$sysfunction['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'sysfunctions/edit/'.$sysfunction['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'sysfunctions/delete/'.$sysfunction['id']?>" onclick="return confirm('Are you sure you want to delete function &ldquo;<?php echo $sysfunction['function_code']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
	    <?php endif;?>
	    </td>
		  <td>
	      <?php echo $sysfunction['function_code'] ?>
	    </td>
		  <td>
	      <?php echo $sysfunction['function_name'] ?>
	    </td>
		  <td>
	      <?php echo $sysfunction['parent_id'] ?>
	    </td>

	    <td>
	      <?php echo $sysfunction['controller_name'] ?>
	    </td>
	    <td>
	      <?php echo $sysfunction['url_link'] ?>
	    </td>
	    <td>
	      <?php echo $sysfunction['permission_type'] ?>
	    </td>
	    <td align="center"><?php echo date("m/d/Y", strtotime($sysfunction['created'])) ?> </td>
	    <td align="center"><?php if ( ($sysfunction['modified']) != null ) echo date("m/d/Y", strtotime($sysfunction['modified'])) ?> </td>
	  </tr>
	  <?php $i++;?>
	  <?php endforeach; ?>
  </table>
    <table width="95%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
      </tr>
    </table>
  <?php $pagination->loadingId = 'loading2';?>
<?php if($pagination->set($paging)):?>
	<div id="page-numbers">
<table width="95%"  border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="33%" align="left"><?php echo $pagination->result('Results: ')?></td>
    <td width="33%"></td>
    <td width="33%" align="right">
		<?php echo $pagination->prev('Prev')?>
		<?php echo $pagination->numbers()?>
		<?php echo $pagination->next('Next')?>
	</td>
  </tr>
</table>
	</div>
<?php endif;?>
</div>
<!-- elements::ajax_user_list end -->
