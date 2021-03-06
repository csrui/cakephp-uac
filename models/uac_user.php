<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class UacUser extends UacAppModel {
	
	var $name = 'UacUser';
	
    var $validate = array(
            'email' => array(
                'Please provide a valid e-mail' => array(
                    'rule' => 'email'
                ),
                'This e-mail is already taken, please try another' => array(
                    'rule' => 'isUnique'
                )
			),
            'password' => array(
                'Password must be at least five characters' => array(
                    'rule' => array('minLength', 5)
                ),
                'Passwords must match' => array(
                    'rule' => array('passwordCompare', 'password2')
                )
            ),
	);

	var $hasOne = array(
		'UacProfile' => array(
			'className' => 'Uac.UacProfile',
			'foreignKey' => 'uac_user_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	var $hasMany = array(
		'UacGigya' => array(
			'className' => 'Uac.UacGigya',
			'foreignKey' => 'uac_user_id',
			'dependent' => true
		)
	);
	
	var $hasAndBelongsToMany = array(
		'UacRole' => array(
			'className' => 'Uac.UacRole'
		)
	);
	
	
	public function signUp($data) {
		
		$this->create($data);
		
		if ($this->save($data) !== false) {
			
			return $this->UacProfile->signUpProfile($data);
			
		}
		
		return false;
		
	}
	

	/**
	 * Compares two password strings to see if they are equal
	 *
	 * @param array $data 
	 * @param string $fieldTwo 
	 * @return bool
	 * @author Rui Cruz
	 */
	function passwordCompare($data, $fieldTwo) {		
		
		if (!isset($_POST['data'][$this->alias][$fieldTwo])) return true;

		if($data['password'] != $this->generatePassword($_POST['data'][$this->alias][$fieldTwo])) {
			$this->invalidate($fieldTwo, __('Passwords must match', true));
			return false;
		}

		return true;
	}
		
	/**
	 * Custom password hashing function
	 * Needs $this->Auth->authenticate = $this->UacUser; in the Controllers beforeFilter
	 *
	 * @param array $data 
	 * @return array
	 * @author Rui Cruz
	 */
	function hashPasswords($data) {        	
					
		if (isset($data[$this->alias]['password'])) {
           	
			if(!empty($data[$this->alias]['password'])) {
				
				$data[$this->alias]['password'] = $this->generatePassword($data[$this->alias]['password']);

			}
		
		}

		return $data;
	
	}
	
	/**
	 * Takes care of the password hashing algorithm
	 *
	 * @param string $string 
	 * @return string
	 * @author Rui Cruz
	 */
	public function generatePassword($string = null) {
		
		if (!is_null($string)) {
			
			return Security::hash($string, null, true);
			
		} else {
			
			#TODO Handle randomized password generation
			
		}
		
	}

}
?>