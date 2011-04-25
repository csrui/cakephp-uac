<div class="users password-recover">

	<h2><?php __('Recover your password')?></h2>

	<span class="grid_7 alpha">

		<p>
			<?php __('If you have forgotten your password we can help you reset it') ?>.
		    <br />
		    <?php __('Please enter your <strong>e-mail address</strong> and we will send you a link to change your password') ?>.
		</p>
		
		<br />	

		<?php 

		    echo $this->Form->create('UacUser');
			echo $this->Form->input('email');	
		    echo $this->Form->end(__('Request a password reset', true)); 

		?>

	
	</span>

	<span class="grid_3 omega" id="sidebox">
		
		<div class="box">
			<p class="title"><?php __('Helpful hints') ?></p>
			<p><?php __('You will receive an e-mail with a link to request a password change') ?></p>.
			<p><?php __('Please allow a few minutes for the e-mail to arrive.')?></p>
			
			<p class="tip">
			    <?php echo sprintf(__('Remember to add the address %s to your e-mail safelist', true), '<strong>'.Configure::read('Email.username').'</strong>') ?>
			</p>
			
			
		</div>
	
	</span>

</div>