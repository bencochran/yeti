<p>
	<strong>AVOID DUPLICATES &mdash; SEARCH BEFORE UPLOADING. THANKS!</strong>
</p>
<p>
	Before uploading the torrent, please make sure you set its Announce URL / Tracker URL to
	<a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $active_user->name . '#announce'; ?>">your unique Announce URL</a>.
	Once it's been uploaded, you'll need to refresh the torrent in your client. Refreshing can be done by pausing and
	unpausing your torrent, or by choosing some sort of "update tracker" option.
</p>
<p>
	Still need some help? <a href="<?php echo WWW_BASE_PATH ?>help#upload">Read more about uploading torrents</a>.
</p>

<h3>Upload Form</h3>
<form enctype="multipart/form-data" class="upload" action="<?php echo WWW_BASE_PATH ?>torrent/upload" method="post">
	<label for="torrent">
		.torrent File:
		<span>(make sure you <a href="<?php echo WWW_BASE_PATH ?>help#upload">created it correctly</a>)</span>
	</label>
	<input id="torrent" name="torrent" type="file" size="40" />
	<br /><br />

	<label for="category_cid">
		Category:
	</label>
	<select id="category_cid" name="category_cid">
		<?php foreach($categories as $category): ?>
		<option value="<?php echo $category->cid; ?>"<?php if ($category_cid == $selected_cid):?> selected="selected"<?php endif; ?>><?php echo $category->name; ?></option>
		<?php endforeach; ?>
	</select>
	<br /><br />

	<label for="title">
		Title: <span>(at least 2 characters)</span>
	</label>
	<input id="title" name="title" type="text" size="40" value="<?php echo $title ?>"/>
	<br /><br />

	<label for="description">
		Description: <span>(optional)</span>
	</label>
	<textarea id="description" name="description"><?php echo $description ?></textarea>
	<br /><br />

	<button type="submit" name="submit" value="submit">Upload!</button>
</form>
