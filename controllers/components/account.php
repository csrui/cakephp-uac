<?php

    class AccountComponent extends Object {
		
		var $__roleModel = 'UaRole';
		
		var $__cookieOption = 'remember_me';
		
		var $Controller = null;
		
		var $data = null;
		
		//called before Controller::beforeFilter()
		function initialize(&$controller, $settings = array()) {
			// saving the controller reference for later use
			$this->Controller =& $controller;
			
			// if no Roles are in Session, redirect
			if (!$this->__pre_check()) {
				
			}
		}
	
		//called after Controller::beforeFilter()
		function startup(&$controller) {
		}
	
		//called after Controller::beforeRender()
		function beforeRender(&$controller) {
		}
	
		//called after Controller::render()
		function shutdown(&$controller) {
		}
	
		//called before Controller::redirect()
		function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
		}
	
		function redirectSomewhere($value) {
			// utilizing a controller method
			$this->Controller->redirect($value);
		}

		
		private function __pre_check() {
			
			if (!$this->Controller->Session->read('Auth.UaRole')) {
				return false;
			}
			
			return true;
			
		}
		
		function login() {
			
			$conditions = $this->Controller->Auth->data['UacUser'];
			$this->data = $this->Controller->UacUser->find('first', compact('conditions'));

			unset($conditions[$this->__cookieOption]);

			# SET SESSION WITH ALL MODEL INFORMATION
			foreach($this->data as $model_name => $model_data) {
			
				$this->Controller->Session->write('Auth.'.$model_name, $model_data);
				
			}
			
			# UPDATE USERS LAST_LOGIN PROPERTY
			$this->Controller->UacUser->id = $this->data['UacUser']['id'];
			$this->Controller->UacUser->saveField('last_login', date('Y-m-d H:i:s'));
			
			$this->__setCookies();
			
		}
		
		function checkInvitation() {
			
			# CHECK IF INVITATION IS MANDATORY
			if (configure::read('App.invitation_only') == false) {
				
				$result = true;
				
			} elseif (!isset($this->Controller->data['UaUser']['activation_code'])) {
				
				$result = false;
				
			} else {
			
				define('INVITATION_CODE', $this->Controller->data['UaUser']['activation_code']);
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
			
			$this->Controller->Session->write('Signup.invitation_ok', $result);
			
		}
		
		/**
		 * Save Cookies if data is available
		 * @return void
		 */
		function __setCookies() {
			
			if (!empty($this->Controller->data)) {
				
				$cookie = array();
				$cookie['username'] = $this->data['UaUser']['username'];
				$cookie['password'] = $this->data['UaUser']['password']; #TODO Check if password is encripted or not
				
				if (isset($this->Controller->data[$this->Controller->Auth->userModel][$this->__cookieOption])) {
					
					$this->Controller->Cookie->write('Auth.'.$this->Controller->Auth->userModel, $cookie, true, Configure::read('User.Cookie.LifeTime'));
				    unset($this->Controller->data[$this->Controller->Auth->userModel][$this->__cookieOption]);
					
				}
			}
			
		}
		
		/**
		 * Kills current session and cookies
		 * @return void
		 */
		function logout() {
			
			$this->Controller->autoRender = false;
			$this->Controller->Auth->logout();
			$this->Controller->Session->delete('Auth');
			$this->Controller->redirect('/');
			
		}
		
		/**
		 * Checks for the request roles
		 * @return void
		 */
		function roles() {
			
			$needed_roles = (array) func_get_args();
			
			$session_roles = $this->Controller->Session->read('Auth.'.$this->__roleModel);
			
			$result = false;
			
			if (!empty($needed_roles) && !empty($session_roles)) {
			
				foreach($needed_roles as $need_role) {
					
					foreach($session_roles as $sess_role) {
						
						if (strtolower($need_role) == strtolower($sess_role['name'])) {
							
							$result = true;
							break;
							
						}
						
					}
					
				}
				
			}
			
			if ($result === false) {
			
				$this->Controller->Session->setFlash(__("You don't have permission to access that resource", true));
				$this->Controller->redirect('/');
				
			}
			
		}
		
		/**
		 * 
		 * Checks for the Auth->user('id') in a given string/array
		 * @param mixed $data
		 * @param mixed $path
		 * @return bol
		 */
		function containAuth($data, $path = null) {
			
			if (is_null($path)) {
				
				return $data == $this->Controller->Auth->user('id');
				
			} else {
								
				$path_result = set::extract($data, $path);
				return in_array($this->Controller->Auth->user('id'), $path_result);
				
			}
			
			
		}
				
	}
	
?>