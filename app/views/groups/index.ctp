<div class="content-container">
  <div class="button-row">
    <ul>
        <li><?php echo $html->link(__('Add Group', true), '/groups/add/'.$course_id, array('class' => 'add-button')); ?></li>
        <li><?php echo $html->link(__(' Import Group(s)', true), '/groups/import/'.$course_id, array('class' => 'add-button')); ?></li>
    </ul>
  </div>
  <div>
    <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </div>
</div>
