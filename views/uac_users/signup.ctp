<div class="users signup">

	<span class="grid_5 alpha">
		
		<h2><?php __('Sign up') ?></h2>
		<p><?php __('Create a new account') ?></p>		
		
		<?php

		echo $this->Form->create('UacUser');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->end(__('Sign up', true));

		?>		
		
	</span>
	
	<span class="grid_5 omega">

		<h2><?php __('Using other providers') ?></h2>
		<p><?php __('It\'s fast and easy') ?></p>

		<?php echo $this->element('gigya_signin'); ?>	
	</span>

	<span class="clear"></span>
	<p><small><?php echo sprintf(__('By signing up you agree with the %s', true), $this->Html->link(__('terms of usage', true), Configure::read('User.signup.agreement'))); ?></small></p>

</div>