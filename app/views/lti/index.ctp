<?php
if (isset($invalidlti))
{
	echo "<p>LTI Request failure: $invalidlti</p>";
}
else
{
	echo "<p>The LTI request completed successfully, but the app failed to redirect for some reason. Try clicking <a href='/home'>here</a> to see if you're properly logged in.</p>";
}
?>
