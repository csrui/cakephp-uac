<?php

class UacFriendshipsController extends UacAppController {
	
	
	function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->Auth->deny('*');
		
	}
	
	// function isAuthorized() {
	// 			
	// 	if (in_array($this->action, array('delete'))) {
	// 
	// 		$data = $this->UacFriendship->getStatus($this->data['UacFriendship']['friend_id']);
	// 
	// 		pr($data);
	// 		
	// 		die();			
	// 	
	// 		if (in_array($this->Auth->user('id'), array($data['UacFriendship']['friend_id'], $data['UacFriendship']['requester_id']))) return true;
	// 
	// 		return false;
	// 
	// 	}
	// 
	// 	return true;
	// 
	// }
	// 
	// public function index() {
	// 
	// 	$friends = $this->UacFriendship->friends($this->Auth->user('id'));
	// 	$this->Session->write('Friends', $friends);
	// 
	// 	$conditions = array(
	// 		'OR' => array(
	// 			'UacFriendship.requester_id' => $this->Auth->user('id'),
	// 			'UacFriendship.friend_id' => $this->Auth->user('id')
	// 		)
	// 	);
	// 	
	// 	// $pending = $this->UacFriendship->find('all', array('conditions' => set::merge($conditions, array('accepted' => 0))));
	// 	// $this->set('pending', $pending);
	// 
	// 	$friends = $this->UacFriendship->find('all', array('conditions' => set::merge($conditions, array())));
	// 	$this->set('friends', $friends);
	// 	
	// }
	
	/**
	 * Creates a new friend request
	 *
	 * @return void
	 * @author Rui Cruz
	 */
	public function add() {
		
		if (!$this->RequestHandler->isPOST()) {
		
			$this->Session->setFlash(__('Invalid id', true));
			
		} else {
			
			# FIND A PREVIOUS EXISTING CONNECTION
			
			// $conditions = array(
			// 	'OR' => array(
			// 		array(
			// 			'UacFriendship.requester_id' => $this->Auth->user('id'),
			// 			'UacFriendship.friend_id' => $this->data['UacFriendship']['friend_id']
			// 		),
			// 		array(
			// 			'UacFriendship.requester_id' => $this->data['UacFriendship']['friend_id'],
			// 			'UacFriendship.friend_id' => $this->Auth->user('id')
			// 		)
			// 	)
			// );
			// 
			// $existing_connections = $this->UacFriendship->find('count', compact('conditions'));
			// 
			// if ($existing_connections == 0) {
			// 	
			// 	$this->data['UacFriendship']['requester_id'] = $this->Auth->user('id');
			// 	$this->UacFriendship->create($this->data);
			// 	if ($this->UacFriendship->save($this->data)) {
			// 
			// 		$this->Session->setFlash(__('Added friend request', true));
			// 
			// 	}
			// 	
			// }
			
			if ($this->UacFriendship->makeFriend($this->Account->id(), $this->data['UacFriendship']['friend_id'])) {
				
				$this->Session->setFlash(__('Added friend', true));
				
			}

		}
				
		$this->redirect($this->referer());
				
	}
	
	
	public function delete() {
		
		if (!$this->RequestHandler->isDELETE() && !isset($this->data['UacFriendship']['friend_id'])) {
			
			$this->Session->setFlash(__('Invalid friend', true));
			
		} elseif ($this->UacFriendship->delete($this->Account->id(), $this->data['UacFriendship']['friend_id'])) {
			
			$this->Session->setFlash(__('Friendship deleted', true));
			
		} else {

			$this->Session->setFlash(__('Friendship was not deleted', true));
			
		}
		
		$this->redirect($this->referer());
		
	}	
	
}

?>