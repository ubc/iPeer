<?php
if ($isadmin && $upgradefailed)
{
?>
  <p>
  The database upgrade failed with error: <?php echo $upgradefailed; ?>
  </p>
<?php
}
else if ($isadmin)
{
?>
  <p>
  The iPeer database has been upgraded to the latest version. Please logout and re-login.
  </p>
<?php
}
?>
