<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.EvalEvents.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell">
    <A HREF="<?php echo $this->webroot.$this->theme;?>events/add/">
    <?php echo $html->image('layout/yellow_arrow.gif',
    array('align'=>'middle', 'border'=>'0','alt'=>'yellow_arrow'))?> &nbsp;Add Event
      </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell">
    <A HREF="<?php echo $this->webroot.$this->theme;?>events/goToClassList/<?php echo $course_id;?>">
    <?php echo $html->image('layout/yellow_arrow.gif',
    array('align'=>'middle', 'border'=>'0','alt'=>'yellow_arrow'))?> &nbsp;<?php __('List Evaluation Events / Results')?>
      </a></td></tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell"><?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).__('Export Evaluation Results', true), '/evaluations/export/courseId='.$course_id, array('escape' => false))?></td>
  </tr>
</table>
<?php endif; ?>
