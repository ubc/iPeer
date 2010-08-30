<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.Group.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell"><A HREF="<?php echo $this->webroot.$this->themeWeb;?>groups/add/"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;Create Group (Manual)
      </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><A HREF="<?php echo $this->webroot.$this->themeWeb;?>groups/goToClassList/<?php echo $this->controller->rdAuth->courseId; ?>"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;List Groups
      </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle','alt'=>'yellow_arrow'))?> &nbsp;Send Group Email
      </td>
  </tr>
</table>
<?php endif; ?>
