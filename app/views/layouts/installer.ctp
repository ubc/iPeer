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
<?php echo $html->css('datepicker')?>
<?php //echo $html->script('calculate_marks')?>
<?php echo $html->script('prototype')?>
<?php echo $html->script('ipeer')?>
<?php echo $html->script('showhide')?>
<!-- AJAX Include Files -->
<?php echo $html->script('scriptaculous')?>
<?php echo $html->script('zebra_tables')?>
<!-- End of AJAX Include Files -->
<!-- Validation Include Files -->
<?php echo $html->script('validate')?>
<?php echo $html->script('submitvalidate')?>
<!-- End of Validation Include Files -->
</head>
<body>
<table class="tableback" width="94%" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>
    <td width="2%" rowspan="2"><?php echo $html->image('layout/banner_left.gif',array('alt'=>'banner_left'))?></td>
    <td width="25%" height="72" valign="top"><span class="style1">
        <?php echo $html->image('layout/ipeer_banner.gif',array('alt'=>'ipeer_banner'))?></span>
    <span class="bannerText"><span style='font-size: 120%;'>2.2</span>&nbsp;&nbsp;<?php __('with TeamMaker')?></span></td>
    <td width="46%" valign="top"><br />
      <table width="100%" border="0" align="right" cellpadding="0" cellspacing="4" class="miniLinks">
      <tr class="miniLinks">
          <td width="157">&nbsp;</td>
          <td width="57" align="right"></td>
      </tr>
    </table></td>
    <td width="6%" rowspan="2" valign="top"><div align="right"><?php echo $html->image('layout/blocks.gif',array('alt'=>'blocks'))?></div></td>
    <td width="2%" rowspan="2" valign="top"><div align="right"><?php echo $html->image('layout/banner_right.gif',array('alt'=>'banner_right'))?></div></td>
  </tr>
  <tr>
    <td height="50" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="971" height="38" valign="bottom">
        <div id="header">
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="5"></td>
  </tr>
</table>
<div id="wrap">
  <div class="box">
    <div class="bi">
      <div class="bt"><div>&nbsp;</div></div>

      <div class="content-wrap">
      <div class='title'>
        <span class='text'><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?>
          <?php echo $title_for_layout;?> </span>
      </div>

        <?php if (!empty($errmsg)): ?>
        <div align="center" class="title2"><font color="red"><?php echo $errmsg; ?></font></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
        <div align="center" class="title2"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($session->check('Message.flash')): echo $session->flash(); endif;?>
        <?php echo $this->Session->flash('auth'); ?>
        <?php echo $this->Session->flash('email'); ?>
        <?php echo $this->Session->flash('auth'); ?>
        <div align="left" id="loading"><?php echo $html->image('spinner.gif',array('alt'=>'spinner'))?></div>
        <div id="content"><?php echo $content_for_layout;?></div>
      <h1 align="center"><span class="footer"><?php __('Powered by iPeer and TeamMaker - Created by UBC and Rose-Hulman')?></span></h1>
      </div>

      <div class="bb"><div>&nbsp;</div></div>
    </div>
  </div>
</div>

<!-- Debugging output -->
<?php if(!Configure::read('debug') == 0 &&
        isset($this->params['controller']) &&
        'install' != $this->params['controller'] &&
        'upgrade' != $this->params['controller']) { ?>

    <!-- Prepare the SVN revision number and table -->
    <script language="JavaScript" type="text/javascript">
        // Toggles the details display of the snv revision.
        function toggleDivision(divID) {
            var division = $(divID);
            division ?
                (division.style.display = (division.style.display == "block") ? "none" : "block") :
                (alert ("division was not found"));
        }

    </script>
    <?php
    exec ("svn info ../..", $lines, $retval);
    $revision = __("revision (unknown)", true);
    //Ouput each line as a table
    $svnTable = "<table style='background-color:#FFF5EE'>";
    foreach ($lines as $line) {
        // Convert each output line of "svn info" to an html table.
        if (!empty($line)) {
            if (strpos($line,"Revision") === FALSE) {
                $line = str_replace (": ","</td><td>", $line);
                $svnTable .= "<tr><td>";
                $svnTable .= $line;
                $svnTable .= "</td></tr>";
            } else {
                // If this line is about a revision, save it for display later on.
                $revision = $line;
            }
        }
    }
    $svnTable .= "</table>";
    ?>
    <div style="margin-left: 50px">

    <!-- Render a few debuging elements -->
    <table width="95%"><tr>
    <td>SVN <?php echo $revision;?>
        <a href="javascript:toggleDivision('svn-data');">(details)</a></td>
    <td>User ID: none 
        <a href="javascript:toggleDivision('user-data');">(details)</a></td>
    <td>Courses: <?php echo empty($coursesList) ? 0 : count($coursesList); ?>
        <a href="javascript:toggleDivision('coursesList-data');">(details)</a></td>
    <td>Access: <?php echo empty($action) ? 0 : count($action); ?>
        <a href="javascript:toggleDivision('access-data');">(details)</a></td>
    <td>Actions: <?php echo empty($access) ? 0 : count($access); ?>
        <a href="javascript:toggleDivision('actions-data');">(details)</a></td>
    <td>Params: <?php echo empty($params) ? 0 : count($params); ?>
        <a href="javascript:toggleDivision('params-data');">(details)</a></td>
    <td>SQL Log
        <a href="javascript:toggleDivision('SQL-data');">(details)</a></td>
    <td> <a style="color:blue" href="javascript:toggleDivision('allowedBy-data')"><?php __('Allowed By', true)?>&hellip;</a></td>
    </tr>

</table>

    <!-- The actual debugging data -->
    <div style="display: none" id="svn-data">
        <?php echo $svnTable; ?></div>
    <div style="display: none; background-color:#FFFFE9; width: 90%;" id="user-data">
        <h1>$user variable</h1>
        <?php echo "(Empty)"; ?></div>
    <div style="display: none; background-color:#E9FFFF; width: 90%;" id="coursesList-data">
        <h1>$coursesList variable</h1>
        <?php if(!empty($coursesList)) var_dump($coursesList); else echo __("(Empty)", true)?></div>
    <div style="display: none; background-color:#FFE9FF; width: 90%;" id="actions-data">
        <h1>$actions variable</h1>
        <?php if(!empty($action)) var_dump($action); else echo __("(Empty)", true)?></div>
    <div style="display: none; background-color:#E9FFFF; width: 90%;" id="access-data">
        <h1>$access variable</h1>
        <?php if(!empty($access)) var_dump($access); else echo __("(Empty)", true)?></div>
    <div style="display: none; background-color:#E9FFFF; width: 90%;" id="params-data">
        <h1>$params variable</h1>
        <?php if(!empty($params)) var_dump($params); else echo __("(Empty)", true)?></div>
    <div style="display: none; width: 95%; text-align: right;" id="allowedBy-data">
        <?php echo !empty($allowedBy)? $allowedBy : __("No AllowedBy data was set", true); ?></div>

    <div style="display: none; width: 95%; background-color: #FFE9FF;" id="SQL-data">
        <?php
            $dataSource = ConnectionManager::getDataSource('default');
            echo !empty($dataSource) ? $dataSource->showLog() : __("No SQL data", true);
        ?></div>

<?php } // end if(!constant('DEBUG') == 0) ?>
<?php echo $this->Js->writeBuffer(); // Write cached scripts?>
</body>
</html>
