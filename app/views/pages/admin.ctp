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
        'Faculties',
        array('controller' => 'faculties')
    );
    echo '</li>';
}

if (User::hasPermission('controllers/departments')) {
    echo '<li>';
    echo $this->Html->link(
        'Departments',
        array('controller' => 'departments')
    );
    echo '</li>';
}

if (User::hasPermission('functions/user/superadmin')) {
    echo "<li>";
        echo $this->Html->link(
            'OAuth Client Credentials',
            array('controller' => 'oauthclients')
        );
    echo "</li>";
}

if (User::hasPermission('functions/user/superadmin')) {
    echo "<li>";
        echo $this->Html->link(
            'OAuth Token Credentials',
            array('controller' => 'oauthtokens')
        );
    echo "</li>";
}

// System Parameters
if (User::hasPermission('controllers/sysparameters')) {
    echo '<li>';
    echo $this->Html->link(
        'System Parameters',
        array('controller' => 'sysparameters')
    );
    echo '</li>';
}
?>
</ul>
</div>
