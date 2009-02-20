<?php if (!empty($comments)): ?>
	<div class="comments">
		<?php $i = 0; foreach($comments as $comment):?>
			<?php $this->renderElement('comment', array('comment' => $comment, 'i' => $i++)); ?>
		<?php endforeach; ?>
	</div>
	<?php $this->renderElement('pagination', array('page'=>$page,'items'=>$count,'items_per_page'=>$window,'base_url'=>$base_url)); ?>
	
<?php else: ?>
	<p>
		<em>There are no comments for this torrent.</em>
	</p>
<?php endif; ?>

<p class="tools"><a href="<?php echo WWW_BASE_PATH; ?>torrent/<?php echo $torrent->fid; ?>">&larr; Return to torrent details</a></p>

<form class="comment" action="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid ?>/comment" method="post">
	<p>
		<label for="body">
			<strong>Post a Comment:</strong>
		</label>
		<textarea id="body" name="body"></textarea>
	</p>
	<p>
		<button type="submit" name="submit" value="submit">Post!</button>
	</p>
</form>