<table class="standardtable">
    <tr>
        <th><?php __('Username')?></th>
        <td><?php echo User::hasPermission('functions/viewusername') ? $user['User']['username'] : 'N/A'; ?></td>
        <th> <?php __('Role')?></th>
        <td>
        <?php
        $role = $user['Role']['0']['name'];
        echo ucfirst($role);
        ?>
        </td>
    </tr>
    <tr>
        <th id="last_name_label"><?php __('Last Name')?></th>
        <td><?php echo $user['User']['last_name']; ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th><?php __('First Name')?></th>
        <td><?php echo $user['User']['first_name']; ?> </td>
        <td></td>
        <td></td>
    </tr>
    <?php if (in_array($user['Role']['0']['name'], array('student', 'tutor'))): ?>
    <tr>
        <th><?php __('Student No.')?></th>
        <td><?php echo $user['User']['student_no']; ?> </td>
        <td></td>
        <td></td>
    </tr>
    <?php else: ?>
    <tr>
        <th><?php __('Title')?></th>
        <td><?php echo $user['User']['title']; ?> </td>
        <td></td>
        <td></td>
    </tr>
    <?php endif;?>
    <tr>
        <th><?php __('Email')?></th>
        <td>
        <?php if(!empty($user['User']['email'])): ?>
            <?php if (User::hasPermission('functions/viewemailaddresses')) { ?>
                <a href="mailto:<?php echo $user['User']['email']; ?>">
                <?php echo $html->image('icons/email_icon.gif', array('border'=>'0','alt'=>'Email'));?>
                <?php echo $user['User']['email']; ?>
            <?php } else {
                echo 'N/A';
            } ?>
            </a>
        <?php endif; ?>
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th><?php __('Creator')?></th>
        <td><?php echo $user['User']['creator'];?></td>
        <th><?php __('Updater')?></th>
        <td><?php echo $user['User']['updater'];?></td>
    </tr>
    <tr>
        <th><?php __('Create Date')?></th>
        <td><?php echo $user['User']['created']; ?></td>
        <th><?php __('Update Date')?></th>
        <td><?php echo $user['User']['modified']; ?></td>
    </tr>
    <tr>
        <td colspan='4'>
        <form name="frm" id="frm" method="post" action="#">
        <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1 ? history.back() : window.close());">
        </form>
        </td>
    </tr>
</table>
