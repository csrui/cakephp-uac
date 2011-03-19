<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
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

	function beforeSave($options) {
		
		parent::beforeSave($options);
		
		#TODO Transform Array values to JSON Objects
		
	}
	
}
?>