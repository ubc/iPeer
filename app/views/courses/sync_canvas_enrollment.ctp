<?php
if ($canvasCourses) {
    $cCourse = $this->Form->input(
        'Course.canvas_course',
        array(
            'type' => 'select',
            'id' => 'CanvasCourses',
            'label' => __('Compare with Canvas course', true),
            'options' => array_map(function($value) { return $value->name; }, $canvasCourses),
            'default' => $canvasCourseId,
            'after' => $html->link(__('Compare', true), '#', array('onclick' => 'return doCompare()', 'id' => 'btn_compare'))
        )
    );
    echo $html->div('input text', $cCourse, array('id' => 'canvas_courses'));
}
?>
<h2><?php echo __('Students currently enrolled in this iPeer course', true)?></h2>
<?php 
echo $this->Form->create('Course', array('id' => 'unEnrollForm', 'action' => 'syncCanvasEnrollment/' . $courseId . '/' . $canvasCourseId));
?>

<table id="table_id_1">
    <thead>
        <tr>
<?php
$columns = array('id', 'sel', 'username', 'full_name', 'email', 'student_no', 'enrolled_in_canvas');
foreach ($columns as $val) {
    if ($val == 'sel') {
        echo "<th>&nbsp;</th>";
    } else {
        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
            continue;
        }
        if ($val == 'username' && !User::hasPermission('functions/viewusername')) {
            continue;
        }
        echo "<th>".Inflector::humanize($val)."</th>";
    }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($classList as $person) {
    echo "<tr>";
    foreach ($columns as $val) {
        if ($val == 'sel') {
            $unenroll = $this->Form->checkbox(
                'Course.unenroll',
                array(
                    'type' => 'select',
                    'id' => 'unenroll_'.$person['id'],
                    'value' => $person['id'],
                    'name' => 'data[Course][unenroll][]',
                    'hiddenField' => false,)
                );
            echo "<td>";
            echo $html->div('input text', $unenroll, array('id' => 'unenroll_div_'.$person['id']));
            echo "</td>";
        } else {
            if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
                continue;
            }
            if ($val == 'username' && !User::hasPermission('functions/viewusername')) {
                continue;
            }
            if ($val == 'enrolled_in_canvas') {
                echo "<td>".(empty($canvasCourseId) ? '' : ($person[$val] ? 'Yes' : 'No'))."</td>";
            } else {
                echo "<td>".$person[$val]."</td>";
            }
        }
    }
    echo "</tr>";
}
?>

    </tbody>
</table>
<?php 
echo $html->div('center', $form->submit(__('Unenroll selected', true), array('id' => 'btn_unenroll', 'div' => false, 'onclick' => 'javascript: return unenrollConfirmed();')));
echo $form->end();
?>
<br/>

<h2><?php echo __('Students enrolled in the Canvas course but not the iPeer course', true)?></h2>

<?php 
echo $this->Form->create('Course', array('id' => 'enrollForm','action' => 'syncCanvasEnrollment/' . $courseId . '/' . $canvasCourseId));
?>
<table id="table_id_2">
    <thead>
        <tr>
<?php
$columns = array('id', 'sel', 'username', 'full_name', 'email', 'student_no');
foreach ($columns as $val) {
    if ($val == 'sel') {
        echo "<th>&nbsp;</th>";
    } else {

        if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
            continue;
        }
        if ($val == 'username' && !User::hasPermission('functions/viewusername')) {
            continue;
        }
        echo "<th>".Inflector::humanize($val)."</th>";
    }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
$enrolled_id = Set::extract('/id', $classList);
foreach ($iPeerUserByCanvasEnrollment as $person) {
    if (!in_array($person['id'], $enrolled_id)) {
        echo "<tr>";
        foreach ($columns as $val) {
            if ($val == 'sel') {
                $unenroll = $this->Form->checkbox(
                    'Course.enroll_by_canvas',
                    array(
                        'type' => 'select',
                        'id' => 'enroll_'.$person['id'],
                        'value' => $person['id'],
                        'name' => 'data[Course][enroll_by_canvas][]',
                        'hiddenField' => false,)
                    );
                echo "<td>";
                echo $html->div('input text', $unenroll, array('id' => 'enroll_div_'.$person['id']));
                echo "</td>";
            } else {
                if ($val == 'email' && !User::hasPermission('functions/viewemailaddresses')) {
                    continue;
                }
                if ($val == 'username' && !User::hasPermission('functions/viewusername')) {
                    continue;
                }
                echo "<td>".$person[$val]."</td>";
            }
        }
        echo "</tr>";
    }
}
?>

    </tbody>
</table>
<?php 
echo $html->div('center', $form->submit(__('Enroll selected', true), array('id' => 'btn_enroll', 'div' => false, 'onclick' => 'javascript: return enrollConfirmed();')));
echo $form->end();
?>
<br/>

<h2><?php echo __('Students enrolled in the Canvas course and without iPeer accounts', true)?></h2>

<?php 
echo $this->Form->create('Course', array('action' => 'syncCanvasEnrollment/' . $courseId . '/' . $canvasCourseId));
?>
<table id="table_id_3">
    <thead>
        <tr>
<?php
$columns = array('id', 'sel', 'key', 'name');
foreach ($columns as $val) {
    if ($val == 'sel') {
        echo "<th>&nbsp;</th>";
    } else {
        if ($val == 'key' && !User::hasPermission('functions/viewusername')) {
            continue;
        }
        echo "<th>".Inflector::humanize($val)."</th>";
    }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($canvasStudentWithoutiPeer as $person) {
    if (!in_array($person->id, $enrolled_id)) {
        echo "<tr>";
        foreach ($columns as $val) {
            if ($val == 'sel') {
                $unenroll = $this->Form->checkbox(
                    'Course.create_and_enroll',
                    array(
                        'type' => 'select',
                        'id' => 'create_and_enroll_'.$person->id,
                        'value' => $person->id,
                        'name' => 'data[Course][create_and_enroll][]',
                        'hiddenField' => false,)
                    );
                echo "<td>";
                echo $html->div('input text', $unenroll, array('id' => 'create_and_enroll_div_'.$person->id));
                echo "</td>";
            } else {
                if ($val == 'key' && !User::hasPermission('functions/viewusername')) {
                    continue;
                }
                if ($val == 'key') {
                    $key = $person->canvas_user_key;
                    echo "<td>".$person->$key."</td>";
                } else {
                    echo "<td>".$person->$val."</td>";
                }
            }
        }
        echo "</tr>";
    }
}
?>

    </tbody>
</table>
<?php 
echo $form->end();
?>

<script type="text/javascript">
function enrollConfirmed()
{
    var confirmed = confirm("Are you sure you want to enroll selected students to this iPeer course?");
    return confirmed;
}

function unenrollConfirmed()
{
    var confirmed = confirm("Are you sure you want to unenroll selected students from this iPeer course?");
    return confirmed;
}

function doCompare()
{
    if (jQuery('#CanvasCourses').val() == '') {
        jQuery('#btn_compare').attr('href', '#');
        return false;
    } else {
        jQuery('#btn_compare').attr('href', '<?php echo $this->base; ?>/courses/syncCanvasEnrollment/<?php echo $courseId ?>/'+jQuery('#CanvasCourses').val());
        return true;
    }
}

jQuery(document).ready(function() {
    jQuery('#CanvasCourses').prepend('<option value="" <?php echo !(empty($canvasCourseId))? '' : 'selected="selected"' ?>></option>');
    
    jQuery('#btn_enroll').attr('disabled', true);
    jQuery(document).on("click", "input:checkbox[id^='enroll_']", function(){
        jQuery('#btn_enroll').attr('disabled', true);
        jQuery("input:checkbox[id^='enroll_']").each(function(){
            if (jQuery(this).prop('checked')) { 
                jQuery('#btn_enroll').attr('disabled', false);
            }
        });
    });

    jQuery('#btn_unenroll').attr('disabled', true);
    jQuery(document).on("click", "input:checkbox[id^='unenroll_']", function(){
        jQuery('#btn_unenroll').attr('disabled', true);
        jQuery("input:checkbox[id^='unenroll_']").each(function(){
            if (jQuery(this).prop('checked')) { 
                jQuery('#btn_unenroll').attr('disabled', false);
            }
        });
    });

	var elmnt = null;
    /*
     * Initialise DataTables, with no sorting on the 'details' column
     */
    var oTable1 = jQuery('#table_id_1').dataTable( {
      "sPaginationType" : "full_numbers",
      "aoColumnDefs": [
        { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] },  // the id column
        { "bSearchable": false, "bVisible": true, "bSortable":false, "aTargets": [ 1 ] },  // the checkbox
        { "bSearchable": false, "bVisible": true, "bSortable":true, "aTargets": [ -1 ] },  // canvas column
        { "sWidth": "5px", "aTargets": [1]},
        ],
      "aaSorting" : [[2, 'asc']],
    });
    var oTable2 = jQuery('#table_id_2').dataTable( {
      "sPaginationType" : "full_numbers",
      "aoColumnDefs": [
        { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] },   // the id column
        { "bSearchable": false, "bVisible": true, "bSortable":false, "aTargets": [ 1 ] },   // the checkbox column
        { "sWidth": "5px", "aTargets": [1]},
        ],
      "aaSorting" : [[2, 'asc']],
    });
    var oTable3 = jQuery('#table_id_3').dataTable( {
      "sPaginationType" : "full_numbers",
      "aoColumnDefs": [
        { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] },   // the id column
        { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 1 ] },   // the checkbox column
        { "sWidth": "5px", "aTargets": [1]},
        ],
      "aaSorting" : [[2, 'asc']],
    });
    
    // jQuery Datatable hides invisible rows (e.g. search filtered, pagenation etc).
    // Add back the selected items for form submission
    jQuery('#unEnrollForm').submit(function(){
        jQuery(oTable1.fnGetHiddenNodes()).find('input:checked').appendTo(this);
    });
    jQuery('#enrollForm').submit(function(){
        jQuery(oTable2.fnGetHiddenNodes()).find('input:checked').appendTo(this);
    });    

} );
</script>