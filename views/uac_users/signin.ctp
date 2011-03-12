<?php

echo $this->Form->create('UacUser');
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->end(__('Log in', true));

echo $this->Html->link(__('Forgot your password?', true), array('action' => 'password_recover'));

?>