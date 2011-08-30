<?php
echo $tut;
?>

	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
	width="800" height="638" id="movie" >
	<param name="movie" value="<?php echo $this->webroot.$this->theme ?>img/wizard/tut_wrapper.swf">
	<param name="quality" value="high">
	<param name="bgcolor" value="#ffffff">
	<embed src="<?php echo $this->webroot.$this->theme ?>img/wizard/tut_wrapper.swf" quality="high" bgcolor="#FFFFFF" width="800" height="638" name="movie" type="application/x-shockwave-flash"	pluginspage="http://www.macromedia.com/go/getflashplayer">
	</embed>
	</object>
