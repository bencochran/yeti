<?php if ($count < 1): ?>
	<p><em>There are no news items posted. No news is good news, right?</em><p>
<?php else: ?>
	<?php $i=0; foreach ($posts as $post): ?>
		<?php $this->renderElement('news_item', array('post' => &$post, 'i' => $i++)); ?>
	<?php endforeach; ?>	
<?php endif; ?>