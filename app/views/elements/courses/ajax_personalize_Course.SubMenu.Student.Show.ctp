<?php
 if ($userPersonalize->getPersonalizeValue('Course.SubMenu.Student.Show')== 'true') : ?>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="10" bgcolor="#FFB66F">&nbsp;</td>
    <td width="96%" class="tablecell">
        <A HREF="<?php echo $this->webroot.$this->theme;?>users/add/S">
        <?php echo $html->image('layout/yellow_arrow.gif',
            array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?> &nbsp;<?php __('Add Student')?></a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><A HREF="<?php echo $this->webroot.$this->theme;?>users/goToClassList/<?php echo $course_id;?>">
        <?php echo $html->image('layout/yellow_arrow.gif',
                            array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?>
                            &nbsp;<?php __('List Students')?> </a></td>
  </tr>
  <tr>
    <td bgcolor="#FFB66F">&nbsp;</td>
    <td class="tablecell"><A HREF="<?php echo $this->webroot.$this->theme;?>emailer/write/<?php echo 'C'.$course_id;?>">
        <?php echo $html->image('layout/yellow_arrow.gif',
                            array('align'=>'middle', 'border'=>'0', 'alt'=>'yellow_arrow'))?>
                            &nbsp;<?php __('Email to All Students')?></a></td>
  </tr>
</table>
<?php endif; ?>
