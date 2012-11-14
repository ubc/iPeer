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
            <td><?php echo $this->Html->link($group['group_num'], '/groups/edit/'.$group['id'], array('class' => 'edit-button'));?></td>
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
</table>
