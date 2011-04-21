<?php if ($uac->inSession()): ?>

    <span class="photo"><?php echo $uac->avatar('tiny', $uac->get('UacProfile.avatar_filename')) ?></span>

	<ul class="options">

    	<li><?php echo $this->Html->link($uac->get('UacProfile.screen_name'), array('plugin' => 'uac', 'controller' => 'uac_profiles', 'action' => 'view', $uac->get('UacProfile.id'))) ?></li>
	
    	<li><?php echo $this->Html->link(__('Sign Out', true), array('plugin' => 'uac', 'controller' => 'uac_users', 'action' => 'signout')) ?></li>  
    
    <?php
    
    	if (is_array(configure::read('User.Area.urls'))) {

        	foreach(configure::read('User.Area.urls') as $name => $url) {
        		$url = sprintf($url, $uac->get('UacUser.id'));   
            	echo '<li>' . $this->Html->link($name, $url) . '</li>';
        	}
        	
        }
         
    ?>
	
	</ul>

<?php else: ?>

	<p align="center">
		<?php __('Have an account?') ?> 
    	<?php echo $this->Html->link(__('Log in', true), array('plugin' => 'uac', 'controller' => 'uac_users', 'action' => 'signin'), array('class' => 'login')) ?> 

		<?php echo $this->Html->link(__('Sign up', true), array('plugin' => 'uac', 'controller' => 'uac_users', 'action' => 'signup'), array('class' => 'register')) ?>
	</p>

<?php endif; ?>