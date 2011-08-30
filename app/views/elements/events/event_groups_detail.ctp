<table width="100%"  border="0" align="center" cellpadding="4" cellspacing="2" bgcolor="#FFFFFF">
<?php if (count($data) > 0):?>
    <tr>
	    <th align="left" width="25%"><?php __('Group No.')?></th>
	    <th align="left" width="25%"><?php __('Group Name')?></th>
	    <th align="left" width="50%"><?php __('Members')?></th>
	  </tr>
	  <?php foreach($data as $group):?>
	  <?php if (isset($group['id'])): ?>
  	  <tr>
          <td>
       	    <?php if($user['role'] == 'A' || $user['role'] == 'I'):?>
        		  <?php echo $this->Html->link($html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit', 'valign' => 'middle')), 
                                           '/events/editGroup/'.$group['id'].'/'.$event_id. '/'. $popup,
                                           array('onclick' => ('y' == $popup ? '"wopen(this.href, \'popup\', 700, 500); return false;"' : ''),
                                                 'escape' => false));?>
       	    <?php endif;?>
            <?php echo $group['group_num'] ?></td>
          <td><?php echo $group['group_name']; ?></td>
          <td>
          	  <?php if (isset($group['Member'])): ?>
          	  <table>
          	  <?php foreach($group['Member'] as $member): ?>
          	  <tr>
                  <td><?php echo $this->element('users/user_info', array('data'=>$member));?></td>
              </tr>
              <?php endforeach; ?>
              </table>
              <?php endif;?>

          </td>
        </tr>
    <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <tr><th cols="3" align="left" width="25%">
       <?php echo "No Groups"; ?>
    </td></tr>
<?php endif; ?>

<?php if(isset($popup) && $popup == 'y'): ?>
    <tr><td colspan="3" align="center">
		<input type="button" value="<?php __('Close Window')?>" onclick="window.close()">
    </td></tr>
<?php endif; ?>    
</table>
<div align = "center">

</div>
