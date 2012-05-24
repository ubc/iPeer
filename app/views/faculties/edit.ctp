<div class="faculties form">
<?php echo $this->Form->create('Faculty');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Save', true));?>
</div>
