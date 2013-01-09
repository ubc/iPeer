<?php if (!isset($course['Course']['record_status']) || $course['Course']['record_status'] == 'A') { ?>
<div class="button-row">
<ul>
  <li>
    <?php echo ($course['Course']['id'] != null) ? $html->link(__('Add Event', true), '/events/add/'.$course['Course']['id'], array('class' => 'add-button')) : ""; ?>
  </li>
</ul>
</div>
<?php } ?>
<div>
<?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
</div>
