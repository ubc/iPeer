<div class="content-container">
  <div class="button-row">
    <ul>
        <li><?php echo $html->link(__('Add Sys Parameter', true), '/sysparameters/add', array('class' => 'add-button')); ?></li>
    </ul>
  </div>
  <div>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
  </div>
</div>
