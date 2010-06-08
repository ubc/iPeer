<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
<form id="searchForm" action="">
<input type="hidden" name="id" value="<?php echo $id?>"/>
<table width="95%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="left" colspan="5"><?php echo $html->image('/icons/caution.gif', array('alt'=>'Due Date'));?>&nbsp;<b>Event Due:</b>&nbsp; <?php echo $this->controller->Output->formatDate($data['Event']['due_date']) ?></td>
  </tr>
  <tr>
    <td colspan="2" align="left">
     	<b>Filter:</b>
		  <select name="filter_select" onChange="javascript:show_hide_input(this);">
		    <option value="-1">None</option>
    		<option value="listUnreview">List Not Reviewed Evaluations</option>
    		<option value="late">List Late Evaluations </option>
    		<option value="low">List Low Mark Evaluations</option>
     	</select>
    </td>
    <td align="left">
      <input type="text" size="4" id="threshold" style="visibility:hidden;" name="threshold" value="<?php if(isset($maxPercent)) echo $maxPercent;?>"/>
    </td>
  </tr>
  <tr>
    <td valign="center" align="left"><a valign="center" href="<?php echo $this->webroot.$this->themeWeb;?>evaluations/export/"><?php echo $html->image('/icons/export_excel.gif', array('alt'=>'Export'));?>&nbsp;Export Evaluations&nbsp;</td>
    <td valign="bottom">
	    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/changeAllCommentRelease/'.$data['Event']['id'].';1'?>" >Release All Comments</a>
    </td>
    <td valign="bottom">
	    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/changeAllCommentRelease/'.$data['Event']['id'].';0'?>" >Unrelease All Comments</a>
    </td>
    <td valign="bottom">
	    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/changeAllGradeRelease/'.$data['Event']['id'].';1'?>" >Release All Grades</a>
    </td>
    <td valign="bottom">
	    <a href="<?php echo $this->webroot.$this->themeWeb.'evaluations/changeAllGradeRelease/'.$data['Event']['id'].';0'?>" >Unrelease All Grades</a>
    </td></tr>
</table>
</form>
 <?php echo $ajax->observeForm('searchForm', array('update'=>'eval_table', 'url'=>"/evaluations/viewsearch/", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>
<a name="list"></a>
<div id='eval_table'>
    <?php
    $params = array('controller'=>'evaluations', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
    echo $this->renderElement('evaluations/ajax_evaluation_result_list', $params);
    ?>
</div>
</td></tr>
<tr><td align="center">
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
      <tr>
			  <td colspan="3">
			    <?php echo $html->linkTo('Back to Evaluation Event Listing', '/evaluations/index/'); ?>
          <?php
              if (!empty($rdAuth->courseId)) {
                echo '&nbsp;|&nbsp;';
                echo $html->linkTo('Back to Course Home', '/courses/home/'.$rdAuth->courseId);
              }
        ?>
        </td>
      </tr></table>
    </td>
    <td width="55%">&nbsp; </td>
  </tr>
</table>
</td></tr>
</table>

