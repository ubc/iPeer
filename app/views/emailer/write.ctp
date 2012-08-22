<div id="EmailerWrite">
<?php echo $html->script('emailtemplates.js'); echo $html->script('calendar1') ?>
<div align=center><form method="post" action="<?php echo $html->url('/emailer/write/') ?>" name="emailer" id="emailer" class="emailer">
    <table>
        <tr>
            <td>To:</td>
            <td><p><?php if(!empty($recipients)) {
                        echo $html->link($recipients['name'], $recipients['link'], array('target' => '_blank'));
                    }
                ?></p>
                <div id="add-div"></div>
                <?php echo $this->Form->select('recipients', $recipients_rest);
                    echo $this->Js->link($html->image('icons/add.gif', array('alt'=>'Add Additional Recipient', 'valign'=>'middle', 'border'=>'0')).__(' Add Additional Recipient', true),
                        array('action' => 'addRecipient'),
                        array('escape' => false,
                            'success' => '
                                $("add-div").insert({before: "<div>"+response.responseText+"</div>"});
                                $$("option[value="+$F("recipients")+"]").invoke("remove")',
                            'error' => 'alert("'.__('Please select a recipient!', true).'")',
                            'dataExpression' => true,
                            'evalScripts' => true,
                            'data' => '{recipient_id:$F("recipients")}'
                        )
                    )
                ?>
            </td>
        </tr>
        <tr>
            <td>Date: </td>
            <td><?php echo $form->input('Email.date', array(
                            'div' => false,
                            'label' => false,
                            'style' => 'width:77%',
                            'value' => date("Y-m-d H:i:s")
                        )
                    )
                ?>
                <a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');">
                    <?php echo $html->image('icons/cal.gif', array('align' => 'middle', 'border' => '0', 'alt' => 'cal')) ?>
                </a>
                <?php echo $form->error('Email.date', 'Please enter a valid date.') ?>
            </td>
        </tr>
        <tr>
            <td>Schedule:</td>
            <td><table>
                    <tr>
                        <td><?php echo $form->input('Email.schedule', array(
                                        'type' => 'radio',
                                        'options' => array(
                                                '0' => ' - '.__('Single Email',true).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                                '1' => ' - '.__('Multiple Emails',true),
                                            ),
                                        'default' => '0',
                                        'onClick' => "toggle_schedule(this)",
                                        'legend' => false
                                    )
                                )
                            ?>
                        </td>
                    </tr>
                    <tr id="scheduling">
                        <td><?php echo $form->input('Email.times', array(
                                        'div' => false,
                                        'label' => false,
                                        'value' => '1',
                                        'size' => '3'
                                    )
                                );
                                echo $form->input('Email.interval_num', array(
                                        'div' => false,
                                        'label' => __(' time(s), every ', true)
                                    )
                                );
                                echo $form->input('Email.interval_type', array(
                                        'div' => false,
                                        'label' => false,
                                        'options' => array(
                                            '60' => __('minute(s)',true),
                                            '3600' => __('hour(s)',true),
                                            '86400' => __('day(s)',true)
                                        ),
                                        'selected' => '3600'
                                    )
                                )
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>Template:</td>
            <td><?php echo $html->link(__('Add Email Template', true), '/emailtemplates/add/', array('target' => '_blank'));
                    echo $form->input('Email.template', array(
                            'type' => 'select',
                            'id' => 'template',
                            'options' => $templatesList,
                            'empty' => __('-- No Template --', true),
                            'label' => false,
                            'onChange' => "new Ajax.Updater('email_content','".
                                $this->webroot.$this->theme."emailtemplates/displayTemplateContent/'+this.options[this.selectedIndex].value,
                                {method: 'post', asynchronous: true, evalScripts:false}); 
                                new Ajax.Updater('email_subject','".
                                $this -> webroot.$this -> theme."emailtemplates/displayTemplateSubject/'+this.options[this.selectedIndex].value,
                                {method: 'post', asynchronous: true, evalScripts:false});
                                return false;",
                            'escape' => false
                        )
                    )
                ?>
            </td>
        </tr>
        <tr>
            <td>Subject:</td>
            <td><?php echo $this->Form->input('Email.subject', array(
                            'id' => 'email_subject',
                            'cols' => '60',
                            'rows' => '1',
                            'label' => false
                        )
                    )
                ?>
            </td>
        </tr>
        <tr>
            <td>Insert Merge Field:</td>
            <td><?php echo $this->Form->input('Email.merge', array(
                            'type' => 'select',
                            'id' => 'merge',
                            'name' => 'merge',
                            'options' => $mergeList,
                            'empty' => __('-- Select Merge Field --', true),
                            'label' => false
                        )
                    )
                ?>
            </td>
        </tr>
        <tr>
            <td>Content:</td>
            <td><?php echo $this->Form->input('Email.content', array(
                            'id' => 'email_content',
                            'cols' => '60',
                            'rows' => '15',
                            'label' => false
                        )
                    )
                ?>
            </td>
        </tr>
    </table>
    <?php echo $form->submit('Send') ?>
</form></div></div>
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
