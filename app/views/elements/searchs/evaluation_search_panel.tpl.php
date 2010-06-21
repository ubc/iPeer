<?php echo $javascript->link('calendar_us')?>
<form name="frm" id="frm" method="POST" action="">
<input type="hidden" id="search_type" name="search_type" value="<?php echo $display?>"/>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="80%"><div align="left" id="criteria_panel">
        <table width="60%" border="0" align="center" cellpadding="4" cellspacing="2">

          <tr class="searchtable1">
            <td height="25" colspan="3" align="center">Evaluation Event Search Panel</td>
          </tr>
         <tr class="tablecell2">
            <td width="186" id="course_label">Course:</td>
            <td width="437"><?php
                $params = array('controller'=>'searchs', 'courseList'=>$courseList, 'courseId'=>null, 'defaultOpt'=>'A','sticky_course_id'=>(isset($sticky['course_id'])? $sticky['course_id']:null));
                echo $this->renderElement('courses/course_selection_box', $params);
                ?></td>
            <td width="201" id="course_msg" class="error">&nbsp;</td>
          </tr><tr class="tablecell2">
            <td width="100" id="duedate_label">Due Date:</td>
            <td id="duedate">
            	  <table width="100%"><tr align="left">
          				<td width="10%" align="left">FROM:</td>
          				<td width="40%" align="left">
<script type="text/javascript">
<!--
var calDueDateB = new tcal ({
		'formname': 'frm',
		'controlname': 'data[Search][due_date_begin]'
	});
	
	// individual template parameters can be modified via the calendar variable
	calDueDateB.a_tpl.yearscroll = false;
	calDueDateB.a_tpl.weekstart = 1;
//-->
</script>
                		<?php echo $html->input('Search/due_date_begin', array('size'=>'50','class'=>'input', 'style'=>'width:75%;','value'=>(isset($sticky['due_date_begin']))? $sticky['due_date_begin']:'')) ?>
                	</td>

                	<td width="10%" align="left">&nbsp;&nbsp;TO:</td>
                	<td width="40%" align="left">
                		<?php echo $html->input('Search/due_date_end', array('size'=>'50','class'=>'input', 'style'=>'width:75%;','value'=>(isset($sticky['due_date_end']))? $sticky['due_date_end']:'')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:calDueDateE.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'left','alt'=>'cal', 'border'=>'0'))?></a>
                	</td>
            	  </tr>
            	  </table>
            </td>
            <td width="100" id="duedate_msg" class="error" >&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td id="releasedate_label">Release Date:</td>
            <td id="releasedate">
            	  <table width="100%"><tr align="left">
          				<td width="10%" align="left">FROM:</td>
          				<td width="40%" align="left">
                		<?php echo $html->input('Search/release_date_begin', array('size'=>'50','class'=>'input', 'style'=>'width:75%;','value'=>(isset($sticky['release_date_begin']))? $sticky['release_date_begin']:'')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:releaseDateB.popup();"><?php echo $html->image('icons/cal.gif',array('align'=>'left', 'border'=>'0', 'alt'=>'cal'))?></a>
                	</td>
                	<td width="10%" align="left">&nbsp;&nbsp;TO:</td>
                	<td width="40%" align="left">
                		<?php echo $html->input('Search/release_date_end', array('size'=>'50','class'=>'input', 'style'=>'width:75%;','value'=>(isset($sticky['release_date_end']))? $sticky['release_date_end']:'')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:releaseDateE.popup();"><?php echo $html->image('icons/cal.gif',array('align'=>'left','alt'=>'cal', 'border'=>'0'))?></a>
                	</td>
            	  </tr>
            	  </table>
            </td>
            <td id="releasedate_msg" class="error">&nbsp;</td>
          </tr>
          <tr class="tablecell2">
            <td colspan="3"><div align="center"><?php echo $ajax->submit('Search',array('url'=>'/searchs/display','update'=>'search_table')) ?>
        	  <input type="reset" name="Reset" value="Reset">
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
</form>

<div id="search_result">
<?php
$params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'display'=>'search');
echo $this->renderElement('evaluations/ajax_evaluation_list', $params);
?>
</div>
<script type="text/javascript">
<!--

// create calendar object(s) just after form tag closed
// specify form element as the only parameter (document.forms['formname'].elements['inputname']);
// note: you can have as many calendar objects as you need for your application
/*var calDueDateB = new calendar1(document.forms['frm'].elements['data[Search][due_date_begin]']);
calDueDateB.year_scroll = false;
calDueDateB.time_comp = false;

var calDueDateE = new calendar1(document.forms['frm'].elements['data[Search][due_date_end]']);
calDueDateE.year_scroll = false;
calDueDateE.time_comp = false;


var releaseDateB = new calendar1(document.forms['frm'].elements['data[Search][release_date_begin]']);
releaseDateB.year_scroll = false;
releaseDateB.time_comp = false;

var releaseDateE = new calendar1(document.forms['frm'].elements['data[Search][release_date_end]']);
releaseDateE.year_scroll = false;
releaseDateE.time_comp = false;*/



/*var calDueDateB = new tcal ({
		'formname': 'frm',
		'controlname': 'data[Search][due_date_begin]'
	});
	
	// individual template parameters can be modified via the calendar variable
	calDueDateB.a_tpl.yearscroll = false;
	calDueDateB.a_tpl.weekstart = 1;
*/

//-->
</script>
