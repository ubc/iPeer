<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.SimpleEvals.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell"><A HREF="<?php echo $this->webroot.$this->theme;?>simpleevaluations/add/"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<?php __('Add Simple Evaluation')?>
      </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><A HREF="<?php echo $this->webroot.$this->theme;?>simpleevaluations/index/"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<?php __('List Simple Evaulations')?>
      </a></td>
  </tr>
</table>
<?php endif; ?>
