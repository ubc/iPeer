<!-- elements::ajax_title_validate end -->
<?php 
if (empty($fieldvalue)) $fieldvalue = '';
if (!empty($data[0])){
  echo __('Title', true).'"'.$data.'"'.__('already exists.', true); 
}
//echo the hidden field for real username
echo ' <input type="hidden" name="data[Event][title]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_title_valiate end -->
