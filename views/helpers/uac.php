<?php

class UacHelper extends AppHelper {

	var $helpers = array('Html', 'Session');

	private $__session_name;
	
	private $__user_session = null;


	/**
	 * Reads the user session into the $__user_session property
	 * @return void
	 */
	function beforeRender() {
		
		parent::beforeRender();
		
		$this->__session_name = Configure::read('User.Session.Name');
		
		# IF NOT SET RESETS TO DEFAULT
		if (empty($this->__session_name)) {
			$this->__session_name = 'Auth';
		}
		
		if ($this->inSession()) {
        	$this->__user_session = $this->Session->read($this->__session_name);
        }
                
	}


	/**
	 * Check for a valid user session
	 *
	 * @return bol
	 */
	public function inSession() {
		return $this->Session->check($this->__session_name.'.UacUser');
	}
	 
	/**
	 * Return User Model information stored in a Session
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key = null) {
		
		return $this->__readSessionArray($key);
		
	}


	public function lookupRelation($profile_id) {
		
		$friends = $this->Session->read('Friends');
		
		foreach($friends as $friend) {
			
			if ($friend['UacFriendship']['requester_id'] == $profile_id || $friend['UacFriendship']['friend_id'] == $profile_id) {
				
				return $friend['UacFriendship'];
				
			}
			
		}
		
		return false;
		
	}


	/**
	 * Search in a hasMany situation Model
	 * Usefull to check if a user belongs to a certain Role
	 *
	 * @param string $model
	 * @param string $value
	 * @return bol
	 */
	public function modelContains($model, $value, $key = null)
	{
			
		if (is_array($this->__readSessionArray($model)))
		{
			foreach($this->__readSessionArray($model) as $array)
			{
				if (is_array($array))
				{
					if (!is_null($key))
					{
						if ($array[$key] == $value)
						{
							return true;
						}
					}
					elseif (in_array($value, $array))
					{
						return true;
					}
				}
			}
		}
			
		return false;
			
	}
	
	function getGravatar($size = 40) {
		return $this->gravatar($this->get('email'), $size);
	}

	function gravatar($email, $size = 40) {
		
		$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5( strtolower($email) )."&size=".$size;
		#"&default=".urlencode($default).
		return $this->Html->image($grav_url, array('class' => 'gravatar'));
		#return '-- GRAVATAR ' . $email . ' --';
		 
	}
	
	function avatar($size, $avatar_file_name, $options = null) {		
		
		$default_options = array('class' => 'avatar');
		$options = Set::merge($options, $default_options);
		
		if (empty($avatar_file_name) || is_null($avatar_file_name)) {
			
			return $this->Html->image(DS . 'profiles_default' . DS . 'thumb' . DS . $size . DS . 'default.jpg', $options);
			
		} else {
		
			return $this->Html->image(DS . 'profiles' . DS . 'thumb' . DS . $size . DS . $avatar_file_name, $options);
			
		}
		
	}

	
	/**
	 * Return informations from the User Session Array
	 *
	 * @param string $key
	 * @return mixed
	 */
	private function __readSessionArray($key) {
		 
		if ($this->inSession()) {

			if (strpos($key, '.')) {
				$sequence = explode('.', $key);
				return $this->__user_session[$sequence[0]][$sequence[1]];
			}
			elseif (isset($this->__user_session[$key]))
			{
				return $this->__user_session[$key];
			}

		}
		 
		return false;
		 
	}
	 
	 
	/**
	 * Processes request automaticaly to determine if it returns a string or an object
	 *
	 * @param string $model
	 * @param string $key
	 * @return mixed
	 */
	private function __getAutoSelection($model = null, $key = null)
	{

		if (is_null($key))
		{
			return $this->getObj($model);
		}
		else
		{
			return $this->__readSessionArray("$model.$key");
		}

	}	
	
}

?>