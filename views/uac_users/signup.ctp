<?php echo $this->Html->script('gigya', array('inline' => false)) ?>
<?php echo $this->Html->scriptBlock('
$(document).ready(function(){
	gigyaSignOut();
	gigyaSignIn();
});'); ?>

<div class="users signup">

	<span class="grid_5 alpha">
		
		<h2><?php __('Sign up') ?></h2>
		<p><?php __('Create a new account') ?></p>		
		
		<?php

		echo $this->Form->create('UacUser');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		
		if (Configure::read('App.invitation_only') == true) {
			echo $this->Form->input('activation_code', array('title' => __('Insert your invitation code for access', true)));
		}
		
		echo $this->Form->end(__('Sign up', true));

		?>		
		
	</span>
	
	<span class="grid_5 omega">

		<h2><?php __('Using other providers') ?></h2>
		<p><?php __('It\'s fast and easy') ?></p>

		<div id="gigyaLoginDiv"></div>	
	</span>

	<span class="clear"></span>
	<p><small><?php echo sprintf(__('By signing up you agree with the %s', true), $this->Html->link(__('terms of usage', true), Configure::read('User.signup.agreement'))); ?></small></p>

</div>