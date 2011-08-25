<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.EvalEvents.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell">
      <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' '.__('Add Event', true), '/events/add', array('escape' => false))?>
    </td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell">
      <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' '.__('List Evaluation Events / Results', true), '/events/goToClassList/'.$course_id, array('escape' => false))?>
    </td>
  </tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell">
      <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' '.__('Export Evaluation Results', true), '/evaluations/export/courseId='.$course_id, array('escape' => false))?>
    </td>
  </tr>
</table>
<?php endif; ?>
