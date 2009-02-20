<h3>How do I create and upload a torrent using uTorrent?</h3>
<ol>
	<li>Launch uTorrent if it's not already running.</li>
	<li>Find the File menu and pick the "Create New Torrent..." menu item.</li>
	<li>In the window that shows up, find the "Select Source" area.</li>
	<li>Click the "Add File" button if you want to create a torrent out of a 
		single file, or click the "Add Directory" button if you want to create a 
		torrent out of a directory.</li>
	<li>In the file selection window that shows up, pick either the single file 
		or the directory you want to use to create your torrent.</li>
	<li>Back in the "Create New Torrent..." window, find the "Torrent 
		Properties" area.</li>
	<li>In the trackers text field, fill in
		<a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $active_user->name; ?>#announce">your
			unique Announce URL</a>. The URL should look something like this: 
			<?php echo $base_tracker; ?>/*/announce</li>
	<li>Find the "Other" area.</li>
	<li>Check both the "Start seeding" and "Private torrent" checkboxes. If you 
		don't mark the torrent as private, it will be denied.</li>
	<li>Click the "Create and save as..." button at the bottom of the 
		window.</li>
	<li>Pick a spot to save the .torrent file. To keep things nice and tidy, 
		it's a good idea to pick a single directory to save all your .torrent 
		files in. Note that .torrent files are just the tiny little files that 
		explain to uTorrent how to download the actual contents of the 
		torrent.</li>
	<li>Once you've saved the .torrent file, click the "Close" button.</li>
	<li>The torrent will show up in the main uTorrent window with a little red 
		[x] next to it. If you look at the status column for the torrent, you'll 
		see that it's been flagged as an "unregistered torrent."</li>
	<li>Visit the <a href="<?php echo WWW_BASE_PATH ?>torrent/upload">upload 
		page</a> in your web browser.</li>
	<li>Fill out the necessary fields, pick the .torrent file that you created 
		in uTorrent, and click the "Upload!" button.</li>
	<li>Assuming you see a green success message after clicking the "Upload!" 
		button, your torrent is now registered with
		<?php echo SITE_NAME ?>.</li>
	<li>Switch back to uTorrent, right click on the torrent you created and 
		uploaded, and choose "Update Tracker." If the "Update Tracker" option is 
		grayed out, stop and start the torrent instead.</li>
	<li>The icon next to the torrent name should change from the red [x] to a 
		green arrow, the status should change to"Seeding," and after a few 
		minutes the <?php echo SITE_NAME ?> website should list you as a seeder 
		of the torrent.</li>
	<li>Way to go! You've just added a new torrent to
		<?php echo SITE_NAME ?>!</li>
</ol>