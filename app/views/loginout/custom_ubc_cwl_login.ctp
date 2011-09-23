<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
  <table width="421" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class=footer>
      <td height="128" colspan="2"><div align="center">
        <table width="199" height="115" border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable">
          <tr>
            <td width="230" height="21" background="../img/layout/header_back.gif"><div align="center" class="smallHeader">iPeer Login </div></td>
          </tr>
          <tr>
            <td height="93" background="../img/layout/small_table_back.gif"><table width="199" border="0" cellpadding="6" cellspacing="0">
                <tr>
                  <td width="62"><?php __('For Students')?>:</td>
                 <td width="140">
                    <A HREF="<?php echo $CWL['LoginURL'].'?serviceName='.$CWL['applicationID'].'&serviceURL=https://'.$_SERVER['SERVER_NAME'].$html->url('/loginout/loginByCWL', true)?>"><IMG SRC="https://www.auth.cwl.ubc.ca/CWL_login_button.gif " WIDTH="76" HEIGHT="25" ALT="CWL Login" BORDER="0"></A>
									</td>
                </tr>
                <tr>
                  <td><?php __('Others')?>:</td>
                  <td><A HREF="loginByDefault"><?php echo $html->image("layout/ipeer_login.gif",array("align"=>"absmiddle", "alt"=>"iPeer Login", "border"=>"0"))?>
									</a>
									</td>
                </tr>
            </table></td>
          </tr>
       </table>
      </div></td>
    </tr>
  </table>


</td>
<tr>
<td>
</td>
</tr>
</table>

