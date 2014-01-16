<?php if (!empty($errors)) { ?>
    <h3><?php echo __('User(s) failed to transfer:', true)?></h3>
    <?php echo $this->element('courses/import_summary_list', array('data'=>$errors, 'identifier' => $identifier, 'note' => __('Reason', true))); ?>
<?php } ?>

<h3><?php echo __('User(s) successfully transferred:', true)?></h3>
<?php echo $this->element('courses/import_summary_list', array('data'=>$success, 'identifier' => $identifier, 'note' => __('Note', true))); ?>

<p>
<?php 
echo $html->link(__('Back to Course', true), "/courses/home/$courseId");
?>
</p>