<!-- elements::ajax_survey_result_list end -->
<?php /*debug($info);*/ ?>
<?php echo $javascript->link('search.js');?>
<?php echo $this->element("list/ajaxList", array ("paramsForList" =>$paramsForList)); ?>
<!--<?php echo $this->Paginator->numbers(array('before' => '<span class="numbers">Go to Page: ', 'after' => '</span>')); ?>
<div id="ajax_update">
    <table width="95%"  border="0" cellspacing="2" cellpadding="4">
        <tr>
            <td align="right">
            </td>
        </tr>
    </table>
    <?php if(isset($info)):?>
	<table width="100%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
		<tr style="background-color:#CCCCDD;">
	        <td colspan="3" align="center">
	            <table cellspacing="0" cellpadding="0" width="100%"><tr><td align="left"><?php echo $data['Event']['title']; ?></td>
	                <td align="right"><?php echo $this->Html->link(__('View Summary', true), '/evaluations/viewSurveySummary/'.$data['Event']['template_id'], 
                                                     array('onClick' => "wopen(this.href, 'popup', 650, 500); return false;"))?></td>
                </tr>
                </table>
            </td>
	    </tr>
	    <tr class="tableheader">
	        <th width="40%"><?php echo $this->Paginator->sort(__('Full Name', true), 'full_name'); ?></th>
	        <th width="20%"><?php echo $this->Paginator->sort(__('Student Number', true), 'student_no'); ?></th>
	        <th width="40%"><?php echo __('Date Submitted', true); ?></th>
	    </tr>
	    <?php foreach ($info as $student):?>
	    <!-- for finding if a submission for the event exists -->
            <?php $date_submitted = 'Not Submitted'; ?>
            <?php foreach ($student['Submission'] as $submission) {
                if ($submission['event_id'] == $eventId) {
                    $date_submitted = $submission['date_submitted'];
                } else {
                    $date_submitted = 'Not Submitted';
                }
            } ?>
            
        <tr>
            <td><?php echo $student['User']['full_name']; ?> </td>
            <td align="center"><?php echo $this->Html->link($student['User']['student_no'], '/users/view/'.$student['User']['student_no']); ?> </td>
            <?php if ('Not Submitted' == $date_submitted) {?>
                <td><?php echo $date_submitted; ?> </td>
            <?php } else { ?>
                <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($date_submitted))) ?> </td>
            <?php } ?>
            
        </tr>
        <?php endforeach; ?>

    </table>
    <?php else:?>
        <?php __('No survey available.')?>
    <?php endif;?>
</div>-->
 <!--<?php echo $this->Paginator->prev(__('<<Prev', true), array('class' => 'PrevPg'), null, array('class' => 'PrevPg DisabledPgLk'))?>-->
<!--<span class='counter'><?php echo $this->Paginator->counter(array(
	'format' => 'Total Results: {:count}',
)); ?></span>
<?php echo $this->Paginator->numbers(array('before' => '<span class="numbers">Go to Page: ', 'after' => '</span>')); ?>-->
<!--<?php echo $this->Paginator->next(__('Next>>', true), array('class' => 'NextPg'), null, array('class' => 'NextPg DisabledPgLk'))?>-->
<!-- elements::ajax_survey_result_list end -->