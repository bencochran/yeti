<?php if (!empty($fail_message)): ?>
<p class="failure">
	<?php echo $fail_message; ?>
</p>
<?php endif; ?>

<h3>General Info</h3>
<form enctype="multipart/form-data" class="upload" action="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/edit" method="post">
	<label for="category_cid">
		Category:
	</label>
	<select id="category_cid" name="category_cid">
		<?php foreach($categories as $category) { ?>
		<option value="<?php echo $category->cid; ?>" <?php if($torrent->category == $category) { ?> selected="selected"<?php } ?>><?php echo $category->name; ?></option>
		<?php } ?>
	</select>
	<br /><br />

	<label for="title">
		Title: <span>(at least 2 characters)</span>
	</label>
	<input id="title" name="title" type="text" size="40" value="<?php echo $torrent->title; ?>" />
	<br /><br />

	<label for="description">
		Description: <span>(optional)</span>
	</label>
	<textarea id="description" name="description"><?php echo $torrent->description_edit; ?></textarea>
	<br /><br />

	<input type="hidden" name="fid" value="<?php echo $torrent->fid; ?>" />

	<button type="submit" name="submit" value="submit">Edit!</button>	
</form>

<p class="tools"><a href="<?php echo WWW_BASE_PATH; ?>torrent/<?php echo $torrent->fid; ?>">&larr; Return to torrent details</a></p>
