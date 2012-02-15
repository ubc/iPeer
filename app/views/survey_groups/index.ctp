<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td align="center">
    <table width="95%" border="0" cellspacing="0" cellpadding="2">
        <tr><td style="text-align:right">
            <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Survey Group Set', true), 'valign'=>'middle')); ?>
            <?php echo $html->link(__('Add Survey Group Set', true), '/surveygroups/makegroups/'.$course_id); ?>
        </td></tr>
        <tr><td style="padding:0px">
            <?php echo $this->element("list/ajaxList", $paramsForList);?>
        <?php // For admin, show this note about insturctor column?>
        </td></tr>
        </table>
</td>
</tr>
</table>
