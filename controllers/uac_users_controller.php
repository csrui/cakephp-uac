<?php
class UacUsersController extends UacAppController {

	var $name = 'UacUsers';
	
	function beforeFilter() {
		
		parent::beforeFilter();
		$this->Auth->allow('signup');
		
	}
	
	
	function signup() {
		
		if (!empty($this->data)) {
			$this->UacUser->create($this->data);
			if ($this->UacUser->save($this->data)) {
				$this->data['UacProfile']['uac_user_id'] = $this->UacUser->id;
				$this->UacUser->UacProfile->create();
				$this->UacUser->UacProfile->save($this->data);
				$this->Session->setFlash(__('Your account is created', true));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash(__('Sorry there\'s a problem and we cant create your account', true));
			}
			
		}
		
	}
	
	
	function signin() {
		
		if ($this->Auth->user()) {

			$this->Account->login();
			$this->redirect($this->Auth->redirect());
			
		} else {
			$this->Session->setFlash(__('No user found with the provided username and password', true));
		}
		
	}
	
	
	function signout() {
		
		$this->Account->logout();
		
	}

}
?>