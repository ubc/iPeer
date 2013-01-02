<?php if (isset($view) && $view):?>
    <?php //For view page; display the name only
    echo $courseList[$courseId];?>
<?php else:?>

     <!-- //For Edit or Add pages; shows the selection box -->
    <?php
    $empty = isset($empty) ? $empty : null;
    $disabled = isset($disabled) ? $disabled : false;
    $options = $courseList;
    asort($options);
    $defaultOptions = array(
        '-1' => __(' --- No Course Selected --- ', true),
        'A' => __(' --- All Courses --- ', true));
    $options = Set::pushDiff($defaultOptions, $options);
    echo $this->Form->select('course_id', $options,
        isset($defaultOpt) ? $defaultOpt : null,
        array(
            'empty' => $empty,
            'id' => (isset($id_prefix) ? $id_prefix.'_' : '').'course_id',
            'disabled' => $disabled
        )
    );
?>
<?php endif;?>
