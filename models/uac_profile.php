<?php

class UacProfile extends UacAppModel {
	
	var $name = 'UacProfile';
	
	var $validate = array(
		'uac_user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'UacUser' => array(
			'className' => 'Uac.UacUser',
			'foreignKey' => 'uac_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
		'UacImage' => array(
			'className' => 'Uac.UacImage',
			'foreignKey' => 'uac_profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);	
	
}
?>