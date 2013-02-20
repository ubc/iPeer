<div id='CourseEditForm'>
<?php
echo $this->Form->create('Course');
echo $this->Form->input('id');
echo $form->input('Course.course');
echo $html->div('help-text', _('Course subjects and course numbers, e.g. APSC 201 001'));
echo $form->input('Course.title');
echo $html->div('help-text', _('Course title, e.g. Technical Communication'));

function makeInstructor($i, $userId, $fullName, $html, $form) {
    // make instructor text box
    $name = "'$fullName'";
    $input = $form->label(' ') . $form->text("Instructor.$i.full_name", array('default' => $fullName, 'disabled' => true)) . 
        $html->link('X', '#', array('onclick' => "rmInstructor($i, $name, $userId); return false;"));
    $input .= $form->hidden("Instructor.$i.id", array('default' => $userId));
    $ret = $html->div('input text', $input, array('id' => "instructorsList$i"));
    return $ret;
}

$numInstructors = 0;
$selected = array();
$instructorTemplate = makeInstructor(-1, -2, -3, $html, $form);
$prof = $form->input('instructors', array(
    'after' => $html->link('Add Instructor','#', array('onclick' => 'addInstructor(); return false;')),
    'empty' => '-- Select an instructor --'));
if (isset($this->data) && isset($this->data['Instructor'])) {
    foreach($this->data['Instructor'] as $key => $val) {
        $prof .= makeInstructor($key, $val['id'], $val['full_name'], $html, $form);
        $numInstructors = $key + 1;
        $selected[] = $val['id'];
    }
}
echo $html->div('input text', $prof, array('id' => 'instructors'));

echo $form->input(
    'Course.record_status',
    array(
        'type' => 'select',
        'id' => 'status',
        'label' => 'Status',
        'options' => $statusOptions,
    )
);
echo $form->input('Department', array(
    'label' => __($this->Vocabulary->translate('Department'), true),
));
echo $html->div('help-text', _('Selecting correct assoication will allow admin to help troubleshooting.')); 
echo $this->Form->input('homepage');
echo $html->div('help-text', _('e.g. http://mycoursehome.com'));

echo $html->div('center', $form->submit(_('Save'), array('div' => false)));
echo $form->end(); ?>
</div>

<script type="text/javascript">


// remove all already added instructors from the drop down
var selected = <?php echo json_encode($selected); ?>;
jQuery.each(selected, function(key, value) {
    jQuery('#CourseInstructors option[value='+value+']').remove();
});

var numInstructors = <?php echo $numInstructors; ?>;
function addInstructor() {
    // grab selected instructor
    var userId = jQuery('#CourseInstructors').val();
    // not to add empty value
    if (userId != '') {
        var full_name = jQuery('#CourseInstructors option:selected').text();
        var template = '<?php echo $instructorTemplate; ?>';
        template = template.replace(/-1/g, numInstructors);
        template = template.replace(/-2/g, userId);
        template = template.replace(/-3/g, full_name);
        jQuery(template).appendTo('#instructors');
        // remove instructor from drop down
        jQuery('#CourseInstructors option[value='+userId+']').remove();
        numInstructors++;
    }
}

function rmInstructor(num, fullName, userId) {
    jQuery("#instructorsList" + num).remove();
    // add instructor back to drop down
    jQuery('#CourseInstructors').append(jQuery('<option>', {
        value: userId,
        text: fullName
    }));
}
</script>