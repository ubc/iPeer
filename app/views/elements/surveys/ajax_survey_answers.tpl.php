<?php
echo '<table border="0" cellspacing="4" cellpadding="2" width="100%">';

for ( $i=1; $i<=$count; $i++){
	if(!empty($responses))
		$tmp = $responses[$i-1]['Response']['response'];
	else
		$tmp = null;

	echo '<tr><td width="6%">'.$i.'. </td><td align="left">';
	echo $html->input('Question/response_'.$i, array('size'=>'25','class'=>'input', 'style'=>'width:75%;', 'value'=>$tmp));
	echo "</td></tr>";
}
echo "</table>";
echo '<input type="hidden" name="data[Question][count]"  value="'.$count.'" />';
?>