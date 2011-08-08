<!-- elements::ajax_username_validate end -->
<?php 
if (empty($fieldvalue)) $fieldvalue = '';
if (!empty($data[0]) && $isEnrolled){
  echo 'Username "'.$data.'" already exists.'; 
  
/*  if (!empty($role) && $role == 'S' && !$isEnrolled) {
    echo '<br>Use enrol function on User Listing page to enrol this student to one or more courses.';
  }*/  
}
//echo the hidden field for real username
echo ' <input type="hidden" name="data[User][username]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_username_valiate end -->
