<?php

echo $this->Form->create('UacUser');
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->end(__('Sign up', true));

?>