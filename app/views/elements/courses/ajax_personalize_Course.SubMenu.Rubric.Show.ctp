<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.Rubric.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell"><A HREF="<?php echo $this->webroot.$this->theme;?>rubrics/add/"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<?php __('Add Rubric')?>
      </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><A HREF="<?php echo $this->webroot.$this->theme;?>rubrics/index/"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<?php __('List Rubrics')?>
      </a></td>
  </tr>
</table>
<?php endif; ?>
