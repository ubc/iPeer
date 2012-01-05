<?php
if ($isadmin)
{
?>
<p>
You are about to upgrade your iPeer instance. Please make sure you have backed up your database and files before proceeding!
</p>
<p>
<a href='<?php echo $html->url('step2'); ?>'>Confirm</a>
</p>
<?php
}
?>
