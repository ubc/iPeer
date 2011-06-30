<form method="post" action="<?php echo $html->url('/emailer/write/'); ?>" name="emailer" id="emailer" class="emailer">
  <table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="2" align="center">Write Email</td>
          </tr>
          <tr class="tablecell2">
            <td>From:&nbsp;</td>
            <td><?php echo $form->input('Email.from', array('size' => '80%', 'label'=>false, 'value' => $from));?></td>
          </tr>
          <tr class="tablecell2">
            <td>To:&nbsp;</td>
            <td><?php echo $form->input('Email.to', array('size' => '80%','label' => false,'value' => $to));?></td>
          </tr>
          <tr class="tablecell2">
            <td>Cc:&nbsp;</td>
            <td><?php echo $form->input('Email.cc', array('size' => '80%','label' => false));?></td>
          </tr>
          <tr class="tablecell2">
            <td>Bcc:&nbsp;</td>
            <td><?php echo $form->input('Email.bcc', array('size' => '80%','label' => false));?></td>
          </tr>
          <tr id="tablecell2" class="tablecell2">
            <td>Template:&nbsp;</td>
            <td><?php echo $form->input('Email.template', array(
              'type' => 'select',
              'id' => 'template',
              'options' => $templatesList,
              'empty' => '-- No Template --',
              'label' => false,
              'onChange' => "new Ajax.Updater('email_content','".
                  $this->webroot.$this->theme."emailer/emailTemplate/'+this.options[this.selectedIndex].value,
                   {method: 'post', asynchronous: true, evalScripts:false});  return false;",
                'escape'=>false
            ));?>
            </td>
          </tr>
          <tr class="tablecell2">
            <td>Message:</td>
            <td><?php
                echo $form->textarea('Email.content', array(
                  'id' => 'email_content',
                  'lable' => "Content: ",
                  'cols' => '60',
                  'rows' => '15',
                  'escape' => false
                ));
            ?></td>
          </tr>
        </table>
        <div><?php echo $form->submit('Send'); ?></div>
    </td>
  </tr>
</table>
</form>