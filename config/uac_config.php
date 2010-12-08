<?php

$config['User.edit.redirect'] = '/';

$config['User.profile.genders'] = array(
	'Unspecified' => 'Unspecified',
	'Male' => 'Male',
	'Female' => 'Female'
);

$config['User.profile.meta'] = array(
);

$config['App.languages'] = Set::merge(
	Configure::read('App.languages'),
	$config['App.languages'] = array(
		'eng' => 'English'
	)
);

?>