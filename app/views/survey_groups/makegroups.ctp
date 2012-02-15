<?php if (!empty($surveys)): ?>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
    <td align="center">
    <form id="searchForm" action="">
    <center>
    <!-- Ensure that there's at least on Suevey to choose from -->
        <h3><?php __('Please Select a Survey to make Groups')?>: </h3>
        <?php echo $this->Form->select('survey_select', $surveys, null, array('empty' => __('Select a survey', true)));?>
    </center>
    <br /> <br />
    <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_makegroups_table', 'url'=>"makegroupssearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>  </form>
    <div id='survey_makegroups_table'></div>
    </td>
    </tr>
    </table>
<?php else: ?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"><tr><td>
    <center><h3><?php __(' No Surveys found for this course!')?></h3>
    <?php __('Please create a survey first, and have your students complete it.')?>
    <br /><br />
    <input type="button" value="<?php __('Go Back')?>" onClick="javascript:window.history.back()">
</td></tr></table>
<?php endif; // if (count($data) > 0)?>
