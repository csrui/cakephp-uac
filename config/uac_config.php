<?php

$config['User.edit.redirect'] = '/';
$config['User.signup.redirect'] = '/';
$config['User.signup.agreement'] = '/page/user_agreement';

$config['User.profile.genders'] = array(
	'Unspecified' => 'Unspecified',
	'Male' => 'Male',
	'Female' => 'Female'
);

$config['User.profile.meta'] = array(
);

$config['User.cookie.lifetime'] = '+2 days';

$config['App.languages'] = Set::merge(
	Configure::read('App.languages'),
	$config['App.languages'] = array(
		'eng' => 'English'
	)
);

?>