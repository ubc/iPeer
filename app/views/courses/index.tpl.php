<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td align="center">
    <table width="95%" border="0" cellspacing="0" cellpadding="2">
        <tr><td style="text-align:right">
            <?php echo $html->image('icons/add.gif',
                array('alt'=>'Add Course', 'align'=>'middle')); ?>
            &nbsp;
            <?php echo $html->linkTo('Add Course', '/courses/add'); ?>
        </td></tr>
        <tr><td style="padding:0px">
            <?php echo $this->renderElement("list/ajaxList", $paramsForList);?>

        <?php // For admin, show this note about insturctor column?>
        <?php if ($this->controller->rdAuth->role == 'A') : ?>
        <div style="text-align:right">
            <strong>*Note:</strong> When searching by Instructor, the results will return any course they<br />
              are leading. However, only one instructor is listed above by when not searching.<br/>
        </div>
        <?php endif; ?>


        </td></tr>
        </table>
</td>
</tr>
</table>