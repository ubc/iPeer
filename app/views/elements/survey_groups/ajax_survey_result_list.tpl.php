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
    <?php if(isset($data[0])):?>
	<table width="65%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
	  <tr style="background-color:#CCCCDD;">
	    <td colspan="3" align="center">
	    <table cellspacing="0" cellpadding="0" width="100%"><tr><td align="left"><?php echo $data[0]['Survey']['name']; ?></td>
	    <td align="right"><a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/viewSurveySummary/'.$data[0]['Survey']['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;">View Summary</a></td></tr></table></td>
	  </tr>
	  <tr class="tableheader">
	    <th>Student Name</th>
	    <th>Student Number</th>
	    <th>Time Submitted</th>
	  </tr>
  	<?php $i = 0;?>
	  <?php
	  for ($j=0; $j < count($data['User']); $j++): $student = $data['User'][$j]['User']; ?>
	  <tr class="tablecell">
	    <td align="center" width="50%">
	      <?php
	      if (count($data[0]['User']) == 0) {
	        echo $student['last_name'].", ".$student['first_name'];
	      } else {
          for ($k=0; $k < count($data[0]['User']); $k++) {
		            if (in_array($student['id'],$data[0]['User'][$k])) { ?>
		    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/viewEvaluationResults/'.$data[0]['eventId']."/".$student['id']?>" onclick="wopen(this.href, 'popup', 650, 500); return false;"><?php  echo $student['last_name'].", ".$student['first_name']?></a>
		    <?php
		              break;
		            } elseif ($k >= count($data[0]['User'])-1)
		               echo $student['last_name'].", ".$student['first_name'];
		       }
	      }
		    ?>
	    </td>
	    <td align="center">
		    <a href="<?php echo $this->webroot.$this->themeWeb.'users/view/'.$student['id']?>"><?php echo $student['student_no'] ?></a>
	    </td>
		  <td align="center">
		    <?php
		    if (count($data[0]['User']) == 0) {
          echo "Not submitted.";
		    } else {
		      for ($k=0; $k < count($data[0]['User']); $k++) {
            if (in_array($student['id'],$data[0]['User'][$k]) ) {
              echo $this->controller->Output->formatDate($data[0]['User'][$k]['time']);
              break;
            } elseif ($k >= count($data[0]['User'])-1)
              echo "Not submitted.";
  		    }
		    }
		    ?>
	    </td>
	  </tr>
	  <?php $i++;?>
	  <?php endfor; ?>
	  <tr><td colspan="3">
	  <?php $pagination->loadingId = 'loading2';?>
<?php if($pagination->set($paging)):?>
	<div id="page-numbers">
<table width="95%"  border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="33%"><?php echo $pagination->result('Results: ')?></td>
    <td width="33%"></td>
    <td width="33%" align="right">
		<?php echo $pagination->prev('Prev')?>
		<?php echo $pagination->numbers()?>
		<?php echo $pagination->next('Next')?>
	</td>
  </tr>
</table>
	</div>
<?php endif;?>
    </td></tr>
  </table>
  <?php else:?>
    No survey available.
  <?php endif;?>
</div>
<!-- elements::ajax_survey_result_list end -->
