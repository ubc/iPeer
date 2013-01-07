<div id="EmailerWrite">
<?php
    echo $html->script('emailtemplates.js'); echo $html->script('calendar1');
    $html->script("jquery-ui-timepicker-addon", array("inline"=>false));
?>
<div align=center><form method="post" action="<?php echo $html->url('/emailer/write/') ?>" name="emailer" id="emailer" class="emailer">
    <table>
        <tr>
            <td>To:</td>
            <td width="600px"><p><?php if(!empty($recipients)) {
                        echo $html->link($recipients['name'], $recipients['link']);
                    }
                ?></p>
                <div id="add-div"></div><?php natcasesort($recipients_rest);?>
                <?php echo $this->Form->select('recipients', $recipients_rest);
                    echo $this->Js->link(__('Add Additional Recipient', true),
                        array('action' => 'addRecipient'),
                        array('escape' => false,
                            'class' => 'add-button',
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
                            'style' => 'width:77%'
                        )
                    )
                ?>
                <?php echo $form->error('Email.date', 'Please enter a valid date.') ?>
            </td>
        </tr>
        <tr>
            <td>Schedule:</td>
            <td>
                <div>
                    <?php echo $form->input('Email.schedule', array(
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
                </div>
                <div id="scheduling">
                        <?php echo $form->input('Email.times', array(
                                        'div' => false,
                                        'label' => __('(send ', true),
                                        'value' => '1',
                                        'size' => '3'
                                    )
                                );
                                echo $form->input('Email.interval_num', array(
                                        'div' => false,
                                        'size' => '3',
                                        'label' => __(' times, with ', true)
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
                                );
                                echo ' in between each)';
                            ?>
                    </div>
            </td>
        </tr>
        <tr>
            <td>Template:</td>
            <td><?php
                    echo $form->input('Email.template', array(
                            'type' => 'select',
                            'id' => 'template',
                            'options' => $templatesList,
                            'empty' => __('-- No Template --', true),
                            'label' => false,
                            'div'   => false,
                            'onChange' => "new Ajax.Updater('email_content','".
                                $this->webroot.$this->theme."emailtemplates/displayTemplateContent/'+this.options[this.selectedIndex].value,
                                {method: 'post', asynchronous: true, evalScripts:false});
                                new Ajax.Updater('email_subject','".
                                $this -> webroot.$this -> theme."emailtemplates/displayTemplateSubject/'+this.options[this.selectedIndex].value,
                                {method: 'post', asynchronous: true, evalScripts:false});
                                return false;",
                            'escape' => false
                        )
                    );
                    echo $html->link(__('Add Email Template', true), '/emailtemplates/add/', array('class' => 'add-button'));
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
                            'label' => false,
                            'onChange' => '$("email_content").value = $F("email_content") + $F("merge");',
                            'escape' => false
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
    $('scheduling').style.display = 'none';
    function toggle_schedule(el) {
        if (el.value == '1')
            $('scheduling').style.display = 'block';
        else
            $('scheduling').style.display = 'none';
    }
</script>
<script type="text/javascript">
    initDateTime();
    function initDateTime() {
        var format = { dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss' }
        jQuery("#EmailDate").datetimepicker(format);
    }
</script>
