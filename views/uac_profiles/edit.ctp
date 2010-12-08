<div id="profile-avatar">
	<span class="photo"><?php echo $uac->avatar('medium', $uac->get('UacProfile.avatar_filename')) ?></span>
	<?php echo $this->Html->link(__('Change', true), array('plugin' => 'uac', 'controller' => 'uac_images', 'action' => 'add'))?>
</div>

<ul>
	<li><?php echo $this->Html->Link('Change password', array('controller' => 'uac_users', 'action' => 'password_change'))?></li>
</ul>

<?php

echo $this->Form->create('UacProfile');
echo $this->Form->input('id');
echo $this->Form->input('screen_name');
echo $this->Form->input('about');

echo $this->Form->input('first_name');
echo $this->Form->input('last_name');

echo $this->Form->input('dob', array('label' => 'Date of birth', 'type' => 'text', 'class' => 'datepicker'));
echo $this->Form->input('gender', array('options' => $genders));

echo $this->Form->input('address');

$languages = Configure::read('App.languages');
if (!empty($languages)) {
	echo $this->Form->input('language', array('options' => $languages));
}

echo $this->Form->end(__('Save profile', true));

?>