<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td><h4><font color="green">Files have been created successfully!</font></h4>
<p><b>File list:</b><br />
<ul>
    <?php foreach ($files as $file): ?>
        <li><?php echo $file; ?></li>
    <?php endforeach; ?>
</ul><br />
    <a href="<?php echo $html->url('index'); ?>">Go back!</a>&nbsp;&nbsp;
    OR&nbsp;&nbsp;
    <a href="<?php echo $url; ?>" target="blank">Visit "<?php echo $url; ?>"!</a>
</p></td>
  </tr>
</table>
