<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center">
  <br>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url('install4') ?>" onSubmit="return validate()">

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
                  <!--<tr>
                  	<td width="130" id="session_name_label"><?php __('Session Name')?>:</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/session_name', array('id'=>'session_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT session_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>'ipeer'))?></td>
                  	<td width="663" id="session_name_msg" class="error"/>
                  </tr>-->
                  <tr>
                  	<td width="130" id="debug_mode_label"><?php __('Debug Mode')?>:</td>
                  	<td width="337" align="left">
										<?php
										$modes = array('0'=>'Production', '1'=>'Development', '2'=>'Full Debug With SQL', '3'=>'Full Debug With SQL and Dump of the Current Object');
										echo $html->selectTag('SysParameter/system.debug_mode', $modes, null, null, null, false);
										 ?>
										</td>
                  	<td width="663" id="debug_mode_msg" class="error"/>
                  </tr>
                  <!--tr>
                  	<td width="130" id="debug_verbosity_label">Debug Verbosity:</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/system.debug_verbosity', array('id'=>'debug_verbosity', 'size'=>'50', 'class'=>'validate required NUMERIC_FORMAT debug_verbosity_msg Invalid_Numeric_Value.', 'value'=>'1'))?></td>
                  	<td width="663" id="debug_verbosity_msg" class="error"/>
                  </tr-->
                  <tr>
                  	<td width="130" id="login_text_label"><?php __('Custom Login Text')?>:</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/display.login_text', array('id'=>'login_text', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT login_text_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>'<a href=\'http://www.ubc.ca\' target=\'_blank\'>UBC</a>'))?></td>
                  	<td width="663" id="login_text_msg" class="error"/>
                  </tr>
                  <!--tr>
                  	<td width="130" id="email_schedule_label"><?php __('Email Scheduling')?>:</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/email_schedule', array('id'=>'email_schedule', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT email_schedule_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>'false'))?></td>
                  	<td width="663" id="email_schedule_msg" class="error"/>
                  </tr-->
                  <tr>
                  	<td width="130" id="absolute_url_label"><?php __('Absolute URL')?> (e.g. http://myserver.com/ipeer):</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/system.absolute_url', array('id'=>'absolute_url', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT absolute_url_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>'http://'))?></td>
                  	<td width="663" id="absolute_url_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="domain_label"><?php __('Domain')?>:</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/system.domain', array('id'=>'domain', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT domain_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
                  	<td width="663" id="domain_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="super_admin_label"><?php __('Super administrator username')?>:</td>
                  	<td width="337" align="left"><?php echo $html->input('SysParameter/system.super_admin', array('id'=>'super_admin', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT super_admin_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>'root'))?></td>
                  	<td width="663" id="super_admin_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="super_admin_label"><?php __('Super administrator password')?>:</td>
                  	<td width="337" align="left"><?php echo $html->password('Admin/password', array('id'=>'password', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT password_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>''))?></td>
                  	<td width="663" id="password_msg" class="error"/>
                  </tr>
                  <tr>
                  	<td width="130" id="contact_info_label"><?php __('Custom Contact Info')?>:</td>
                  	<td width="337" align="left"><?php echo $html->textarea('SysParameter/display.contact_info', array('id'=>'contact_info', 'class'=>'validate required TEXT_FORMAT super_admin_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>__('Please enter your custom contact info. HTML tabs are acceptable.',true), 'cols'=>'40', 'rows'=>'10'))?></td>
                  	<td width="663" id="contact_info_msg" class="error"/>
                  </tr>
                  <tr>
                    <td width="130" id="admin_email_label"><?php __("Administrator's Email Address")?></td>
                    <td width="337" align="left"><?php echo $html->input('SysParameter/system.admin_email', array('id'=>'admin_email', 'size'=>'50', 'class'=>'validate required EMAIL_FORMAT super_admin_msg Invalid_Text._Must_Be_A_Valid_Email_Address.', 'value'=>__("Please enter the iPeer administrator\'s email address.",true), ))?></td>
                    <td width="663" class="error" />
                  </tr>
                  <tr>
                    <td width="130"><?php __('Password Reset Email Subject')?></td>
                    <td width="337" align="left"><?php echo $html->input('SysParameter/system.password_reset_emailsubject', array('id'=>'password_reset_emailsubject', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT super_admin_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>__('iPeer Password Reset Notification', true)))?></td>
                    <td width="663" class="error"/>
                  </tr>
                  <tr>
                    <td width="130"><?php __('Password Reset Email Message')?></td>
                    <td width="337" align="left"><?php echo $html->textarea('SysParameter/system.password_reset_email', array('id'=>'password_reset_email',  'class'=>'validate required TEXT_FORMAT super_admin_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>htmlentities(__('Dear <username>, <br> Your iPeer password has been reset to', true).' \'<newpassword>\'. <br><br> '.__('iPeer Administrator', true)), 'cols'=>'40', 'rows'=>'10'))?></td>
                    <td width="663" class="error"/>
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
    <?php echo $html->submit(__('Install iPeer', true), array('name'=>'next')) ?></br>
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
