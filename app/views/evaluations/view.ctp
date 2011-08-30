<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="2">
    <?php if (!empty($data)) : ?>
      <tr>
        <td align="center" colspan="6">
        <h3><?php echo $data['Event']['title']?></h3>
        <?php echo $html->image('icons/caution.gif', array('alt'=>'Due Date'));?>
        &nbsp;<b>Event Due:</b>&nbsp;
        <?php echo Toolkit::formatDate($data['Event']['due_date']) ?>
        <br /><br />
        </td>
      </tr>
      <tr >
        <?php $root = $this->webroot.$this->theme;
              $eventId = $data['Event']['id'];?>
        <td>
            <?php 
                echo $html->link(
                        $html->image('icons/export_excel.gif', array('alt'=>'Export')).__(" Export Evaluations", true),
                        "export/".$courseId,
                        array('escape' => false)
                );
            ?>
        </td>
        <td>            
            <?php echo $html->link(__('Release All Comments', true),
                            'changeAllCommentRelease/'.$eventId.';1',
                            array('escape' => false));
            ?>
        </td>
        <td>
            <?php echo $html->link(__('Unrelease All Comments', true),
                            'changeAllCommentRelease/'.$eventId.';0',
                            array('escape' => false));
            ?>
        </td>
        <td>
            <?php echo $html->link(__('Release All Grades', true),
                            'changeAllGradeRelease/'.$eventId.';1',
                            array('escape' => false));
            ?>
        </td>
        <td>
            <?php echo $html->link(__('Unrelease All Grades', true),
                            'changeAllGradeRelease/'.$eventId.';0',
                            array('escape' => false));
            ?>
        </td>
        </tr><tr><td>&nbsp;</td><tr>
    <?php endif; ?>
        <tr><td colspan=10>
                <?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
        </td></tr>
  <tr>
    <td>
     <?php echo $html->link(__('Back to Evaluation Event Listing', true), '/evaluations/index/'); ?>
     <?php if (!empty($rdAuth->courseId)) {
        echo '&nbsp;|&nbsp;';
        echo $html->link(__('Back to Course Home', true), '/courses/home/'.$rdAuth->courseId);
      } ?>
    </td>
  </tr>
</table>
</td></tr>
</table>

