<div class='adminpage'>
<ul>
<?php
if (!User::hasPermission('adminpage')) {
    echo "<p class='message error-message'>
        No permission to access the admin page found.</p>";
}

if (User::hasPermission('controllers/faculties')) {
    echo '<li>';
    echo $this->Html->link(
        __('Faculties',true),
        array('controller' => 'faculties')
    );
    echo '</li>';
}

if (User::hasPermission('controllers/departments')) {
    echo '<li>';
    echo $this->Html->link(
        __('Departments',true),
        array('controller' => 'departments')
    );
    echo '</li>';
}

if (User::hasPermission('functions/user/superadmin')) {
    echo "<li>";
        echo $this->Html->link(
            __('OAuth Client Credentials',true),
            array('controller' => 'oauthclients')
        );
    echo "</li>";
}

if (User::hasPermission('functions/user/superadmin')) {
    echo "<li>";
        echo $this->Html->link(
            __('OAuth Token Credentials',true),
            array('controller' => 'oauthtokens')
        );
    echo "</li>";
}

// System Parameters
if (User::hasPermission('controllers/sysparameters')) {
    echo '<li>';
    echo $this->Html->link(
        __('System Parameters',true),
        array('controller' => 'sysparameters')
    );
    echo '</li>';
}

// Permissions Editor
if (User::hasPermission('controllers/accesses/view')) {
    echo '<li>';
    echo $this->Html->link(
        __('Permissions Editor',true),
        array('controller' => 'accesses', 'action' => 'view')
    );
    echo '</li>';
}
?>
</ul>
</div>
