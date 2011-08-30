<!-- elements::ajax_group_validate end -->
<?php
if (empty($fieldvalue)) $fieldvalue = '';

if (!empty($data[0])){
  echo '<font color="red">'.__('Group', true).' "'.$data.'" '.__('already exists', true).'</font>';
}

echo ' <input type="hidden" name="data[Group][group_num]"  value="'.$fieldvalue.'" />';
?>
<!-- elements::ajax_group_valiate end -->
