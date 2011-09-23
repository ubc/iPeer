<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
  <table width="421" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr class=footer>
      <td height="128" colspan="2"><div align="center">
        <table width="199" height="115" border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable">
          <tr>
            <td width="230" height="21" background="/img/layout/header_back.gif"><div align="center" class="smallHeader">iPeer Login </div></td>
          </tr>
          <tr>
            <td height="93" background="/img/layout/small_table_back.gif" align="center">

<!-- begin login form -->
<?php echo $this->element('login_' . Inflector::underscore($auth_module_name), array('login_url', $login_url, 'is_logged_in' => $is_logged_in))?>
<!-- end login form -->

            </td>
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

