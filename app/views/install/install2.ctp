<script type="text/javascript">
<!--
var checkobj;

function agreesubmit(el){
	checkobj=el;
	if (document.all||document.getElementById){
		for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
			var tempobj=checkobj.form.elements[i];
			if(tempobj.type.toLowerCase()=="submit")
				tempobj.disabled=!checkobj.checked;
		}
	}
}

function defaultagree(el){
	if (!document.all&&!document.getElementById){
		if (window.checkobj&&checkobj.checked)
			return true;
		else{
			alert(__("Please read/accept license to continue installation", true));
			return false;
		}
	}
}
//-->
</script>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center">
  <br>
<form action="<?php echo $html->url('install3') ?>" method="post" name="adminForm" id="adminForm" onSubmit="return defaultagree(this)">
<input type="hidden" name="required" id="required" value="agreecheck" />

<table width="95%"  border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td>&nbsp;</td>
    <td><?php __('Step 2: License Agreement')?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><a href="http://ipeer.olt.ubc.ca">iPeer </a> <?php __('is Free Software released under the GNU/GPL License.')?> </td>
  </tr>
  <!-- Property of System Requirements -->
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="10%">&nbsp;</td>
        <td><strong><?php __('GNU/GPL License')?>: </strong></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="100%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#E9ECEF">
            <tr>
              <td><iframe src="gpl" width="100%" height="500" class="license" frameborder="0" scrolling="auto"></iframe> </td>
            </tr>
         </table></td>
         <td>&nbsp;</td>
        </tr>
    </table></td>
  </tr>

  <!-- Next -->
  <tr>
    <td width="80%" colspan="2"  align="center">

      <input type="checkbox" name="agreecheck"  class="inputbox" onClick="agreesubmit(this)" />
          <span class="style3"><?php __(' I Accept the GPL License')?></span></td>
    <td align="right">
      <?php echo $form->submit('Next >>', array('disabled'=>'true', 'name'=>'next')) ?>
    </td>
  </tr>
</table>
</form>
</td></tr></table>
