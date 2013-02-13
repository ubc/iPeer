<!-- when render from controller, pass empty field text -->
<!-- default first option to be selected -->
<option value="">-- Pick a <?php echo $empty ?> --</option>
<?php foreach ($options as $key => $value) { ?>
<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php } ?>