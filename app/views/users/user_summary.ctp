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
<?php echo $this->element('users/user_summary_list', array('data'=>$data['failed_students']));?>
<?php endif; ?>

<?php $msg = ('resetPassword' == $this->action || 'edit' == $this->action) ? 'User(s) modified successfully:' : 'User(s) created successfully:';?>

<?php if (isset($data['created_students'])) : ?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td width="300">
            <b><?php echo $msg?></b>
          </td>
          <td></td>
        </tr></table>
      </td>
      <td width="55%">&nbsp; </td>
    </tr>
  </table>
<?php echo $this->element('users/user_summary_list', array('data'=>$data['created_students']));?>
<?php endif; ?>

<?php if (isset($data['User'])) : ?>
  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td width="45%"><table width="403" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td width="300">
            <b><?php echo $msg?></b>
          </td>
          <td></td>
        </tr></table>
      </td>
      <td width="55%">&nbsp; </td>
    </tr>
  </table>
<?php echo $this->element('users/user_summary_list', array('data'=>$data['User']));?>
<?php endif; ?>

  <table width="95%"  border="0" cellspacing="2" cellpadding="4">
    <tr>
      <td>
      <?php echo $html->link('Back to User Add', '/users/add/'); ?>&nbsp;|&nbsp;
      <?php echo $html->link('Back to User Listing', '/users/index/'); ?>
      <?php echo (!empty($course_id)) ? '&nbsp;|&nbsp;' . $html->link('Back to Course Home', '/courses/home/'.$course_id) : '';?>
      </td>
    </tr>
  </table>
</td></tr></table>
