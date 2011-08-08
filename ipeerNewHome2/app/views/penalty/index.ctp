<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
<tr class="tablecell2">
  <td>Allow Self-Evaluation?:</td>
  <td>
	<?php
      echo $form->input('Event.self_eval', array(
		'type' => 'radio',
		'options' => array('1' => ' - Enable&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '0' => ' - Disable'),
		'default' => '0',
		'legend' => false
      ));
    ?>
  </td>
  <td>&nbsp;</td>
</tr>
</table>


<script>
  function addElement(tag_type, target, parameters) {
	//Create element
    var newElement = document.createElement(tag_type);

    //Add parameters
      if (typeof parameters != 'undefined') {
		for (parameter_name in parameters) {
		  newElement.setAttribute(parameter_name, parameters[parameter_name]);
        }
      }

	//Append element to target
	document.getElementById(target).appendChild(newElement);
  }
</script>