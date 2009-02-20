<?php if (!empty($fail_message)): ?>
<p class="failure">
	<?php echo $fail_message; ?>
</p>
<?php endif; ?>

<form enctype="multipart/form-data" action="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>/delete" method="post">
	<p>Are you sure you want to delete the torrent "<?php echo $torrent->title; ?>"? If you proceed, anyone else leeching or seeding the torrent will no longer be able to do so. You <em>cannot</em> undo this action.</p>
	<p>
		<button type="submit" name="submit" value="delete">Delete it!</button>
		<button type="submit" name="submit" value="cancel">Oh no! Nevermind</button>	
	</p>
</form>