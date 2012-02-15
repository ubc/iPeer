<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
<html>
<head>
<title>iPeer V2 with TeamMaker</title>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
<meta name="MSSmartTagsPreventParsing" content="true" />

<!--link rel="shortcut icon" href="favicon.ico" type="image/x-icon"-->
<?php echo $html->css('ipeer')?>
<?php echo $html->script('showhide')?>
<?php echo $html->script('calculate_marks')?>
<?php echo $html->script('ipeer')?>
<!-- AJAX Include Files -->
<?php echo $html->script('prototype')?>
<?php echo $html->script('scriptaculous')?>
<?php echo $html->script('zebra_tables')?>
<!-- End of AJAX Include Files -->
<!-- Validation Include Files -->
<?php echo $html->script('validate')?>
<?php echo $html->script('submitvalidate')?>
<!-- End of Validation Include Files -->

<script type="text/javascript" language="JavaScript">
<!--
    var sUrl = document.location.href;
    var oReg = /\/posts$/ig;
    if (oReg.test(sUrl))    document.location.href = sUrl + '/';

-->
</script>
<?php
//global $pageNav;
//$pageNav = $_REQUEST['pageNav'];
?>
</head>
<body>

<div id="wrap">
	<div class="box">
		<div class="bi">
			<div class="bt">
				<div>&nbsp;</div>
			</div>
			<div id="pop_title" align="center"><?php echo $html->image('layout/small_header.gif',array('border'=>'0','height'=>'44','align'=>'middle','alt'=>'small_header'))?></div>
			<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td> <?php echo $title_for_layout;?> </td>
			  </tr>
			</table>
        <?php if (!empty($errmsg)): ?>
        <div align="center" class="title2"><font color="red"><?php echo $errmsg; ?></font></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
        <div align="center" class="title2"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php echo $content_for_layout;?>
			<p>&nbsp;</p>
			<div class="bb">
				<div>&nbsp;</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
