<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr><td>
    <center style="font-size:150%"><?php __('The group CSV file was processed.')?></center><br />
    <center><?php __('Click OK to return
    to the list of groups, or look below for the results of the import.')?>
    <br /> <br />
    <input type="button" name="Okay" value="OK" onClick="window.location='<?php echo $this->webroot . "groups/goToClassList/". $courseId ?>'";>
    </center>
    <br /> <br />
    <div style="border:1px solid;">
    <center><span style="font-size:150%"><?php __('Group Import Results')?></span></center>
    <br />
    <center><u style="font-size:130%"><?php __('Group Creation')?></u></center>
    <br />
    <center>
    <?php // Group List ?>
    <span style="background:#CCFFCC"><?php __('Groups Created')?>: <?php echo $results['groups_added']; ?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="background:#FFFFCC"><?php __('Groups Skipped')?>: <?php echo $results['groups_skipped']; ?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="background:#FFDDDD"> <?php __('Errors in Groups')?>: <?php echo $results['groups_error']; ?></span>

    <table>
    <tr><td>&nbsp;</td></tr>
    <?php if (empty($results['groups'])) : ?>
        <tr><td style='background:#FFCC99'><?php __('No groups Listed')?></td></tr>
    <?php else: ?>
        <?php foreach ($results['groups'] as $group) : ?>
            <?php $bgColor = $group['present'] ? ( $group['created'] ? '#CCFFCC' : '#FFFFCC') : '$FFCCC'; ?>
            <?php $imageUrl = $this->webroot . "img/icons/" . ($group['present'] ? "green_check.gif" : "red_x.gif" )?>
            <tr>
            <td><img src="<?php echo $imageUrl?>"/></td>
            <td>
                <b><?php echo $group['name']; ?></b>
            <td>:</td>
            </td><td style="background:<?php echo $bgColor; ?>">
                <?php echo $group['reason']; ?>
            </td></tr>
            <tr><td>&nbsp;</td></tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </table>
    </center>
    <br />
    <center><u style="font-size:130%"><?php __('User->Group Assignment')?></u></center>
    <br />
    <center>
    <?php // User List ?>
    <span style="background:#CCFFCC"><?php __('Users Added To Groups')?>: <?php echo $results['users_added']; ?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="background:#FFFFCC"><?php __('Entries Skipped')?>: <?php echo $results['users_skipped']; ?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="background:#FFDDDD"> <?php __('Errors in Entries')?>: <?php echo $results['users_error']; ?></span>
    <br /><br />
    <table>
    <?php if (empty($results['users'])) : ?>
        <tr><td style='background:#FFCC99'><?php __('No Students Listed')?></td></tr>
    <?php else: ?>
        <?php foreach ($results['users'] as $user) : ?>
            <?php $bgColor = $user['valid'] ? ( $user['added'] ? '#CCFFCC' : '#FFFFCC') : '#FFDDDD'; ?>
            <?php $imageUrl = $this->webroot . "img/icons/" . ($user['valid'] ? "green_check.gif" : "red_x.gif" )?>
            <tr style="background:<?php echo $bgColor; ?>;solid 2px">
            <td rowspan=4 style="background:white"><img src="<?php echo $imageUrl?>"/>
            &nbsp;&nbsp;&nbsp;</td>
            <td>User : </td>
            <?php if (!empty($user['data'])) : ?>
                <?php $data = $user['data']['User'];?>
                <td>
                    <?php echo ("<i>$data[student_no]</i>"); ?>
                </td><td>
                    <?php echo ("<b>$data[last_name]</b>"); ?>
                </td><td>
                    <?php echo ("$data[first_name]"); ?>
                </td>
            <?php else: ?>
                <td colspan=10><b><?php __('Error.')?></b> <?php __("See the 'Result:' line for details")?></td>
            <?php endif; ?>
            </tr>
            <tr><td style="background:<?php echo $bgColor; ?>"><?php __('Entry')?> :</td>
            <td colspan=10 style="background:#E0E8FF">
                <?php echo $user['line']; ?>
            </td></tr>
            <tr style="background:<?php echo $bgColor; ?>"><td><?php __('Result')?>:</td>
            <td colspan=10>
                <?php echo $user['status']; ?>
            </td></tr>
            <tr><td>&nbsp;</td></tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </table>
    </center>
    </div>
    </td></tr>
</table>
</table>


