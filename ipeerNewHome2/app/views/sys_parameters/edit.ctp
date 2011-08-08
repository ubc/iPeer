<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
<tr>
<td>
<b><?php
echo empty($data['SysParameter']['id'])?'Add':'Edit' ?> Sys Parameters</b>


    <?php    
    echo $this->Form->create('Sysparameter', 
                                   array('id' => 'frm',
                                         'url' => array('action' => 'edit')))?>

<?php echo empty($data['SysParameter']['id']) ? null : $form->hidden('id'); ?>
<input type="hidden" name="required" id="required" value="id parameter_code function_name" />
<p>
<table width="100%" cellspacing="0" cellpadding="4">
<tr>
	<td width="130" id="id_label">id*:</td>
	<td width="337" align="left"><?php echo $form->input('SysParameter.id', array('id'=>'id', 'size'=>'50', 'type'=>'text', 'label'=>false, 'class'=>'validate required NUMERIC_FORMAT id_msg Invalid_Numeric_Value.'))?></td>
	<td width="663" id="id_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="parameter_code_label">Parameter Code:</td>
	<td width="337" align="left"><?php echo $form->input('SysParameter.parameter_code', array('id'=>'function_name', 'type'=>'text', 'label'=>false, 'size'=>'50', 'class'=>'validate required TEXT_FORMAT parameter_code_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="parameter_code_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="parameter_value_label">Parameter Value:</td>
	<td width="337" align="left"><?php echo $form->input('SysParameter.parameter_value', array('id'=>'parameter_value', 'size'=>'50', 'type'=>'text', 'label'=>false, 'class'=>'validate required TEXT_FORMAT parameter_value_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
	<td width="663" id="parameter_value_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="parameter_type_label">Paramenter Type:</td>
	<td align="left"><?php
	  $types = array('S'=>'String','I'=>'Integer', 'B'=>'Boolean');
		echo $form->select('SysParameter.parameter_type', $types, $html->value('SysParameter.parameter_type'));
	   ?>
	</td>
	<td width="663" id="parameter_type_msg" class="error"/>
</tr>
<tr>
	<td width="130" id="description_label">Description:</td>
	<td align="left"><?php echo $form->input('SysParameter.description', array('id'=>'description', 'size'=>'50', 'type'=>'text', 'label'=>false, 'class'=>'validate none TEXT_FORMAT description_msg Invalid_Text._At_Least_One_Word_Is_Required.')) ?>
	</td>
	<td width="663" id="description_msg" class="error"/>
</tr>
<tr>
  <td colspan="3" align="left">
  <br />
  * &nbsp;&nbsp;<strong>id</strong> of sys_parameters table are manually assigned and grouped by module as follow:<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1000 &nbsp; 	system<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2000 &nbsp; 	display<br/>
  </td>
</tr>
</table>
<p>
	<?php echo $form->submit('Save') ?><?php echo $html->link('Back', '/sysparameters'); ?>
</p>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>
