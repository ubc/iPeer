<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
<script type="text/javascript" language="JavaScript">
<!--
document.write('<form name="frm" id="frm" method="POST" action="<?php echo $html->url("loginByDefault") ?>" onSubmit="return validate()">');
//document.write('<input type="hidden" name="page" value="'+<?php //echo $page?>+'"/>');
document.write('  <br>');
document.write('  <table width="471" border="0" align="center" cellpadding="4" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#e2e2e2">');
document.write('    <tr class="footer">');
document.write('      <td height="128" colspan="2"><div align="justify">');
document.write('        <table width="199" border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable">');
document.write('          <tr>');
document.write('                  ');
document.write('            <td width="230" height="21" background="../img/layout/header_back.gif"><div align="center" class="smallHeader">iPeer Login <\/div><\/td>');
document.write('          <\/tr>');
document.write('          <tr>');
document.write('            <td height="115" background="../img/layout/small_table_back.gif"><table width="199" border="0" cellpadding="6" cellspacing="0">');
document.write('                <tr>');
document.write('                  <td width="62">My Username:<\/td>');
document.write('                  <td width="140"><?php echo $html->input("User/username",array("style"=>"width:150px;","id"=>"username")); ?><\/td>');
document.write('                <\/tr>');
document.write('                <tr>');
document.write('                  <td>Password:<\/td>');
document.write('                  <td><?php echo $html->password("User/password",array("style"=>"width:150px;")); ?><\/td>');
document.write('                <\/tr>');
document.write('                <tr>');
document.write('                  <td height="31">&nbsp;<\/td>');
document.write('                  <td> <?php echo $html->submit("Submit") ?>         ');
document.write('                <\/tr>');
document.write('            <\/table><\/td>');
document.write('          <\/tr>');
document.write('        <\/table>');
document.write('      <\/div><\/td>');
document.write('      <td width="250" valign="top"><table width="250" height="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="e2e2e2" class="subTable">');
document.write('        <tr>');
document.write('          <td width="231" height="21" background="../img/layout/header_back.gif"><div align="center" class="smallHeader">University of British Columbia <\/div><\/td>');
document.write('        <\/tr>');
document.write('      <\/table>');
<?php

if (isset($rdAuth->customIntegrateCWL) && !$rdAuth->customIntegrateCWL) { ?>
//document.write('        <br>        <?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> First Time Users: Use student number as username and password. <br>');
//document.write('        <br>');
//document.write('        <?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> Self Enroll (Password Required)<br>');
<?php } ?>
document.write('        <br>');
document.write('        <?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> <a href="/ipeer_v2/loginout/forgot" >Forgot Your Password?<\/a>');
document.write('        <br><br>');
document.write('        <?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> <a href="mailto:<?php echo $admin_email ?>" >Contact iPeer Administrator<\/a> ');
document.write('        <\/td>');
document.write('    <\/tr>');
document.write('  <\/table>');
document.write('<\/form> ');
//-->
</script>


</td>
<tr>
<td>

</td>
</tr>
</table>
