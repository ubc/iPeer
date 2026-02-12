<div id='UserDeleteForm'>
<h2><?php echo __('Delete User', true); ?></h2>

<p><?php echo __('You are about to permanently delete the following user:', true); ?></p>
<pre><code><?php echo h($user['User']['first_name'] . ' ' . $user['User']['last_name'] . ' (' . $user['User']['username'] . ')'); ?></code></pre>
<br />
<?php
echo $this->Form->create('User', array('url' => '/users/delete/' . $user['User']['id']));
echo $this->Form->hidden('confirm', array('value' => '1'));
echo $html->div('center',
    $form->submit(__('Delete User', true), array('div' => false)) .
    ' ' .
    $html->link(__('Cancel', true), 'javascript:history.back()')
);
echo $form->end();
?>
</div>
