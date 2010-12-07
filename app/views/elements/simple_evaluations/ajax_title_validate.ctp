<!-- elements::ajax_title_validate end -->
<?php 
if (!empty($data[0])){
  echo 'Name "'.$data.'" already exists.'; 
}
//echo the hidden field for real title
echo ' <input type="hidden" name="data[SimpleEvaluation][name]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_title_valiate end -->
