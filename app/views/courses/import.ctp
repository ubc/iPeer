<div id='CourseImport'>
<h2><?php echo __('Instructions', true)?></h2>
<ul>
    <li><?php echo __('All fields are mandatory.', true) ?></li>
    <li><?php echo __('When all fields are filled, the Submit button will become available.', true) ?></li>
    <li><?php echo __('If the source course does not have a survey, the source survey and destination survey fields are not required.') ?></li>
    <li><?php echo __('You can choose to import into a duplicate of the source survey or choose an existing survey.', true) ?></li>
    <li><?php echo __('The conditions below must be met for the move to be successful.', true) ?></li>
</ul>
<h2><?php echo __('Conditions', true)?></h2>
<ul>
    <li><?php echo __('Only Text (.txt) and CSV File (.csv) can be used.', true) ?></li>
    <li><?php echo __('Only student numbers and usernames can be used in the file but not both.', true) ?></li>
    <li><?php echo __('The Destination Course must be accessible to you.', true) ?></li>
    <li><?php echo __('The existing Destination Survey needs to use the same template as the Source Survey.', true) ?></li>
</ul>
<h3><?php echo __('Examples:', true)?></h3>
<pre id='example'>
studentno1
studentno2
studentno3
studentno4
</pre>
<pre id='example'>
username1
username2
username3
username4
</pre>
<h2><?php echo __('Move or Copy Group of Students', true) ?></h2>
<?php
echo $this->Form->create('Course', array('type' => 'file'));
echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
echo $this->Form->input('identifiers', array(
    'type' => 'radio',
    'options' => array('student_no' => 'Student No.', 'username' => 'Username'),
    'legend' => __('Student Identifier', true),
    'default' => 'student_no'
));
echo "<div id='required'>";
echo $this->Form->input('sourceCourses', array('label' => 'Source Course', 'empty' => '-- Pick a course --', 'class' => 'required'));
echo $this->Form->input('sourceSurveys', array('label' => 'Source Survey', 'empty' => '-- Pick a survey --', 'class' => 'required'));
echo '<div class="help-text" id="noSourceSurvey">'.__('No surveys were found in the Source Course.', true).'</div>';
echo $this->Form->input('destCourses', array('label' => 'Destination Course', 'empty' => '-- Pick a course --', 'class' => 'required'));
echo "</div>";
echo $this->Form->input('surveyChoices', array(
    'type' => 'radio',
    'options' => array('1' => 'Duplicate', '0' => 'Existing'),
    'legend' => __('Destination Survey', true),
    'default' => '1'
));
echo $this->Form->input('destSurveys', array('label' => '', 'empty' => '-- Pick a survey --'));
echo '<div class="help-text" id="noDestSurvey">'.__('No surveys were found in the Source Course.', true).'</div>';
echo $this->Form->input('action', array(
    'type' => 'radio',
    'options' => array('1' => 'Move', '0' => 'Copy'),
    'legend' => __('Move or Copy?', true),
    'default' => '1'
));
echo '<div class="help-text">'.__('"Move" will unenrol the student from the Source Course.', true).'</div>';
echo $this->Form->end(array('label' => 'Submit', 'id' => 'submit'));
?>
</div>
<script type="text/javascript">

jQuery("#submit").attr("disabled", "disabled");
jQuery("#noSourceSurvey").hide();
jQuery("#noDestSurvey").hide();
toggleDestSurveys();
jQuery().ready(function() {
    // for changes in the user's choice for destination survey
    jQuery('input[type="radio"][name="data[Course][surveyChoices]"]').change(toggleDestSurveys);
    //enables the submit button when all select fields are filled
    jQuery('select, input[type="radio"][name="data[Course][surveyChoices]"], :file').change(function() {
        var empty = false;
        var choice = jQuery('input[type="radio"][name="data[Course][surveyChoices]"]:checked').val();
        jQuery("#required select.required").each(function() {
            if(jQuery(this).val() == '') {
                empty = true;
            }
        });
        //only check that a destination survey is selected if the existing option is chosen
        if (choice == 0 && jQuery('#CourseDestSurveys').val() == '') {
            empty = true;
        }
        //check to make sure a file is given
        if (jQuery(":file").val() == "") {
            empty = true;
        }
        if (empty) {
            jQuery("#submit").attr("disabled", "disabled");
        } else {
            jQuery("#submit").removeAttr("disabled");
        }
    });
    
    // updating the next field with available options
    jQuery('#CourseSourceCourses').change(function() {
        var id = jQuery('#CourseSourceCourses option:selected').val();
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
        jQuery.getJSON('/courses/ajax_options', {field: 'sCourses', courseId: id},
            function(surveys) {
                populate(surveys, '#CourseSourceSurveys', 'survey');
        });
        jQuery.getJSON('/courses/ajax_options', {field: 'importDestCourses',courseId: id},
            function(courses) {
                populate(courses, '#CourseDestCourses', 'course');
        });
        // check whether the course has a survey event, if not disable destination survey
        if (id > 0) {
            jQuery.getJSON('/courses/ajax_options', {field: 'sCourses', courseId: id},
                function(events) {
                    /* empty array is a javascript array, but if it's not
                    empty it is an object - needed to find a way to find the length
                    for both types */
                    if (Object.keys(events).length > 0) {
                        jQuery("input[id^=CourseSurveyChoices]:radio").removeAttr("disabled");
                        jQuery("#CourseSourceSurveys").removeAttr("disabled");
                        jQuery("#CourseSourceSurveys").addClass("required");
                        jQuery("#noSourceSurvey").hide();
                        jQuery("#noDestSurvey").hide();
                    } else {
                        jQuery("#CourseSurveyChoices1").click();
                        jQuery("input[id^=CourseSurveyChoices]:radio").attr("disabled", "disabled");
                        jQuery("#CourseSourceSurveys").attr("disabled", "disabled");
                        jQuery("#CourseSourceSurveys").removeClass("required");
                        jQuery("#noSourceSurvey").show();
                        jQuery("#noDestSurvey").show();
                    }   
            });
        } else {
            // if the empty option is picked return everything back to normal
            jQuery("input[id^=CourseSurveyChoices]:radio").removeAttr("disabled");
            jQuery("#CourseSourceSurveys").removeAttr("disabled");
            jQuery("#CourseSourceSurveys").addClass("required");
            jQuery("#noSourceSurvey").hide();
            jQuery("#noDestSurvey").hide();
        }
    });
    
    jQuery('#CourseDestCourses').change(function() {
        var sId = jQuery('#CourseSourceSurveys option:selected').val();
        var cId = jQuery('#CourseDestCourses option:selected').val();
        jQuery.getJSON('/courses/ajax_options', {field: 'dCourses', surveyId: sId, courseId: cId},
            function(surveys) {
                populate(surveys, '#CourseDestSurveys', 'survey');
        });
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

function toggleDestSurveys() {
    var choice = jQuery('input[type="radio"][name="data[Course][surveyChoices]"]:checked').val();
    // create a copy of the source survey
    if (choice == 1) {
        jQuery('#CourseDestSurveys').hide();
        jQuery('label[for="CourseDestSurveys"]').hide();
    // choose a survey that has already been created
    } else {
        jQuery('#CourseDestSurveys').show();
        jQuery('label[for="CourseDestSurveys"]').show();
    }
}

</script>