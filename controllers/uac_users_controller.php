<?php

class UacUsersController extends UacAppController {
	
	function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->Auth->allow('signup', 'signin', 'password_recover', 'password_change');
		$this->Auth->authenticate = $this->UacUser;
		
	}
	
	
	public function signup() {
		
		if (!empty($this->data)) {
		
			if ($this->Account->signup()) {
			
				$this->Session->setFlash(__('Your account is created', true));
				
				$this->data['UacUser']['plain_password'] = $this->data['UacUser']['password'];
								
				$signed_in = $this->requestAction(array('controller' => $this->name, 'action' => 'signin'), array('data' => $this->data));
				
				if ($signed_in !== true) {
					
					$this->Session->setFlash(__('Ok, now you can login', true));
					$this->redirect(array('controller' => $this->name, 'action' => 'signin'));
					
				}
			
			} else {
			
				$this->Session->setFlash(__('Sorry there\'s a problem and we cant create your account', true));
			
			}
			
		}
		
	}
	
	function signin() {

		# AUTO LOGIN IF SIGNING UP
		if (isset($this->data['UacUser']['plain_password'])) {
			
			$this->data['UacUser']['password'] = $this->data['UacUser']['plain_password'];
			unset($this->data['UacUser']['plain_password']);
			
			if ($this->Auth->login($this->data)) {
				$this->redirect($this->Auth->loginRedirect);
				return true;
			}
			return false;
			
		}
		
		if (!empty($this->data)) {
		
			if ($this->Account->signin()) {
			
				$this->redirect($this->Auth->loginRedirect);
				return true;
				
			} else {
	
				$this->Session->setFlash(__('No user found with the provided credentials', true));
			
			}
			
		}
		
		return false;
		
	}
	
	
	function signout() {
		
		$this->Account->logout();
		
	}
	
	function password_change($password_hash_code = null) {
		
		#TODO Refactor code to Account Component
		
		if (!is_null($this->Auth->user())) {
			
			$this->UacUser->Contain();
			$user = $this->UacUser->findById($this->Auth->user('id'));
			
		} elseif (!is_null($password_hash_code)) {
			
			$this->UacUser->Contain();
			$user = $this->UacUser->findByPasswordChangeHash($password_hash_code);
			
		}
		
		# REDIRECT IF NO VALID SESSION OR PASSWORD HASH CODE 
		if (!isset($user) || empty($user)) {
			
			$this->redirect($this->Auth->logout());
			
		}
				
		$this->set('title_for_layout', __('Change your password', true));

		if (!empty($this->data)) {

			# COMPARE CURRENT PASSWORDS
			if (is_null($password_hash_code) && ( $this->Auth->password($this->data['UacUser']['oldpassword']) != $user['UacUser']['password'] )) {
		
				$this->Session->setFlash(__('Please correct the errors below', true));
				$this->UacUser->invalidate('oldpassword', __('Your current password doesn\'t match', true));
				unset($this->data['UacUser']);
				return;
				
			}

			$user['UacUser']['password'] = $this->data['UacUser']['password'];

			$this->UacUser->id = $user['UacUser']['id'];
			if ($this->UacUser->saveField('password', $user['UacUser']['password'], true)) {
				
				$this->UacUser->saveField('password_change_hash', null);

				$this->Session->setFlash(__('Your password has been changed', true));

				# SEND EMAIL			
				$this->EmailQueue->to = $user['UacUser']['email'];
				$this->EmailQueue->from = Configure::read('Email.username');
				$this->EmailQueue->subject = sprintf('%s %s', Configure::read('App.name'), __('new password', true));
				$this->EmailQueue->template = $this->action;
				$this->EmailQueue->sendAs = 'both';
				$this->EmailQueue->delivery = 'db';
				$this->EmailQueue->send();
				
				$this->redirect(Configure::read('User.edit.redirect'));

			}

		}
		
		unset($this->data['UacUser']);
		
	}
	
	/**
	 * Generates a password_change_hash and sends to the user to grant password change permission
	 *
	 * @return void
	 * @author Rui Cruz
	 */
	function password_recover() {
		
		# PREVENT LOGGED IN USERS FROM ACCESSING THIS
		if (!is_null($this->Auth->user())) {
			$this->Session->setFlash(__('', true));
			$this->redirect($this->referer());
		}
		
		if (empty($this->data)) return;
			
		$this->UacUser->Contain();
		$user = $this->UacUser->findByEmail($this->data['UacUser']['email']);
		
		if (empty($user)) {
		
			$this->UacUser->invalidate('email', __('Sorry, we cant find any account with that e-mail address', true));
			return;
			
		}
		
		# SEND AN EMAIL SO THE USER CAN CHANGE THE PASSWORD
		$this->Account->password_recover($user);
				
		$this->Session->setFlash(__('You will receive an e-mail with a code shortly', true));
		$this->redirect('/');
				
	}

}
?>