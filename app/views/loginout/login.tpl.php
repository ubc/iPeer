<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url("loginByDefault") ?>" onSubmit="return validate()">

      <div id="login-form">
        <div class="subTable">
          <div class="table-header">iPeer Login</div>
          <div class="table-content">
            <table width="199" border="0" cellpadding="6" cellspacing="0">
                <tr>
                  <td width="62">Username:</td>
                  <td width="140"><?php echo $html->input("User/username",array("style"=>"width:150px;","id"=>"username")); ?></td>
                </tr>
                <tr>
                  <td>Password:</td>
                  <td><?php echo $html->password("User/password",array("style"=>"width:150px;")); ?></td>
                </tr>
                <tr>
                  <td height="31">&nbsp;</td>
                  <td> <?php echo $html->submit("Submit") ?></td>
                </tr>
            </table>
            </div>
        </div>

        <div class="subTable">
          <div class="table-header">University of British Columbia</div>
          <div class="table-content">

          <ul>
<!--<?php if (isset($rdAuth->customIntegrateCWL) && !$rdAuth->customIntegrateCWL): ?>
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> First Time Users: Use student number as username and password. </li>
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> Self Enroll (Password Required)</li>
<?php endif; ?>-->
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> <a href="<?php echo $html->url('forgot')?>" >Forgot Your Password?</a></li>
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> <a href="mailto:<?php echo $admin_email ?>" >Contact iPeer Administrator</a></li>
        </div>
  </div>
</form> 


</td>
</tr>
</table>
