<div id='UserMove'>
<h2><?php echo __('Instructions', true)?></h2>
<ul>
    <li><?php echo __('All fields are mandatory.', true) ?></li>
    <li><?php echo __('When all fields have been filled, the Submit button will become available.', true) ?></li>
    <li><?php echo __('The conditions below must be met for the move to be successful.', true) ?></li>
</ul>
<h2><?php echo __('Conditions', true)?></h2>
<ul>
    <li><?php echo __('The Source Course must have a survey.', true) ?></li>
    <li><?php echo __('The Student being moved must have made a submission to the Source Survey.', true) ?></li>
    <li><?php echo __('The Destination Course must be accessible to you.', true) ?></li>
    <li><?php echo __('The Source Survey needs to use the same template as the Source Survey.', true) ?></li>
</ul>
<h2><?php echo __('Move or Copy Student', true); ?></h2>
<?php
echo $this->Form->create('Course');
echo $this->Form->input('sourceCourses', array('label' => 'Source Course', 'empty' => '-- Pick a course --'));
echo $this->Form->input('sourceSurveys', array('label' => 'Source Survey', 'empty' => '-- Pick a survey --'));
echo $this->Form->input('submitters', array('label' => 'Student', 'empty' => '-- Pick a student --'));
echo $this->Form->input('destCourses', array('label' => 'Destination Course', 'empty' => '-- Pick a course --'));
echo $this->Form->input('destSurveys', array('label' => 'Destination Survey', 'empty' => '-- Pick a survey --'));
echo $this->Form->input('action', array(
    'legend' => 'Move or Copy?',
    'type' => 'radio',
    'options' => array('1' => 'Move', '0' => 'Copy'),
    'default' => '1'
));
echo '<div class="help-text">'.__('"Move" will unenrol the student from the Source Course.', true).'</div>';
echo $this->Form->end(array('label' => 'Submit', 'id' => 'submit'));
?>

</div>
<script type="text/javascript">

jQuery("#submit").attr("disabled", "disabled");

jQuery().ready(function() {
    // creating empty options for select fields below the field that was changed
    // updating the next field with available options
    jQuery('#CourseSourceCourses').change(function() {
        var id = jQuery('#CourseSourceCourses option:selected').val();
        jQuery.getJSON('/courses/ajax_options', {field: 'sCourses', courseId: id},
            function(surveys) {
                populate(surveys, '#CourseSourceSurveys', 'survey');
        });
        jQuery('#CourseSubmitters').find('option').remove().end()
            .append('<option value="">-- Pick a student --</option>');
        jQuery('#CourseDestCourses').find('option').remove().end()
            .append('<option value="">-- Pick a course --</option>');
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });
    jQuery('#CourseSourceSurveys').change(function() {
        var id = jQuery('#CourseSourceSurveys option:selected').val();
        jQuery.getJSON('/courses/ajax_options', {field: 'sSurveys', surveyId: id},
            function(students) {
                populate(students, '#CourseSubmitters', 'student');
        });
        jQuery('#CourseDestCourses').find('option').remove().end()
            .append('<option value="">-- Pick a course --</option>');
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });
    jQuery('#CourseSubmitters').change(function() {
        var sId = jQuery('#CourseSourceSurveys option:selected').val();
        var cId = jQuery('#CourseSourceCourses option:selected').val();
        jQuery.getJSON('/courses/ajax_options', {field: 'submitters', surveyId: sId, courseId: cId},
            function(courses) {
                populate(courses, '#CourseDestCourses', 'course');
        });
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });
    jQuery('#CourseDestCourses').change(function() {
        var sId = jQuery('#CourseSourceSurveys option:selected').val();
        var cId = jQuery('#CourseDestCourses option:selected').val();
        jQuery.getJSON('/courses/ajax_options', {field: 'dCourses', surveyId: sId, courseId: cId},
            function(surveys) {
                populate(surveys, '#CourseDestSurveys', 'survey'); 
        });
    });
    
    // generate the options for the select fields
    function populate(selections, update, empty) {
        var options = '<option value>-- Pick a ' + empty + ' --</option>';
        jQuery.each(selections, function(index, value) {
            options += '<option value="' + index + '">' + value + '</option>';
        });
        jQuery(update).html(options);
    }

    // enables the submit button when all select fields are filled
    jQuery("select").change(function() {
        var empty = false;
        jQuery("select").each(function() {
            if(jQuery(this).val() == '') {
                empty = true;
            }
        });
        if (empty) {
            jQuery("#submit").attr("disabled", "disabled");
        } else {
            jQuery("#submit").removeAttr("disabled");
        }
    });
});

</script>