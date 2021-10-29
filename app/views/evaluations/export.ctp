<div id='exportEval'>
<h2><?php echo __('Instructions', true) ?></h2>
<ul>
    <li><?php echo __("Give the export file a name, which is default to today's date.", true)?></li>
    <!--<li><?php echo __("Choose one of the two export formats.", true)?></li> -->
    <li><?php echo __("Please check at least one from each similarly coloured group.", true)?></li>
    <li><?php echo __("Dropped Students that have evaluated or have been evaluated are marked with an asterisk (*)", true)?></li>
</ul>
<h2><?php echo __('Export', true) ?></h2>
<?php
echo $this->Form->create('ExportEval', array('url' => '/'.$this->params['url']['url']));
echo $this->Form->input('file_name', array(
    'name' => 'file_name', 'value' => isset($file_name) ? $file_name : '',
    'label' => __('Export Filename', true)
));
echo $this->Form->input('export_type', array(
    'name' => 'export_type', 'options' => $fileTypes,
    'label' => __('Export File Type', true)
));
$evaluations = ($fromEvent) ? array($selectedEvent['Event']['id'] => $selectedEvent['Event']['title']) :
    Set::combine($events, '{n}.Event.id', '{n}.Event.title');
echo $this->Form->input('event_id', array(
    'name' => 'event_id', 'options' => $evaluations, 'label' => 'Event Name'
));
echo $this->Form->input('export_all', array(
    'type' => 'checkbox', 'name' => 'include[export_all]', 'checked' => true,
    'label' => __('Include All Evaluations', true)
));
?>
<h3><?php echo __('Evaluation Information', true) ?></h3>
<?php
echo $this->Form->input('course_name', array(
    'type'=>'checkbox', 'name' => 'include[course]', 'checked' => true,
    'label' => __('Include Course Name', true).' <font color="red">*</font>'
));
echo $this->Form->input('event_name', array(
    'type' => 'checkbox', 'name' => 'include[eval_event_names]', 'checked' => true,
    'label' => __('Include Event Name', true).' <font color="red">*</font>'
));
?>
<div class='error-message' id='redError'><?php echo __('Please include at least one of the red fields', true); ?></div>
<?php
echo $this->Form->input('eval_type', array(
    'type' => 'checkbox', 'name' => 'include[eval_event_type]', 'checked' => true,
    'label' => __('Include Evaluation Type', true)
));
?>
<h3><?php echo __('Group and Result', true) ?></h3>
<?php
echo $this->Form->input('group_name', array(
    'type' => 'checkbox', 'name' => 'include[group_names]', 'checked' => true,
    'label' => __('Include Group Names', true)
));
echo $this->Form->input('student_name', array(
    'type' => 'checkbox', 'name' => 'include[student_name]', 'checked' => true,
    'label' => __('Include Student Name', true).' <font color="green">*</font>',
    'div' => array('id' => 'student_name')
));
echo $this->Form->input('student_id', array(
    'type' => 'checkbox', 'name' => 'include[student_id]', 'checked' => true,
    'label' => __('Include Student Id', true).' <font color="green">*</font>',
    'div' => array('id' => 'student_id')
));
?>
<div class='error-message' id='greenError'><?php echo __('Please include at least one of the green fields', true); ?></div>
<?php
echo $this->Form->input('question_title', array(
  'type' => 'checkbox',
  'name' => 'include[question_title]',
  'checked' => false,
  'label' => __('Include Questions', true).' <span style="color: orange">*</span>',
  'div' => array('id' => 'question_title')
));
echo $this->Form->input('comments', array(
    'type' => 'checkbox', 'name' => 'include[comments]', 'checked' => true,
    'label' => __('Include Comments', true).' <font color="orange">*</font>'
));
echo $this->Form->input('grades', array(
    'type' => 'checkbox', 'name' => 'include[grade_tables]', 'checked' => true,
    'label' => __('Include Grades', true).' <font color="orange">*</font>'
));
?>
<div class='error-message' id='orangeError'><?php echo __('Please include at least one of the orange fields', true); ?></div>
<?php
echo $this->Form->input('final_marks', array(
    'type' => 'checkbox', 'name' => 'include[final_marks]', 'checked' => true,
    'label' => __('Include Final Marks', true)
));
echo $this->Form->submit(__('Export', true), array('onClick'=>'return checkSubmit();'));
echo $this->Form->end();
?>
</div>
<script type="text/javascript">
exportTypeToggle();
function exportTypeToggle() {
    var selected = jQuery("#ExportEvalExportType option:selected").val();
    jQuery('.error-message').hide();
    switch(selected) {
        case 'csv':
            jQuery("div#student_id").show();
            jQuery("div#student_name").show();
            jQuery("div#question_title").show();
            break;
        case 'pdf':
            jQuery("div#student_id").hide();
            jQuery("div#student_name").hide();
            jQuery("div#question_title").hide();
            break;
        case 'default':
            jQuery('#fileTypeHelp').html('');
            jQuery("div#student_id").show();
            jQuery("div#student_name").show();
    };
}

function checkSubmit() {
    var valid = true;
    var selected = jQuery("#ExportEvalExportType option:selected").val();
    jQuery('.error-message').hide();
    if (!jQuery("input#ExportEvalEventName").is(':checked') &&
        !jQuery("input#ExportEvalCourseName").is(':checked')) {
        valid = false;
        jQuery('#redError').show();
    }
    if (!jQuery("input#ExportEvalStudentName").is(':checked') &&
        !jQuery("input#ExportEvalStudentId").is(':checked') && selected != 'pdf') {
        valid = false;
        jQuery('#greenError').show();
    }
    if (!jQuery("input#ExportEvalComments").is(':checked') &&
        !jQuery("input#ExportEvalGrades").is(':checked')) {
        valid = false;
        jQuery('#orangeError').show();
    }
    return valid;
}

jQuery().ready(function() {
    jQuery('#ExportEvalExportType').change(function(){
        exportTypeToggle();
    });
});
</script>