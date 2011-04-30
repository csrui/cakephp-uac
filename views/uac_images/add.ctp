<div class="images form">
	<h2><?php __('Upload a picture') ?></h2>

	<?php echo $this->Form->create('UacImage', array('type' => 'file'));?>
		<?php echo $this->Form->input('filename', array('type' => 'file')); ?>
		<?php echo $this->Form->hidden('avatar', array('label' => 'Set as default avatar', 'type' => 'checkbox', 'value' => true)); ?>
		<?php echo $this->Form->input('notes'); ?>
	<?php echo $this->Form->end(__('Upload', true));?>
	
</div>