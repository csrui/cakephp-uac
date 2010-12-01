<div class="uac_images index">
<h2><?php __('Profile Images')?></h2>

<div id="photo-list">
	<?php foreach ($uac_images as $image): ?>
		<div class="photo">
			<?php echo $this->Html->image(DS.'profiles'.DS.'thumb'.DS.'small'.DS.$image['UacImage']['filename'], array('url' => array('action' => 'view', $image['UacImage']['id']))); ?>
			<ul class="actions">
				<li><?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $image['UacImage']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $image['UacImage']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $image['UacImage']['id'])); ?></li>
			</ul>
		</div>
	<?php endforeach; ?>
</div>

<?php echo $this->element('paging') ?>

</div>

<div class="actions">
<h3><?php __('Actions'); ?></h3>
<ul>
	<li><?php echo $this->Html->link(__('New Image', true), array('action' => 'add')); ?></li>
</ul>
</div>