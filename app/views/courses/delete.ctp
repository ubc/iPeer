<div id='CourseDeleteForm'>
<h2><?php echo __('Delete Course', true); ?></h2>

<p><?php echo __('You are about to permanently delete the following course:', true); ?></p>
<pre><code><?php echo h($course['Course']['full_name']); ?></code></pre>
<br />
<?php
echo $this->Form->create('Course', array('url' => '/courses/delete/' . $course['Course']['id']));
echo $this->Form->hidden('confirm', array('value' => '1'));
echo $html->div('center',
    $form->submit(__('Delete Course', true), array('div' => false)) .
    ' ' .
    $html->link(__('Cancel', true), 'javascript:history.back()')
);
echo $form->end();
?>
</div>
