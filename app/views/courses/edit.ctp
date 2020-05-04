<div id='CourseEditForm'>
<?php
if (isset($instructions)) {
    echo $instructions;
}

echo $this->Form->create('Course');
echo $this->Form->input('id');
if ($canvasEnabled) {
    if (!empty($this->data['Course']) && empty($this->data['Course']['id'])) {
        // new course creation based on canvas
        echo $form->hidden('Course.canvas_id');
    } else if (!empty($this->data['Course']) && !empty($this->data['Course']['id'])) {
        // edit existing course
        // canvas courses
        $cCourse = $form->input(
            'Course.canvas_id',
            array(
                'type' => 'select',
                'id' => 'canvas_id',
                'label' => __('Linked with <br/>Canvas course', true),
                'options' => $canvasCourses,
                'default' => $this->data['Course']['canvas_id'],
                'div' => array( 'style' => 'padding-bottom: 10px;' )
            )
        );

        if (empty($canvasCourses)) {
            echo '<div class="input select" style="padding-bottom: 10px;">' . $this->Form->label('Linked with <br/>Canvas course');
            echo $this->Form->select("canvasCourseInaccessible", array('0'=>'No accessible Canvas courses'), null, array("default" => '0', "disabled"=>true));
            echo '</div>';
            echo $form->hidden('Course.canvas_id');
        } else {
            echo $html->div('input text', $cCourse);
        }
    }
}

echo $form->input('Course.course');
echo $html->div('help-text', __('Course subjects and course numbers, e.g. APSC 201 001', true));
echo $form->input('Course.title');
echo $html->div('help-text', __('Course title, e.g. Technical Communication', true));
echo $form->input('Course.term');
echo $html->div('help-text', __('Course term, e.g. 2019 W1', true));

// instructors field
function makeInstructor($i, $userId, $fullName, $html, $form) {
    // make instructor text box
    $name = "'$fullName'";
    $input = $form->label(' ') . $form->text("Instructor.$i.full_name", array('value' => $fullName, 'disabled' => true)) .
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
        // Lookup the fullname in $profs or $instructors
        // In some cases (e.g. add a new course and encounterd error, need to show the add screen again),
        // $profs is not propulated with names
        $prof .= makeInstructor($key, $id, array_key_exists($id, $profs)? $profs[$id] : $instructors[$id], $html, $form);
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
echo $html->div('help-text', __('Selecting correct associations will allow admin to help troubleshooting.', true));
echo $this->Form->input('homepage');
echo $html->div('help-text', __('e.g. http://mycoursehome.com', true));

echo $html->div('center', $form->submit(__('Save', true), array('div' => false)));
echo $form->end(); ?>
</div>

<script type="text/javascript">
<?php if ($canvasEnabled) { ?>
// add the empty option
jQuery('#canvas_id').prepend('<option value="" <?php echo empty($this->data['Course']['canvas_id'])? 'selected="selected"' : '' ?>></option>');
// select the empty option if no linked canvas course
if (jQuery('#canvas_id option[value="<?php echo $this->data['Course']['canvas_id'] ?>"]').length == 0) {
    jQuery('#canvas_id').val('');
}
// warn user if there is already a linked canvas course and they are changing it
else if (jQuery('#canvas_id').val() != '') {
    var prevCanvasId = '<?php echo $this->data['Course']['canvas_id'] ?>';
    jQuery('#canvas_id').focus(function() {
        prevCanvasId = jQuery(this).val();
    }).change(function(){
        jQuery(this).blur();
        if (jQuery(this).val() != '<?php echo $this->data['Course']['canvas_id'] ?>' &&
            prevCanvasId == '<?php echo $this->data['Course']['canvas_id'] ?>' &&
            !confirm('If you change the associated Canvas course, all the data about this association might be erased. Are you sure you want to continue?')) {
            jQuery(this).val(prevCanvasId);
            return false;
        }
    })
}
<?php } ?>
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
