<div class="content-container">
  <?php if ($course['Course']['record_status'] == 'A') { ?>
  <div class="button-row">
    <ul>
        <li><?php echo $html->link(__('Add Group', true), '/groups/add/'.$course['Course']['id'], array('class' => 'add-button')); ?></li>
        <li><?php echo $html->link(__(' Import Group(s)', true), '/groups/import/'.$course['Course']['id'], array('class' => 'add-button')); ?></li>
    </ul>
  </div>
  <?php } ?>
  <div>
    <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </div>
</div>
