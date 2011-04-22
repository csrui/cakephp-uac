<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class UacUsersController extends UacAppController {
	
	function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->Auth->allow('social_signin', 'signup', 'signin', 'password_recover', 'password_change');
		$this->Auth->authenticate = $this->UacUser;
		
		if (in_array($this->action, array('social_sigin', 'signup', 'signin')) && $this->Auth->user()) {
			
			$this->redirect($this->Auth->loginRedirect);
			
		}
		
	}
	
	/**
	 * Handle Gigya sigin
	 *
	 * @return void
	 * @author Rui Cruz
	 */
	public function social_signin() {
		
		$this->autoRender = false;
		
		if (empty($_GET)) {
			$this->Session->setFlash(__('Unable to authenticate you using social networks', true));
			$this->redirect($this->Auth->loginAction);
			return false;
		}
		
		$uid = $_GET['UID'];
		
		# FIND EXISTING USER
		
		$this->data = $this->UacUser->UacGigya->findById($uid);
				
		if ($this->data !== true) {
			
			$this->data['UacGigya'] = array(
				'id' => $_GET['UID'],
				'provider' => $_GET['provider'],
				'data' => serialize($_GET)
			);
			
			$this->data['UacUser'] = array(
				'email' => $_GET['email'],
				'password' => '--social-network--'
			);
			
			$this->data['UacProfile'] = array(
				'screen_name' => $_GET['nickname']
			);

			$this->Account->signUp();
			
			$this->data['UacGigya']['uac_user_id'] = $this->UacUser->id;			
			$this->UacUser->UacGigya->save($this->data);
			
		}
		
		# MUST ADD PASSWORD OR AUTH::LOGIN WONT WORK
		$this->data['UacUser']['password'] = '--social-network--';
		
		# AUTHENTICATE AND REDIRECT
		if ($this->Auth->login($this->data['UacUser'])) {
			
			$this->Account->afterSignin();
			$this->redirect($this->Auth->loginRedirect);
			return true;
			
		} else {
			
			$this->log('Unable to Auth the user from a social network:');
			$this->log($this->data);
			$this->Session->setFlash(__('Unable to authenticate you using social networks', true));
			$this->redirect($this->Auth->loginAction);
			
		}
		
	}
	
	public function signup() {
		
		if (!empty($this->data)) {
			
			if ($this->Account->checkInvitation() !== true) {
				
				$this->Session->setFlash(__('Sorry but the activation code is invalid or expired', true));				
				
			} elseif ($this->Account->signup()) {
			
				$this->Session->setFlash(__('Your account is created', true));
				
				$this->data['UacUser']['plain_password'] = $this->data['UacUser']['password'];
								
				$signed_in = $this->requestAction(array('controller' => $this->name, 'action' => 'signin'), array('data' => $this->data));
				
				if ($signed_in !== true) {
					
					$this->Session->setFlash(__('Ok, now you can login', true));
					$this->redirect($this->Auth->loginAction);
					
				}
			
			} else {
			
				$this->Session->setFlash(__('Please review the form, we can\'t create your account', true));
			
			}
			
		} elseif (isset($_GET['activation_code'])) {
			
			$this->data['UacUser']['activation_code'] = $_GET['activation_code'];
			
		}
		
		unset($this->data['UacUser']['password']);
		
	}
	
	public function signin() {

		# AUTO LOGIN IF SIGNING UP
		if (isset($this->data['UacUser']['plain_password'])) {
			
			$this->data['UacUser']['password'] = $this->data['UacUser']['plain_password'];
			unset($this->data['UacUser']['plain_password']);
			
			if ($this->Auth->login($this->data)) {
				$this->Account->afterSignin();
				$this->redirect($this->Auth->loginRedirect);
				return true;
			}
			return false;
			
		}
		
		if (!empty($this->data)) {
		
			if ($this->Account->afterSignin()) {
			
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
			$user = $this->UacUser->findById($this->Account->id());
			
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