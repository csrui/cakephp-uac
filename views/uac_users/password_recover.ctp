<h2><?php __('Recover your password')?></h2>

<p>
    <?php __('In case you have forgotten your password, we can generate a new one and send it to you') ?>.
    <br />
    <?php __('Please enter your <strong>e-mail</strong> so we can send your account login') ?>
</p>

<br />

<p class="tip">
    <?php echo sprintf(__('Remember to add the address %s to your e-mail safelist', true), '<strong>'.Configure::read('Email.username').'</strong>') ?>
    <br />
    <?php __('and please allow a few minutes to arrive.')?>
</p>

<?php 

    echo $this->Form->create('UacUser');
	echo $this->Form->input('email');	
    echo $this->Form->end(__('Send your new password', true)); 

?>