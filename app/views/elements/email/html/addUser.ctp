<p>
Hello <?php echo $name; ?>,
</p>
<p>
An instructor or admin has created an account for you in iPeer.
</p>
<ul>
  <li>Username: <?php echo $username; ?></li>
  <li>Password: <?php echo $password; ?></li>
</ul>
<p>
You can login <a href='<?php echo $siteurl; ?>'>here</a>.
</p>
<?php
if (!empty($courses)) {
  echo "<p>You have been enrolled in the following courses:</p>";
  echo "<ul>";
  foreach($courses as $val) {
    echo "<li>$val</li>";
  }
  echo "</ul>";
}
?>
