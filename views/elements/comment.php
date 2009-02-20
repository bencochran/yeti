<p class="comment <?php echo ($i%2) ? 'odd' : 'even' ?>">
	<?php echo $comment->body_view ?>
	<span>
		<strong>
			<?php $this->renderElement('datetime', array('time' => $comment->ctime)) ?>
		</strong>
		by
		<strong>
			<?php $this->renderElement('user_byline', array('user' => $comment->user)); ?>
		</strong>
	</span>
</p>
