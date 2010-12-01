<h2><?php __('Change your password') ?></h2>

<?php

	if (isset($this->params['pass'][0])) {
		
		echo $this->Form->create('UacUser', array('url' => array('action' => 'password_change', $this->params['pass'][0])));
		
	} else {
	
		echo $this->Form->create('UacUser');
		
		echo $this->Html->para(null, __('For security please type your current password', true));
    	echo $this->Form->input('oldpassword', array('label' => 'Old Password', 'type' => 'password', 'size' => 20));
		
	}

    echo $this->Html->para('tip', __('For your new password use letters and numbers, this will make it more secure', true) . '<br />' . sprintf(__('Your password has to be at least <strong>%s</strong> chars long', true) , Configure::read('User.Password.Min')));

    echo $this->Form->input('password', array('label' => 'New Password', 'size' => 20));
    echo $this->Html->div(null, '', array('id' => 'passwordMeter'));

    #echo $this->Html->para(null, __('Please enter your new password again just to be sure', true));

    echo $this->Form->input('password2', array('type' => 'password', 'label' => 'Confirm Password', 'size' => 20));

    echo $this->Form->end('Change Password'); 
    
?>