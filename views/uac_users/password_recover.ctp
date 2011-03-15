<div class="users password-recover">

	<h2><?php __('Recover your password')?></h2>

	<span class="grid_7 alpha">


		<p><?php __('In case you have forgotten your password, we can generate a new one and send it to you') ?>.
		    <br />
		    <?php __('Please enter your <strong>e-mail</strong> so we can send your account login') ?>
		</p>
		
		<br />	

		<?php 

		    echo $this->Form->create('UacUser');
			echo $this->Form->input('email');	
		    echo $this->Form->end(__('Send your new password', true)); 

		?>

	
	</span>

	<span class="grid_3 omega" id="sidebox">
		
		<div class="box">
			<p class="title"><?php __('Helpful hints') ?></p>
			<p>You will receive an e-mail with a link to request a password change.</p>
			<p><?php __('and please allow a few minutes to arrive.')?></p>
			
			<p class="tip">
			    <?php echo sprintf(__('Remember to add the address %s to your e-mail safelist', true), '<strong>'.Configure::read('Email.username').'</strong>') ?>
			</p>
			
			
		</div>
	
	</span>

</div>