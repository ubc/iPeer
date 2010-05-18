<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
<html>
<head>
<title>iPeer V2 with TeamMaker</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/-->
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<meta http-equiv="Cache-Control"
	content="no-store, no-cache, must-revalidate" />
<meta name="MSSmartTagsPreventParsing" content="true" />

<!--link rel="shortcut icon" href="favicon.ico" type="image/x-icon"-->
<?php echo $html->charsetTag('UTF-8')?>
<?php echo $html->css('ipeer')?>
<?php //echo $javascript->link('calculate_marks')?>
<?php echo $javascript->link('ipeer')?>
<!-- AJAX Include Files -->
<?php echo $javascript->link('prototype')?>
<?php echo $javascript->link('scriptaculous')?>
<?php echo $javascript->link('zebra_tables')?>
<!-- End of AJAX Include Files -->
<!-- Validation Include Files -->
<?php echo $javascript->link('validate')?>
<?php echo $javascript->link('submitvalidate')?>
<!-- End of Validation Include Files -->
<script language="JavaScript" type="text/javascript">
<!--
function checkEmailAddress()
{
  var redirectURL = <?php echo $this->webroot.$this->themeWeb;?> + "users/editProfile";
  if (document.forms[0].elements['data[User][email]'].value == '') {
    if (confirm('You have not yet enter your email address yet.  Do you want to input it now?') != null) {

      <?php if ($this->params['controller'] == 'users') { ?>
        document.forms[0].elements['data[User][email]'].focus();
      <?php }else {?>
        window.location=redirectURL;
      <?php } ?>
    }
  }
}
-->
</script>
</head>
<body
<?php if ($this->params['controller'] != 'loginout') {
	if (!empty($rdAuth->role) && $rdAuth->role == 'S') {
		if (empty($rdAuth->email))
		echo 'onload="checkEmailAddress()"';
	}
} elseif (substr($_SERVER['REQUEST_URI'],-14) == "loginByDefault")
echo 'onload="document.getElementById(\'username\').focus()"';
?>>
<table class="tableback" width="94%" border="0" align="center"
	cellpadding="0" cellspacing="0">

	<tr>
		<td width="2%" rowspan="2"><?php echo $html->image('layout/banner_left.gif',array('alt'=>'banner_left'))?></td>
		<td width="25%" height="72" valign="top"><span class="style1"><?php echo $html->image('layout/ipeer_banner.gif',array('alt'=>'ipeer_banner'))?></span><span
			class="bannerText">with TeamMaker </span></td>
		<td width="46%" valign="top"><br />
		<table width="100%" border="0" align="right" cellpadding="0"
			cellspacing="4" class="miniLinks">
			<tr class="miniLinks">
			<?php if (!empty( $rdAuth->id)) :?>
				<td width="350" align="right"><?php echo $html->image('layout/icon_edit.gif',array('alt'=>'icon_edit'))?>
				<a
					href="<?php echo $this->webroot.$this->themeWeb;?>users/editProfile"
					class="miniLinks">Edit Profile (<?php echo $rdAuth->fullname ?>)</a></td>
				<td width="57" align="right"><?php echo $html->image('layout/icon_arrow.gif',array('alt'=>'icon_arrow'))?>
				<a
					href="<?php echo $this->webroot.$this->themeWeb;?>loginout/logout"
					class="miniLinks">Logout</a></td>
					<?php else:?>
				<td width="157" ></td>
				<td width="57" align="right"><?php echo $html->image('layout/icon_arrow.gif',array('alt'=>'icon_arrow'))?>
				<a href="<?php echo $this->webroot.$this->themeWeb;?>loginout/login"
					class="miniLinks">Login</a></td>
					<?php endif;?>
			
			</tr>
		</table>
		</td>
		<td width="6%" rowspan="2" valign="top">
		<div align="right"><?php echo $html->image('layout/blocks.gif',array('alt'=>'blocks'))?></div>
		</td>
		<td width="2%" rowspan="2" valign="top">
		<div align="right"><?php echo $html->image('layout/banner_right.gif',array('alt'=>'banner_right'))?></div>
		</td>
	</tr>
	<tr>
		<td height="50" colspan="2" valign="top">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="971" height="38" valign="bottom">
				<div id="header">
				<ul>

					<!-- Parameters of $access based on the table SYS_FUNCTION -->
				<?php if (!empty($access['HOME'])) {
					$homeSysFunc = $access['HOME'];    ?>
					<li
					<?php if ($this->params['controller'] == 'home') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb.$homeSysFunc['url_link'];?>"><span><?php echo $html->image('layout/icon_home.gif',array('border'=>'0','alt'=>'icon_home'))?>
						<?php echo $homeSysFunc['function_name']?> </span></a></li>
						<?php }?>
						<?php if (!empty($access['COURSE'])) {
							$courseSysFunc = $access['COURSE'];    ?>
					<li
					<?php if ($this->params['controller'] == 'courses' || $this->params['controller'] == 'events' || $this->params['controller'] == 'groups' || $this->params['controller'] == 'evaluations' || $this->params['controller'] == 'surveygroups') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb.$courseSysFunc['url_link'];?>"><span><?php echo $courseSysFunc['function_name']?></span></a></li>
						<?php }?>
						<?php if (!empty($access['USR']) && ($rdAuth->role == 'A' || $rdAuth->role == 'I')) {
							$userSysFunc = $access['USR'];    ?>
					<li
					<?php if ($this->params['controller'] == 'users') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb.$userSysFunc['url_link'];?>"><span><?php echo $userSysFunc['function_name']?></span></a></li>
						<?php } else if (!empty($access['USR']) && ($rdAuth->role == 'S')){?>
					<li
					<?php if ($this->params['controller'] == 'users') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb;?>users/editProfile"><span>Edit
					Profile</span></a></li>
					<?php } ?>
					<?php if (!empty($access['EVAL_TOOL'])) {
						$evaltoolSysFunc = $access['EVAL_TOOL'];    ?>
					<li
					<?php if ($this->params['controller'] == 'evaltools'  || $this->params['controller'] == 'simpleevaluations' || $this->params['controller'] == 'rubrics' || $this->params['controller'] == 'surveys' || $this->params['controller'] == 'mixevals') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb.$evaltoolSysFunc['url_link'];?>"><span><?php echo $evaltoolSysFunc['function_name']?></span></a></li>
						<?php }?>
						<?php if (!empty($access['ADV_SEARCH'])) {
							$searchSysFunc = $access['ADV_SEARCH'];    ?>
					<li
					<?php if ($this->params['controller'] == 'searchs') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb.$searchSysFunc['url_link'];?>"><span><?php echo $searchSysFunc['function_name']?></span></a></li>
						<?php }?>

						<?php if (!empty($access['SYS_PARA'])) {
							$rubricSysFunc = $access['SYS_PARA'];    ?>
					<li
					<?php if ($this->params['controller'] == 'sysparameters') echo 'id="current"'; ?>><a
						href="<?php echo $this->webroot.$this->themeWeb.$rubricSysFunc['url_link'];?>"><span><?php echo $rubricSysFunc['function_name']?></span></a></li>
						<?php }?>
				</ul>
				</div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="5"></td>
	</tr>
</table>
<div id="wrap">
<div class="box">
<div class="bi">
<div class="bt">
<div>&nbsp;</div>
</div>

<table class="title" width="100%" border="0" cellspacing="0"
	cellpadding="0">
	<tr>
		<td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?>
		<?php echo $title_for_layout;?></td>
		<td>
		<div align="right"><?php if ($this->controller->rdAuth->role != 'S' && $this->params['controller'] != 'loginout') echo '<a href="'.$this->webroot.$this->themeWeb.'framework/tutIndex" onclick="wopen(this.href, \'popup\', 800, 600); return false;">'.$html->image('layout/button_ipeer_tutorial.gif',array('border'=>'0','alt'=>'button_ipeer_tutorial')).'</a>' ?></div>
		</td>
	</tr>
</table>
		<?php if (!empty($errmsg)): ?>
<div align="center" class="title2"><font color="red"><?php echo $errmsg; ?></font></div>
<?php endif; ?> <?php if (!empty($message)): ?>
<div align="center" class="title2"><?php echo $message; ?></div>
<?php endif; ?>
<div align="left" id="loading"><?php echo $html->image('spinner.gif',array('alt'=>'spinner'))?></div>
<?php echo $content_for_layout;?>
<p>&nbsp;</p>

<h1 align="center"><span class="footer">Powered by iPeer and TeamMaker -
Created by UBC and Rosce-Hulman</span></h1>
<div class="bb">
<div>&nbsp;</div>
</div>
</div>
</div>
</div>
<script>
	  var doShowSnvRevision = false;
	  // Toggles the details display of the snv revision.
      function toggleShowSnvRevision() {
		var division = document.getElementById("svn-data");
		if (!division) { 
			alert ("svn-data div was not found");
			return;
		} else {
			doShowSnvRevision = !doShowSnvRevision;
			division.style.display = doShowSnvRevision ? "block" : "none";
		}
      }
</script>
<div style="margin-left: 50px">
	SVN <?php passthru("svn info ../.. | grep -i revision");?>
	<a href="javascript:toggleShowSnvRevision();">details</a>
	<div style="display: none" id="svn-data" >
		<pre>
			<?php passthru("svn info ../..");?>
		</pre>
	</div>
</div>
</body>
</html>
