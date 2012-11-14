<div class="content-container">
  <div class="button-row">
    <ul>
      <li>
        <?php echo ($courseId != null) ? $html->link(__('Add Event', true), '/events/add/'.$courseId, array('class' => 'add-button')) : ""; ?>
      </li>
    </ul>
  </div>
  <div>
    <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </div>
</div>
