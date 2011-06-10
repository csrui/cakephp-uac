<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class AccountComponent extends Object {
	
	var $components = array(
		'CronMailer.EmailQueue',
		'Uac.Gravatar'
	);
	
	var $controller = null;
	
	var $userModel = null;

	
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
		
		if (isset($this->controller->UacUser)) {
			$this->userModel = $this->controller->UacUser;
		}
		
		$this->settings = Set::merge($this->settings, $settings);
		
	}
	
	public function setUserModel(&$userModel) {
		
		$this->userModel = $userModel; 
		
	}
	
	/**
	 * Alias functions to get the associated model ID
	 *
	 * @return int
	 * @author Rui Cruz
	 */
	public function id() {
		
		return $this->user('UacProfile.id');
		
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
	 * Gets the current user offset from the current server
	 *
	 * @return int
	 * @author Rui Cruz
	 */
	public function offset() {
		
		$timezone = date_create('now', timezone_open($this->Account->user('UacProfile.timezone')));
		return $timezone->getOffset();		
		
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

			$this->userModel->Contain('UacProfile', 'UacRole');
			$data = $this->userModel->findById($this->user('UacUser.id'));

			if (empty($data)) {

				$this->log('Account::afterSignin is failing');
				return false;

			}

			# SET SESSION WITH ALL MODEL INFORMATION
			foreach($data as $model_name => $model_data) {
	
				$this->controller->Session->write('Auth.'.$model_name, $model_data);
		
			}
	
			# UPDATE USERS LAST_LOGIN PROPERTY
			$this->userModel->id = $data['UacUser']['id'];
			$this->userModel->saveField('last_login', date('Y-m-d H:i:s'));
	
			$this->__setCookies();
			
			return true;
			
		}
		
		return false;
		
	}
	
	/**
	 * Create a new account for a user
	 *
	 * @param array $data 
	 * @return bol
	 * @author Rui Cruz
	 */
	public function signup($data) {		

		if ($this->createNewAccount($data)) {
			
			$this->afterSignup($data);
			return true;
			
		} else {
			
			unset($this->controller->data['UacUser']['password']);			
			return false;
			
		}

	}
		
	private function afterSignup($data) {
		
		#TODO Try to set profile image using a gravatar
		#var_dump($this->Gravatar->getImage($this->controller->data['UacUser']['email'], 80, GravatarImageSet::FOUROFOUR, GravatarRating::X));
		
		$this->EmailQueue->to = $data['UacUser']['email'];
		$this->EmailQueue->from = Configure::read('Email.username');
		$this->EmailQueue->subject = sprintf('%s %s', Configure::read('App.name'), __('New account activation', true));
		$this->EmailQueue->template = $this->controller->action;
		$this->EmailQueue->sendAs = 'both';
		$this->EmailQueue->delivery = 'db';
		$this->EmailQueue->send();
		
	}
	
	
	public function inviteNewUser($data) {

		if ($this->createNewAccount($data)) {
			
			$data['UacUser']['id'] = $this->userModel->id;
			
			$new_hash = $this->generatePasswordHash($data);

			# SEND AN EMAIL WITH AN URL TO RESET THE PASSWORD
			$hashed_url = Router::url(array('plugin' => null, 'controller' => 'users', 'action' => 'activate', $new_hash));

			$this->controller->set(array(
				'email' => $data['UacUser']['email'],
				'new_hash' => $new_hash,
				'hashed_url' => configure::read('App.url') . $hashed_url
			));
			
			return true;

		} else {

			return false;

		}

	}
	
	/**
	 * Handles signup data. Creates a new User account and a new Profile
	 *
	 * @return bol
	 * @author Rui Cruz
	 */	
	private function createNewAccount($data) {

		return $this->userModel->signUp($data);
		
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

		# IF SPACIAL DATA IS EMPTY WE SHOULD NOT UPDATE
		if (!empty($data['UacProfile']['location'])) {
			
			$data['UacProfile']['location'] = DboSource::expression(sprintf('POINT(%s)', $data['UacProfile']['location']));
			
		}
		
		if ($this->controller->UacProfile->save($data) !== false) {
			
			$new_session_data = $this->user();
			$new_session_data = Set::merge($new_session_data, $data);
			
			$this->controller->Session->write('Auth', $new_session_data);
			return true;
			
		}
		
		return false;
		
	}
	
	/**
	 * Send an e-mail to the user to reset the password
	 *
	 * @param array $user 
	 * @return void
	 * @author Rui Cruz
	 */
	public function password_recover($user) {		
				
		$new_hash = $this->generatePasswordHash($user);

		# SEND AN EMAIL WITH AN URL TO RESET THE PASSWORD
		$hashed_url = Router::url(array('plugin' => null, 'controller' => 'users', 'action' => 'password_change', $new_hash));
		
		$this->controller->set(array(
			'email' => $user['UacUser']['email'],
			'new_hash' => $new_hash,
			'hashed_url' => configure::read('App.url') . $hashed_url
		));

		$this->EmailQueue->to = $user['UacUser']['email'];
		$this->EmailQueue->from = sprintf('%s <%s>', Configure::read('App.name'), Configure::read('Email.username'));
		$this->EmailQueue->subject = sprintf('%s %s', Configure::read('App.name'), __('password recovery', true));
		$this->EmailQueue->template = $this->controller->action;
		$this->EmailQueue->sendAs = 'both';
		$this->EmailQueue->delivery = 'db';
		$this->EmailQueue->send();		
		
	}
	
	public function generatePasswordHash($user) {
		
		if (!is_numeric($user['UacUser']['id'])) {
			Debugger::log('Requesting new password hashes for invalid users');
			Debugger::log($user);
			return false;
		}
		
		# GENERATE NEW HASH AND SAVE IT
		$this->userModel->id = $user['UacUser']['id'];
		$new_hash = Security::hash($user['UacUser']['email'].time(), null, true);
		
		if ($this->userModel->saveField('password_change_hash', $new_hash) === false) {
			return false;
		}
		
		return $new_hash;
		
	}
	
	/**
	 * Checks the current user session for the existence of at least on Role
	 *
	 * @return bool
	 * @author Rui Cruz
	 */
	public function hasRole() {
		
		if (empty($this->roles)) {
			$this->roles = $this->controller->Session->read('Auth.UacRole');
		}
		
		return !empty($this->roles);
		
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
	
	function checkInvitation($data) {
		
		$result = false;
		
		# CHECK IF INVITATION IS MANDATORY
		if (configure::read('App.invitation_only') !== true) {
			
			$result = true;
			
		} elseif (!isset($data['UacUser']['activation_code'])) {
			
			$result = false;
			
		} else {
		
			define('INVITATION_CODE', $data['UacUser']['activation_code']);
			$InvitationCode = ClassRegistry::init('Invitation.InvitationCode');
			$result = $InvitationCode->lookup(INVITATION_CODE);
			
		}
				
		if ($result === false) {
			$this->userModel->invalidate('activation_code', __('Invalid code', true));
		}
		
		$this->controller->Session->write('Signup.invitation_ok', $result);
		return $result;
		
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