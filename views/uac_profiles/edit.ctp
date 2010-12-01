<div id="profile-avatar">
	<span class="photo"><?php echo $uac->avatar('medium', $uac->get('UacProfile.avatar_filename')) ?></span>
	<?php echo $this->Html->link(__('Change', true), array('plugin' => 'uac', 'controller' => 'uac_images', 'action' => 'add'))?>
</div>

<?php

echo $this->Form->create('UacProfile');
echo $this->Form->input('screen_name');
echo $this->Form->input('about');
echo $this->Form->end(__('Save profile', true));

?>