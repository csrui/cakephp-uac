<?php

class AccountComponent extends Object {
	
	var $components = array('Facebook.Connect', 'CronMailer.EmailQueue');
	
	var $controller = null;
	
	var $settings = array(
		'cookie_name' => 'remember_me',
		'roles_redirect' => '/'
	);
	
	var $roles = array();
	
	var $data = null;
	
	//called before Controller::beforeFilter()
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
		
		$this->settings = Set::merge($this->settings, $settings);
		
	}
	
	/**
	 * Return Auth session
	 *
	 * @return array
	 * @author Rui Cruz
	 */
	public function user($property = null) {
		
		$auth_data = $this->controller->Session->read('Auth');
		
		if (is_null($property)) {
			
			return $auth_data;
			
		} else {
			
			return Set::extract($property, $auth_data);
			
		}
		
	}	
	
	/**
	 * After signin in, we add extra data to the current session
	 *
	 * @return bol
	 * @author Rui Cruz
	 */
	function afterSignin() {
		
		if ($this->controller->Auth->user()) {

			# CLEAN UP AFTER SIGNUP / LOGIN
			unset($this->controller->Auth->data['UacUser']['plain_password']);

			$this->controller->UacUser->Contain('UacProfile', 'UacRole');
			$this->data = $this->controller->UacUser->findById($this->user('UacUser.id'));

			if (empty($this->data)) {

				$this->log('Account::afterSignin is failing');
				$this->log($conditions);
				return false;

			}

			unset($conditions[$this->settings['cookie_name']]);

			# SET SESSION WITH ALL MODEL INFORMATION
			foreach($this->data as $model_name => $model_data) {
	
				$this->controller->Session->write('Auth.'.$model_name, $model_data);
		
			}
	
			# UPDATE USERS LAST_LOGIN PROPERTY
			$this->controller->UacUser->id = $this->data['UacUser']['id'];
			$this->controller->UacUser->saveField('last_login', date('Y-m-d H:i:s'));
			#TODO Clear password_change_hash
	
			$this->__setCookies();
			
			return true;
			
		}
		
		return false;
		
	}
	
	
	/**
	 * Handles signup data. Created a new User account and a new Profile
	 *
	 * @return bol
	 * @author Rui Cruz
	 */
	public function signup() {

		if (!empty($this->controller->data)) {
			
			if ($this->controller->UacUser->signUp($this->controller->data)) {
				
				$this->afterSignup();
				return true;
				
			} else {
				
				unset($this->controller->data['UacUser']['password']);
				
				return false;
				
			}
			
		}

	}
	
	
	private function afterSignup() {
		
		$this->EmailQueue->to = $this->controller->data['UacUser']['email'];
		$this->EmailQueue->from = Configure::read('Email.username');
		$this->EmailQueue->subject = sprintf('%s %s', Configure::read('App.name'), __('New account activation', true));
		$this->EmailQueue->template = $this->controller->action;
		$this->EmailQueue->sendAs = 'both';
		$this->EmailQueue->delivery = 'db';
		$this->EmailQueue->send();
		
	}
	
	/**
	 * Saves profiles changes and updates Auth session data
	 *
	 * @param array $data 
	 * @return mixed
	 * @author Rui Cruz
	 */
	public function updateProfile($data) {
		
		if (empty($data)) return false;
		
		if ($this->controller->UacProfile->save($data) !== false) {
			
			$new_session_data = $this->user();
			$new_session_data = Set::merge($new_session_data, $data);
			
			$this->controller->Session->write('Auth', $new_session_data);
			
		}
		
		return $result;
		
	}
	
	/**
	 * Send an e-mail to the user to reset the password
	 *
	 * @param array $user 
	 * @return void
	 * @author Rui Cruz
	 */
	function password_recover($user) {		
		
		# GENERATE NEW HASH AND SAVE IT
		$this->controller->UacUser->id = $user['UacUser']['id'];
		$new_hash = Security::hash($user['UacUser']['email'].time(), null, true);
		$this->controller->UacUser->saveField('password_change_hash', $new_hash);

		# SEND AN EMAIL WITH AN URL TO RESET THE PASSWORD
		$hashed_url = Router::url(array('plugin' => null, 'controller' => 'users', 'action' => 'password_change', $new_hash));
		
		$this->controller->set(array(
			'email' => $user['UacUser']['email'],
			'new_hash' => $new_hash,
			'hashed_url' => $hashed_url
		));

		$this->EmailQueue->to = $user['UacUser']['email'];
		$this->EmailQueue->from = Configure::read('Email.username');
		$this->EmailQueue->subject = sprintf('%s %s', Configure::read('App.name'), __('password recovery', true));
		$this->EmailQueue->template = $this->controller->action;
		$this->EmailQueue->sendAs = 'both';
		$this->EmailQueue->delivery = 'db';
		$this->EmailQueue->send();		
		
	}
	
	
	
	/**
	 * 
	 * Search for at least one role in the user session
	 * @param unknown_type $needed_roles
	 * @param unknown_type $redirect
	 */
	function checkRoles($needed_roles, $redirect = false) {
		
		if (empty($this->roles)) {
			$this->roles = $this->controller->Session->read('Auth.UacRole');
		}
		
		$result = false;
		
		if (!empty($needed_roles) && !empty($this->roles)) {
		
			foreach($needed_roles as $need_role) {
				
				foreach($this->roles as $sess_role) {
					
					if (strtolower($need_role) == strtolower($sess_role['name'])) {
						
						$result = true;
						break;
						
					}
					
				}
				
			}
			
		}
		
		if ( ($redirect === true) && ($result !== true) ) {
		
			$this->controller->Session->setFlash(__("You don't have permission to access that resource", true));
			$this->controller->redirect($this->settings['roles_redirect']);
			
		}

		return $result;
		
	}
	
	function checkInvitation() {
		
		# CHECK IF INVITATION IS MANDATORY
		if (configure::read('App.invitation_only') == false) {
			
			$result = true;
			
		} elseif (!isset($this->controller->data['UacUser']['activation_code'])) {
			
			$result = false;
			
		} else {
		
			define('INVITATION_CODE', $this->controller->data['UacUser']['activation_code']);
			$InvitationCode = ClassRegistry::init('Invitation.InvitationCode');
			$record = $InvitationCode->findByCode(INVITATION_CODE);
			
			if (!$record) {
				
				$result = false;
				
			} elseif ($record['InvitationCode']['amount'] == 0) {
				
				$result = false;
				
			} else {
			
				$InvitationCode->id = $record['InvitationCode']['id'];
				$InvitationCode->saveField('amount', $record['InvitationCode']['amount']-1);
				$result = true;
				
			}
			
		}
		
		$this->controller->Session->write('Signup.invitation_ok', $result);
		
	}
	
	/**
	 * Save Cookies if controller data is available
	 * @return void
	 */
	function __setCookies() {
		
		if (!empty($this->controller->data)) {
			
			$cookie = array();
			$cookie['email'] = $this->controller->data['UacUser']['email'];
			#$cookie['password'] = $this->controller->data['UacUser']['password']; #TODO Check if password is encripted or not
			
			if (isset($this->controller->data[$this->controller->Auth->userModel][$this->settings['cookie_name']])) {
				
				# SET A NEW COOKIE
				$this->controller->Cookie->write('Auth.'.$this->controller->Auth->userModel, $cookie, true, Configure::read('User.cookie.lifetime'));
			    unset($this->controller->data[$this->controller->Auth->userModel][$this->settings['cookie_name']]);
				
			}
		}
		
	}
	
	/**
	 * Kills current session and cookies
	 * @return void
	 */
	function logout() {
		
		$this->controller->autoRender = false;
		$this->controller->Auth->logout();
		
		# MAKE SURE ALL OTHER UAC MODELS ARE REMOVED FROM SESSION 
		$this->controller->Session->delete('Auth');
		$this->controller->redirect($this->controller->Auth->logoutRedirect);
		
	}
	
			
}
	
?>