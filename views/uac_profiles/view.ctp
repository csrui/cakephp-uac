<?php pr($profile) ?>

<h2><?php echo $profile['UacProfile']['screen_name'] ?></h2>

<h3><?php echo $profile['UacProfile']['first_name'] ?> <?php echo $profile['UacProfile']['last_name'] ?></h3>
<span class="gender"><?php echo $profile['UacProfile']['gender']?></span>

<span class="photo"><?php echo $uac->avatar('small', $profile['UacProfile']['avatar_filename']) ?></span>

<?php echo nl2br($profile['UacProfile']['about']) ?>