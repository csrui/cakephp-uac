<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class UacRole extends UacAppModel {
	
	
	var $hasAndBelongsToMany = array(
		'UacUser' => array(
			'className' => 'Uac.UacUser'
		)
	);
	
	
}

?>