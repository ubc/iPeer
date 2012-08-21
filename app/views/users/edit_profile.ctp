<?php echo $this->Form->create('User', array('id' => 'frm',
                                            'url' => array('action' => $this->action),
                                            'inputDefaults' => array('div' => false,
                                                                    'before' => '<td width="200px">',
                                                                    'after' => '</td>',
                                                                    'between' => '</td><td>')))?>
  <table width="95%" align="center">
    <tr class="tablecell2">
      <?php echo $this->Form->input('username', array('id' => 'username', 'size'=>'50', 'readonly' => true, 'label' => __('Username', true)));?>
    </tr>
    
    <!-- First Name -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('first_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT first_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('First Name', true))) ?>
      <td id="first_name_msg" class="error"></td>
    </tr>

    <!-- Last Name -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('last_name', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT last_name_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('Last Name', true)))?>
      <td id="last_name_msg" class="error"></td>
    </tr>

    <!-- Email -->
    <tr class="tablecell2">
      <?php echo $this->Form->input('email', array('size'=>'50', 'class'=>'validate none EMAIL_FORMAT email_msg Invalid_Email_Format.', 'after' => '', 'label' => __('Email', true))) ?>
      <td id="email_msg" class="error"></td>
    </tr>

    <?php if($is_student) {?>
    <!-- Student Number -->
    <tr class="tablecell2 student_field">
      <?php echo $this->Form->input('student_no', array('size'=>'50', 'class'=>'validate none', 'label' => __('Student Number', true))) ?>
      <td id="student_no_msg" class="error"></td>
    </tr>
    <?php } else { ?>
    <!-- Title -->
    <tr class="tablecell2 nonstudent_field">
      <?php echo $this->Form->input('title', array('size'=>'50', 'class'=>'validate none TEXT_FORMAT title_msg Invalid_Text._At_Least_One_Word_Is_Required.', 'label' => __('Title', true))) ?>
      <td id="title_msg" class="error"></td>
    </tr>
    <?php }?>
    
    <tr class="tablecell2">
      <td colspan="4"><hr align=left width=100%><h3><?php __('Change Password')?>:</h3></td>
    </tr>
    <tr class="tablecell2">
      <?php echo $this->Form->input('old_password', array('type'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT pw_msg0 Must_be_6+_characters,_alphanumeric.', 'label'=>__('Old Password:', true), 'disabled'=>$viewPage)) ?>
      <td id="pw_msg0" class="error"/>
    </tr>
    <tr class="tablecell2">
      <?php echo $this->Form->input('tmp_password', array('type'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT pw_msg1 Must_be_6+_characters,_alphanumeric.', 'label'=>__('New Password:', true), 'disabled'=>$viewPage)) ?>
      <td id="pw_msg1" class="error"/>
    </tr>
    <tr class="tablecell2">
      <?php echo $this->Form->input('confirm_password', array('type'=>'password', 'size'=>'50', 'class'=>'validate none PASSWORD_FORMAT pw_msg2 Must_be_6+_characters,_alphanumeric.', 'label'=>__('Confirm New Password:', true), 'disabled'=>$viewPage)) ?>
      <td id="pw_msg2" class="error"/>
    </tr>
  </table>
    <div class="oauth">
    <!-- OAuth Client Credentials -->
    <hr align=left width=95%>
    <h3><?php __('OAuth Client Credentials')?>:</h3>
    <?php if (count($clients) == 0 || User::hasPermission('controllers/oauthclients')) { ?>
        <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Client Credential', true))); ?>&nbsp;<?php echo $html->link(__('Add Client Credential', true), '/oauth_clients/add', array('id' => 'add')); ?>
    <?php } ?>
    <?php if (count($clients) > 0) { ?>
        <?php foreach ($clients as $key => $client) { ?>
            <p><label id=key><?php echo __('Key').': '.$client['OauthClient']['key'];?></label>
            <label id=secret><?php echo __('Secret').': '.$client['OauthClient']['secret'];?></label>
            <?php echo $this->Form->select('OauthClient.'.$key.'.enabled', $enabled, $client['OauthClient']['enabled'], array('empty' => false)); ?>
            <?php echo $this->Form->input('OauthClient.'.$key.'.id', array('value' => $client['OauthClient']['id'])); ?>
            <?php echo $html->link('X', '/oauth_clients/delete/'.$client['OauthClient']['id'], array('id' => 'delete')); ?></p>
            <label id=comment><?php echo __('Comment').': '.$client['OauthClient']['comment'];?></label>
        <?php } ?>
    <?php } ?>
    <!-- OAuth Token Credentials -->
    <hr align=left width=95%>
    <h3><?php __('OAuth Token Credentials')?>:</h3>
    <?php echo $html->image('icons/add.gif', array('alt'=>__('Add Token Credential', true), 'valign'=>'middle')); ?>&nbsp;<?php echo $html->link(__('Add Token Credential', true), '/oauth_tokens/add', array('id' => 'add')); ?>
    <?php if (count($tokens) > 0) { ?>
            <p><?php foreach ($tokens as $index => $token) { ?>
            <label id=key><?php echo _('Key').': '.$token['OauthToken']['key'];?></label>
            <label id=secret><?php echo _('Secret').': '.$token['OauthToken']['secret'];?></label>
            <?php echo _('Expires').': '.$token['OauthToken']['expires'];?>
            <?php echo $this->Form->select('OauthToken.'.$index.'.enabled', $enabled, $token['OauthToken']['enabled'], array('empty' => false)); ?>
            <?php echo $this->Form->input('OauthToken.'.$index.'.id', array('value' => $token['OauthToken']['id'])); ?>
            <?php echo $html->link('X', '/oauth_tokens/delete/'.$token['OauthToken']['id'], array('id' => 'delete')); ?></p>
        <?php } ?>
    <?php } ?>
    </div>
    <br>
    <?php if ($viewPage==false): echo $this->Form->submit(__('Save', true));
        endif; ?>
<?php $this->Form->end();?>