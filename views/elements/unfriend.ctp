<?php

echo $this->Form->create('UacFriendship', array('type' => 'delete', 'url' => array('plugin' => 'uac', 'controller' => 'uac_friendships', 'action' => 'delete')));
echo $this->Form->hidden('friend_id', array('value' => $friend_id));
echo $this->Form->button(empty($title) ? __('Remove', true) : $title, array('class' => 'friend-remove'));
echo $this->Form->end();

?>