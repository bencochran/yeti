<?php if ($count > 0): ?>
	<table class="messagelist">
	<th class="marker">&nbsp;</th>
	<th class="from_user">From</th>
	<th class="time">Sent</th>
	<th class="subject">Subject</th>
		<?php $i=0; foreach ($messages as $message): ?>
			<?php $this->renderElement('message_in_table', array('message' => &$message, 'i' => $i++)); ?>
		<?php endforeach; ?>
	</table>
	<?php $this->renderElement('pagination', array('page'=>$page,'items'=>$count,'items_per_page'=>15,'base_url'=>$base_url)); ?>
<?php else: ?>
	<p><em>You don't have any messages.</em></p>
<?php endif; ?>