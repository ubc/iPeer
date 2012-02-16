<div class="content-container">
  <div class="button-row">
    <ul>
            <?php if($can_add_user):?>
                <li><?php echo $html->image('icons/add.gif', array('alt'=>__('Add User', true), 'valign'=>'middle')); ?>&nbsp;<?php echo $html->link(__('Add User', true), '/users/add'); ?></li>
            <?php endif;?>
            <?php if($can_import_user):?>
                <li><?php echo $html->image('icons/add.gif', array('alt'=>__('Import User', true), 'valign'=>'middle')); ?>&nbsp;<?php echo $html->link(__('Import User', true), '/users/import'); ?></li>
            <?php endif;?>
    </ul>
  </div>
  <div>
            <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </div>
</div>
