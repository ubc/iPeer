<div class="content-container">
  <div class="button-row">
    <ul>
      <li><?php echo $html->image('icons/add.gif', array('valign'=>'middle'))?>
          <?php echo $html->link( __('Add Course', true), 
                                 '/courses/add',
                                 array('escape' => false)); ?></li>
    </ul>
  </div>
  <div>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
  </div>
</div>
