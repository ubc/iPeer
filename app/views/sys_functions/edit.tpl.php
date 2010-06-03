
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
<tr>
<td>
<b><?php echo empty($params['data']['SysFunction']['id'])?'Add':'Edit' ?> Sys Functions</b>
<?php echo empty($params['data']['SysFunction']['id']) ? null : $html->hidden('SysFunction/id'); ?>


<p>
<table width="100%" cellspacing="0" cellpadding="4">
<form name="frm" id="frm" method="POST" action="<?php echo $html->url('edit') ?>" onSubmit="return validate()">
<input type="hidden" name="required" id="required" value="id function_code function_name" />
<tr>
	<td width="130" id="id_label">id*:</td>
	<td width="337" align="right"><?php echo $html->input('SysFunction/id', array('id'=>'id', 'size'=>'50', 'class'=>'validate required NUMERIC_FORMAT id_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="id_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="function_code_label">Function Code:</td>
	<td width="337" align="right"><?php echo $html->input('SysFunction/function_code', array('id'=>'function_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT function_code_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="function_code_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="sysfunction_name_label">Function Name:</td>
	<td width="337" align="right"><?php echo $html->input('SysFunction/function_name', array('id'=>'function_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT sysfunction_name_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="sysfunction_name_msg" class="error"/>

</tr>

<tr>
	<td width="130" id="parent_id_label">Parent Id:</td>
	<td align="right"><?php echo $html->input('SysFunction/parent_id', array('id'=>'parent_id', 'size'=>'50', 'class'=>'validate required NUMERIC_FORMAT parent_id_msg Invalid_Number.')) ?>
	</td>
	<td width="663" id="parent_id_msg" class="error"/>
</tr>

<tr>
	<td width="130" id="controller_name_label">Controller Name:</td>
	<td align="right"><?php echo $html->input('SysFunction/controller_name', array('id'=>'controller_name', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT controller_name_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?>
	</td>
	<td width="663" id="controller_name_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="url_link_label">URL Link:</td>
	<td align="right"><?php echo $html->input('SysFunction/url_link', array('id'=>'url_link', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT url_link_msg Invalid_Email_Format.')) ?>
  </td>
  <td width="663" id="url_link_msg" class="error"/>
</tr>

<tr>
	<td width="130" id="permission_type_label">Permission Type:</td>
	<td align="right"><?php echo $html->input('SysFunction/permission_type', array('id'=>'permission_type', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT permission_type_msg Invalid_Email_Format.')) ?>
  </td>
  <td width="663" id="permission_type_msg" class="error"/>
</tr>
<tr><td>
<?php echo $html->submit('Save') ?> <?php echo $html->linkTo('Back', '/sysfunctions'); ?>
</td></tr>
</form>
</table>
<p>

</p>

</td>
</tr>
</table>
</td>
</tr>
</table>
