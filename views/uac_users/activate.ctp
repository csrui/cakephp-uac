<h2><?php __('Activate your account') ?></h2>

<?php

	echo $this->Form->create('UacUser', array('url' => array('action' => 'activate', $this->params['pass'][0])));

    echo $this->Html->para('tip', __('For your new password use letters and numbers, this will make it more secure', true) . '<br />' . sprintf(__('Your password has to be at least <strong>%s</strong> chars long', true) , Configure::read('User.Password.Min')));

    echo $this->Form->input('password', array('label' => 'New Password', 'size' => 20));
    echo $this->Html->div(null, '', array('id' => 'passwordMeter'));

    echo $this->Form->input('password2', array('type' => 'password', 'label' => 'Confirm Password', 'size' => 20));

    echo $this->Form->end('Save your password'); 
    
?>