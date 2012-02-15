<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
<tr>
<td>
<b><?php echo empty($params['data']['SysParameter']['id'])?'Add':'Edit' ?> <?php __('Sys Parameters')?></b>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['SysParameter']['id'])?'add':'edit') ?>" onSubmit="return validate()">
<?php echo empty($params['data']['SysParameter']['id']) ? null : $html->hidden('SysParameter/id'); ?>
<input type="hidden" name="required" id="required" value="id parameter_code function_name" />
<p>
<table width="100%" cellspacing="0" cellpadding="4">
<tr>
	<td width="130" id="id_label"><?php __('id*:', true)?></td>
	<td width="337" align="left"><?php echo $html->input('SysParameter/id', array('id'=>'id', 'size'=>'50', 'class'=>'validate required NUMERIC_FORMAT id_msg Invalid_Numeric_Value.'))?></td>
	<td width="663" id="id_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="parameter_code_label"><?php __('Parameter Code')?>:</td>
	<td width="337" align="left"><?php echo $html->input('SysParameter/parameter_code', array('id'=>'function_name', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT parameter_code_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="parameter_code_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="parameter_value_label"><?php __('Parameter Value')?>:</td>
	<td width="337" align="left"><?php echo $html->input('SysParameter/parameter_value', array('id'=>'parameter_value', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT parameter_value_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="parameter_value_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="parameter_type_label"><?php __('Paramenter Type')?>:</td>
	<td align="left"><?php
	  $types = array('S'=>__('String', true),'I'=>__('Integer', true), 'B'=>__('Boolean', true));
		echo $html->selectTag('SysParameter/parameter_type', $types, $html->tagValue('SysParameter/parameter_type'), null, null, false);
	   ?>
	</td>
	<td width="663" id="parameter_type_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="description_label"><?php __('Description')?>:</td>
	<td align="left"><?php echo $html->input('SysParameter/description', array('id'=>'description', 'size'=>'50', 'class'=>'validate none TEXT_FORMAT description_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?>
	</td>
	<td width="663" id="description_msg" class="error"/>
</tr>
<tr>
  <td colspan="3" align="left">
  <br />
  * &nbsp;&nbsp;<strong><?php __('id</strong> of sys_parameters table are manually assigned and grouped by module as follow:')?><br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1000 &nbsp; 	system<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2000 &nbsp; 	display<br/>
  </td>
</tr>
</table>
<p>
	<?php echo $html->submit(__('Save', true)) ?><?php echo $html->link(__('Back', true), '/sysparameters'); ?>
</p>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>
