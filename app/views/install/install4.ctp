<script type="text/javascript">
  function validPassword(){
    var pw = $('password').value;
    if(!pw){
      alert("Please Enter a valid password.");
      return false;
    }
    return true;
  }
    
</script>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center">
  <br>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url('install5') ?>" onSubmit="return validate()">

<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td>&nbsp;</td>
    <td><?php __('Step 4: System Parameters Configuration')?> </td>
    <td>&nbsp;</td>
  </tr>
  <!-- Property of Recommended Settings -->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong><?php __('iPeer System Configuration Parameters')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                  	<td width="130" id="login_text_label"><?php __('Custom Login Text')?>:</td>
                  	<td width="337" align="left">
                        <textarea name="data[SysParameter][display.login_text]" id="login_text" class="validate required TEXT_FORMAT login_text_msg Invalid_Text._At_Least_One_Word_Is_Required." cols="40" rows="4" >&lt;a href=&#039;http://www.ubc.ca&#039; target=&#039;_blank&#039;&gt;UBC&lt;/a&gt;</textarea>
                        </td>
                  	<td width="663" id="login_text_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="absolute_url_label"><?php __('Absolute URL')?> (e.g. http://myserver.com/ipeer):</td>
                  	<td width="337" align="left">
                        <div class="input text"><input name="data[SysParameter][system.absolute_url]" type="text" id="absolute_url" size="50" class="validate required TEXT_FORMAT absolute_url_msg Invalid_Text._At_Least_One_Word_Is_Required." value=<?php echo $absolute_url; ?> /></div>
                        </td>
                  	<td width="663" id="absolute_url_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="domain_label"><?php __('Domain')?>:</td>
                  	<td width="337" align="left">
                        <div class="input text"><input name="data[SysParameter][system.domain]" type="text" id="domain" size="50" class="validate required TEXT_FORMAT domain_msg Invalid_Text._At_Least_One_Word_Is_Required." value=<?php echo $domain_name; ?> /></div>
                        </td>
                  	<td width="663" id="domain_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="super_admin_label"><?php __('Super administrator username')?>:</td>
                  	<td width="337" align="left">
                        <div class="input text"><label for="super_admin">Super Admin</label><input name="data[SysParameter][system.super_admin]" type="text" id="super_admin" size="50" class="validate required TEXT_FORMAT super_admin_msg Invalid_Text._At_Least_One_Word_Is_Required." value="root" /></div>
                        </td>
                  	<td width="663" id="super_admin_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="super_admin_label"><?php __('Super administrator password')?>:</td>
                  	<td width="337" align="left"><?php echo $form->password('Admin.password', array('id'=>'password', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT password_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>''))?></td>
                  	<td width="663" id="password_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="contact_info_label"><?php __('Custom Contact Info')?>:</td>
                  	<td width="337" align="left">
                        <textarea name="data[SysParameter][display.contact_info]" id="contact_info" class="validate required TEXT_FORMAT super_admin_msg Invalid_Text._At_Least_One_Word_Is_Required." cols="40" rows="10" >Please enter your custom contact info. HTML tabs are acceptable.</textarea>
                        </td>
                  	<td width="663" id="contact_info_msg" class="error"/>
                  </tr>
                  <tr>
                    <td width="130" id="admin_email_label"><?php __("Administrator's Email Address")?></td>
                    <td width="337" align="left">
                    <div class="input text"><label for="admin_email">Admin Email</label><input name="data[SysParameter][system.admin_email]" type="text" id="admin_email" size="50" class="validate required EMAIL_FORMAT super_admin_msg Invalid_Text._Must_Be_A_Valid_Email_Address." value="Please enter the iPeer administrator\&#039;s email address." /></div>
                    </td>
                    <td width="663" class="error" />
                  </tr>
                  </table>
              </td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">
    <?php echo $form->submit(__('Install iPeer', true), array('name'=>'next', 'onClick' => "return validPassword();")) ?></br>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
        <a href="manualdoc" target="_blank"><?php __('Manual Configuration')?></a>
    </td>
  </tr>

</table>
</form>
</td></tr></table>
