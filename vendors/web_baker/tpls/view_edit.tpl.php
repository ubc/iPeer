<style type="text/css">
  div.error_message {background-color:#FFE8E8;border:1px solid red;padding:4px;margin:2px}
</style>

<h4><?php echo empty($params['data']['%MODEL_NAME%']['id'])?'Add':'Edit' ?> %MODEL_NAME%</h4>
<form name="frm" id="frm" method="POST" action="<?php echo $html->url(empty($params['data']['%MODEL_NAME%']['id'])?'add':'edit') ?>">
<?php echo empty($params['data']['%MODEL_NAME%']['id']) ? null : $html->hidden('%MODEL_NAME%/id'); ?>
<p>
	Field 1: <?php echo $html->input('%MODEL_NAME%/field1', array('size'=>'50')) ?>
  <?php echo $html->tagErrorMsg('%MODEL_NAME%/field1', 'Title is required.') ?>
</p>	
<p>
	Field 2:<?php echo $html->input('%MODEL_NAME%/field2', array('size'=>'50')) ?>
  <?php echo $html->tagErrorMsg('%MODEL_NAME%/field2', 'Body is required.') ?>
</p>
<p>
	<?php echo $html->submit('Save') ?>
</p>
</form>