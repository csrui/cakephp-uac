<?php
class UacUsersController extends UacAppController {

	var $name = 'UacUsers';
	
	function beforeFilter() {
		
		parent::beforeFilter();
		$this->Auth->allow('signup', 'password_recover', 'password_change');
		$this->Auth->authenticate = $this->UacUser;
		
	}
	
	
	function signup() {
		
		if (!empty($this->data)) {
			
			$this->UacUser->create($this->data);			
			if ($this->UacUser->save($this->data)) {
				
				$this->Session->setFlash(__('Your account is created', true));
				$this->redirect(array('action' => 'signin'));
				
			} else {
				
				$this->Session->setFlash(__('Sorry there\'s a problem and we cant create your account', true));
				unset($this->data['UacUser']['password']);
				
			}
			
		}
		
	}
	
	
	function signin() {
		
		if (!empty($this->data)) {
		
			if ($this->Auth->user()) {
	
				$this->Account->login();
				$this->redirect($this->Auth->redirect());
				
			} else {
	
				$this->Session->setFlash(__('No user found with the provided username and password', true));
				
			}
			
		}
		
	}
	
	
	function signout() {
		
		$this->Account->logout();
		
	}
	

	function password_change($password_hash_code = null) {
		
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
				$this->redirect(array('controller' => 'uac_profiles', 'action' => 'edit'));

				/*
				$this->set(array(
                       #'username' => $user['UacUser']['username'],
                       'password' => $user['UacUser']['password'],
				));

				# SEND EMAIL
			
				if (Configure::read('debug') > 0) $this->Email->delivery = 'debug';
			
				$this->Email->smtpOptions = Configure::read('Email');
				$this->Email->from = sprintf('%s <%s>', Configure::read('App.name'), Configure::read('App.email'));
				$this->Email->subject = 'Here\'s your account login to ' . Configure::read('App.name');
				$this->Email->to = $user['UacUser']['email'];
				$this->Email->template = $this->action;
				$this->Email->sendAs = 'html';

				if (!$this->Email->send()) {
				
					$this->log("Error sending email '{$this->action}'", LOG_ERROR);
					$this->log($this->Email->smtpError, LOG_ERROR);
					$this->Session->setFlash(sprintf(__('There was an error sending the e-mail, please contact us at %s', true), Configure::read('App.email')));
				
				} else {
				
					$this->redirect(Configure::read('User.edit.redirect'));
				
				}
				*/

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
		
		if (!empty($this->data)) {
			
			$this->UacUser->Contain();
			$user = $this->UacUser->findByEmail($this->data['UacUser']['email']);
			
			if (empty($user)) {
			
				$this->UacUser->invalidate('email', __('Sorry, we cant find any account with that e-mail address', true));
				return;
				
			}
			
			# GENERATE NEW HASH AND SAVE IT
			$this->UacUser->id = $user['UacUser']['id'];
			$new_hash = Security::hash($user['UacUser']['email'].time(), null, true);
			$this->UacUser->saveField('password_change_hash', $new_hash);
			
			$this->set(array(
                   'email' => $user['UacUser']['email'],
                   'new_hash' => $new_hash,
			));

			# SEND EMAIL
			
			if (Configure::read('debug') > 0) $this->Email->delivery = 'debug';
			
			$this->Email->smtpOptions = Configure::read('Email');
			$this->Email->from = sprintf('%s <%s>', Configure::read('App.name'), Configure::read('App.email'));
			$this->Email->subject = __('Password recovery from ', true) . Configure::read('App.name');
			$this->Email->to = $user['UacUser']['email'];
			$this->Email->template = $this->action;
			$this->Email->sendAs = 'html';

			if (!$this->Email->send()) {
				
				$this->log("Error sending email '{$this->action}'", LOG_ERROR);
				$this->log($this->Email->smtpError, LOG_ERROR);
				$this->Session->setFlash(sprintf(__('There was an error sending the e-mail, please contact us at %s', true), Configure::read('App.email')));
				
			} else {
				
				$this->Session->setFlash(__('You will recieve an e-mail with a code shortly', true));
				$this->redirect('/');
				
			}
			
		}
				
	}

}
?>