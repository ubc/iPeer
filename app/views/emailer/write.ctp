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
<!--          <tr class="tablecell2">
            <td>To:&nbsp;</td>
            <td><?php echo $form->input('Email.to', array('size' => '80%','label' => false,'value' => $to));?>
            </td>
          </tr>-->
          <tr class="tablecell2">
            <td>To:&nbsp;</td>
            <td><?php if (isset($recipients)):?>
              <?php foreach($recipients as $i):?>
              <div><?php echo $this->element('emailer/edit_recipient', array('recipient' => $i['User']));?></div>
              <?php endforeach;?>
              <?php endif;?>
              <div id="add-div"></div>
              <?php echo $this->Form->select('recipients', $recipients_rest);?>
              <?php echo $this->Js->link($html->image('icons/add.gif', array('alt'=>'Add Recipient', 'valign'=>'middle', 'border'=>'0')).' Add Recipient',
                                   array('action' => 'addRecipient'),
                                   array('escape' => false,
                                         'success' => '$("add-div").insert({before: "<div>"+response.responseText+"</div>"});$$("option[value="+$F("recipients")+"]").invoke("remove")',
                                         'error' => 'alert("Communication error!")',
                                         'dataExpression' => true,
                                         'evalScripts' => true,
                                         'data' => '{recipient_id:$F("recipients")}'))?>

          </td>
          </tr>
<!--          <tr class="tablecell2">
            <td>Cc:&nbsp;</td>
            <td><?php echo $form->input('Email.bcc', array('size' => '80%','label' => false));?></td>
          </tr>
          <tr class="tablecell2">
            <td>Bcc:&nbsp;</td>
            <td><?php echo $form->input('Email.bcc', array('size' => '80%','label' => false));?></td>
          </tr>-->
          <tr id="tablecell2" class="tablecell2">
            <td>Template:&nbsp;</td>
            <td>
              <table>
              <tr><td>
              <?php echo $html->link('Add Email Template', 'add/', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"));?>
              </td></tr>
              <tr><td>
              <?php echo $form->input('Email.template', array(
                'type' => 'select',
                'id' => 'template',
                'options' => $templatesList,
                'empty' => '-- No Template --',
                'label' => false,
                'onChange' => "new Ajax.Updater('email_content','".
                    $this->webroot.$this->theme."emailer/displayTemplate/'+this.options[this.selectedIndex].value,
                     {method: 'post', asynchronous: true, evalScripts:false});  return false;",
                  'escape'=>false
              ));?>
              </td></tr>
              </table>
            </td>
          </tr>
          <tr class="tablecell2">
            <td>Subject:&nbsp;</td>
            <td><?php echo $form->input('Email.subject', array('size' => '80%', 'label'=>false));?></td>
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

<!--<script type="text/javascript">
  new Autocomplete('query', { serviceUrl: '/ipeer/users/user_list'});
</script>-->