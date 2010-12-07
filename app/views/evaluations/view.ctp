<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
    <?php if (!empty($data)) : ?>
      <tr>
        <td align="center" colspan="6">
        <h3><?php echo $data['Event']['title']?></h3>
        <?php echo $html->image('/icons/caution.gif', array('alt'=>'Due Date'));?>
        &nbsp;<b>Event Due:</b>&nbsp;
        <?php echo $this->controller->Output->formatDate($data['Event']['due_date']) ?>
        <br /><br />
        </td>
      </tr>
      <tr >
        <?php $root = $this->webroot.$this->theme;
              $eventId = $data['Event']['id'];?>
        <td><a valign="center" href="<?php echo $root;?>evaluations/export/">
            <?php echo $html->image('/icons/export_excel.gif', array('alt'=>'Export'));?>
            &nbsp;Export Evaluations&nbsp;</td>
        <td>
            <a href="<?php echo $root."evaluations/changeAllCommentRelease/$eventId;1"?>">Release All Comments</a>
        </td>
        <td>
            <a href="<?php echo $root."evaluations/changeAllCommentRelease/$eventId;0"?>">Unrelease All Comments</a>
        </td>
        <td>
            <a href="<?php echo $root."evaluations/changeAllGradeRelease/$eventId;1"?>">Release All Grades</a>
        </td>
        <td>
            <a href="<?php echo $root."evaluations/changeAllGradeRelease/$eventId;0"?>">Unrelease All Grades</a>
        </td>
        </tr><tr><td>&nbsp;</td><tr>
    <?php endif; ?>
        <tr><td colspan=10>
                <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
        </td></tr>
  <tr>
    <td>
     <?php echo $html->link('Back to Evaluation Event Listing', '/evaluations/index/'); ?>
     <?php if (!empty($rdAuth->courseId)) {
        echo '&nbsp;|&nbsp;';
        echo $html->link('Back to Course Home', '/courses/home/'.$rdAuth->courseId);
      } ?>
    </td>
  </tr>
</table>
</td></tr>
</table>

