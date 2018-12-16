<div id='Events'>
<?php
$html->script("jquery-ui-timepicker-addon", array("inline"=>false));

echo $this->Form->create('Event', array('action' => "add/$course_id"));
echo $this->Form->input('course_id', array('default' => $course_id));
echo $this->Form->input('title', array('label' => 'Event Title'));
echo "<div id='titleWarning' class='red'></div>";
echo $this->Form->input('description', array('type' => 'textarea'));
echo $this->Form->input('event_template_type_id');
echo $this->Form->input('SimpleEvaluation',
    array('div' => array('id' => 'SimpleEvalDiv'), 'label' => $html->link('Preview', '', array('id' => 'prevS', 'target' => '_blank'))));
echo $this->Form->input('Rubric',
    array('div' => array('id' => 'RubricDiv'), 'label' => $html->link('Preview', '', array('id' => 'prevR', 'target' => '_blank'))));
echo $this->Form->input('Survey',
    array('div' => array('id' => 'SurveyDiv'), 'label' => $html->link('Preview', '', array('id' => 'prevV', 'target' => '_blank'))));
echo $this->Form->input('Mixeval',
    array('div' => array('id' => 'MixevalDiv'), 'label' => $html->link('Preview', '', array('id' => 'prevM', 'target' => '_blank'))));
echo $this->Form->input(
    'self_eval',
    array(
        'legend' => 'Self-Evaluation',
        'type' => 'radio',
        'options' => array('1' => 'Enabled', '0' => 'Disabled'),
        'default' => '0'
    )
);
echo $this->Form->input(
    'com_req',
    array(
        'legend' => 'Comments Required',
        'type' => 'radio',
        'options' => array('1' => 'Enabled', '0' => 'Disabled'),
        'default' => '0'
    )
); ?>
<div class='help-text'><?php __("Doesn't apply to Mix Evaluation. Required questions are set in the template.") ?></div>
<?php echo $this->Form->input(
    'auto_release',
    array(
        'legend' => 'Auto-Release Results',
        'type' => 'radio',
        'options' => array('1' => 'Enabled', '0' => 'Disabled'),
        'default' => '0'
    )
);
echo $this->Form->input(
    'enable_details',
    array(
        'legend' => 'Student Result Mode',
        'type' => 'radio',
        'options' => array('0'=> 'Basic', '1' => 'Detailed'),
        'default' => '1'
    )
); ?>
<div class='help-text'><?php echo __('Basic view only shows grades. Detailed view shows both grades and comments') ?></div>
<?php
echo $this->Form->input('due_date', array('type' => 'text'));
echo $this->Form->input('release_date_begin', array('label' => 'Evaluation Released From', 'type' => 'text'));
echo $this->Form->input('release_date_end', array('label' => 'Until', 'type' => 'text'));
echo $this->Form->input('result_release_date_begin',
    array('div' => array('id' => 'ResultReleaseBeginDiv'), 'label' => 'Results Released From', 'type' => 'text'));
echo $this->Form->input('result_release_date_end',
    array('div' => array('id' => 'ResultReleaseEndDiv'), 'label' => 'Until', 'type' => 'text'));

echo $this->Form->input(
    'email_schedule',
    array(
        'label' => 'Email Reminder Frequency ',
        'options' => $emailSchedules,
        'div' => array('id' => 'emailSchedule'),
        'disabled' => !$reminder_enabled
    )
);
?>
<?php if (!$reminder_enabled): ?>
    <?php echo $this->Form->input('email_schedule', array('hidden' => true, 'type' => 'text', 'value' => 0, 'label' => false)); ?>
    <div class='email-help-text'><?php __('Email reminder feature is disabled in the system.') ?></div>
<?php else: ?>
<div class='email-help-text'><?php __('Select the number of days in between each email reminder for submitting
    evaluations. The first email is sent when the event is released.') ?></div>
<?php endif; ?>
<?php
echo $this->Form->input('EmailTemplate',
    array('div' => array('id' => 'EmailTemplateDiv'), 'label' => $html->link('Preview', '', array('id' => 'prevE', 'target' => '_blank'))));
?>
<div class='email-temp-help-text'><?php __('Select the preferred email template.') ?></div>
<?php
echo $this->Form->input('Group',
    array('div' => array('id' => 'GroupsDiv'), 'label' => 'Group(s)')); ?>
<div class='selectAll'>
<?php echo $this->Form->button('Select All', array('type' => 'button', 'id' => 'selectAll'));
echo $this->Form->button('Unselect All', array('type' => 'button', 'id' => 'unselectAll'));?>
</div>
<div class='help-text'><?php __('Holding "ctrl" or "command" key to select multiple groups.') ?></div>

<?php
// No nice way of inserting new penalty entries using CakePHP, doing it
// manually.
echo "<div id='penaltyInputs'>";
echo $this->Form->label(
    'latep',
    'Late Penalties',
    array('class' => 'penaltyLabel')
);
// Keep track of the number of penalties entered. This is mostly for smart
// 'resume' where the user encounters an error during form submit and we need
// to preserve the data already entered. Initially, there should only be one
// penalty field.
$numPenalties = 0;
// If the user encountered an error and had previously entered more than one
// penalties, then we need to restore those fields.
if (isset($this->data) && isset($this->data['Penalty'])) {
    $numPenalties = sizeof($this->data['Penalty']);
}
// Write out the field, start at -1 since we're using one of the passes
// to generate a template for javascript
$percent = range(0,100); // 0,100 since array index starts from 0, and we want
unset($percent[0]); // the index to match the percentage value
for ($i = -1; $i < $numPenalties; $i++) {
    $inputs =
        '<div class="penaltyInput" id="penaltyInput'.$i.'">' .
        $this->Form->label("latep$i", '', array('class' => 'penaltyLabel')) .
        $this->Form->label("prefix", 'Then until', array('class' => 'penaltyInLabel')) .
        $this->Form->text("Penalty.$i.days_late", array('default' => $i + 1)) .
        $this->Form->label('days', 'days after the due date', array('class' => 'penaltyInLabel')) .
        $this->Form->select(
            "Penalty.$i.percent_penalty",
            $percent,
            null,
            array('empty' => false, 'default' => '1')
        ) .
        $this->Form->label('%','% deducted',array('class' => 'penaltyInLabel')).
        '<a href="#" onclick="rmPenaltyInputs('.$i.'); return false;">X</a>' .
        "</div>";
    if ($i < 0) {
        // save for use as a template in javascript, should work in Lin/Win/Mac
        $penaltyInputs = str_replace(array("\n", "\r"), "", $inputs);
    }
    else {
        echo $inputs;
    }

}
echo '<a class="addPenaltyButton"
    href="#" onclick="addPenaltyInputs(); return false;">Add Penalty</a>';
echo "</div>";

echo $this->Form->submit();
echo $this->Form->end();

// Removed for enhancement #516 - "Allow duplicate event title"
/*
echo $ajax->observeField(
    'EventTitle',
    array(
        'update'=>'titleWarning',
        'url'=>'checkDuplicateName/'.$course_id,
        'frequency'=>1,
        'loading'=>"Element.show('loading');",
        'complete'=>"Element.hide('loading');stripe();"
    )
);
*/
?>
</div>

<script type="text/javascript">
// change the datetime text input boxes to show the datetimepicker
initDateTime();
// make sure that the correct event template type is selected initially
toggleEventTemplate();
// attach an event handler to deal with changes in event template type
jQuery("#EventEventTemplateTypeId").change(toggleEventTemplate);
// attach event handlers to deal with changes in event template selection
jQuery("#EventSimpleEvaluation").change(updatePreview);
jQuery("#EventRubric").change(updatePreview);
jQuery("#EventSurvey").change(updatePreview);
jQuery("#EventMixeval").change(updatePreview);
jQuery("#EventEmailSchedule").change(toggleEmailTemplate);
jQuery("#EventEmailTemplate").change(updateEmailPreview);
jQuery("input:radio[name=data['Event']['self_eval']]").change(toggleSelfEval);
updateEmailPreview();
toggleEmailTemplate();
toggleSelfEval();
// for redirecting to the add event view for the selected course
changeCourseId();
// attach event handlers to deal with changes in course selection
jQuery("#EventCourseId").change(changeCourseId);
// select all groups
jQuery("#selectAll").click(function() {
    jQuery("#GroupGroup option").prop('selected', true);
});
// unselect all groups
jQuery("#unselectAll").click(function() {
    jQuery("#GroupGroup option").prop('selected', false);
});
// keep track of the number of penalties entered.
var penaltyCount = <?php echo $numPenalties; ?>;

function initDateTime() {
    var format = { dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss' }
    jQuery("#EventDueDate").datetimepicker(format);
    jQuery("#EventReleaseDateBegin").datetimepicker(format);
    jQuery("#EventReleaseDateEnd").datetimepicker(format);
    jQuery("#EventResultReleaseDateBegin").datetimepicker(format);
    jQuery("#EventResultReleaseDateEnd").datetimepicker(format);
}

function addPenaltyInputs() {
    // This is the penalty input template we generated in the penalty section
    var penaltyInputs = '<?php echo $penaltyInputs ?>';
    //console.log("count: " + penaltyCount);
    // In order to insert multiple entries of Penalty correctly, CakePHP
    // requires that the form name be indexed accordingly. Here, we replace
    // the default index 0 with whatever index the user is on at the moment.
    penaltyInputs = penaltyInputs.replace(/>0</g, '>' +(penaltyCount + 1)+ '<');
    penaltyInputs = penaltyInputs.replace(/value="0"/g,
        'value="' +(penaltyCount + 1)+ '"');
    penaltyInputs = penaltyInputs.replace(/-1/g, penaltyCount);
    if (penaltyCount == 0) {
        penaltyInputs = penaltyInputs.replace("Then until", "From the due date to");
        penaltyInputs = penaltyInputs.replace("days after the due date", "days late");
    }
    jQuery(penaltyInputs).appendTo("#penaltyInputs");

    penaltyCount++;
}

function rmPenaltyInputs(num) {
    jQuery("#penaltyInput"+num).remove();
}

// hide or show the appropriate event template selection based on the user's
// selected event template type
function toggleEventTemplate() {
    var eventType = jQuery("#EventEventTemplateTypeId").val();
    if (eventType == '1') {
        jQuery("#SimpleEvalDiv").show();
        jQuery("#RubricDiv").hide();
        jQuery("#SurveyDiv").hide();
        jQuery("#MixevalDiv").hide();
        jQuery("div.radio").show();
        jQuery("#penaltyInputs").show();
        jQuery("#ResultReleaseBeginDiv").show(); // no result release for survey
        jQuery("#ResultReleaseEndDiv").show(); // no result release for survey
        jQuery("#GroupsDiv").show();
        jQuery("div.help-text").show();
        jQuery("div.selectAll").show();
        updatePreview();
    }
    else if (eventType == '2') {
        jQuery("#SimpleEvalDiv").hide();
        jQuery("#RubricDiv").show();
        jQuery("#SurveyDiv").hide();
        jQuery("#MixevalDiv").hide();
        jQuery("div.radio").show();
        jQuery("#penaltyInputs").show();
        jQuery("#ResultReleaseBeginDiv").show(); // no result release for survey
        jQuery("#ResultReleaseEndDiv").show(); // no result release for survey
        jQuery("#GroupsDiv").show();
        jQuery("div.help-text").show();
        jQuery("div.selectAll").show();
        updatePreview();
    }
    else if (eventType == '3') {
        jQuery("#SimpleEvalDiv").hide();
        jQuery("#RubricDiv").hide();
        jQuery("#SurveyDiv").show();
        jQuery("#MixevalDiv").hide();
        jQuery("div.radio").hide(); // no self eval and comments in surveys
        jQuery("#penaltyInputs").hide(); // no penalty in surveys
        jQuery("#ResultReleaseBeginDiv").hide(); // no result release for survey
        jQuery("#ResultReleaseEndDiv").hide(); // no result release for survey
        jQuery("#GroupsDiv").hide(); // no groups in surveys
        jQuery("div.help-text").hide(); // no groups in surveys
        jQuery("div.selectAll").hide(); // no groups in surveys
        updatePreview();
    }
    else if (eventType == '4') {
        jQuery("#SimpleEvalDiv").hide();
        jQuery("#RubricDiv").hide();
        jQuery("#SurveyDiv").hide();
        jQuery("#MixevalDiv").show();
        jQuery("div.radio").show();
        jQuery("#penaltyInputs").show();
        jQuery("#ResultReleaseBeginDiv").show(); // no result release for survey
        jQuery("#ResultReleaseEndDiv").show(); // no result release for survey
        jQuery("#GroupsDiv").show();
        jQuery("div.help-text").show();
        jQuery("div.selectAll").show();
        updatePreview();
    }
    jQuery('#emailSchedule').show(); // show email reminder frequency at all times
}

// redirects user to a new events add view based on the course selected
function changeCourseId() {
    var courseId = jQuery("#EventCourseId").val();

    if (courseId != <?php echo $course_id ?>) {
        window.location.replace("<?php echo $this->base; ?>/events/add/" + courseId);
    }
}

// update event id for the preview link
function updatePreview() {
    var eventType = jQuery("#EventEventTemplateTypeId").val();
    var url = null;
    if (eventType == '1') {
        var eventIdToPrev = jQuery("#EventSimpleEvaluation").val();
        url = "<?php echo $this->base; ?>/simpleevaluations/view/";
        jQuery("#prevS").attr("href", url + eventIdToPrev);
    }
    else if (eventType == '2') {
        var eventIdToPrev = jQuery("#EventRubric").val();
        url = "<?php echo $this->base; ?>/rubrics/view/";
        jQuery("#prevR").attr("href", url + eventIdToPrev);
    }
    else if (eventType == '3') {
        var eventIdToPrev = jQuery("#EventSurvey").val();
        console.log("Id: " + eventIdToPrev);
        url = "<?php echo $this->base; ?>/surveys/view/";
        jQuery("#prevV").attr("href", url + eventIdToPrev);
    }
    else if (eventType == '4') {
        var eventIdToPrev = jQuery("#EventMixeval").val();
        var selfEval = jQuery("input:radio[name=data['Event']['self_eval']]:checked").val();
        url = "<?php echo $this->base; ?>/mixevals/view/";
        jQuery("#prevM").attr("href", url + eventIdToPrev + '/' + selfEval);
    }
}

function toggleSelfEval() {
    var eventType = jQuery("#EventEventTemplateTypeId").val();
    if (eventType = '4') {
        var templateId = jQuery("#EventMixeval").val();
        var selfEval = jQuery("input:radio[name=data['Event']['self_eval']]:checked").val();
        url = "<?php echo $this->base; ?>/mixevals/view/";
        prevM.href = url + templateId + '/' + selfEval;
    }
}

// update email template id for the preview link
function updateEmailPreview() {
    var emailId = jQuery("#EventEmailTemplate").val();
    var url = "<?php echo $this->base; ?>/emailtemplates/view/"
    jQuery("#prevE").attr("href", url + emailId);
}

// show / hide email template input
function toggleEmailTemplate() {
    var freq = jQuery("#EventEmailSchedule").val();
    if (freq == 0) {
        jQuery('div#EmailTemplateDiv').hide();
        jQuery('.email-temp-help-text').hide();
    } else {
        jQuery('div#EmailTemplateDiv').show();
        jQuery('.email-temp-help-text').show();
    }
}
</script>
