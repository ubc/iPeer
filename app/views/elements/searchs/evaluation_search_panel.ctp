<?php echo $html->script('datepicker');
?>
<form name="frm" id="frm" method="POST">
<input type="hidden" id="search_type" name="search_type" value="<?php echo $display?>"/>
<table border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="80%"><div align="left" id="criteria_panel">
        <table border="0" align="center" cellpadding="4" cellspacing="2">

          <tr class="searchtable1">
            <td height="25" colspan="3" align="center"><?php __('Evaluation Event Search Panel')?></td>
          </tr>
         <tr class="tablecell2">
            <td width="186" id="course_label"><?php __('Course')?>:</td>
            <td width="437"><?php
                $params = array('defaultOpt'=>'A');
                echo $this->element('courses/course_selection_box', $params);
                ?></td>
            <td width="201" id="course_msg" class="error">&nbsp;</td>
          </tr><tr class="tablecell2">
            <td width="186" id="event_type_label"><?php __('Event Type')?>:</td>
            <td width="437"><?php 
                echo $form->input('Search.event_type', array('type' => 'select', 'options' => $eventTypes, 'empty' => __('--- All Event Types ---', true),'label' => false));
                ?></td>
            <td width="201" id="event_type_msg" class="error">&nbsp;</td>
          </tr><tr class="tablecell2">
            <td width="100" id="duedate_label"><?php __('Due Date')?>:</td>
            <td id="duedate">
            	  <table width="100%"><tr align="left">
          				<td width="10%" align="left"><?php __('FROM')?>:</td>
          				<td width="40%" align="left">
                    <?php echo $form->input('Search.due_date_begin', array('size'=>'50','class'=>'input',  'label'=> false, 'style'=>'width:75%;','value'=>(isset($sticky['due_date_begin']))? $sticky['due_date_begin']:'')) ?>
                	</td>

                	<td width="10%" align="left">&nbsp;&nbsp;<?php __('TO')?>:</td>
                	<td width="40%" align="left">
                		<?php echo $form->input('Search.due_date_end', array('size'=>'50','class'=>'input', 'label'=> false, 'style'=>'width:75%;','value'=>(isset($sticky['due_date_end']))? $sticky['due_date_end']:'')) ?>
                	</td>
            	  </tr>
            	  </table>
            </td>
            <td width="100" id="duedate_msg" class="error" >&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td id="releasedate_label"><?php __('Release Date:')?></td>
            <td id="releasedate">
            	  <table width="100%"><tr align="left">
          				<td width="10%" align="left"><?php __('FROM')?>:</td>
          				<td width="40%" align="left">
                		<?php echo $form->input('Search.release_date_begin', array('size'=>'50', 'label'=> false, 'class'=>'input', 'style'=>'width:75%;','value'=>(isset($sticky['release_date_begin']))? $sticky['release_date_begin']:'')) ?>
                	</td>
                	<td width="10%" align="left">&nbsp;&nbsp;<?php __('TO')?>:</td>
                	<td width="40%" align="left">
                		<?php echo $form->input('Search.release_date_end', array('size'=>'50',  'label'=> false, 'class'=>'input', 'style'=>'width:75%;','value'=>(isset($sticky['release_date_end']))? $sticky['release_date_end']:'')) ?>
                	</td>
            	  </tr>
            	  </table>
                <?php __('specify a date range to search any event that are being released during that period of time.')?>
            </td>
            <td id="releasedate_msg" class="error">&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td colspan="3"><div align="center"><?php echo $form->submit('Search',array('url'=>'/searchs/searchEvaluation')) ?>
        	  <input type="button" name="Reset" value="Reset" onClick="window.location.href=window.location.href;">
            </div></td>
          </tr>
        </table>
        </div>
      </td>
    </tr>
  </table>
</form>

<div id="search_result">
<?php

$params = array('controller'=>'searchs', 'data'=>$data, 'display'=>'search', array('currentUser'=>$currentUser), array('names'=>$names));
//echo $this->element('evaluations/ajax_evaluation_list', array('currentUser'=>$currentUser));
echo $this->element('evaluations/ajax_evaluation_list', $params);
?>
</div>
<script type="text/javascript">
var calDueDateB = new DatePicker({
  relative:'SearchDueDateBegin',
  keepFieldEmpty:true,
  dateFormat: [ ["yyyy","mm","dd"], "-" ]
});
var calDueDateE = new DatePicker({
  relative:'SearchDueDateEnd',
  keepFieldEmpty:true,
  dateFormat: [ ["yyyy","mm","dd"], "-" ]
});
var releaseDateB = new DatePicker({
  relative:'SearchReleaseDateBegin',
  keepFieldEmpty:true,
  dateFormat: [ ["yyyy","mm","dd"], "-" ]
});
var releaseDateE = new DatePicker({
  relative:'SearchReleaseDateEnd',
  keepFieldEmpty:true,
  dateFormat: [ ["yyyy","mm","dd"], "-" ]
});
//-->
</script>
