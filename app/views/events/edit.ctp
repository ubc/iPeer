<div id='Events'>

<?php
$html->script("jquery-ui-timepicker-addon", array("inline"=>false));
echo $this->Form->create('Event', array('action' => "edit/".$event['Event']['id']));
echo '<input type="hidden" name="required" id="required" value="eventId" />';
echo $this->Form->input('id');
?>

<label class="courseLabel"><?php __('Course')?></label>
<label class="course"><?php echo $event['Course']['full_name'] ?></label>
<?php echo $this->Form->input('title', array('label' => 'Event Title'));
echo $this->Form->input('description', array('type' => 'textarea'));
echo $this->Form->input('event_template_type_id');

echo $this->Form->input('SimpleEvaluation',
    array(
        'div' => array('id' => 'SimpleEvalDiv'), 
        'label' => $html->link(
            'Preview', '', array('id' => 'prevS', 'target' => '_blank')),
        'selected' => $simpleSelected
    )
);
echo $this->Form->input('Rubric',
    array(
        'div' => array('id' => 'RubricDiv'), 
        'label' => $html->link(
            'Preview', '', array('id' => 'prevR', 'target' => '_blank')),
        'selected'=> $rubricSelected
    )
);
echo $this->Form->input('Survey',
    array(
        'div' => array('id' => 'SurveyDiv'), 
        'label' => $html->link(
            'Preview', '', array('id' => 'prevV', 'target' => '_blank')),
        'selected' => $surveySelected
    )
);
echo $this->Form->input('Mixeval',
    array(
        'div' => array('id' => 'MixevalDiv'), 
        'label' => $html->link(
            'Preview', '', array('id' => 'prevM', 'target' => '_blank')),
        'selected' => $mixevalSelected
    )
);

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
);
echo $this->Form->input('due_date', array('type' => 'text'));
echo $this->Form->input('release_date_begin', array('label' => 'Evaluation Released From', 'type' => 'text'));
echo $this->Form->input('release_date_end', array('label' => 'Until', 'type' => 'text'));
echo $this->Form->input('result_release_date_begin', 
    array('label' => 'Results Released From', 'type' => 'text'));
echo $this->Form->input('result_release_date_end', 
    array('label' => 'Until', 'type' => 'text'));
echo $this->Form->input('Group', 
    array('div' => array('id' => 'GroupsDiv'), 'label' => 'Group(s)'));

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
        '<input type="hidden" name="required" id="required" value="eventId" />'.
        $this->Form->input("Penalty.$i.id").
        $this->Form->label("latep$i", '', array('class' => 'penaltyLabel')) .
        $this->Form->text("Penalty.$i.days_late", array('default' => $i + 1)) .
        $this->Form->label('days', 'days', array('class' => 'penaltyInLabel')) .
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
        jQuery("#GroupsDiv").show();
        updatePreview();
    }
    else if (eventType == '2') {
        jQuery("#SimpleEvalDiv").hide();
        jQuery("#RubricDiv").show();
        jQuery("#SurveyDiv").hide();
        jQuery("#MixevalDiv").hide();
        jQuery("div.radio").show();
        jQuery("#penaltyInputs").show();
        jQuery("#GroupsDiv").show();
        updatePreview();
    }
    else if (eventType == '3') {
        jQuery("#SimpleEvalDiv").hide();
        jQuery("#RubricDiv").hide();
        jQuery("#SurveyDiv").show();
        jQuery("#MixevalDiv").hide();
        jQuery("div.radio").hide(); // no self eval and comments in surveys
        jQuery("#penaltyInputs").hide(); // no penalty in surveys
        jQuery("#GroupsDiv").hide(); // no groups in surveys
        updatePreview();
    }
    else if (eventType == '4') {
        jQuery("#SimpleEvalDiv").hide();
        jQuery("#RubricDiv").hide();
        jQuery("#SurveyDiv").hide();
        jQuery("#MixevalDiv").show();
        jQuery("div.radio").show();
        jQuery("#penaltyInputs").show();
        jQuery("#GroupsDiv").show();
        updatePreview();
    }
}

// update event id for the preview link
function updatePreview() {
    var eventType = jQuery("#EventEventTemplateTypeId").val();
    var url = null;
    if (eventType == '1') {
        var eventIdToPrev = jQuery("#EventSimpleEvaluation").val();
        url = "<?php echo $this->base; ?>/simpleevaluations/view/";
        prevS.href = url + eventIdToPrev;
    }
    else if (eventType == '2') {
        var eventIdToPrev = jQuery("#EventRubric").val();
        url = "<?php echo $this->base; ?>/rubrics/view/";
        prevR.href = url + eventIdToPrev;
    }
    else if (eventType == '3') {
        var eventIdToPrev = jQuery("#EventSurvey").val();
        console.log("Id: " + eventIdToPrev);
        url = "<?php echo $this->base; ?>/surveys/view/";
        prevV.href = url + eventIdToPrev;
    }
    else if (eventType == '4') {
        var eventIdToPrev = jQuery("#EventMixeval").val();
        url = "<?php echo $this->base; ?>/mixevals/view/";
        prevM.href = url + eventIdToPrev;
    }
}
</script>
