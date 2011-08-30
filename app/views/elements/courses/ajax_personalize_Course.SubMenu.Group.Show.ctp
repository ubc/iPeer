<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.Group.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell">
    <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).'&nbsp;Create Group (Manual)', 
                                 '/groups/add/'.$course_id, 
                                 array('escape' => false))?>
    </td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).'&nbsp;'.__('List Groups',true), 
                                                       '/groups/goToClassList/'.$course_id, 
                                                       array('escape' => false))?>
      </a></td>
  </tr>
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell">
    <?php echo $this->Html->link($this->Html->image('layout/yellow_arrow.gif',array('valign'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow')).'&nbsp;Export Groups Infomation', 
                                 '/groups/export/'.$course_id, 
                                 array('escape' => false))?>
    </td>
  </tr>
  <tr>
<!--  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle','alt'=>'yellow_arrow'))?> &nbsp;<?php __('Send Group Email')?>
      </td>
  </tr>-->
</table>
<?php endif; ?>
