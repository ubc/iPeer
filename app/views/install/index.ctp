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
        <td><strong><?php __('System Requirements')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                    <td width="30%" rowspan="7" class="style6"><div align="justify"><?php __('All items in this section must be green. Please take action to correct any item that is shown in red before installation.')?> </div></td>
                    <td width="10%"></td>
                    <td width="40%"><?php __('PHP version')?> &gt;= 4.3.2</td>
                    <td width="20%"><?php echo phpversion() < '4.3.2' ? '<b><font color="red">'.__('No', true).'</font></b>' : '<b><font color="green">'.__('Yes', true).'</font></b>';?></td>
                  </tr>
                  <tr>
                    <td><div align="center"></div></td>
                    <td><?php __('MySQL Support')?></td>
                    <td><?php echo function_exists( 'mysql_connect' ) ? '<b><font color="green">'.__('Available', true).'</font></b>' : '<b><font color="red">'.__('Unavailable', true).'</font></b>';?></td>
                  </tr>
                  <tr>
                    <td><div align="center"></div></td>
                    <td><?php __('Directory app/config writable ')?></td>
                    <td>
                      <?php 
                      echo is_writable(CONFIGS) ?
                      '<b><font color="green">'.__('Yes', true).'</font></b>' :
                      '<b><font color="red">'.__('No', true).'</font></b>';
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td><div align="center"></div></td>
                    <td><?php __('File app/config/database.php writable ')?></td>
                    <td>
                      <?php 
                      echo is_writable(CONFIGS.'database.php') ?
                      '<b><font color="green">'.__('Yes', true).'</font></b>' :
                      '<b><font color="red">'.__('No', true).'</font></b>';
                      ?>
                    </td>
                  </tr>
<!--          			  <tr>
          			   <td>&nbsp;</td>
          			   <td>PEAR extensions</td>
          			   <td><b><?php
          			   @include_once("DB.php");
               	  echo class_exists('DB') ? '<font color="green">'.__('Available', true).'</font>' : '<font color="red">'.__('Not Installed', true).'</font>';
          			   ?></b></td>
          			  </tr>-->
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
        <td><strong><?php __('Optional Requirements')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="90%"  border="0" cellspacing="2" cellpadding="10">
                  <tr>
                    <td width="30%" rowspan="7" class="style6"><div align="justify"><?php __('All items in this section are optional.')?> </div></td>
                    <td width="10%"></td>
                    <td width="40%"><?php __('Sendmail or Sendmail Wrapper <br/>(Required if you want email functions.)')?></td>
                    <td width="20%"><b><?php
            				   echo ini_get("sendmail_path") ? '<font color="green">'.__('Installed', true).'</font>' : '<font color="red">'.__('Not Installed', true).'</font>';
            			   ?></b></td>
                  </tr>
                  <tr>
                    <td><div align="center"></div></td>
            			  <td><?php __('"at" permissions for email scheduling')?></td>
            			  <td><?php
                        $output;
                        $return_var;
                        //echo | at `date +%H:%M` //this one might work.. but generates an empty job
                        exec("atq",$output,$return_var); //won't work on windows
                        if($return_var != 0)
                        	echo '<b><font color="red">'.__('Denied. Remove Apache daemon').'<br/> ('. exec("whoami") . ") ".__('from', true)." \"/etc/at.deny\" or \"/var/at/at.deny\"</font></b>";
                        else
                        	echo '<b><font color="green">'.__('Allowed', true).'</font></b>';
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
        <td><strong><?php __('Recommended Settings')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><table width="95%"  border="0" cellspacing="2" cellpadding="10">
                  <tr><td class="title"> <?php __('Directive')?></td><td class="title"><?php __(' Recommended')?></td><td class="title" ><?php __('Actual')?></td></tr>
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
      <?php echo $form->submit('Next >>') ?>

    </td>
  </tr>
</table>


</form>
</td></tr></table>
