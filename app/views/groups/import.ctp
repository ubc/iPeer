<br />
<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> <?php __('Import Groups From Text (.txt) or CSV File (.csv)')?></td>
        <td></td>
      </tr>
  </table>
<div id="import" style="background: #FFF;">
  <br>
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
	<table width="95%"  border="0" cellspacing="2" cellpadding="8" align="center">
  <tr class="tableheader">
    <td width="50%"><?php __('Instructions')?></td>
    <td width="50%"><?php __('Import')?></td>
  </tr>
  <tr class="tablecell2">
    <td>
      <?php __('NOTE:')?>
      <ul>
        <li><?php __('Please make sure the username column matches the username column in student import file.')?></li>
        <li><?php __('Please make sure to remove the header in CSV file.')?></li>
        <li><?php __('All columns are required.')?></li>
      </ul>

      <br />
      <?php __('Format')?>:
      <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
<?php __('Username, Group# (e.g. 5 for group 5), and Group Name')?>
      </pre>

      <?php __('Example')?>:
      <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
29978037, 1, <?php __('Team A')?>
29978063, 1, <?php __('Team A')?>
29978043, 2, <?php __('Team B')?>
29978051, 2, <?php __('Team B')?>
      </pre>
	</td>
    <td valign="top"><br>
<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" >
    <h3>1) <?php __('Please select a CSV file to import')?>:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="file" name="file" value="<?php __('Browse')?>" /><br>
    <?php
        $params = array('controller'=>'users', 'coursesList'=>$coursesList, "defaultOpt" => $courseId);
    ?>
    <br /><h3>2) <?php __('Select the course to import into')?>:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo $this->element('courses/course_selection_box', $params); ?>
    <br /><br /><h3>3) <?php __('Click the button bellow to Create the Groups')?>:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="<?php __('Import Group CSV')?> "/>
</form>
<br></td>
  </tr>
</table>