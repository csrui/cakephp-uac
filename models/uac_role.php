<?php

class UacRole extends UacAppModel {
	
	
	var $hasAndBelongsToMany = array(
		'UacUser' => array(
			'className' => 'Uac.UacUser'
		)
	);
	
	
}

?>