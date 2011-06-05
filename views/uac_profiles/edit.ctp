<?php echo $this->Html->script('/uac/js/geolocation.js', array('inline' => false)); ?>
<?php echo $this->Html->scriptBlock('

	var userGeo = userGeo(false, {input : "UacProfileLocation", location_name_input: "UacProfileLocationName", notification_container : "location-notification"});

'); ?>

<h2><?php __('Edit your profile') ?></h2>

<span class="grid_8 alpha">

	<?php

	echo $this->Form->create('UacProfile');
	echo $this->Form->input('id');
	echo $this->Form->input('screen_name');
	echo $this->Form->input('about');

	echo $this->Form->input('first_name');
	echo $this->Form->input('last_name');

	echo $this->Form->input('dob', array('label' => 'Date of birth', 'class' => 'dobpicker', 'empty' => true, 'minYear' => date('Y', strtotime('-80 years')), 'maxYear' => date('Y', strtotime('-5 years'))));
	echo $this->Form->input('gender', array('options' => $genders));

	echo $this->Form->hidden('location', array('value' => ''));
	echo $this->Form->input('location_name', array('label' => __('Location', true), 'title' => __('Let your friends know where you are', true)));

	echo $this->Form->input('timezone', array('type' => 'select', 'options' => $timezones, 'empty' => '--Please select--'));

	$languages = Configure::read('App.languages');
	if (!empty($languages)) {
		echo $this->Form->input('language', array('options' => $languages));
	}

	echo $this->Html->para('notice', __('Can we check your location?<br />This allows you to find <strong>Venues</strong> and <strong>Friends</strong> near by.', true), array('id' => 'location-notification'));

	echo $this->Form->end(__('Save profile', true));

	?>
	</span>

<span class="grid_2 omega">
	
	<div id="profile-avatar">
		<span class="photo"><?php echo $uac->avatar('medium', $uac->get('UacProfile.avatar_filename')) ?></span>
		<br />
		<?php echo $this->Html->link(__('Change picture', true), array('plugin' => 'uac', 'controller' => 'uac_images', 'action' => 'add'))?>
	</div>

	<ul>
		<li><?php echo $this->Html->Link('Change password', array('plugin' => 'uac', 'controller' => 'uac_users', 'action' => 'password_change'))?></li>
	</ul>
	
</span>
