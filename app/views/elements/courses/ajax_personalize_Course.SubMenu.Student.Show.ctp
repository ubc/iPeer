<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.Student.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell">
      <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' '.__('Add Student', true), 
                                   '/users/add/S', 
                                   array('escape' => false))?>
    </td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell">
      <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' '.__('List Students', true), 
                                   '/users/goToClassList/'.$course_id, 
                                   array('escape' => false))?>
    </td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell">
      <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).' '.__('Email to All Students', true), 
                                   '/emailer/write/C'.$course_id, 
                                   array('escape' => false))?>
    </td>
  </tr>
</table>
<?php endif; ?>
