<?php if (!isset($this->params['form']['submit'])) { ?>
    <div id='exportEvalCanvas'>
    <h2><?php echo 'Push Grades for ' . $eventName . ' to Canvas'; ?></h2>
    <ul class="bulleted-indent-list">
        <li><?php echo __("Press the \"Export\" button below to push the results for this evaluation to Canvas.", true)?></li>
        <span class="pre-wrap">
            <?php echo __("When you do this, ", true) ?><span class="red-highlight"><?php echo __("two things will happen", true)?></span>:
        </span>
        <ol class="dec-indent-list">
            <li><?php echo __("An assignment will be created on the <span class=\"highlight\">Assignments page</span> in the associated Canvas course for this Evaluation.", true)?></li>
            <li><?php echo __("In the Canvas gradebook, the assignment is set with <span class=\"highlight\">Manually Post Grades Policy</span>. Please use the 'Post grades' function in Canvas to <a href='https://faculty.canvas.ubc.ca/showing-and-hiding-grades-in-the-new-gradebook/#Canvas:NewGradebookCommunication(Draft)-Postinggradesforanassignment' target='_blank'>make grades visible to students</a>.", true)?></li>
        </ol>
    </ul>
    <br><br><?php
    echo $this->Form->create('ExportEvalCanvas', array('url' => '/'.$this->params['url']['url']));
    echo $this->Form->end(array('label' => 'Export', 'name' => 'submit'));
}
else if ($canvasProgressId) { ?>

<div class="progressbar-wrapper">
    <div class="progressbar" id="export-progressbar"></div>
</div>

<h2 id="export-heading">Export in progress...</h2>
<div id="export-report" style="display:none;"><?php

    if (count($exportReportDetails)) { ?>

        <h2>Export completed, but there were some complications.</h2>
        <a href="#" id="export-report-open">See details</a>
        <div id="export-report-details" style="display:none;">
            <p>Export details:</p>
            <ul>
                <li><?php echo implode('</li><li>', $exportReportDetails); ?></li>
            </ul>
        </div>

    <?php } else { ?>

        <h2>Export completed successfully.</h2>

    <?php } ?>
</div>
<br><br>

<script type="text/javascript">

    jQuery(document).ready(function(){

        // make the progressbar come alive
        updateProgressbar();

        // show export details when clicking on the link
        jQuery('#export-report-open').click(function(){
            jQuery(this).hide();
            jQuery('#export-report-details').slideDown();
        });
    });

    function updateProgressbar() {
        jQuery.get('<?php echo Router::url(null, false) . '/' . $canvasProgressId; ?>', function( data ) {
            jQuery('#export-progressbar').css('width', data.completion + '%');
            if (data.workflow_state == 'queued' || data.workflow_state == 'running') {
                setTimeout(updateProgressbar, 500);
            }
            else if (data.workflow_state == 'failed') {
                jQuery('#export-progressbar').css('background', 'red');
                jQuery('#export-heading').html('Export failed');
            }
            else if (data.workflow_state == 'completed') {
                jQuery('#export-progressbar').css('background', 'green');
                jQuery('#export-heading').html('Export successful').hide();
                jQuery('#export-report').slideDown();
            }
        });
    }

</script>

<?php } else { ?>
    <div id="export-report">
        <h2>Export failed.</h2>
        <?php if (count($exportReportDetails)) { ?>
            <div id="export-report-details">
                <p>Export details:</p>
                <ul>
                    <li><?php echo implode('</li><li>', $exportReportDetails); ?></li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<br><br>
<div>
   <?php echo $html->link(__('Back to Evaluation Result', true), '/evaluations/view/'.$eventIdBack); ?>
   <?php
    if (isset($canvasCourseUrl)) {
        echo '&nbsp;|&nbsp;';
        echo $html->link(__('Jump to Canvas course', true), $canvasCourseUrl, array('target'=>'_blank', 'escape'=>false, 'class'=>'external-link'));
    }
   ?>
</div>
