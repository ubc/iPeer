<?php
echo '<table border="0" cellspacing="4" cellpadding="2" width="100%">';

for ( $i=0; $i<$count; $i++){
	if(!empty($responses))
		$tmp = $responses[$i]['Response']['response'];
	else
		$tmp = null;

	echo '<tr><td width="6%">'.($i+1).'. </td><td align="left">';
	echo $this->Form->input('response_'.($i+1), array('size'=>'25','class'=>'input', 'style'=>'width:75%;', 'value'=>$tmp));
	echo "</td></tr>";
}
echo "</table>";
echo '<input type="hidden" name="data[Question][count]"  value="'.$count.'" />';
?>
