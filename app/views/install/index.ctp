<?php
// NOTE:
// The detection logic should really be in the controller. But the code is
// pretty clean right now, so we'll defer that refactoring until needed.
//

function get_php_setting($val)
{
    $r =  (ini_get($val) == '1' ? 1 : 0);
    return $r ? 'ON' : 'OFF';
}

$no = '<b class="red">'.__('No', true).'</b>';
$yes = '<b class="green">'.__('Yes', true).'</b>';

// Mandatory requirements init
$phpver = $no;
$REQPHPVER = '7.2';
$mysql = $no;
$configwritable = $no;
$dbconfig = $no;
$magicquotes = $yes;
$guard_plugin = $no;

// Optional requirements init
$sendmail = $no;
$emailperm = $no;

// Mandatory requirements check
if (version_compare(phpversion(), $REQPHPVER) >= 0) {
  $phpver = $yes;
}
if (function_exists('mysqli_connect')) {
  $mysql = $yes;
}
if (get_magic_quotes_gpc()) {
    // magic quotes need to be off or json gets escaped, ref ticket #330
    $magicquotes = $no;
}
if (file_exists(APP.DS.'plugins'.DS.'guard'.DS.'config'.DS.'guard_default.php') || file_exists(CONFIGS.'guard.php')) {
    $guard_plugin = $yes;
}

// Optional requirements check
if (ini_get("sendmail_path")) { // send mail
  $sendmail = $yes;
}
// ldap
$ldap = function_exists('ldap_connect') ? $yes : $no;

// DOMDocment
$domdoc = (extension_loaded('dom') && class_exists('DOMDocument')) ? $yes : $no;

// Recommended requirements init

// make sure that the php memory limit is at least 64 mb
$limit = ini_get('memory_limit');
$unit = substr($limit, -1);
// convert to bytes
if ($limit == -1) $limit = 9999999999999; # no memory limit
else if (strcasecmp($unit, 'k') == 0) $limit = (int)$limit * 1024;
else if (strcasecmp($unit, 'm') == 0) $limit = (int)$limit * 1024 * 1024;
else if (strcasecmp($unit, 'g') == 0) $limit = (int)$limit * 1024 * 1024 * 1024;
$memlimit = $no;
if ($limit >= 64 * 1024 * 1024) $memlimit = $yes;

$php_recommended_settings = array(
  array (
    'name' => 'Safe Mode',
    'directive' => 'safe_mode',
    'recommend' => 'OFF'
  ),
  array (
    'name' => 'Display Errors',
    'directive' => 'display_errors',
    'recommend' => 'OFF'
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

// Recommended requirements check
foreach ($php_recommended_settings as $key => $setting)
{
    if (isset($setting['directive'])) {
        $php_recommended_settings[$key]['actual'] = get_php_setting($setting['directive']);
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
    <td><?php __('magic_quotes_gpc is off ')?></td>
    <td><?php echo $magicquotes; ?></td>
  </tr>
  <tr>
    <td><?php __('Guard Plugin')?>
        <div class='help'><?php __('If you cloned iPeer from git, you may need to run git submodule init and git submodule update.')?></div>
    </td>
    <td><?php echo $guard_plugin; ?></td>
  </tr>
</table>

<h4>Optional</h4>

<table>
  <tr>
    <td width="70%"><?php __('Sendmail or Sendmail Wrapper')?>
        <div class="help"><?php __('Required if you want email functions.')?></div>
    </td>
    <td width="30%"><?php echo $sendmail ?></td>
  </tr>
  <tr>
      <td><?php __('PHP LDAP Extension.')?>
        <div class="help"><?php __('Required if you are planning to use LDAP authentication.')?></div>
      </td>
      <td><?php echo $ldap; ?></td>
  </tr>
  <tr>
    <td><?php __('PHP DOMDocument.') ?>
        <div class="help"><?php __('Required if you are planning to use TeamMaker.')?></div>
    </td>
    <td><?php echo $domdoc; ?></td>
</table>

<h4>Recommended</h4>
<table>
    <tr>
        <td width="70%">
        <?php __('PHP memory limit at least 64 MB') ?>
        <div class="help"><?php __('Some operations, such as export, requires more memory when dealing with large courses.')?></div>
        </td>
        <td width="30%"><?php echo $memlimit ?></td>
    </tr>
</table>
<table>
  <tr>
    <th width="40%"><?php __('PHP Directive')?></th>
    <th width="30%"><?php __('Recommended')?></th>
    <th width="30%"><?php __('Actual')?></th>
  </tr>
  <?php foreach ($php_recommended_settings as $setting):?>
    <tr>
    <td><?php echo $setting['name']?>
        <div class="help"><?php echo isset($setting['description']) ? $setting['description'] : ''?></div>
    </td>
    <td><?php echo $setting['recommend']?></td>
    <td><b class='<?php echo ($setting['actual'] == $setting['recommend'] ? 'green' : 'red')?>'><?php echo $setting['actual']?></font></td>
    </tr>
  <?php endforeach; ?>
</table>
<form action="<?php echo $html->url('install2') ?>" id="sysreqform">
  <?php
  echo $form->submit('Next >>', array('id' => 'next'));
  ?>
</form>
</div>
