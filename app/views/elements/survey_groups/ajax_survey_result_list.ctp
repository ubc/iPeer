<!-- elements::ajax_survey_result_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
<?php
    if (isset($pagination->params['pass']['0'])) {
    $count=0;
    if (isset($data[$count]['eventId'])) {
        foreach ($data as $piece) {
            if ($pagination->params['pass']['0'] == $data[$count]['eventId']) {
            $data[0] = $data[$count];
            continue;
            }
            $count++;
            }
        }
    }
?>
	<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right"><?php echo $pagination->show('Show ',null,'survey_result_table'); ?>
        </div></td>
      </tr>
    </table>
    <?php if(isset($data)):?>
	<table width="65%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr style="background-color:#CCCCDD;">
	    <td colspan="3" align="center">
	    <table cellspacing="0" cellpadding="0" width="100%"><tr><td align="left"><?php echo $data['Survey']['name']; ?></td>
	    <td align="right"><?php echo $this->Html->link(__('View Summary', true), '/evaluations/viewSurveySummary/'.$data['Survey']['id'], 
                                                     array('onClick' => "wopen(this.href, 'popup', 650, 500); return false;"))?></td>
      </tr>
      </table>
      </td>
	  </tr>
	  <tr class="tableheader">
	    <th><?php __('Student Name')?></th>
	    <th><?php __('Student Number')?></th>
	    <th><?php __('Time Submitted')?></th>
	  </tr>
	  <?php foreach ($data['Course']['Enrol'] as $student):?>
	  <tr class="tablecell">
	    <td align="center" width="50%">
	      <?php echo empty($student['date_submitted']) ? $student['full_name'] :
                    $this->Html->link($student['full_name'], '/evaluations/viewEvaluationResults/'.$data['Event'][0]['id']."/".$student['id'], 
                                     array('onClick' => "wopen(this.href, 'popup', 650, 500); return false;"));?>
	    </td>

	    <td align="center">
		    <?php echo $this->Html->link($student['student_no'], '/users/view/'.$student['id'])?>
	    </td>

		  <td align="center">
		    <?php echo empty($student['date_submitted']) ? __('Not submitted', true) : Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($student['date_submitted'])))?>
	    </td>
	  </tr>
	  <?php endforeach; ?>
	  <tr><td colspan="3">
	  <?php $pagination->loadingId = 'loading2';?>

<?php if($pagination->set($paging)):?>
	<div id="page-numbers">
<table width="95%"  border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="33%"><?php echo $pagination->result(__('Results: ', true))?></td>
    <td width="33%"></td>
    <td width="33%" align="right">
		<?php echo $pagination->prev(__('Prev', true))?>
		<?php echo $pagination->numbers()?>
		<?php echo $pagination->next(__('Next', true))?>
	</td>
  </tr>
</table>
	</div>
<?php endif;?>

    </td></tr>
  </table>
  <?php else:?>
    <?php __('No survey available.')?>
  <?php endif;?>
</div>
<!-- elements::ajax_survey_result_list end -->
