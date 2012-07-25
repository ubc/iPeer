<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>


<td align="center">
    <table width="95%" border="0" cellspacing="0" cellpadding="2">
    <tr><td style="text-align:right;padding:6px 0 0 0">
            <?php 
            if ($courseId != null) {
                echo $html->image('icons/add.gif',
                    array('alt'=>__('Add Event', true), 'align'=>'middle'));
                echo $html->link(__('Add Event', true), 
                    '/events/add/'.$courseId); 
            }
            ?>
    </td></tr>
    <tr><td style="padding:0px">
        <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
    </td></tr>
    </table>
    </td></tr>
</table>
