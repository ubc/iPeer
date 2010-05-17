<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.TeamMaker.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<a href="<?php echo $this->webroot.$this->themeWeb;?>surveys/index/">Edit Survey </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<a href="<?php echo $this->webroot.$this->themeWeb;?>surveygroups/viewresult/">View Survey Results</a> </td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<a href="<?php echo $this->webroot.$this->themeWeb;?>surveygroups/makegroups/">Create Groups (Auto)</a> </td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><?php echo $html->image('layout/yellow_arrow.gif',array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<a href="<?php echo $this->webroot.$this->themeWeb;?>surveygroups/index/">List Survey Group Sets</a> </td>
  </tr>
</table>
<?php endif; ?>
