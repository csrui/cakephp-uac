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
	
	
	public function getConnectionIDs($user_id) {
		
		$conditions = array(
			'OR' => array(
				'UacFriendship.requester_id' => $user_id,
				'UacFriendship.friend_id' => $user_id
			)
		);
		
		$this->Contain();
		$friendships = $this->find('all', array('conditions' => $conditions));
		
		$tmp = array();

		foreach($friendships as $fs) {
			
			if ($fs['UacFriendship']['requester_id'] == $user_id) {
				
				$tmp[] = $fs['UacFriendship']['friend_id'];
				
			} else {
				
				$tmp[] = $fs['UacFriendship']['requester_id'];
				
			}
			
		}
		
		return $tmp;
		
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