<div class="content-container">
  <div class="button-row">
    <ul>
            <?php if($can_merge_users):?>
                <li><?php echo $html->link(__('Merge Users', true), '/users/merge', array('class' => 'merge-button')); ?></li>
            <?php endif;?>
            <?php if($can_add_user):?>
                <li><?php echo $html->link(__('Add User', true), '/users/add', array('class' => 'add-button')); ?></li>
            <?php endif;?>
            <?php if($can_import_user):?>
                <li><?php echo $html->link(__('Import User', true), '/users/import', array('class' => 'add-button')); ?></li>
            <?php endif;?>
    </ul>
  </div>
  <div>
            <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </div>
</div>
