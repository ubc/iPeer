<div class="faculties form">
<?php echo $this->Form->create('Faculty');?>
	<fieldset>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Create', true));?>
</div>
