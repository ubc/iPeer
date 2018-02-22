<?php if (!isset($this->params['form']['submit'])) { ?>
    <div id='exportEvalCanvas'>
    <h2><?php echo 'Push Grades for ' . $eventName . ' to Canvas'; ?></h2>
    <ul class="bulleted-list">
        <li><?php echo __("Press the \"Export\" button below to push grades for this evaluation to Canvas.", true)?></li>
        <li><?php echo __("If you have not yet pushed the grades, an assignment will be created in the associated Canvas course for this Evaluation.", true)?></li>
        <li><?php echo __("The assignment will be muted, so students will NOT see the grades until you manually unmute the assignment in Canvas.", true)?></li>
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
        jQuery.get('<?php echo '/'.$this->params['url']['url'] . '/' . $canvasProgressId; ?>', function( data ) {
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