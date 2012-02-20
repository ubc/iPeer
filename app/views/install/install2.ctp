<script type="text/javascript">
<!--
function agreesubmit() {
  if ($('#agreecheck').is(':checked'))
  {
    $('#next').removeAttr('disabled');
  }
  else
  {
    $('#next').attr('disabled', 'disabled');
  }
}
//-->
</script>

<div class='install'>
  <h3>Step 2: License Agreement</h3>

  <p>
  <a href="http://ipeer.olt.ubc.ca">iPeer</a> 
  <?php __(' is Free Software released under the GNU/GPL License.')?> 
  </p>

  <iframe src="gpl" width="100%" height="600" class="license" frameborder="0">
  </iframe>

  <form action="<?php echo $html->url('install3') ?>" 
  method="post" name="adminForm" 
  onsubmit="return defaultagree(this)" id="gplform">
    <input type="checkbox" name="agreecheck" id="agreecheck" 
    onclick="agreesubmit(this)" class="floatleft" />
    <label for="agreecheck">I Accept the GPL License</label>
    <?php 
    echo $form->submit('Next >>', 
      array('disabled'=>'true', 'name'=>'next', 'id' => 'next')) 
    ?>
  </form>
</div>
