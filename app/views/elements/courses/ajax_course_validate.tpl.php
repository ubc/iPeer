<!-- elements::ajax_course_validate end -->
<?php
if (!empty($data[0])){
  echo '<font color="red">Course "'.$data.'" already exists.</font>';
}

//echo the hidden field for real username
echo ' <input type="hidden" name="data[Course][course]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_course_valiate end -->
