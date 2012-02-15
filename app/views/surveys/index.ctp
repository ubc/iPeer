<div class="content-container">
  <?php echo $this->element('evaltools/tools_menu', array());?>

  <div class="list-add">
    <?php echo $html->link($html->image('icons/add.gif', array('alt'=>__('Add Survey', true), 'valign'=>'middle')).__(' Add Survey', true), '/surveys/add/'.$course_id, array('escape' => false)); ?>
  </div>

  <div><?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?></div>
</div>
