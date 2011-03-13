<?php echo $this->Html->script('/uac/js/geolocation.js', array('inline' => false)); ?>
<?php echo $this->Html->scriptBlock('

	var userGeo = userGeo(false, {input : "UacProfileLocation", notification_container : "location-notification"});

'); ?>

<?php echo $this->Html->para('notice', __('Can we check your location?<br />This allows you to find <strong>Venues</strong> and <strong>Friends</strong> near by.', true), array('id' => 'location-notification'));  ?>

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

echo $this->Form->hidden('location', array('value' => ''));

$languages = Configure::read('App.languages');
if (!empty($languages)) {
	echo $this->Form->input('language', array('options' => $languages));
}

echo $this->Form->end(__('Save profile', true));

?>