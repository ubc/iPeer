<div class="content-container">
  <?php echo $this->element('evaltools/tools_menu', array());?>

  <div class="list-add">
    <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Rubric', true), 'valign'=>'middle')); ?>&nbsp;
     <?php echo $html->link(__('Add Rubric', true), '/rubrics/add'); ?>
  </div>

  <div><?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?></div>
</div>
