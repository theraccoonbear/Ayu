<div class="blogs form">
<?php echo $this->Form->create('Blog');?>
	<fieldset>
		<legend><?php __('Edit Blog'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Blog.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Blog.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Blogs', true), array('action' => 'index'));?></li>
	</ul>
</div>