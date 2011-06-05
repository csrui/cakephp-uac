<?php
class UacFriendship extends UacAppModel {

	var $validate = array(
		'requester_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'friend_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'accepted' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mute' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'UacProfile' => array(
			'className' => 'Uac.UacProfile',
			'foreignKey' => 'friend_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	 * Returns a list of friends. 
	 * Users that have a bidirectional relation to another users
	 *
	 * @param mixed $user_ids 
	 * @param array $blacklist 
	 * @return mixed
	 * @author Rui Cruz
	 */
	public function getFriends($user_ids, $blacklist = array()) {

		$options['joins'] = array(
			array(
				'table' => 'uac_friendships',
				'alias' => 'f2',
				'type' => 'INNER',
				'conditions' => array(
					'UacFriendship.requester_id = f2.friend_id',
					'UacFriendship.friend_id = f2.requester_id'
				)
			)
		);

		$options['conditions'] = array(
			'UacFriendship.requester_id' => $user_ids
		);
	
		$options['conditions'] = set::merge($options['conditions'], array(
			'NOT' => array(
				'UacFriendship.friend_id' => set::merge($user_ids, $blacklist)
			)
		));
		
		$options['group'] = array(
			'UacProfile.id'
		);

		$this->Contain('UacProfile');
		$friends = $this->find('all', $options);
		
		return $friends;

	}	
	
	
	public function getStatus($user_id, $friend_id) {
		
		$conditions = array(
			'OR' => array(
				array(
					'UacFriendship.requester_id' => $user_id,
					'UacFriendship.friend_id' => $friend_id
				),
				array(
					'UacFriendship.requester_id' => $friend_id,
					'UacFriendship.friend_id' => $user_id
				)				
			)
		);
		
		$this->Contain();
		$connections = $this->find('all', compact('conditions'));
		
		return $connections;
		
	}
	
	
	public function getPending($user_id, $blacklist = array()) {
		
		$conditions = array(
			array(
				'UacFriendship.friend_id' => $user_id
			),
			'NOT' => array(
				'UacFriendship.requester_id' => set::merge($user_id, $blacklist)
			)
		);
		
		$friendships = $this->find('all', array('conditions' => $conditions));
		
		return $friendships;
		
	}

	public function makeFriend($requester_id, $friend_id) {
		
		$data = array(
			'requester_id' => $requester_id,
			'friend_id' => $friend_id
		);
		
		return $this->save($data);
		
	}
	
	
	public function delete($requester_id, $friend_id) {
		
		$conditions = array(
			'OR' => array(
				array(
					'requester_id' => $requester_id,
					'friend_id' => $friend_id
				),
				array(
					'friend_id' => $requester_id,
					'requester_id' => $friend_id
				)
			)			
		);
		
		return $this->deleteAll($conditions);
		
	}
	
}
?>