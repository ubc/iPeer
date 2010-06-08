<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
  <table width="95%" border="0" cellspacing="2" cellpadding="4">
	  <tr>
	    <td colspan="5">
      <?php
      echo $this->renderElement('evaltools/tools_menu', array());
      ?>
	    </td>
	    <td align="right" colspan="3"><div align="right"><?php echo $pagination->show('Show ',null,'survey_table')?></div></td>
	  </tr>
      <tr class="tableheader">
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
        <th width="13%">Actions</th>
	    <?php endif;?>
        <th width="17%"><?php echo $pagination->sortLink('Survey',array('name','desc'))?></th>
        <th width="17%"><?php echo $pagination->sortLink('Course',array('course_id','desc'))?></th>
        <th width="14%"><?php echo $pagination->sortLink('Creator',array('user_id','desc'))?></th>
        <th width="14%"><?php echo $pagination->sortLink('Due Date',array('due_date','desc'))?></th>
        <th width="8%"><?php echo $pagination->sortLink('Released?',array('released','desc'))?></th>
        <!--th width="10%">Release Now</th-->
      </tr>
      <?php
      $i = '0';
      if (isset($data)&&is_array($data)&&!empty($data)) {
        foreach ($data as $row): $survey = $row['Survey']; ?>
        <tr class="tablecell">
          <td align="center">
            <a href="<?php echo $this->webroot.$this->themeWeb.'surveys/view/'.$survey['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
            <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
      	      <?php if ($rdAuth->id == $survey['creator_id'] or ($rdAuth->role=='A')): ?>
                <a href="<?php echo $this->webroot.$this->themeWeb.'surveys/edit/'.$survey['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
      	      <?php else: ?>
      	        <?php echo $html->image('icons/editdisabled.gif',array('border'=>'0','alt'=>'Edit'))?>
      	      <?php endif; ?>
      	      <?php if ($rdAuth->id == $survey['creator_id'] or ($rdAuth->role=='A')): ?>
        	      <a href="<?php echo $this->webroot.$this->themeWeb.'surveys/delete/'.$survey['id']?>" onclick="return confirm('All associating events and evaluation data will be deleted as well.\n Are you sure you want to delete survey &ldquo;<?php echo $survey['name']?>&rdquo;?')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
      	      <?php else: ?>
      	        <?php echo $html->image('icons/deletedisabled.gif',array('border'=>'0','alt'=>'Delete'))?>
      	      <?php endif; ?>
      	      <a href="<?php echo $this->webroot.$this->themeWeb.'surveys/copy/'.$survey['id']?>"><?php echo $html->image('icons/copy.gif',array('border'=>'0','alt'=>'Copy'))?></a>

            <?php endif;?>
          </td>
          <td align="left"><?php echo $html->link($survey['name'], '/surveys/questionssummary/'.$survey['id']) ?></td>
          <td align="left"><?php echo $survey['course_id'] == '-1' ? 'N/A':$sysContainer->getCourseName($survey['course_id'])//echo $row['Course']['course']  ?></td>
          <td align="left"><?php echo $html->link($sysContainer->getUserInfo($survey['creator_id']), '/users/view/'.$survey['creator_id']) ?></td>
          <td align="center"> <?php
              if (!empty($survey['due_date'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($survey['due_date'])));
          ?></td>
          <td align="center">
  		<?php
  		//if($survey['released'])
  		if (strtotime($survey['release_date_begin']) <= time() && strtotime($survey['release_date_end']) >= time())
  			echo $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check'));
  		else
  			echo $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));
  	 ?></td>
          <!--td align="center">
  		<?php
  			if( !$survey['released'] )
  				echo '<a href='.$this->webroot.$this->themeWeb.'surveys/releaseSurvey/'.$survey['id'].'>'.$html->image('layout/yellow_arrow.gif',array('border'=>'0','alt'=>'yellow_arrow'), 'Confirm Survey Release: \"'. $survey['name'] .'\"?').'</a>';
  			else
  				echo '-';
  		?>
  		</td-->
        </tr>
        	<?php $i++;?>
        <?php endforeach;
      }?>
  <?php if ($i == 0) :?>
	<tr class="tablecell" align="center">
	    <td colspan="8">Record Not Found</td>
  </tr>
  <?php endif;?>
    </table>
    <table width="95%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
      </tr>
  </table>
	<?php $pagination->loadingId = 'loading2';?>

	<div id="page-numbers">
    <table width="95%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="33%" align="left"><?php echo $pagination->result('Results: ')?></td>
        <td width="33%"></td>
        <td width="33%" align="right">
	<?php if($pagination->set($paging)):?>
          <?php echo $pagination->prev('Prev')?>
          <?php echo $pagination->numbers()?>
          <?php echo $pagination->next('Next')?> </td>
   <?php endif;?>
      </tr>
    </table>
</div>

</div>
<!-- elements::ajax_user_list end -->
