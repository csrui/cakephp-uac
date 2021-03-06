<?php
/**
 * undocumented class
 *
 * @package Uac
 * @author Rui Cruz
 */
class UacProfile extends UacAppModel {
	
	var $name = 'UacProfile';
	
	var $validate = array(
		'uac_user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username'=>array(
			'Username has to be at least four characters' => array(
				'rule' => array('minLength', 4)
			),
			'This username is already taken, please try another' => array(
				'rule' => 'isUnique'
			),
			'Please use only letters, numbers, dashes or underscores' => array(
				'rule' => 'alphaNumericDashUnderscore'
			)
		)
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'UacUser' => array(
			'className' => 'Uac.UacUser',
			'foreignKey' => 'uac_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasOne = array(
		'UacProfileMeta' => array(
			'className' => 'Uac.UacProfileMeta',
			'foreignKey' => 'uac_profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'dependent' => true,
		)
	);

	
	var $hasMany = array(
		'UacImage' => array(
			'className' => 'Uac.UacImage',
			'foreignKey' => 'uac_profile_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'dependent' => true,			
		)
	);	
	
	
	/**
	 * Generates a new profile and tries to guess screen_name if not provided
	 *
	 * @param array $data 
	 * @return bol
	 * @author Rui Cruz
	 */
	public function signUpProfile($data) {
	
		$data['UacProfile']['uac_user_id'] = $this->UacUser->id;
		
		if (empty($data['UacProfile']['screen_name'])) {

			$email = explode('@', $data['UacUser']['email']);
			$data['UacProfile']['screen_name'] = $email[0];
			
		}
	
		$this->create($data[$this->alias]);
		return $this->save($this->data[$this->alias]);
			
	}	
	
}
?>