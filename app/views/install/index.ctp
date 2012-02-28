<?php
// NOTE:
// The detection logic should really be in the controller. But the code is 
// pretty clean right now, so we'll defer that refactoring until needed.
//
// Need to be restyled so that it looks better. Deferred until default layout
// is refactored.
//

function get_php_setting($val) {
	$r =  (ini_get($val) == '1' ? 1 : 0);
	return $r ? 'ON' : 'OFF';
}

$no = '<b class="red">'.__('No', true).'</b>';
$yes = '<b class="green">'.__('Yes', true).'</b>';

// mandatory requirements init
$phpver = $no;
$REQPHPVER = '5.0';
$mysql = $no;
$configwritable = $no;
$dbconfig = $no;

// optional requirements init
$sendmail = $no;
$emailperm = $no;

// mandatory requirements check
if (version_compare(phpversion(), $REQPHPVER) >= 0)
{
  $phpver = $yes;
}
if (function_exists('mysql_connect'))
{
  $mysql = $yes;
}
if (is_writable(CONFIGS))
{
  $configwritable = $yes;
}
if (is_writable(CONFIGS.'database.php'))
{
  $dbconfig = $yes;
}

// optional requirements check
if (ini_get("sendmail_path"))
{
  $sendmail = $yes;
}
$output;
$return_var;
exec("atq",$output,$return_var); //won't work on windows
if($return_var == 0)
{
  $emailperm = $yes;
}

// recommended requirements init
$php_recommended_settings = array(
  array (
    'name' => 'Safe Mode',
    'directive' => 'safe_mode',
    'recommend' => 'OFF'
  ),
  array (
    'name' => 'Display Errors',
    'directive' => 'display_errors',
    'recommend' => 'ON'
  ),
  array (
    'name' => 'File Uploads',
    'directive' => 'file_uploads',
    'recommend' => 'ON'
  ),
  array (
    'name' => 'Register Globals',
    'directive' => 'register_globals', 
    'recommend' => 'OFF'
  ),
  array (
    'name' => 'Output Buffering',
    'directive' => 'output_buffering',
    'recommend' => 'OFF'
  ),
  array (
    'name' => 'Session auto start',
    'directive' => 'session.auto_start',
    'recommend' => 'OFF'
  ),
);

// recommended requirements check
foreach ($php_recommended_settings as &$setting)
{
  $actual = get_php_setting($setting['directive']);
  if ($actual == $setting['recommend'])
  {
    $setting['actual'] = "<b class='green'>".$actual."</b>";
  }
  else
  {
    $setting['actual'] = "<b class='red'>".$actual."</b>";
  }
}

?>


<div class='install'>
<h3>Step 1: System Requirements Check</h3>

<h4>Mandatory</h4>
<table>
  <tr>
    <td width="70%"><?php __('PHP version')?> &gt;= <?php echo $REQPHPVER;?></td>
    <td width="30%"><?php echo $phpver; ?></td>
  </tr>
  <tr>
    <td><?php __('MySQL support')?></td>
    <td><?php echo $mysql; ?></td>
  </tr>
  <tr>
    <td><?php __('Directory app/config writable ')?></td>
    <td><?php echo $configwritable; ?></td>
  </tr>
  <tr>
    <td><?php __('File app/config/database.php writable ')?></td>
    <td><?php echo $dbconfig; ?></td>
  </tr>
</table>

<h4>Optional</h4>

<table>
  <tr>
    <td width="70%"><?php __('Sendmail or Sendmail Wrapper <br />(Required if you want email functions.)')?></td>
    <td width="30%"><?php echo $sendmail ?></td>
  </tr>
  <tr>
	  <td><?php __('Permissions for email scheduling. <br />If failed, remove Apache user from "/etc/at.deny" or "/var/at/at.deny"')?></td>
	  <td><?php echo $emailperm; ?></td>
  </tr>
</table>

<h4>Recommended</h4>
<table>
  <tr>
    <th width="40%"><?php __('PHP Directive')?></th>
    <th width="30%"><?php __('Recommended')?></th>
    <th width="30%"><?php __('Actual')?></th>
  </tr>
  <?php
  foreach ($php_recommended_settings as $setting)
  {
    echo "<tr>";
    echo "<td>". $setting['name'] ."</td>";
    echo "<td>". $setting['recommend'] ."</td>";
    echo "<td>". $setting['actual'] ."</td>";
    echo "</tr>";
  }
  ?>
</table>
<form action="<?php echo $html->url('install2') ?>" id="sysreqform">
  <?php 
  echo $form->submit('Next >>', array('id' => 'next')); 
  ?>
</form>
</div>
