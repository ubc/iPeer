<br />
<table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo $html->image('layout/icon_ipeer_logo.gif',array('border'=>'0','alt'=>'icon_ipeer_logo'))?> Import Groups From Text (.txt) or CSV File (.csv)</td>
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
    <td width="50%">Instructions</td>
    <td width="50%">Import</td>
  </tr>
  <tr class="tablecell2">
    <td>
      NOTE:
      <ul>
        <li>Please make sure the username column matches the username column in student import file.</li>
        <li>Please make sure to remove the header in CSV file.</li>
        <li>All columns are required.</li>
      </ul>

      <br />
      Format:
      <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
Username, Group# (e.g. 5 for group 5), and Group Name
      </pre>

      Example:
      <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
29978037, 1, Team A
29978063, 1, Team A
29978043, 2, Team B
29978051, 2, Team B
      </pre>
	</td>
    <td valign="top"><br>
<form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import') ?>" enctype="multipart/form-data" >
    <h3>1) Please select a CSV file to import:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="file" name="file" value="Browse" /><br>
    <?php
        $params = array('controller'=>'users', 'coursesList'=>$coursesList, "defaultOpt" => $courseId);
    ?>
    <br /><h3>2) Select the course to import into:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo $this->element('courses/course_selection_box', $params); ?>
    <br /><br /><h3>3) Click the button bellow to Create the Groups:</h3>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Import Group CSV"/>
</form>
<br></td>
  </tr>
</table>