<div class="content-container">
  <div class="button-row">
    <ul>
      <li><?php echo $html->link(__('Add Survey Group Set', true), '/surveygroups/makegroups/'.$course_id, array('class' => 'add-button')); ?></li>
    </ul>
  </div>
  <div>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
    <?php // For admin, show this note about insturctor column?>
  </div>
</div>
