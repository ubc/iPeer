<table width="100%"  border="0" align="center" cellpadding="4" cellspacing="2" bgcolor="#FFFFFF">
<?php if (count($data) > 0):?>
    <tr>
	    <th align="left" width="25%">Group No.</th>
	    <th align="left" width="25%">Group Name</th>
	    <th align="left" width="50%">Members</th>
	  </tr>
    <?php $i = '0';?>
	  <?php
	 	  //print_r($data);
	  foreach($data as $row): $group = $row['Group'];
	  if (isset($group['id'])) {?>
  	  <tr>
          <td>
       	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
        		  <a <?php echo ('y' == $popup ? 'onclick="wopen(this.href, \'popup\', 700, 500); return false;"' : '')?> href="<?php echo $this->webroot.$this->themeWeb.'events/editGroup/'.$group['id'].'/'.$event_id. '/'. $popup?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
       	    <?php endif;?>
            <?php echo $group['group_num'] ?></td>
          <td><?php echo $group['group_name'] ?></td>
          <td>
          	  <?php if (isset($group['Students'])): ?>
          	  <table>
          	  <?php foreach($group['Students'] as $row): $student = $row['users']; ?>
          	  <tr>
                  <td><?php
                  $params = array('controller'=>'courses', 'userId'=>$student['id']);
                  echo $this->renderElement('users/user_info', $params);
                  ?></td>
                </tr>
          	  <?php $i++;?>
              <?php endforeach; ?>
              </table>
              <?php endif;?>

          </td>
        </tr>
  	  <?php $i++;?>
    <?php }
    endforeach; ?>

<?php else: ?>
    <tr><th cols="3" align="left" width="25%">
       <?php echo "No Groups"; ?>
    </td></tr>
<?php endif; ?>

<?php if(isset($popup) && $popup == 'y'): ?>
    <tr><td colspan="3" align="center">
		<input type="button" value="Close Window" onclick="window.close()">
    </td></tr>
<?php endif; ?>    
</table>
<div align = "center">

</div>
