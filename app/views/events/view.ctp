<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($event['Event']['id'])?'add':'edit') ?>">
<?php echo empty($event['Event']['id']) ? null : $this->Form->hidden('id'); ?>

<table class="full-size event-view">
    <tr class="tableheader"><td colspan="2">Evaluation Event</td></tr>
    <tr class="tablecell2">
        <td width="20%" id="course_label"><?php __('Course:')?></td>
        <td><?php echo $event['Course']['full_name']; ?>
        </td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Event Title:')?>&nbsp;</td>
        <td><?php echo $event['Event']['title']; ?></td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Description:')?>&nbsp;</td>
        <td><?php echo $event['Event']['description']; ?></td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Evaluation Format:')?>&nbsp;</td>
        <td>
            <table border="0" align="left" cellpadding="4" cellspacing="2">
                <tr>
                    <td width="50%" align="left" valign="top" >
                        <?php
                        foreach($eventTypes as $row):
                            $eventTemplateType = $row['EventTemplateType'];
                            if (!empty($event['Event']['event_template_type_id']) && $event['Event']['event_template_type_id'] == $eventTemplateType['id']) {
                                echo $eventTemplateType['type_name'];
                            }
                        endforeach; ?>
                        <br>
                        <br>
                        <div id='template_table'>
                        <?php
                        $params = array('controller'=>'events', 'eventTemplates'=>$eventTemplates, 'default'=>$default, 'model'=>$model, 'templateID'=>$event['Event']['template_id'], 'view'=>1);
                        echo $this->element('events/ajax_event_template_list', $params);
                        ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Allow Self-Evaluation?:')?></td>
        <td>
            <?php echo $event['Event']['self_eval']==1? 'Enable' : 'Disable'; ?>
        </td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Require Student Comments?:')?> </td>
        <td><?php echo $event['Event']['com_req']==1? 'Yes' : 'No'; ?></td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Due Date:')?>&nbsp;</td>
        <td><?php echo Toolkit::formatDate($event['Event']['due_date']) ?></td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Evaluation Release Date: <font color="red">*</font>')?></td>
        <td id="release_date_begin">
            <table width="100%">
                <tr align="left">
                    <td width="10%"><?php __('FROM:')?></td>
                    <td width="90%">
                        <?php echo Toolkit::formatDate($event['Event']['release_date_begin']) ?>
                    </td>
                </tr>
                <tr>
                    <td width="10%"><?php __('TO:')?></td>
                    <td width="90%">
                        <?php echo Toolkit::formatDate($event['Event']['release_date_end']) ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Result Release Date: <font color="red">*</font>')?></td>
        <td id="result_release_date_begin">
            <table width="100%">
                <tr align="left">
                    <td width="10%"><?php __('FROM:')?></td>
                    <td width="90%">
                        <?php echo Toolkit::formatDate($event['Event']['result_release_date_begin']) ?>
                    </td>
                </tr>
                <tr>
                    <td width="10%"><?php __('TO:')?></td>
                    <td width="90%">
                        <?php echo Toolkit::formatDate($event['Event']['result_release_date_end']) ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="tablecell2">
        <td><?php __('Late Penalty:')?>&nbsp;</td>
        <td>
            <?php
            if (!empty($penalty)) {
                foreach ($penalty as $tmp) {
                    echo 'Deduct '.$tmp['Penalty']['percent_penalty'].'% if late by '.$tmp['Penalty']['days_late'].' day';
                    if (1 != $tmp['Penalty']['days_late']) {
                        echo 's';
                    }
                    echo '<br>';
                    $dayslate_count = $tmp['Penalty']['days_late'];
                }
            } else {
                echo 'No late penalties currently set';
            }
            ?>
        </td>
    </tr>
    <tr class="tablecell2">
        <td valign="top"><?php __('Groups Assignment:')?>&nbsp;</td>
        <td>
        <?php
            $params = array('controller'=>'events', 'data'=>$event['Group'], 'event_id' => $event_id, 'popup' => 'y');
            echo $this->element('events/event_groups_detail', $params);
        ?>
        </td>
    </tr>
</table>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
        <td width="45%">
            <table width="403" border="0" cellspacing="0" cellpadding="4">
                <tr>
                    <td colspan="2"><?php echo $html->link(__('Edit this Event', true), '/events/edit/'.$event['Event']['id']); ?> |
                    <?php echo $html->link(__('Back to Event Listing', true), '/events/index/'.$course_id); ?></td>
                </tr>
            </table>
        </td>
        <td></td>
    </tr>
</table>
</form>
