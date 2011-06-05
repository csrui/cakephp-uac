<?php 

# PREVENT INTERACTIONS WITH SELF
if ($friend_id == $this->Session->read('Auth.UacProfile.id')) return false;

if (!empty($friendship) && $friendship['UacFriendship']['requester_id'] == $this->Session->read('Auth.UacProfile.id')) return false;
// $friendship = $this->Uac->lookupRelation($profile_id);

if (!empty($friendship)) {
	
	echo $this->Form->create('UacFriendship', array('url' => array('plugin' => 'uac', 'controller' => 'uac_friendships', 'action' => 'add')));
	echo $this->Form->hidden('friend_id', array('value' => $friend_id));
	echo $this->Form->button(__('Accept request', true), array('class' => 'friend-accept'));	
	echo $this->Form->end();

} else {
	
	echo $this->Form->create('UacFriendship', array('url' => array('plugin' => 'uac', 'controller' => 'uac_friendships', 'action' => 'add')));
	echo $this->Form->hidden('friend_id', array('value' => $friend_id));
	echo $this->Form->button(__('Add as friend', true), array('class' => 'friend-request'));
	echo $this->Form->end();	

// } elseif ($friendship['accepted'] == 0 && $friendship['requester_id'] == $profile_id) {
// 	
// 	
// 	echo $this->Form->create('UacFriendship', array('type' => 'delete', 'url' => array('plugin' => 'uac', 'controller' => 'uac_friendships', 'action' => 'delete')));
// 	echo $this->Form->hidden('id', array('value' => $friendship['id']));	
// 	echo $this->Form->button(__('Remove from friends', true), array('class' => 'friend-remove'));
// 	echo $this->Form->end();
// 
// } else {
// 	
// 	echo $this->Form->create('UacFriendship', array('type' => 'delete', 'url' => array('plugin' => 'uac', 'controller' => 'uac_friendships', 'action' => 'delete')));
// 	echo $this->Form->hidden('id', array('value' => $friendship['id']));
// 	echo $this->Form->button(__('Remove from friends', true), array('class' => 'friend-remove'));
// 	echo $this->Form->end();
 	
}

?>