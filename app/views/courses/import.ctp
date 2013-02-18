<div id='CourseImport'>
<h2><?php __('Instructions')?></h2>
<ul>
    <li><?php __('All fields are mandatory.') ?></li>
    <li><?php __('When all fields are filled, the Submit button will become available.') ?></li>
    <li><?php __('You can choose to import into a duplicate of the source survey or choose an existing survey.') ?></li>
</ul>
<h3><?php __('Examples:')?></h3>
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
<h2><?php __('Move or Copy Group of Students') ?></h2>
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
echo $this->Form->input('sourceCourses', array('label' => 'Source Course', 'empty' => '-- Pick a course --'));
echo $this->Form->input('sourceSurveys', array('label' => 'Source Survey', 'empty' => '-- Pick a survey --'));
echo $this->Form->input('destCourses', array('label' => 'Destination Course', 'empty' => '-- Pick a course --'));
echo "</div>";
echo $this->Form->input('surveyChoices', array(
    'type' => 'radio',
    'options' => array('1' => 'Duplicate', '0' => 'Existing'),
    'legend' => __('Destination Survey', true),
    'default' => '1'
));
echo $this->Form->input('destSurveys', array('label' => '', 'empty' => '-- Pick a survey --'));
echo $this->Form->input('action', array(
    'type' => 'radio',
    'options' => array('1' => 'Move', '0' => 'Copy'),
    'legend' => __('Move or Copy?', true),
    'default' => '1'
));
echo $this->Form->end(array('label' => 'Submit', 'id' => 'submit', 'disabled' => 'disabled'));

$this->Js->get('#CourseSourceCourses')->event('change',
    $this->Js->request(
        array('controller' => 'courses', 'action'=>'ajax_options/sCourses'),
        array('update' => '#CourseSourceSurveys', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm' => false, 'inline' => true)))
));
$this->Js->get('#CourseSourceCourses')->event('change',
    $this->Js->request(
        array('controller' => 'courses', 'action'=>'ajax_options/importDestCourses'),
        array('update' => '#CourseDestCourses', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm' => false, 'inline' => true)))
));
$this->Js->get('#CourseDestCourses')->event('change',
    $this->Js->request(
        array('controller' => 'courses', 'action'=>'ajax_options/importDestSurveys'),
        array('update' => '#CourseDestSurveys', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm' => false, 'inline' => true)))
));

?>
</div>
<script type="text/javascript">

toggleDestSurveys();
jQuery().ready(function() {
    // for changes in the user's choice for destination survey
    jQuery('input[type="radio"][name="data[Course][surveyChoices]"]').change(toggleDestSurveys);
    //enables the submit button when all select fields are filled
    jQuery('select, input[type="radio"][name="data[Course][surveyChoices]"], :file').change(function() {
        var empty = false;
        var choice = jQuery('input[type="radio"][name="data[Course][surveyChoices]"]:checked').val();
        jQuery("#required select").each(function() {
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
    jQuery('#CourseSourceCourses').change(function() {
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });
});

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