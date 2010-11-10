<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr><td>
    <center><?php echo $html->linkTo("Return to group list.", "index"); ?></center>
    <center><h3>Group Import Results</h3></center>
    <center><h4>Group Creation</h4></center>
    <center>
    <?php ?>

    <?php // Group List ?>
    <table>
    <tr><td>Groups Created:</td><?php ?><td></tr>

    <tr><td>&nbsp;</td></tr>
    <?php if (empty($results['groups'])) : ?>
        <tr><td style='background:#FFCC99'>No groups Listed</td></tr>
    <?php else: ?>
        <?php foreach ($results['groups'] as $group) : ?>
            <?php $bgColor = $group['present'] ? ( $group['created'] ? '#CCFFCC' : '#FFFFCC') : '$FFCCC'; ?>
            <tr><td>
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
    <center><h4>User->Group Assignment</h4></center>
    <center>
    <?php // User List ?>
    <table>
    <?php if (empty($results['users'])) : ?>
        <tr><td style='background:#FFCC99'>No Students Listed</td></tr>
    <?php else: ?>
        <?php foreach ($results['users'] as $user) : ?>
            <?php $bgColor = $user['valid'] ? ( $user['added'] ? '#CCFFCC' : '#FFFFCC') : '#FFDDDD'; ?>
            <tr style="background:<?php echo $bgColor; ?>">
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
                <td colspan=10><b>Error: User not found. </b> See the 'Results:' line for details</td>
            <?php endif; ?>
            </tr>
            <tr><td style="background:<?php echo $bgColor; ?>">Entry :</td>
            <td colspan=10 style="background:#E0E8FF">
                <?php echo $user['line']; ?>
            </td></tr>
            <tr<tr style="background:<?php echo $bgColor; ?>"><td>Result:</td>
            <td colspan=10>
                <?php echo $user['status']; ?>
            </td></tr>
            <tr><td>&nbsp;</td></tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </table>
    </center>
    </td></tr>
    <tr><td>
    </td></tr>
</table>
</table>

