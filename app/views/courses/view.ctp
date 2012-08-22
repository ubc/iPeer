<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
  <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
    <tr class="tableheader">
      <td align="center" colspan="4"><?php echo $data['Course']['title']; ?></td>
      </tr>
    <tr class="tablecell">
      <td width="86"><?php __('Course')?>:</td>
      <td width="389" colspan="3"><?php echo $data['Course']['course']; ?></td>
    </tr>
    <tr class="tablecell">
      <td><?php __('Title')?>:</td>
      <td colspan="3"><?php echo $data['Course']['title']; ?></td>
    </tr>
    <tr class="tablecell">
      <td valign="top"><?php __('Instructor(s)')?>:</td>
      <td colspan="3">
	      <table width="100%" border="0" cellspacing="2" cellpadding="2">
	  <?php
	  $maillToAll = '';
    foreach($data['Instructor'] as $i):?>
	    <?php $maillToAll .= $i['email'].';';?>
  	  <tr>
  		  <td width="15"><a href="mailto:<?php echo $i['email']?>"><?php echo $html->image('icons/email_icon.gif',array('border'=>'0','alt'=>__('Email', true)))?></a></td>
        <td><?php echo $this->element('users/user_info', array('data' => $i))?></td></tr>
    <?php endforeach;?>
	  </table>
	  <?php if (!empty($maillToAll)): ?>
	   <a href="mailto:<?php echo $maillToAll?>"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>__(' Email To All', true)))?><?php __(' Email To All Instructors')?></a>
	  <?php endif;?> 
	  </td>
    </tr>
    <tr class="tablecell">
      <td><?php __('Status')?>:</td>
      <td colspan="3"><?php if( $data['Course']['record_status'] == "A" ) echo __("Active", true); else echo __("Inactive", true); ?></td>
    </tr>
    <tr class="tablecell">
      <td><?php __('Homepage')?>:</td>
      <td colspan="3"><a href="<?php echo !empty($data['Course']['homepage'])? $data['Course']['homepage']:'#'; ?>" target="_blank"><?php echo $data['Course']['homepage']; ?></a>
        </td>
    </tr>
    <tr class="tablecell">
      <td id="creator_label"><small><?php __('Creator')?>:</small></td>
      <td align="left"><small><?php echo $data['Course']['creator']?></td><!--$this->element('users/user_info', array('data'=>$data['Course']['creator_id']));?></small></td>-->
      <td id="updater_label"><small><?php __('Updater')?>:</small></td>
      <td align="left"><small><?php echo $data['Course']['updater']?></td><!--$this->element('users/user_info', array('data'=>$data['Course']['updater_id']));?></small></td>-->
    </tr>
    <tr class="tablecell">
      <td id="created_label"><small><?php __('Create Date')?>:</small></td>
      <td align="left"><small><?php echo $data['Course']['created']; ?></small></td>
      <td id="updated_label"><small><?php __('Update Date')?>:</small></td>
      <td align="left"><small><?php echo $data['Course']['modified']; ?></small></td>
    </tr>
    <tr class="tablecell">
      <td colspan="4" align="center">
      <input type="button" name="Back" value=<?php __('Back')?> onClick="javascript:(history.length > 1) ? history.back() : window.close();">
      </td>
      </tr>
  </table>
</td>
  </tr>
</table>
