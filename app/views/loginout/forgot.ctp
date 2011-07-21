<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
<br />
<form action="<?php echo $html->url("/loginout/forgot") ?>" method="POST">
<table border="0" align="center" cellpadding="4" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#e2e2e2">
  <tr class="footer"">
		<td height="128">
			<div align="justify">
			  <table border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable">
			    <tr>
				    <td height="21" background="../img/layout/header_back.gif"><div align="center" class="smallHeader"><?php __('Forgot Your Password?')?></div></td>
			    </tr>
			    <tr>
				    <td height="115" background="../img/layout/small_table_back.gif">
				      <table width="199" border="0" cellpadding="6" cellspacing="0">
                <tr>
                  <td><?php __('Student Number')?>:</td>
                  <td><input name="student_no" type="textbox" class="validate required" value="<?php if(isset($student_no)) echo $student_no; ?> " /></td>
                </tr>
			          <tr>
					        <td><?php __('Email address')?>:</td>
					        <td><input name="email" type="textbox" class="validate EMAIL_FORMAT required" value="<?php if(isset($email)) echo $email; ?>" /></td>
				        </tr>
				        <tr>
					        <td colspan="2">
						      <div align="center">
						        <?php echo $html->submit('Submit', array('align'=>'center')); ?>
                    <input type="button" value="Back to Login" onclick="window.location='<?php echo $html->url('/loginout/login')?>'" />
					        </div>
				          </td>
			          </tr>
			        </table>
            </td>
          </tr>
        </table>
			</div>
		</td>
    <td height="100%" width="250" valign="top">
      <table border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable" width="250">
        <tr>
          <td height="21" background="../img/layout/header_back.gif"><div align="center" class="smallHeader"><?php __('Instructions')?></div></td>
        </tr>
          
      </table>
      <br />
     <?php __('Your password can be reset by submitting your student number and your iPeer email address here. Your new password will be sent to your iPeer email address.')?>'
    </td>
	</tr>
</table>
</form>

</td>
</tr>
</table>
</table>