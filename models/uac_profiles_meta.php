<?php
class UacProfileMeta extends UacAppModel {
	
	var $belongsTo = array(
		'UacProfile' => array(
			'className' => 'UacProfile',
			'foreignKey' => 'uac_profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
}
?>