<?php

echo $this->Form->create('UacUser');
echo $this->Form->input('email');
echo $this->Form->input('password');

echo $this->Html->para(null, sprintf(__('By signing up you agree with the %s', true), $this->Html->link(__('terms of usage', true), Configure::read('User.signup.agreement'))));

echo $this->Form->end(__('Sign up', true));

?>