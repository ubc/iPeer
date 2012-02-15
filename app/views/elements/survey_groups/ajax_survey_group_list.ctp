<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td><div align="right"><?php echo $pagination->show('Show ',null,'survey_groupset_table')?></div></td>
      </tr>
  </table>
  <table width="95%" border="0" cellspacing="2" cellpadding="4">
      <tr class="tableheader">
        <th width="10%">Actions</th>
        <th width="20%"><?php echo $pagination->sortLink('Survey',array('name','desc'))?></th>
        <th width="20%"><?php echo $pagination->sortLink('Number of Groups',array('course_id','desc'))?></th>
        <th width="8%"><?php echo $pagination->sortLink('Released?',array('released','desc'))?></th>
        <th width="10%"><?php __('Release Now')?></th>
      </tr>
      <?php if(isset($data) && $data != null):?>
      <?php foreach ($data as $row): $survey = $row['SurveyGroupSet']; ?>
      <tr class="tablecell">
        <td align="center"> <a href="<?php echo $this->webroot.$this->theme.'/surveys/questionssummary/'.$survey['survey_id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>__('View', true)))?></a>
          <?php if($this->Auth->user('role') == 'A' || $this->Auth->user('role') == 'I'):?>
          <a href="<?php echo $this->webroot.$this->theme.'surveygroups/editgroupset/'.$survey['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>__('Edit', true)))?></a> <a href="<?php echo $this->webroot.$this->theme.'surveygroups/deletesurveygroupset/'.$survey['id']?>" onclick="return confirm('<?php __('Are you sure you want to delete survey group set')?> &ldquo;<?php echo $survey['set_description']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>__('Delete', true)))?></a>
          <?php endif;?></td>
        <td><?php echo $html->link($survey['set_description'], '/surveys/questionssummary/'.$survey['survey_id']) ?></td>
        <td align="center"><?php echo $survey['num_groups']?></td>
        <td align="center">
		<?php
		if($survey['released'])
			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
		else
			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
	 ?></td>
        <td align="center">
		<?php
			if( !$survey['released'] )
				echo '<a href='.$this->webroot.$this->theme.'surveygroups/releasesurveygroupset/'.$survey['id'].'>'.$html->image('layout/yellow_arrow.gif',array('border'=>'0','alt'=>'yellow_arrow'), __('Confirm Survey Release', true).': \"'. $survey['set_description'] .'\"?').'</a>';
			else
				echo '-';
		?>
		</td>
      </tr>
      <?php endforeach; ?>
      <?php endif;?>
    </table>
    <table width="95%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
      </tr>
  </table>
	<?php $pagination->loadingId = 'loading2';?>
	<?php if($pagination->set($paging)):?>
	<div id="page-numbers">
    <table width="95%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="33%" align="left"><?php echo $pagination->result(__('Results: ', true))?></td>
        <td width="33%"></td>
        <td width="33%" align="right"> <?php echo $pagination->prev(__('Prev', true))?> <?php echo $pagination->numbers()?> <?php echo $pagination->next(__('Next', true))?> </td>
      </tr>
    </table>
</div>
<?php endif;?>
</div>
<!-- elements::ajax_user_list end -->
