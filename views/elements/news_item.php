<p class="newsitem">
	<strong><abbr title="<?php echo date('M j, y h:i:s a',$post->ctime) ?>"><?php echo date('m.d.y',$post->ctime) ?></abbr> + <?php if (!empty($this->title)): ?><?php echo $post->title ?> + <?php endif; ?></strong>
	<?php echo $post->body ?>
</p>
