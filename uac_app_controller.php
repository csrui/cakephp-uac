<?php

Configure::load('uac_config');

class UacAppController extends AppController {
	
	function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->set('title_for_layout', __('Users', true));
		
	}
	
}

?>