<?php $root = $this->webroot.$this->theme;
$eventId = $data['Event']['id'];?>

<div class="content-container">
  <div style="text-align: center;">
        <h3><?php echo $data['Event']['title']?></h3>
        <?php echo $html->image('icons/caution.gif', array('alt'=>'Due Date'));?>
        &nbsp;<b>Event Due:</b>&nbsp;
        <?php echo Toolkit::formatDate($data['Event']['due_date']) ?>
  </div>
  <div class="button-row">
    <ul>
      <li><?php echo $html->link(__(" Export Evaluations", true), "export/event/".$eventId, array('class' => 'export-excel-button'));?></li>
      <?php if ($canvasEnabled) { ?>
      <li><?php echo $html->link(__('Push Grades to Canvas', true), 'exportCanvas/'.$eventId, array('class' => 'canvas-button'));?></li>
      <?php } ?>
      <?php if ($viewReleaseBtns) { ?>
      <li><?php echo $html->link(__('Release All Comments', true), 'changeAllCommentRelease/'.$eventId.';1', array('class' => 'button'));?></li>
      <li><?php echo $html->link(__('Unrelease All Comments', true), 'changeAllCommentRelease/'.$eventId.';0', array('class' => 'button'));?></li>
      <li><?php echo $html->link(__('Release All Grades', true), 'changeAllGradeRelease/'.$eventId.';1',array('class' => 'button'));?></li>
      <li><?php echo $html->link(__('Unrelease All Grades', true),'changeAllGradeRelease/'.$eventId.';0',array('class' => 'button'));?></li>
      <?php } ?>
    </ul>
  </div>
  <div>
    <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
  </div>
  <div>
     <?php echo $html->link(__('Back to Evaluation Event Listing', true), '/evaluations/index/'); ?>
     <?php if (!empty($data)) {
        echo '&nbsp;|&nbsp;';
        echo $html->link(__('Back to Course Home', true), '/courses/home/'.$data['Event']['course_id']);
      } ?>
  </div>
</div>
