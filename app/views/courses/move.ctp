<div id='UserMove'>
<h2><?php __('Instructions')?></h2>
<ul>
    <li><?php __('All fields are mandatory.') ?></li>
    <li><?php __('When all fields have been filled, the Submit button will become available.') ?></li>
    <li><?php __('Only students who have submitted their surveys will appear in the list.') ?></li>
    <li><?php __('For moving multiple students, go to import (currently unavailable).') ?></li>
</ul>
<h2><?php __('Move or Copy Student') ?></h2>
<?php
echo $this->Form->create('Course', array('action' => 'move/'.$courseId));
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
echo $this->Form->end(array('label' => 'Submit', 'id' => 'submit', 'disabled' => 'disabled'));

//populate source surveys
$this->Js->get('#CourseSourceCourses')->event('change',
    $this->Js->request(
        array('controller'=>'courses', 'action'=>'ajax_options/sCourses'),
        array('update' => '#CourseSourceSurveys', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm'=>false, 'inline'=>true)))
));
$this->Js->get('#CourseSourceSurveys')->event('change', 
    $this->Js->request(
        array('controller'=>'courses', 'action'=>'ajax_options/sSurveys'),
        array('update' => '#CourseSubmitters', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm'=>false, 'inline'=>true)))
));
$this->Js->get('#CourseSubmitters')->event('change',
    $this->Js->request(
        array('controller'=>'courses', 'action'=>'ajax_options/submitters'),
        array('update' => '#CourseDestCourses', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm'=>false, 'inline'=>true)))
));
$this->Js->get('#CourseDestCourses')->event('change',
    $this->Js->request(
        array('controller'=>'courses', 'action'=>'ajax_options/dCourses'),
        array('update' => '#CourseDestSurveys', 'dataExpression' => true, 'async' => true,
        'data' => $js->serializeForm(array('isForm'=>false, 'inline'=>true)))
));
?>

</div>
<script type="text/javascript">

jQuery().ready(function() {
    // creating empty options for select fields below the field that was changed
    jQuery('#CourseSourceCourses').change(function() {
        jQuery('#CourseSubmitters').find('option').remove().end()
            .append('<option value="">-- Pick a student --</option>');
        jQuery('#CourseDestCourses').find('option').remove().end()
            .append('<option value="">-- Pick a course --</option>');
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });
    jQuery('#CourseSourceSurveys').change(function() {
        jQuery('#CourseDestCourses').find('option').remove().end()
            .append('<option value="">-- Pick a course --</option>');
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });
    jQuery('#CourseSubmitters').change(function() {
        jQuery('#CourseDestSurveys').find('option').remove().end()
            .append('<option value="">-- Pick a survey --</option>');
    });

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