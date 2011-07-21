<?php 
  echo $html->script('emailtemplates.js');
  echo $html->script('calendar1');
?>
<form method="post" action="<?php echo $html->url('/emailer/write/'); ?>" name="emailer" id="emailer" class="emailer">
  <table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="3" align="center"><?php __('Write Email')?></td>
          </tr>
          <tr class="tablecell2">
            <td><?php __('To')?>:&nbsp;</td>
            <td>
              <?php
                if(!empty($recipients))
                  echo $html->link($recipients['name'], $recipients['link'], array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"));
              ?>
              <br/><br/>
              <div id="add-div"></div>              
              <?php echo $this->Form->select('recipients', $recipients_rest);?>

              <?php echo $this->Js->link($html->image('icons/add.gif', array('alt'=>'Add Additional Recipient', 'valign'=>'middle', 'border'=>'0')).__(' Add Additional Recipient', true),

                                   array('action' => 'addRecipient'),
                                   array('escape' => false,
                                         'success' => '$("add-div").insert({before: "<div>"+response.responseText+"</div>"});$$("option[value="+$F("recipients")+"]").invoke("remove")',
                                         'error' => 'alert("'.__('Communication error!', true).'")',
                                         'dataExpression' => true,
                                         'evalScripts' => true,
                                         'data' => '{recipient_id:$F("recipients")}'))?>

            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td><?php __('Schedule?')?>:</td>

            <td>
              <table>
              <tr><td>
              <?php echo $form->input('Email.date', array('div'=>false, 'label'=> 'Date :', 'type'=>'text', 'size'=>'50','class'=>'input', 'style'=>'width:75%;', 'value'=>date("Y-m-d H:i:s", time()))) ?>&nbsp;&nbsp;
              <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a>
              <?php echo $form->error('Email.date', 'Please enter a valid date.')?>
              </td></tr>
              <tr><td>
              <?php
                echo $form->input('Email.schedule', array(
                   'type' => 'radio',
                   'options' => array('0' => ' - '.__('Single Email',true).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                      '1' => ' - '.__('Multiple Emails',true).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                     ),
                   'default' => '0',
                   'onClick' => "toggle_schedule(this)",
                   'legend' => false
                ));
              ?>
              </td></tr>
              <tr id="scheduling"><td>

                <table>

                <tr><td>
                <?php

                  echo $form->input('Email.times', array('div'=>false, 'label'=>' ', 'value' => '1', 'size' => '3')).__(' time(s), ',true);
                  echo $form->input('Email.interval_num', array('div'=>false, 'label'=>__('  Every ', true)));
                  echo $form->input('Email.interval_type', array('div'=>false, 'label'=>false, 'options'=> array('60' => __('minute(s)',true),'3600' => __('hour(s)',true),'86400' => __('day(s)',true)), 'selected'=>'3600'));

                ?>
                </td></tr></table>
              </td></tr></table>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr id="tablecell2" class="tablecell2">
            <td><?php __('Template')?>:&nbsp;</td>
            <td>
              <table>
              <tr><td>
              <?php echo $html->link(__('Add Email Template', true), 'add/', array('onclick' => "wopen(this.href, 'popup', 650, 500); return false;"));?>
              </td></tr>
              <tr><td>
              <?php echo $form->input('Email.template', array(
                'type' => 'select',
                'id' => 'template',
                'options' => $templatesList,
                'empty' => __('-- No Template --', true),
                'label' => false,
                'onChange' => "new Ajax.Updater('email_content','".
                    $this->webroot.$this->theme."emailtemplates/displayTemplateContent/'+this.options[this.selectedIndex].value,
                     {method: 'post', asynchronous: true, evalScripts:false}); 
                     new Ajax.Updater('email_subject','".
                    $this->webroot.$this->theme."emailtemplates/displayTemplateSubject/'+this.options[this.selectedIndex].value,
                     {method: 'post', asynchronous: true, evalScripts:false});
                     return false;",
                  'escape'=>false
              ));?>
              </td></tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td><?php __('Subject')?>:&nbsp;</td>
            <td><?php echo $form->textarea('Email.subject', array('id' => 'email_subject','cols' => '60'));?></td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td><?php __('Insert Merge Field')?>:&nbsp;</td>
            <td><?php echo $form->input('Email.merge', array(
                        'type' => 'select',
                        'id' => 'merge',
                        'name' => 'merge',
                        'options' => $mergeList,
                        'empty' => __('-- Select Merge Field --',true),
                        'label' => false,
                        'onChange' => "insertAtCursor(document.emailer.email_content, this.value)"
                      ));?>  </td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tablecell2">

            <td><?php __('Content')?>:</td>

            <td><table><tr>
            <td><?php
                echo $form->textarea('Email.content', array(
                  'id' => 'email_content',

                  'cols' => '60',
                  'rows' => '15',
                  'escape' => false
                ));
            ?></td></tr>
            <tr><td>
            <?php 
              //echo $form->submit('Preview', array('name'=>'preview', 'onclick'=>"wopen(this.href, 'popup', 650, 500); return false;")); ?>
            </td></tr>
            </table></td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <div><?php echo $form->submit(__('Send', true)); ?></div>
    </td>
  </tr>
</table>
</form>
<script>

  var cal1 = new calendar1(document.forms[0].elements['data[Email][date]']);
  cal1.year_scroll = false;
  cal1.time_comp = true;

  $('scheduling').style.visibility = 'hidden';

  function toggle_schedule(el) {
        if (el.value == '1')
                $('scheduling').style.visibility = 'visible';
        else
                $('scheduling').style.visibility = 'hidden';
  }
</script>