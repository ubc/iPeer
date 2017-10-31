<div id='CourseEditForm'>
<?php
if (isset($instructions)) {
    echo $instructions;
}

echo $this->Form->create('Course');
echo $this->Form->input('id');

if ($canvasEnabled && !$canvasTokenSet) {
    echo $html->link(__('Authorize Canvas access', true), '#', array('onclick' => 'javascript: jQuery("#CourseDoCanvasAuthorize").val(1); document.forms[0].submit(); return false;'));
} else {
    // canvas courses
    $cCourse = $form->input(
        'Course.canvas_course',
        array(
            'type' => 'select',
            'id' => 'CanvasCourses',
            'label' => __('Populate from Canvas course', true),
            'options' => $canvasCourses,
            'after' => $html->link(__('Populate', true), '#', array('onclick' => 'javascript: if (CanvasCourses.value == "") { return false; } document.forms[0].submit(); return false;'))
        )
    );
        echo $html->div('input text', $cCourse, array('id' => 'canvas_courses'));
    if (empty($canvasCourses)) {
        echo $html->div('help-text', __('No accessible Canvas course', true));
    }
}

echo $form->hidden('doCanvasAuthorize', array('value' => 0));
echo $form->input('Course.course');
echo $html->div('help-text', __('Course subjects and course numbers, e.g. APSC 201 001', true));
echo $form->input('Course.title');
echo $html->div('help-text', __('Course title, e.g. Technical Communication', true));

// instructors field
function makeInstructor($i, $userId, $fullName, $html, $form) {
    // make instructor text box
    $name = "'$fullName'";
    $input = $form->label(' ') . $form->text("Instructor.$i.full_name", array('default' => $fullName, 'disabled' => true)) .
        $html->link('X', '#', array('onclick' => "rmInstructor($i, $name, $userId); return false;"));
    $input .= $form->hidden("Instructor.Instructor.$i", array('value' => $userId));
    $ret = $html->div('input text', $input, array('id' => "instructorsList$i"));
    return $ret;
}

$numInstructors = 0;
$selected = array();
$instructorTemplate = makeInstructor(-1, -2, -3, $html, $form);
$prof = $form->input('instructors', array(
    'after' => $html->link(__('Add Instructor', true),'#', array('onclick' => 'addInstructor(); return false;')),
    'empty' => '-- Select an instructor --'));
if (isset($this->data) && isset($this->data['Instructor'])) {
    // have names for instructors that may not be in the original list of instructors
    // eg. admin adds an instructor from a different department to the course.
    $profs = Set::combine($this->data['Instructor'], '{n}.id', '{n}.full_name');
    foreach($this->data['Instructor']['Instructor'] as $key => $id) {
        $prof .= makeInstructor($key, $id, $profs[$id], $html, $form);
        $numInstructors = $key + 1;
        $selected[] = $id;
    }
}
echo $html->div('input text', $prof, array('id' => 'instructors'));

// tutors field
function makeTutor($i, $userId, $fullName, $html, $form) {
    // make tutor text box
    $name = "'$fullName'";
    $input = $form->label(' ') . $form->text("Tutor.$i.full_name", array('default' => $fullName, 'disabled' => true)) .
        $html->link('X', '#', array('onclick' => "rmTutor($i, $name, $userId); return false;"));
    $input .= $form->hidden("Tutor.Tutor.$i", array('value' => $userId));
    $ret = $html->div('input text', $input, array('id' => "tutorsList$i"));
    return $ret;
}

$numTutors = 0;
$tutorSelected = array();
$tutorTemplate = makeTutor(-1, -2, -3, $html, $form);
$tutor = $form->input('tutors', array(
    'after' => $html->link(__('Add Tutor', true), '#', array('onclick' => 'addTutor(); return false;')),
    'empty' => '-- Select a tutor --'));
if (isset($this->data) && isset($this->data['Tutor'])) {
    foreach($this->data['Tutor']['Tutor'] as $key => $id) {
        $tutor .= makeTutor($key, $id, $tutors[$id], $html, $form);
        $numTutors = $key + 1;
        $tutorSelected[] = $id;
    }
}
echo $html->div('input text', $tutor, array('id' => 'tutors'));

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
    'label' => $this->Vocabulary->translate('Department'),
));
echo $html->div('help-text', __('Selecting correct assoication will allow admin to help troubleshooting.', true));
echo $this->Form->input('homepage');
echo $html->div('help-text', __('e.g. http://mycoursehome.com', true));

echo $html->div('center', $form->submit(__('Save', true), array('div' => false, 'onclick' => 'javascript: CanvasCourses.value=""; return true;')));
echo $form->end(); ?>
</div>

<script type="text/javascript">

jQuery('#CanvasCourses').prepend('<option value="" selected="selcted"></option>');

// remove all already added instructors from the drop down
var selected = <?php echo json_encode($selected); ?>;
jQuery.each(selected, function(key, value) {
    jQuery('#CourseInstructors option[value='+value+']').remove();
});
var selected = <?php echo json_encode($tutorSelected); ?>;
jQuery.each(selected, function(key, value) {
    jQuery('#CourseTutors option[value='+value+']').remove();
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

var numTutors = <?php echo $numTutors; ?>;
function addTutor() {
    // grab selected tutor
    var userId = jQuery('#CourseTutors').val();
    // not to add empty value
    if (userId != '') {
        var full_name = jQuery('#CourseTutors option:selected').text();
        var template = '<?php echo $tutorTemplate; ?>';
        template = template.replace(/-1/g, numTutors);
        template = template.replace(/-2/g, userId);
        template = template.replace(/-3/g, full_name);
        jQuery(template).appendTo('#tutors');
        // remove tutor from drop down
        jQuery('#CourseTutors option[value='+userId+']').remove();
        numTutors++;
    }
}

function rmTutor(num, fullName, userId) {
    jQuery("#tutorsList" + num).remove();
    // add tutor back to drop down
    jQuery('#CourseTutors').append(jQuery('<option>', {
        value: userId,
        text: fullName
    }));
}
</script>
