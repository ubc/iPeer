
<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF" align="center">
<tr>
<td>
<table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
<tr>
<td>
<b><?php echo empty($params['data']['SysFunction']['id'])?'Add':'Edit' ?> Sys Functions</b>
<?php echo empty($params['data']['SysFunction']['id']) ? null : $html->hidden('SysFunction/id'); ?>


<p>
<table><tr><td>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url('edit') ?>" onSubmit="return validate()">
  <table width="100%" cellspacing="0" cellpadding="4">
  <input type="hidden" name="required" id="required" value="id function_code function_name" />
  <tr>
      <td width="130" id="id_label">id*:</td>
      <td width="337" align="right"><?php echo $html->input('SysFunction/id', array('id'=>'id', 'size'=>'50', 'class'=>'validate required NUMERIC_FORMAT id_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
      <td width="663" id="id_msg" class="error"/>
  </tr>
  <tr>
      <td width="130" id="function_code_label">Function Code:</td>
      <td width="337" align="right"><?php echo $html->input('SysFunction/function_code', array('id'=>'function_code', 'size'=>'50', 'class'=>'validate required TEXT_FORMAT function_code_msg Invalid_Text._At_Least_One_Word_Is_Required.'))?></td>
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
  <?php echo $html->submit('Save') ?></td><td><?php echo $html->linkTo('Back', '/sysfunctions'); ?>
  </td></tr>
  </form>
  </table>
</td><td style="background-color: #404040"> <!-- Black border -->
  <form>
  <!-- Render the input helper-->
  <table style="background-color: #FFFFD0">
    <tr><td colspan="2"><b>Entry Helper:</b></td><tr>
    <tr><td colspan="2" style="color:gray"> (overwrites enries to the left)</td><tr>
    <tr><td align="right">ID:</td><td>          <input type="text" id="helperID"    onkeyup="updateID();"></td></tr>
    <tr><td align="right">Controller:</td><td>  <input type="text" id="helperCont"  onkeyup="updateContFuncParam();"></td></tr>
    <tr><td align="right">Function:</td><td>    <input type="text" id="helperFunc"  onkeyup="updateContFuncParam();"></td></tr>
    <tr><td align="right">First Param:</td><td> <input type="text" id="helperParam" onkeyup="updateContFuncParam();"></td></tr>

    <tr><td colspan="2">
      <table>
      <tr><td colspan="2"><b>Permission Levels:</b></td><tr>
      <tr><td align="center">Admin</td><td align="center">Instructor</td><td align="center">Student</td></tr>
      <tr><td align="center"><input name="helperRole" type="radio" onchange="updatePermissionType('A');"></td>
          <td align="center"><input name="helperRole" type="radio" onchange="updatePermissionType('AI');"></td>
          <td align="center"><input name="helperRole" type="radio" onchange="updatePermissionType('AIS');"></td>
      </tr>
      <tr><td><br /></td></tr>
      <tr><td><b>Prnt.ID:</b></td>
          <td><input type="submit" onclick="updateParentID(0);  return false;" value="Set to 0" ></td>
          <td><input type="submit" onclick="updateParentID(1000);return false;" value="Set to 1000" ></td>
          </tr>
      </table>
    </td></tr>
  </table>
  </form>
  <script>

  // Define the field's names
  var hID, hCont, hFunc, hParam;
  var dID, dCode, dFunc, dCont,  dURL, dPerm;
  //  Find all of the above on this page
  hID = $("helperID"); hCont = $("helperCont"); hFunc = $("helperFunc"); hParam = $("helperParam");
  dID = $("id"); dCode = $("function_code"); dFunc = $("function_name"); dParent = $("parent_id");
  dCont = $("controller_name"); dURL = $("url_link"); dPerm = $("permission_type");
  // Check that all of the above were actually found
  if (!(hID && hCont && hFunc && hParam && dID && dCode && dFunc && dParent && dCont && dURL && dPerm)) {
    alert("Entry Helper:\nSome Input Fields not found!");
  }

  // Updates just the ID
  function updateID() {
    dID.value = hID.value;
  }

  // Updates just the Permission Type
  function updatePermissionType(type){
    if (type !== undefined) dPerm.value = type;
    else alert("updatePermissionType(type) needs parameter.");
  }

  // Updates just the Parent Value
  function updateParentID(value) {
    if (value !== undefined) dParent.value = value;
    else alert("updateParentID(value) needs parameter.");
  }

  // Update the Controller Function, and URL Link fields
  function updateContFuncParam() {
    var c = hCont.value; var f = hFunc.value; var p = hParam.value;
    var params = p.replace(/,/g, " ").replace(/^\s*|\s*$/g,'');
    dCode.value = (c + (f ? ("_" + f) : "" )).toUpperCase();
    dFunc.value = c.charAt(0).toUpperCase() + c.substr(1) + (f ? (" " + f.charAt(0).toUpperCase() + f.substr(1)) : "");
    dCont.value = c;
    if (p) { // Add parameters if needed
      dFunc.value += " " + "(" + params.replace(/ /g, ", ") + ")"; // Format extra params to look function-style.
      dCode.value += "_" + params.replace(/ /g,"_").toUpperCase();              //
    }

    dURL.value = c + (f ? ("/" + f) : "/index") + (p ? ("/" + params.replace(/ /g,"/")) : "");
  }

  // Load the values from the left form into the helper
  function loadFromURL() {
      // Copy the ID over
      hID.value = dID.value;
      // Extract the rest of the data from the URL
      var url = dURL.value;
      // Cut of a trailing "/" if present.
      if (url.lastIndexOf("/") == (url.length - 1)) {
        url = url.substr(0, url.length - 1);
      }
      var s = url.split("/", 3);
      // Fill in controller, if present
      if (s.length >= 1) {
        hCont.value = s[0];
      }
      // Fill in function, if present
      if (s.length >= 2) {
        if (s[1] != "index") { // Index is the default.
          hFunc.value = s[1];
        }
      }
      // Fill in parameter(s), if present
      if (s.length >= 3) {
        hParam.value = s[2].replace(/\//g,", "); // proper visual parameter format
      }
  }

  // Load the values from the form
  loadFromURL();

  </script>
</table>

</td></tr></table>
</td></tr></table>
