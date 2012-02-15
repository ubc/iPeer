<?php if (isset($view) && $view):?>
  <?php //For view page; display the name only
  echo $courseList[$courseId];?>
<?php else:?>
  
  <!-- //For Edit or Add pages; shows the selection box -->
  <?php 
  $defaultOpt = isset($defaultOpt) ? $defaultOpt : null;
  $empty = isset($empty) ? $empty : null;
  $disabled = isset($disabled) ? $disabled : false;
  $options = $courseList;
  $defautOptions = array('-1' => __(' --- No Course Selected --- ', true),
                         'A' => __(' --- All Courses --- ', true));
  if (isset($defaultOpt) && array_key_exists($defaultOpt, $defaultOptions)) {
    $options = array($defaultOpt, $defaultOptions[$defaultOpt]) + $options;
  }

  echo $this->Form->select('course_id', $options, $defaultOpt,
                           array('empty' => $empty,
                                 'id' => (isset($id_prefix) ? $id_prefix.'_' : '').'course_id',
                                 'disabled' => $disabled));?>
<?php endif;?>
