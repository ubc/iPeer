<div style="text-align: center;">
<?php if (!empty($events)): ?>
    <form id="searchForm" action="">
        <h3><?php __('Please Select a survey event to make groups')?>: </h3>
        <?php echo $this->Form->select('event_select', $events, null, array('empty' => __('Select a survey', true)));?>
    <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_makegroups_table', 'url'=>"makegroupssearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>  </form>
    <br /><br />
    <div id='survey_makegroups_table'></div>
<?php else: ?>
    <h3><?php __(' No survey event found for this course!')?></h3>
    <?php __('Please create a survey first, and have your students complete it.')?>
    <br /><br />
    <input type="button" value="<?php __('Go Back')?>" onClick="javascript:window.history.back()">
</td></tr></table>
<?php endif;?>
</div>
