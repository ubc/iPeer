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
    <?php if (User::get('role') == 'A') : // For admin, show this note about insturctor column?>
      <div style="text-align:right">
        <strong>*<?php __('Note') ?>:</strong> <?php __('When searching by Instructor, the results will return any course they<br />
         are leading. However, only one instructor is listed above when not searching.') ?><br/>
      </div>
    <?php endif; ?>
  </div>
</div>
