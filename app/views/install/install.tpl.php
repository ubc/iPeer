<?php
function get_php_setting($val) {
	$r =  (ini_get($val) == '1' ? 1 : 0);
	return $r ? 'ON' : 'OFF';
}
?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center">
  <br>
<form action="<?php echo $html->url('install2') ?>">
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td>&nbsp;</td>
    <td>Step 1: System Requirement Check</td>
    <td>&nbsp;</td>
  </tr>
  <!-- Property of System Requirements -->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong>System Requirements: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                    <td width="30%" rowspan="7" class="style6"><div align="justify">All items in this section must be green. Please take action to correct any item that is shown in red before installation. </div></td>
                    <td width="10%"></td>
                    <td width="40%">PHP version &gt;= 4.3</td>
                    <td width="20%"><?php echo phpversion() < '4.1' ? '<b><font color="red">No</font></b>' : '<b><font color="green">Yes</font></b>';?></td>
                  </tr>
                  <tr>
                    <td><div align="center"></div></td>
                    <td>MySQL Support</td>
                    <td><?php echo function_exists( 'mysql_connect' ) ? '<b><font color="green">Available</font></b>' : '<b><font color="red">Unavailable</font></b>';?></td>
                  </tr>
          			  <tr>
          			   <td>&nbsp;</td>
          			   <td>PEAR extensions</td>
          			   <td><b><?php
          			   @include_once("DB.php");
               	  echo class_exists('DB') ? '<font color="green">Available</font>' : '<font color="red">Not Installed</font>';
          			   ?></b></td>
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
  <!-- Property of Optional Requirements -->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong>Optional Requirements: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                    <td width="30%" rowspan="7" class="style6"><div align="justify">All items in this section are optional. </div></td>
                    <td width="10%"></td>
                    <td width="40%">Sendmail or Sendmail Wrapper <br/>(Required if you want email functions.)</td>
                    <td width="20%"><b><?php
            				   echo ini_get("sendmail_path") ? '<font color="green">Installed</font>' : '<font color="red">Not Installed</font>';
            			   ?></b></td>
                  </tr>
                  <tr>
                    <td><div align="center"></div></td>
            			  <td>"at" permissions for email scheduling</td>
            			  <td><?php
                        $output;
                        $return_var;
                        //echo | at `date +%H:%M` //this one might work.. but generates an empty job
                        exec("atq",$output,$return_var); //won't work on windows
                        if($return_var != 0)
                        	echo '<b><font color="red">Denied. Remove Apache daemon ('. exec("whoami") . ") from \"/etc/at.deny\" or \"/var/at/at.deny\"</font></b>";
                        else
                        	echo '<b><font color="green">Allowed</font></b>';
                        ?></td>
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
  <!-- Property of Recommended Settings -->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong>Recommended Settings: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="95%"  border="0" cellspacing="2" cellpadding="10">
                  <tr><td class="title"> Directive</td><td class="title"> Recommended</td><td class="title" >Actual</td></tr>
                  <?php
                		$php_recommended_settings = array(array ('Safe Mode','safe_mode','OFF'),
                		array ('Display Errors','display_errors','ON'),
                		array ('File Uploads','file_uploads','ON'),
                		array ('Register Globals','register_globals','OFF'),
                		array ('Output Buffering','output_buffering','OFF'),
                		array ('Session auto start','session.auto_start','OFF'),
                		);

                		foreach ($php_recommended_settings as $phprec):	?>
                    <tr>
                      <td class="item"><?php echo $phprec[0]; ?>:</td>
                      <td class="toggle"><?php echo $phprec[2]; ?>:</td>
                      <td>
                      <?php	if ( get_php_setting($phprec[1]) == $phprec[2] ) :?>
                          <font color="green"><b>
                      <?php else: 	?>
                          <font color="red"><b>
                      <?php endif;
                    	echo get_php_setting($phprec[1]);
                    	?>
                      </b></font>
                      <td>
                    <?php   endforeach;     ?>
                </table>
              </td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Next -->
  <tr>
    <td colspan="3" align="right">
      <?php echo $html->submit('Next >>', array('name'=>'next')) ?>

    </td>
  </tr>
</table>


</form>
</td></tr></table>