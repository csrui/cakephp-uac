<?php

Configure::load('uac_config');

/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class UacAppController extends AppController {
	
	function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->set('title_for_layout', __('Users', true));
		
	}
	
}

?>