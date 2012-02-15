<form name="frm" id="frm" method="POST" action="">
<input type="hidden" id="search_type" name="search_type" value="<?php echo $display?>"/>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td><div align="left" id="criteria_panel">
        <table width="60%" border="0" align="center" cellpadding="4" cellspacing="2">

          <tr class="searchtable2">
            <td colspan="3" height="25" align="center"><?php __('Evaluation Result Search Panel')?></td>
          </tr>
         <tr class="tablecell2">
            <td width="186" id="course_label"><?php __('Course')?>:</td>
            <td width="437">
            <?php
                $params = array('controller'=>'searchs', 'coursesList'=>$coursesList, 'courseId'=>null, 'defaultOpt'=>'A','sticky_course_id'=>$sticky['course_id']);
                echo $this->element('courses/course_selection_box', $params);
                ?></td>
            <td width="201" id="course_msg" class="error">&nbsp;</td>
          </tr>
         <tr class="tablecell2">
            <td id="event_label"><?php __('Event')?>:</td>
            <td width="440"> <?php echo $ajax->observeField('course_id', array('update'=>'event_box', 'url'=>"/searchs/eventBoxSearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"));?>
            <div name="event_box" id="event_box">
              <?php
                $params = array('controller'=>'events', 'data'=>$data, 'eventId'=>null, 'defaultOpt'=>'A', 'view'=>0, 'disabled'=>0,'sticky_event_id'=>$sticky['event_id']);

                echo $this->element('events/event_selection_box', $params);
              ?></div></td>

            <td width="201" id="event_msg" class="error">&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td width="100" id="status_label"><?php __('Status')?>:</td>
            <td width="200">
              <select name="status" id="status">
                <option value="A"><?php __('--- None ---')?></option>
            		<option value="listNotReviewed" <?php if ($sticky['status']=="listNotReviewed") echo 'selected'?>><?php __('List Not Reviewed Evaluations')?></option>
            		<option value="late" <?php if($sticky['status']=="late") echo 'selected'?>><?php __('List Late Evaluations')?> </option>
            		<option value="low" <?php if($sticky['status']=="low") echo 'selected'?>><?php __('List Ranged Mark Evaluations (set mark range below)')?></option>
             	</select>
              <script>
                $('status').observe('change', function(event) {
                  if('low' == $('status').value) {
                    $('mark-range').show(); 
                  } else {
                    $('mark-range').hide();
                  }
                });
              </script>
            </td>
            <td width="100" id="status_msg" class="error" >&nbsp;</td>
          </tr>
          <tr class="tablecell2" id='mark-range' <?php echo 'low' != $sticky['status'] ? 'style="display:none;"' : ''?>>
            <td id="resultmark_label"><?php __('Result Mark(%)')?>:</td>
            <td><table width="100%"><tr align="left">
          				<td width="10%"><?php __('FROM')?>:</td>
          				<td width="40%">
                		<?php echo $form->input('Search/mark_from', array('size'=>'50','class'=>'input', 'style'=>'width:75%;','value'=>$sticky['mark_from'])) ?>&nbsp;&nbsp;&nbsp;
                	</td>
                	<td width="10%">&nbsp;&nbsp;<?php __('TO')?>:</td>
                	<td width="40%">
                		<?php echo $form->input('Search/mark_to', array('size'=>'50','class'=>'input', 'style'=>'width:75%;','value'=>$sticky['mark_to'])) ?>&nbsp;&nbsp;&nbsp;
                	</td>
            	  </tr>
            	  </table>
            </td>
            <td id="resultmark_msg" class="error">&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td colspan="3"><div align="center"><?php echo $form->submit('Search',array('url'=>'/searchs/SearchResult')) ?>
        	  <input type="reset" name="Reset" value="Reset" onClick="">
            </div></td>
          </tr>
        </table>
        <table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
          <tr>
            <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'left','alt'=>'left'))?></td>
            <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'right','alt'=>'right'))?></td>
          </tr>
        </table>
        </div>
      </td>
    </tr>
  </table>
<?php
$params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'display'=>'search');
echo $this->element('evaluations/ajax_evaluation_result_list', $params);
?>
</form>
