<!-- elements::ajax_survey_validate end -->
<?php
if (!empty($data[0])){
  echo '<font color="red">Survey "'.$data.'" already exists.</font>';
}

echo ' <input type="hidden" name="data[Survey][name]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_survey_valiate end -->
