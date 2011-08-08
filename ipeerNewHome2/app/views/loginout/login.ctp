<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td>

      <div id="login-form-div">
        <div class="subTable">
          <div class="table-header">iPeer Login</div>
          <div class="table-content">
            <?php echo $form->create('loginout', array('url' => array('controller' => 'loginout', 'action' => 'loginByDefault'), 'onSubmit' => 'return validate();'))?>
              <?php echo $form->input("User.username", array('label' => 'Username: ')); ?>
              <script>
                $("UserUsername").focus();
              </script>
              <?php echo $form->input("User.password", array('label' => 'Password: ')); ?>
              <?php echo $form->end("Submit") ?>
          </div>
        </div>

        <div class="subTable">
          <div class="table-header">University of British Columbia</div>
          <div class="table-content">

          <ul>
<!--<?php if (isset($rdAuth->customIntegrateCWL) && !$rdAuth->customIntegrateCWL): ?>
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> First Time Users: Use student number as username and password. </li>
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> Self Enroll (Password Required)</li>
<?php endif; ?>-->
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> <a href="<?php echo $html->url('forgot')?>" >Forgot Your Password?</a></li>
        <li><?php echo $html->image("layout/grey_arrow.gif",array("align"=>"middle",'alt'=>'grey_arrow'))?> <a href="mailto:<?php echo $admin_email ?>" >Contact iPeer Administrator</a></li>
        </div>
  </div>

</td>
</tr>
</table>
