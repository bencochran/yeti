<h3>General Info</h3>
<table class="torrents">
	<tr class="even">
		<td class="torrent">
			<a class="download" title="Download torrent" href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/download">Download Torrent</a><?php if($torrent->has_access(array('user' => $active_user))) { ?> / <a href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/edit">Edit</a> / <a href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/delete">Delete</a><?php } ?>
			<br />
			<span class="info">
			<?php echo pretty_size($torrent->size); ?>;
			added <?php echo time_diff($torrent->ctime); ?>
			by
			<?php $this->renderElement('user_byline', array('user' => $torrent->user)); ?>
			in category <a href="<?php echo WWW_BASE_PATH ?>browse/<?php echo $torrent->category->slug; ?>"><?php echo $torrent->category->name; ?></a>;
			snatched <?php echo $torrent->completed; ?> time<?php echo ($torrent->completed > 1) ? 's' : ''; ?>.
			</span>
		</td>
	</tr>
</table>

<?php if($torrent->description_view and (strlen($torrent->description_view) > 0)): ?>
	<h3>Description</h3>
	<p><?php echo $torrent->description_view; ?></p>
<?php endif; ?>

<h3>Files (<?php echo $file_count; ?>)</h3>
<table class="files">
	<tr>
		<th class="file">File</th>
		<th class="size">Size</th>
	</tr>
	<?php $i=0; foreach($files as $file): ?>
		<?php $this->renderElement('file_in_table', array('file' => $file, 'i' => $i++)); ?>
	<?php endforeach; ?>
</table>
<?php if($file_count > $file_window):?>
	<p><a href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/files">Browse more of the <?php echo $file_count; ?> files in this torrent &rarr;</a></p>
<?php endif; ?>

<h3>Seeders (<?php echo count($seeders); ?>)</h3>
<?php if (!empty($seeders)): ?>
	<p>
		<?php foreach($seeders as $user):?>
			<?php $seeders_html[] = $this->getElement('user_byline',array('user'=>$user)) ?>
		<?php endforeach; ?>
		<?php echo implode(', ',$seeders_html); ?>
	</p>
<?php else: ?>
	<p>
		<em>Nobody is seeding this torrent at the moment.</em>
	</p>
<?php endif; ?>


<h3>Leechers (<?php echo count($leechers); ?>)</h3>
<?php if (!empty($leechers)): ?>
	<p>
		<?php foreach($leechers as $user):?> 
			<?php $leechers_html[] = $this->getElement('user_byline',array('user'=>$user)) ?>
		<?php endforeach; ?>
		<?php echo implode(', ',$leechers_html); ?>
	</p>
<?php else: ?>
	<p>
		<em>Nobody is leeching this torrent at the moment.</em>
	</p>
<?php endif; ?>

<h3>Comments (<?php echo $comment_count; ?>)</h3>
<?php if (!empty($comments)): ?>
	<div class="comments">
		<?php foreach($comments as $comment):?>
			<?php $this->renderElement('comment', array('comment' => $comment)); ?>
		<?php endforeach; ?>
	</div>
	<?php if($comment_count > 5): ?>
		<p><a href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/comments/2">View older comments &rarr;</a></p>
	<?php endif; ?>
<?php else: ?>
	<p>
		<em>There are no comments for this torrent.</em>
	</p>
<?php endif; ?>

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