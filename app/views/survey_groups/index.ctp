<div class="content-container">
<?php if ($course['Course']['record_status'] == 'A') { ?>
  <div class="button-row">
    <ul>
      <li><?php echo $html->link(__('Add Survey Group Set', true), '/surveygroups/makegroups/'.$course['Course']['id'], array('class' => 'add-button')); ?></li>
    </ul>
  </div>
<?php } ?>
  <div>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
    <?php  /*For admin, show this note about insturctor column*/?>
  </div>
</div>
