<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['Survey']['id'])?'add':'edit') ?>">
      <h4><?php echo empty($params['data']['Survey']['id']) ? null : $html->hidden('Survey/id'); ?></h4>
      <?php echo empty($params['data']['Survey']['id']) ? $html->hidden('Survey/creator_id', array('value'=>$rdAuth->id)) : $html->hidden('Survey/updater_id', array('value'=>$rdAuth->id)); ?>
	  <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2">
        <tr class="tableheader">
          <td colspan="3" align="center"><?php echo empty($params['data']['Survey']['id'])?'Add':'Edit' ?> Survey </td>
          </tr>
        <tr class="tablecell2">
          <td width="13%">Survey Title:<font color="red">*</font></td>
          <td width="52%"><input type="hidden" name="assigned" id="assigned"/>
            <input type="text" name="name" id="name" class="input" value="<?php echo empty($params['data']['Survey']['name'])? '' : $params['data']['Survey']['name'] ?>" style="width:250px;">
            <div id="surveyErr">
              <?php
              $fieldValue = isset($this->params['form']['name'])? $this->params['form']['name'] : '';
              $params = array('controller'=>'surveys', 'data'=>null, 'fieldvalue'=>$fieldValue);
            	echo $this->renderElement('surveys/ajax_survey_validate', $params);
              ?>
            </div>
            <?php echo $ajax->observeField('name', array('update'=>'surveyErr', 'url'=>"/surveys/checkDuplicateName", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")) ?> </td>
          <td width="35%"> i.e. "CS100 Experience" </td>
        </tr>
        <tr class="tablecell2">
          <td>Assigned Course:<font color="red">*</font></td>
          <td><?php
              if (!isset($course_id)&&empty($course_id)) {
                $params = array('controller'=>'surveys', 'courseList'=>$courseList, 'courseId'=>null, 'defaultOpt'=>1);
              } else {
                $params = array('controller'=>'surveys', 'courseList'=>$courseList, 'courseId'=>$course_id);
              }
              echo $this->renderElement('courses/course_selection_box', $params);
        ?></td>
          <td>&nbsp;</td>
        </tr>
        <tr class="tablecell2">
          <td>Due Date:<font color="red">*</font></td>
          <td><?php echo $html->input('Survey/due_date', array('size'=>'25','class'=>'input')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal1.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a></td>
          <td> eg. YYYY-MM-DD HH:MM:SS (24 HOUR) </td>
        </tr>
        <tr class="tablecell2">
          <td valign="top">Release Date:<font color="red">*</font></td>
          <td><table width="95" border="0" cellspacing="2" cellpadding="4">
            <tr>
              <td width="12%">From:</td>
              <td nowrap><?php echo $html->input('Survey/release_date_begin', array('size'=>'25','class'=>'input')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal2.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a></td>
            </tr>
            <tr>
              <td width="12%">To:</td>
              <td nowrap><?php echo $html->input('Survey/release_date_end', array('size'=>'25','class'=>'input')) ?>&nbsp;&nbsp;&nbsp;<a href="javascript:cal3.popup(null,null,'<?php echo preg_replace('/app\/webroot/', '', dirname($_SERVER['PHP_SELF'])); ?>');"><?php echo $html->image('icons/cal.gif',array('align'=>'middle', 'border'=>'0','alt'=>'cal'))?></a></td>
            </tr>
          </table>            </td>
          <td valign="top"> eg. YYYY-MM-DD HH:MM:SS (24 HOUR) </td>
        </tr>
        <tr class="tablecell2">
          <td colspan="3" align="center">
        <input type="button" name="Back" value="Back" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
		  </td>
          </tr>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
        <tr>
          <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'middle','alt'=>'corner_bot_left'))?></td>
          <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'middle','alt'=>'corner_bot_right'))?></td>
        </tr>
      </table>
    </form>
	</td>
  </tr>
</table>

<?php echo $javascript->link('calendar1')?>
<script type="text/javascript">
<!--

// create calendar object(s) just after form tag closed
// specify form element as the only parameter (document.forms['formname'].elements['inputname']);
// note: you can have as many calendar objects as you need for your application

var cal1 = new calendar1(document.forms[0].elements['data[Survey][due_date]']);
cal1.year_scroll = false;
cal1.time_comp = true;

var cal2 = new calendar1(document.forms[0].elements['data[Survey][release_date_begin]']);
cal2.year_scroll = false;
cal2.time_comp = true;

var cal3 = new calendar1(document.forms[0].elements['data[Survey][release_date_end]']);
cal3.year_scroll = false;
cal3.time_comp = true;

//-->
</script>