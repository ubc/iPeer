<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right"><?php echo $pagination->show('Show ',null,'parameter_table')?></div></td>
      </tr>
    </table>
	<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr class="tableheader">
	    <th><?php echo $pagination->sortLink('ID',array('id','desc'))?></th>
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	    <th width="8%">Actions</th>
	    <?php endif;?>
	    <th><?php echo $pagination->sortLink('Parameter Code',array('parameter_code','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Parameter Value',array('parameter_value','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Parameter Type',array('parameter_type','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Description',array('description','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Created',array('created','desc'))?></th>
	    <th><?php echo $pagination->sortLink('Last Updated',array('modified','desc'))?></th>
	  </tr>
  	<?php $i = '0';?>
	  <?php foreach ($data as $row): $sysparameter = $row['SysParameter']; ?>
	  <tr class="tablecell">
	    <td align="center">
		<?php echo $sysparameter['id'] ?>
	    </td>
	    <td align="center">
		    <a href="<?php echo $this->webroot.$this->themeWeb.'sysparameters/view/'.$sysparameter['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'sysparameters/edit/'.$sysparameter['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
	      <a href="<?php echo $this->webroot.$this->themeWeb.'sysparameters/delete/'.$sysparameter['id']?>" onclick="return confirm('Are you sure you want to delete parameter &ldquo;<?php echo $sysparameter['parameter_code']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
	    <?php endif;?>
	    </td>
		  <td>
	      <?php echo $sysparameter['parameter_code'] ?>
	    </td>
		  <td>
	      <?php echo htmlentities($sysparameter['parameter_value']) ?>
	    </td>
		  <td>
	      <?php echo $sysparameter['parameter_type'] ?>
	    </td>

	    <td>
	      <?php echo $sysparameter['description'] ?>
	    </td>
	    <td align="center"><?php echo date("m/d/Y", strtotime($sysparameter['created'])) ?> </td>
	    <td align="center"><?php if ( ($sysparameter['modified']) != null ) echo date("m/d/Y", strtotime($sysparameter['modified'])) ?> </td>
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
