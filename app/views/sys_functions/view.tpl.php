<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
<tr>
<td>
<h4>View SysFunction</h4>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['SysFunction']['id'])?'add':'edit') ?>">
<?php echo empty($params['data']['SysFunction']['id']) ? null : $html->hidden('SysFunction/id'); ?>
<p>
<table width="40%" cellspacing="0" cellpadding="4">
<tr>
	<td width="50%" id="function_code_label">Function Code:</td>
  <td align="left"><?php echo $data['SysFunction']['function_code']; ?>
</tr>
<tr>
	<td id="function_name_label">Function Name:</td>
  <td align="left"><?php echo $data['SysFunction']['function_name']; ?>
	</td>
</tr>
<tr>
	<td id="parent_id_label">Parent Id:</td>
  <td align="left"><?php echo $data['SysFunction']['parent_id']; ?>
	</td>
</tr>
<tr>
	<td id="controller_name_label">Controller Name:</td>
  <td align="left"><?php echo $data['SysFunction']['controller_name']; ?>
  </td>
</tr>
<tr>
	<td id="url_link_label">URL Link:</td>
  <td align="left"><?php echo $data['SysFunction']['url_link']; ?>
  </td>
</tr>
<tr>
	<td id="permission_type_label">Permission Type:</td>
  <td align="left"><?php echo $data['SysFunction']['permission_type']; ?>
  </td>
</tr>
</table>

</form>
</td>
</tr>
</table>
<div class="content">
    <p><small>Created: <?php echo $data['SysFunction']['creator_id']; ?></small></p>
    <p><small>Created: <?php echo $data['SysFunction']['created']; ?></small></p>
</div>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><?php echo $html->linkTo('Edit this Function', '/sysfunctions/edit/'.$data['SysFunction']['id']); ?> | <?php echo $html->linkTo('Back to Function Listing', '/sysfunctions'); ?></td>
      </tr></table>
    </td>
    <td align="right" width="55%"></td>
  </tr>
</table>
</td>
</tr>
</table>