<!-- elements::ajax_title_validate end -->
<?php 
if (empty($fieldvalue)) $fieldvalue = '';

if (!empty($data[0])){
  echo 'Title "'.$data.'" already exists.'; 
}
//echo the hidden field for real username
echo ' <input type="hidden" name="data[Event][title]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_title_valiate end -->
