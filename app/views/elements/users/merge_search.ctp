<?php
    $Account = ucwords($account);
?>
<div id='<?php echo $account ?>'>
    <h3><?php printf(__('%s Account', true), $Account); ?></h3>
    <?php echo $this->Form->input('User.'.$account.'Search',
        array('label' => false, 'options' => $searchValue, 'class' => 'search', 'div' => false)); ?>
    <?php echo $this->Form->input('User.'.$account.'SearchValue',
        array('label' => false, 'class' => 'searchValue', 'div' => false)); ?>
    <?php echo $this->Form->input('User.'.$account.'Account',
        array('label' => __('User', true), 'empty' => sprintf(__('-- Search for the %s account --', true), $account), 'class' => 'account')); ?>

    <div id='<?php echo $account ?>Data'>
    <table class='standardtable'>
        <?php if(User::hasPermission('functions/viewusername')) { ?>
            <tr><th><?php echo __('Username', true); ?></th><td id='<?php echo $account ?>Username'></td></tr>
        <?php } ?>
        <tr><th><?php echo __('Last Name', true); ?></th><td id='<?php echo $account ?>LastName'></td></tr>
        <tr><th><?php echo __('First Name', true); ?></th><td id='<?php echo $account ?>FirstName'></td></tr>
        <tr><th><?php echo __('Role', true); ?></th><td id='<?php echo $account ?>Role'></td></tr>
        <tr><th><?php echo __('Title', true); ?></th><td id='<?php echo $account ?>Title'></td></tr>
        <?php if (User::hasPermission('functions/viewemailaddresses')) { ?>
            <tr><th><?php echo __('Email', true); ?></th><td id='<?php echo $account ?>Email'></td></tr>
        <?php } ?>
        <tr><th><?php echo __('Creator', true); ?></th><td id='<?php echo $account ?>Creator'></td></tr>
        <tr><th><?php echo __('Create Date', true); ?></th><td id='<?php echo $account ?>CreateDate'></td></tr>
        <tr><th><?php echo __('Updater', true); ?></th><td id='<?php echo $account ?>Updater'></td></tr>
        <tr><th><?php echo __('Update Date', true); ?></th><td id='<?php echo $account ?>UpdateDate'></td></tr>
    </table>
    </div>
</div>
