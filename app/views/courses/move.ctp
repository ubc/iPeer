<?php
echo $this->Form->create('Course', array('id' => 'UserMove', 'action' => 'move/'.$courseId));
echo $this->Form->input('sourceCourses', array('empty' => '-- Pick a course --'));
echo $this->Form->input('sourceSurveys', array('empty' => '-- Pick a survey --'));
echo $this->Form->input('submitters', array('empty' => '-- Pick a student --'));
echo $this->Form->input('destCourses', array('label' => 'Destination Course', 'empty' => '-- Pick a course --'));
echo $this->Form->input('destSurveys', array('label' => 'Destination Survey', 'empty' => '-- Pick a survey --'));
echo $this->Form->input('action', array(
    'legend' => 'Move or Copy?',
    'type' => 'radio',
    'options' => array('1' => 'Move', '0' => 'Copy'),
    'default' => '1'
));
echo $this->Form->end(array('label' => 'Move Student', 'id' => 'submit', 'disabled' => 'disabled'));

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

<script type="text/javascript">

jQuery().ready(function() {
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