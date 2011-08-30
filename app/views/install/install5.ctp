
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center">
  <br>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">
    <?php __('Configuration!')?> <a href="../home"><?php __('iPeer sucessfully installed!')?></a><br /><br/>
    <?php if($config_writable):?>
      <font color="red"><?php __('Important!!!')?> </font> <?php __('Your configuration directory (app/config) is still writable. Please change it to read only.')?><br /><br/>
    <?php endif;?>
    <?php __('You may now login as')?> '<?php echo $superAdmin ?>'. <br/><br/>
		<?php __("If you chose to install example data, you can login all user accounts using password 'ipeer'")?></p></span>
    </td>
  </tr>
</table>
</td></tr>
</table>
