<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
<html>
<head>
<title>iPeer V2 with TeamMaker</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/-->
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
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
  var redirectURL = "<?php echo $this->webroot.$this->themeWeb;?> " + "users/editProfile";
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
<body <?php
    if (!empty($this->params)) {
        if ($this->params['controller'] != 'loginout') {
            if (!empty($rdAuth->role) && $rdAuth->role == 'S') {
                if (empty($rdAuth->email))
                echo 'onload="checkEmailAddress()"';
                }
        } elseif (substr($_SERVER['REQUEST_URI'],-14) == "loginByDefault") {
                echo 'onload="document.getElementById(\'username\').focus()"';
        }
    }
          ?> >
<table class="tableback" width="94%" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>
    <td width="2%" rowspan="2"><?php echo $html->image('layout/banner_left.gif',array('alt'=>'banner_left'))?></td>
    <td width="25%" height="72" valign="top"><span class="style1"><?php echo $html->image('layout/ipeer_banner.gif',array('alt'=>'ipeer_banner'))?></span><span class="bannerText">with TeamMaker     </span></td>
    <td width="46%" valign="top"><br />
      <table width="100%" border="0" align="right" cellpadding="0" cellspacing="4" class="miniLinks">
      <tr class="miniLinks">
        <?php if (!empty( $rdAuth->id)) :?>
        <td width="350" align="right"><?php echo $html->image('layout/icon_edit.gif',array('alt'=>'icon_edit'))?> <a href="<?php echo $this->webroot.$this->themeWeb;?>users/editProfile" class="miniLinks">Edit Profile (<?php echo $rdAuth->fullname ?>)</a></td>
        <td width="57" align="right"><?php echo $html->image('layout/icon_arrow.gif',array('alt'=>'icon_arrow'))?> <a href="<?php echo $this->webroot.$this->themeWeb;?>loginout/logout" class="miniLinks">Logout</a></td>
        <?php else:?>
        <td width="157"/>
        <td width="57" align="right"><?php echo $html->image('layout/icon_arrow.gif',array('alt'=>'icon_arrow'))?> <a href="<?php echo $this->webroot.$this->themeWeb;?>loginout/login" class="miniLinks">Login</a></td>
        <?php endif;?>

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
          <ul>
            <?php

            /**
             * Creates a user interface tab:
             *      $object and $access should always be set at $this and $access respectively.
             *      $accessVar is the key inside the sys_function function list.
             *      $activeControllers is an aeeay containg all the controllers for which this tab will be
             *        highlighted as active.
             *      $prefix and $postfix will be shown if passesed. Can be text or HTML.
             */
            function generateTab ($object, $access, $accessVar, $activeControllers, $prefix='', $postfix='') {
                if (!empty($access[$accessVar])) {
                    // Get the variables
                    $sysFunc = $access[$accessVar];
                    $name = $sysFunc['function_name'];
                    $url = $object->webroot . $object->themeWeb . $sysFunc['url_link'];
                    // Generate the HTML
                    $current = in_array($object->params['controller'], $activeControllers) ? "id='current'" : "";
                    echo "<li $current><a href='$url'><span>$prefix $name $postfix</span></a></li>";
                }
            }
            ?>

            <!-- Build the actual Tabs -->
            <?php
                if (!empty($access)) {
                    // Home Tab
                    generateTab($this, $access, 'HOME', array('home'),
                                $html->image('layout/icon_home.gif',array('border'=>'0','alt'=>'icon_home')));

                    // Course Tab
                    generateTab($this, $access, 'COURSE', array('courses', 'events', 'groups', 'evaluations', 'surveygroups'));

                   // Users Tab
                    $isStudent = ($rdAuth->role == 'S');
                    if (!$isStudent) {
                        generateTab($this, $access, 'USERS' , array('users'));
                    } else {
                        generateTab($this, $access, 'USR_PROFILE' , array('users'));
                    }

                    //Evaluation Tools Tab
                    generateTab($this, $access, 'EVAL_TOOL',
                                array('evaltools', 'simpleevaluations', 'rubrics', 'surveys', 'mixevals'));

                    // Advanced Search Tab
                    generateTab($this, $access, 'ADV_SEARCH', array('searchs'));

                    // System Parameters Tab
                    generateTab($this, $access, 'SYS_PARA',  array('sysparameters'));

                    // System Functions Tab
                    generateTab($this, $access, 'SYS_FUNC', array('sysfunctions'));
                }
            ?>

          </ul>
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
      <div class="bt">
      <div>&nbsp;</div>
      </div>

      <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?>
          <?php echo $title_for_layout;?> </td>
        <td>
      <div align="right">
        <?php
        if (!empty($this->params)) {
            if ($this->controller->rdAuth->role != 'S' && $this->params['controller'] != 'loginout') {
                echo '<a href="' . $this->webroot . $this->themeWeb .
                'framework/tutIndex" onclick="wopen(this.href, \'popup\', 800, 600); return false;">' .
                $html->image('layout/button_ipeer_tutorial.gif',
                array('border'=>'0','alt'=>'button_ipeer_tutorial')) . '</a>';
            }
        }
        ?>
        </div></td>
      </tr>
      </table>
        <?php if (!empty($errmsg)): ?>
        <div align="center" class="title2"><font color="red"><?php echo $errmsg; ?></font></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
        <div align="center" class="title2"><?php echo $message; ?></div>
        <?php endif; ?>
        <div align="left" id="loading"><?php echo $html->image('spinner.gif',array('alt'=>'spinner'))?></div>
        <?php echo $content_for_layout;?>
			<p>&nbsp;</p>

      <h1 align="center"><span class="footer">Powered by iPeer and TeamMaker - Created by UBC and Rose-Hulman</span></h1>
      <div class="bb">
        <div>&nbsp;</div>
      </div>
    </div>
  </div>
</div>

<!-- Debugging output -->
<?php if(!constant('DEBUG') == 0) { ?>

    <!-- Prepare the SVN revision number and table -->
    <script language="JavaScript" type="text/javascript">
        // Toggles the details display of the snv revision.
        function toggleDivision(divID) {
            var division = $(divID);
            if (!division) {
                alert ("svn-data div was not found");
                return;
            } else {
                if (division.style.display == "block") {
                    division.style.display = "none";
                } else {
                    division.style.display = "block";
                }
            }
        }
    </script>
    <?php
    exec ("svn info ../..", $lines, $retval);
    $revision = "revision (unknown)";
    //Ouput each line as a table
    $svnTable = "<table style='background-color:#FFF5EE'>";
    foreach ($lines as $line) {
        // Convert each output line of "svn info" to an html table.
        if (!empty($line)) {
            if (stripos($line,"revision") === FALSE) {
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
    <!-- Render a few other debuging elements -->
    <table width="95%"><tr>
    <td>SVN <?php echo $revision;?>
        <a href="javascript:toggleDivision('svn-data');">(details)</a></td>
    <td>User ID: <?php echo empty($rdAuth) ? "none" : $rdAuth->id ?>
        <a href="javascript:toggleDivision('rdAuth-data');">(details)</a></td>
    <td>Courses: <?php echo empty($coursesList) ? 0 : count($coursesList); ?>
        <a href="javascript:toggleDivision('coursesList-data');">(details)</a></td>
    <td>Access: <?php echo empty($action) ? 0 : count($action); ?>
        <a href="javascript:toggleDivision('access-data');">(details)</a></td>
    <td>Actions: <?php echo empty($access) ? 0 : count($access); ?>
        <a href="javascript:toggleDivision('actions-data');">(details)</a></td>
    <td>Params: <?php echo empty($params) ? 0 : count($params); ?>
        <a href="javascript:toggleDivision('params-data');">(details)</a></td>
    </tr></table>

    <!-- The actual debugging data -->
    <div style="display: none" id="svn-data">
        <?php echo $svnTable?></div>
    <div style="display: none; background-color:#FFFFE9; width: 90%;" id="rdAuth-data">
        <h1>$rdAuth variable</h1>
        <?php if(!empty($rdAuth)) var_dump($rdAuth); else echo "(Empty)"?></div>
    <div style="display: none; background-color:#E9FFFF; width: 90%;" id="coursesList-data">
        <h1>$coursesList variable</h1>
        <?php if(!empty($coursesList)) var_dump($coursesList); else echo "(Empty)"?></div>
    <div style="display: none; background-color:#FFE9FF; width: 90%;" id="actions-data">
        <h1>$actions variable</h1>
        <?php if(!empty($action)) var_dump($action); else echo "(Empty)"?></div>
    <div style="display: none; background-color:#E9FFFF; width: 90%;" id="access-data">
        <h1>$access variable</h1>
        <?php if(!empty($access)) var_dump($access); else echo "(Empty)"?></div>
    <div style="display: none; background-color:#E9FFFF; width: 90%;" id="params-data">
        <h1>$params variable</h1>
        <?php if(!empty($params)) var_dump($params); else echo "(Empty)"?></div>


<?php } // end if(!constant('DEBUG') == 0) { ?>
</body>
</html>