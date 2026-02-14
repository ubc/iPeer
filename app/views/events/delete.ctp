<div id='EventDeleteForm'>
<h2><?php echo __('Delete Event', true); ?></h2>

<p><?php echo __('You are about to permanently delete the following event:', true); ?></p>
<pre><code><?php echo h($event['Event']['title'] . ' (' . $event['Course']['full_name'] . ')'); ?></code></pre>
<br />
<?php
echo $this->Form->create('Event', array('url' => '/events/delete/' . $event['Event']['id']));
echo $this->Form->hidden('confirm', array('value' => '1'));
echo $html->div('center',
    $form->submit(__('Delete Event', true), array('div' => false)) .
    ' ' .
    $html->link(__('Cancel', true), 'javascript:history.back()')
);
echo $form->end();
?>
</div>
