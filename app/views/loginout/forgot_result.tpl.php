<?php
/*
 * Created on Jul 6, 2006
 */
?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
<br />
<form action="<?php echo $html->url("/loginout/login") ?>" method="POST">
<table border="0" align="center" cellpadding="4" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#e2e2e2">
  <tr class=footer>
    <td height="128" colspan="2">
      <div align="justify">
        <table border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable">
          <tr>
            <td height="21" background="../img/layout/header_back.gif"><div align="center" class="smallHeader">Forgot Your Password?</div></td>
          </tr>
          <tr>
            <td height="115" background="../img/layout/small_table_back.gif">
              <table width="199" border="0" cellpadding="6" cellspacing="0">
                <tr>
                  <td>Student Number:</td>
                  <td><?php echo $student_no; ?></td>
                </tr>
                <tr>
                  <td >Email address:</td>
                  <td ><?php echo $email; ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="2">
            <div align="center">
              <?php echo $html->submit('Back to login screen', array('align'=>'center')); ?>
            </div>
            </td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</table>