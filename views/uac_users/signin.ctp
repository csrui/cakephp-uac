<?php echo $this->Html->script('gigya', array('inline' => false)) ?>
<?php echo $this->Html->scriptBlock('
$(document).ready(function(){
	gigyaSignOut();
	gigyaSignIn();
});'); ?>

<div class="users signin">
	
	<span class="grid_5 alpha">
		
		<h2><?php __('Log in') ?></h2>
		<p><?php __('Please login to continue') ?></p>
		
		<?php

		echo $this->Form->create('UacUser');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->end(__('Log in', true));

		?>
	</span>

	<span class="grid_5 omega">

		<h2><?php __('Using other providers') ?></h2>
		<p><?php __('It\'s fast and easy') ?></p>

		<div id="gigyaLoginDiv"></div>
	</span>
	
	<span class="clear"></span>
	<p><small><?php __('Having trouble? did you') ?> <?php echo $this->Html->link(__('forget your password?', true), array('action' => 'password_recover')); ?></small></p>
	

</div>