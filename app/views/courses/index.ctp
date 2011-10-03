<?php     Configure::write('Config.language', 'eng');?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td align="center">
    <table width="95%" border="0" cellspacing="0" cellpadding="2">
        <tr><td style="text-align:right">
            <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Course', true), 'valign'=>'middle')); ?>
            <?php echo $html->link(__('Add Course', true), '/courses/add'); ?>
        </td></tr>
        <tr><td style="padding:0px">
            <?php echo $this->element("list/ajaxList", $paramsForList);?>
        <?php // For admin, show this note about insturctor column?>
        <?php if (User::get('role') == 'A') : ?>
        <div style="text-align:right">
            <strong>*<?php __('Note') ?>:</strong> <?php __('When searching by Instructor, the results will return any course they<br />
              are leading. However, only one instructor is listed above by when not searching.') ?><br/>
        </div>
        <?php endif; ?>
        </td></tr>
        </table>
</td>
</tr>
</table>
