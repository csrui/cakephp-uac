<?php

Class UacFriend extends UacAppModel {

	var $belongsTo = array(
		'UacProfile' => array(
			'className' => 'Uac.UacProfile',
			'foreignKey' => 'friend_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);	
	

	public function getFriends($user_ids, $blacklist = array()) {
	
		$conditions = array(
			'UacFriend.requester_id' => $user_ids
		);
		
		$conditions = set::merge($conditions, array(
			'NOT' => array(
				'UacFriend.friend_id' => set::merge($user_ids, $blacklist)
			)
		));
	
		$this->Contain('UacProfile');
		return $this->find('all', compact('conditions'));
	
	}	

	
	
}

?>