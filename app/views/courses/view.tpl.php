<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td align="center" colspan="4"><?php echo $data['Course']['title']; ?></td>
      </tr>
    <tr class="tablecell">
      <td width="86">Course:</td>
      <td width="389" colspan="3"><?php echo $data['Course']['course']; ?></td>
    </tr>
    <tr class="tablecell">
      <td>Title:</td>
      <td colspan="3"><?php echo $data['Course']['title']; ?></td>
    </tr>
    <tr class="tablecell">
      <td valign="top">Instructor(s):</td>
      <td colspan="3">
	  <?php
	  $maillToAll = '';
	  echo '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
	  for( $i=0; $i < sizeof($instructor_data); $i++ ){
	    $maillToAll .= $instructor_data[$i]['User']['email'].';';
  	  echo '<tr>';
  		echo '<td width="15"><a href="mailto:'.$instructor_data[$i]['User']['email'].':">'.$html->image('icons/email_icon.gif',array('border'=>'0','alt'=>'Email')).'</a></td><td>';
  		echo '<a href=../../users/view/'.$instructor_data[$i]['User']['id'].'>';
  		echo $instructor_data[$i]['User']['last_name'].', '.$instructor_data[$i]['User']['first_name'].'<br>';
  		echo '</a>';
  		echo '</td></tr>';
	  }
	  echo '</table>';
	  if (!empty($maillToAll)) {?>
	   <a href="mailto:<?php echo $maillToAll?>"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>'Emaill To All'))?></a>
          Email To All Instructors
	  <?php }
	  ?>
	  </td>
    </tr>
    <tr class="tablecell">
      <td>Status:</td>
      <td colspan="3"><?php if( $data['Course']['record_status'] == "A" ) echo "Active"; else echo "Inactive"; ?></td>
    </tr>
  <?php if (!(isset($rdAuth->customIntegrateCWL) && $rdAuth->customIntegrateCWL)) { ?>
    <tr class="tablecell">
      <td>Self Enrollment: </td>
      <td colspan="3"><?php echo $data['Course']['self_enroll']; ?></td>
    </tr>
    <tr class="tablecell">
      <td>Self Enrollment Password: </td>
      <td colspan="3"><?php echo $data['Course']['password']; ?></td>
    </tr>
  <?php } ?>
    <tr class="tablecell">
      <td>Homepage:</td>
      <td colspan="3"><a href="<?php echo !empty($data['Course']['homepage'])? $data['Course']['homepage']:'#'; ?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php echo $data['Course']['homepage']; ?></a>
        </td>
    </tr>
    <tr class="tablecell2">
      <td id="creator_label"><small>Creator:</small></td>
      <td align="left"><small><?php
      $params = array('controller'=>'courses', 'userId'=>$data['Course']['creator_id']);
      echo $this->renderElement('users/user_info', $params);
      ?></small></td>
      <td id="updater_label"><small>Updater:</small></td>
      <td align="left"><small><?php
      $params = array('controller'=>'courses', 'userId'=>$data['Course']['updater_id']);
      echo $this->renderElement('users/user_info', $params);
      ?></small></td>
    </tr>
    <tr class="tablecell2">
      <td id="created_label"><small>Create Date:</small></td>
      <td align="left"><small><?php echo $data['Course']['created']; ?></small></td>
      <td id="updated_label"><small>Update Date:</small></td>
      <td align="left"><small><?php echo $data['Course']['modified']; ?></small></td>
    </tr>
    <tr class="tablecell">
      <td colspan="4" align="center"><input type="button" name="Back" value="Back" onClick="parent.location='<?php echo $this->webroot.$this->themeWeb.$this->params['controller']; ?>'"></td>
      </tr>
  </table>
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
    <tr>
      <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
      <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
    </tr>
  </table>
</td>
  </tr>
</table>
