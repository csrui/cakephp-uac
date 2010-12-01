<div class="images form">
<?php echo $this->Form->create('UacImage', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Add Image'); ?></legend>
	<?php
		echo $this->Form->input('filename', array('type' => 'file'));
		echo $this->Form->input('avatar', array('label' => 'Set as default avatar', 'type' => 'checkbox'));
		echo $this->Form->input('notes');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Images', true), array('action' => 'index'));?></li>
	</ul>
</div>