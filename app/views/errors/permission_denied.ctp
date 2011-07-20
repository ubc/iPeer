<center><div style='width:50%; padding: 20px;border: 1px solid #000; background-color:#FFFFE0'>
<h2><?php __('Access Denied')?></h2>
<h4>( to: <tt><script language='javascript' type='text/javascript'>document.write (document.location.href);</script> )</tt></h4>
<h5><?php echo (!empty($message) ? "reason: $message" : __("You don't have permission to access this page.", true))?></h5>
<input type='button' value=<?php __('Click here to go back')?> onClick='history.back()'>
</div>
</center>
