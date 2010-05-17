<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td align="center">
  <br>
<?php if (isset($data['failed_students'])) : ?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td width="300">
            <b><font color="#FF0000">User(s) failed on creation:</font></b>
          </td>
          <td></td>
        </tr></table>
      </td>
      <td width="55%">&nbsp; </td>
    </tr>
  </table>
<?php
$params = array('controller'=>'users', 'data'=>$data['failed_students']);
echo $this->renderElement('users/user_summary_list', $params);
?>
<?php endif; ?>
<?php if (isset($data['created_students'])) : ?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td width="300">
            <b>User(s) created successfully:</b>
          </td>
          <td></td>
        </tr></table>
      </td>
      <td width="55%">&nbsp; </td>
    </tr>
  </table>
<?php
$params = array('controller'=>'users', 'data'=>$data['created_students']);
echo $this->renderElement('users/user_summary_list', $params);
?>
<?php endif; ?>
<?php if (isset($data['User'])) : ?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td width="300">
            <b>User(s) created successfully:</b>
          </td>
          <td></td>
        </tr></table>
      </td>
      <td width="55%">&nbsp; </td>
    </tr>
  </table>
<?php
$params = array('controller'=>'users', 'data'=>$data['User']);
echo $this->renderElement('users/user_summary_list', $params);
?>
<?php endif; ?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
        <tr>
  			  <td colspan="3">
  			    <?php echo $html->linkTo('Back to User Add', '/users/add/'.$userRole); ?>&nbsp;|&nbsp;
            <?php echo $html->linkTo('Back to User Listing', '/users/index/'); ?>
            <?php
                if (!empty($rdAuth->courseId)) {
                  echo '&nbsp;|&nbsp;';
                  echo $html->linkTo('Back to Course Home', '/courses/home/'.$rdAuth->courseId);
                }
          ?>
          </td>
        </tr></table>
      </td>
      <td width="55%">&nbsp; </td>
    </tr>
  </table>
</td></tr></table>