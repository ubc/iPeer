<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.TeamMaker.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell"><?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' Edit Survey', '/surveys/index/'.$course_id, array('escape' => false))?></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' View Survey Results', '/surveygroups/viewresult/'.$course_id, array('escape' => false))?></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' Create Groups (Auto)', '/surveygroups/makegroups/'.$course_id, array('escape' => false))?></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' List Survey Group Sets', '/surveygroups/index/'.$course_id, array('escape' => false))?></td>
  </tr>
</table>
<?php endif; ?>
