<div class="content-container">

  <?php echo $this->element('evaltools/tools_menu', array());?>

  <div class="list-add">
    <?php echo $html->image('icons/add.gif', array('alt'=>'Add Simple Evaluation', 'align'=>'middle')); ?> &nbsp;
    <?php echo $html->link(__('Add Simple Evaluation', true), '/simpleevaluations/add'); ?>
  </div>

  <div><?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?></div>

</div>
