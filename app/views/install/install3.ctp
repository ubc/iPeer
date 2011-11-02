<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center">
  <br>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url('configdb') ?>" onSubmit="return validate()" enctype="multipart/form-data">
<input type="hidden" name="required" id="required" value="host_name db_user db_name" />

<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td>&nbsp;</td>
    <td><?php __('Step 3: iPeer Database Configuration')?> </td>
    <td>&nbsp;</td>
  </tr>
  <!-- Data Setup Option-->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong><?php __('Data Setup Option')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                  	<td width="130" id="data_opt_label"><?php __('Data Setup')?>:</td>
                  	<td width="500" align="left">
                  	  <input type="radio" name="data_setup_option" value="A" CHECKED  onClick="javascript:$('import_data').hide()"><?php __('Installation with sample data. (Recommended)')?><br>
                      <input type="radio" name="data_setup_option" value="B" onClick="javascript:$('import_data').hide();"><?php __('Basic Installation')?><br />
                      <input type="radio" name="data_setup_option" value="C" onClick="javascript:$('import_data').show();"><?php __('Import Data From iPeer 1.6')?><br />
                  	</td>
                  	<td width="363" id="data_opt_msg" class="error">&nbsp;</td>
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
    <td colspan="3">
    <div id="import_data" style="display: none;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="10%">&nbsp;</td>
          <td><strong><?php __('Import Data from iPeer 1.6')?></strong></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <table width="100%" border="1" cellspacing="0" cellpadding="0" bgcolor="#E9ECEF">
              <tr>
                <td>
                  <table width="90%"  border="0" cellspacing="2" cellpadding="10">
                    <tr>
                      <td width="130" id="data_file_label"><?php __('Data File')?></td>
                      <td width="337" align="left">
                        <input type="file" name="data_file" />
                      </td>
                      <td width="663" id="data_file_msg" class="error">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="130" id="data_to_import_label"><?php __('Data to Import')?></td>
                      <td width="337" align="left">
                        <input type="checkbox" name="to_import[]" value="instructors" checked /><?php __('Instructors')?><br />
                        <input type="checkbox" name="to_import[]" value="courses" checked /><?php __('Courses')?><br />
                        <input type="checkbox" name="to_import[]" value="students" checked /><?php __('Students')?><br />
                        <input type="checkbox" name="to_import[]" value="enrols" checked /><?php __('Enrolments')?><br />
                        <input type="checkbox" name="to_import[]" value="rubrics" checked /><?php __('Rubrics')?><br />
                        <input type="checkbox" name="to_import[]" value="simple_evals" checked /><?php __('Simple Evaluations')?><br />
                        <input type="checkbox" name="to_import[]" value="assignments" checked /><?php __('Assignments')?><br />
                        <input type="checkbox" name="to_import[]" value="surveys" checked /><?php __('Surveys')?><br />
                        <input type="checkbox" name="to_import[]" value="groups" checked /><?php __('Groups')?><br />
                      </td>
                      <td width="663" id="data_to_import_msg" class="error">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </div>
    </td>
  </tr>
  <!-- Database Configuration -->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong><?php __('MySQL Database Configuration Parameters')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                  	<td width="130" id="driver_label"><?php __('DB Driver')?>:</td>
                  	<td width="337" align="left">
										<?php
										$dbDriver = array('mysql'=>'MySQL');
										echo $form->select('DBConfig.driver', $dbDriver);
										 ?>
										</td>
                  	<td width="663" id="driver_msg" class="error">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td width="130" id="connect_label"><?php __('DB Connection')?>:</td>
                  	<td width="337" align="left">
										<?php
										$dbConnection = array('mysql_pconnect'=>'mysql_pconnect');
										echo $form->select('DBConfig.connect', $dbConnection);
										 ?>
										</td>
                  	<td width="663" id="connect_msg" class="error">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td width="130" id="host_name_label"><?php __('Host Name')?>:</td>
                  	<td width="337" align="left"><?php echo $form->input('DBConfig.host', array('id'=>'host_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT host_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'value'=>'localhost'))?></td>
                  	<td width="663" id="host_name_msg" class="error">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td width="130" id="db_user_label"><?php __('DB Username')?>:</td>
                  	<td width="337" align="left"><?php echo $form->input('DBConfig.login', array('id'=>'db_user', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT db_user_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
                  	<td width="663" id="db_user_msg" class="error">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td width="130" id="db_password_label"><?php __('DB Password')?>:</td>
                  	<td width="337" align="left"><?php echo $form->password('DBConfig.password', array('id'=>'db_password', 'size'=>'50', 'class'=>'TEXT_FORMAT'))?></td>
                  	<td width="663" id="db_password_msg" class="error">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td width="140" id="db_name_label"><?php __('Database Name')?>:</td>
                  	<td width="337" align="left"><?php echo $form->input('DBConfig.database', array('id'=>'db_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT db_name_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
                  	<td width="663" id="db_name_msg" class="error">&nbsp;</td>
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
    <?php echo $form->submit('Next >>', array('name'=>'next')) ?><br />
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
