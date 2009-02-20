<h3>How do I create and upload a torrent using Transmission?</h3>
<ol>
	<li>Launch Transmission if it's not already running.</li>
	<li>Find the File menu and choose the "Create Torrent File..." menu 
		item.</li>
	<li>In the file selection window that shows up, pick either the single file 
		or the directory you want to use to create your torrent.</li>
	<li>In the next window, find the "Trackers" area.</li>
	<li>For each item in that list (if any), select it and click the "-" button 
		below the list to each item.</li>
	<li>Now click the "+" button below the list and fill in 
		<a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $active_user->name; ?>#announce">your unique Announce URL</a> into the newly-created field. The URL
		 should look something like this:
	<?php echo $base_tracker; ?>/*/announce</li>
	<li>Check the box next to the "Private" option. If you don't mark the 
		torrent as private it will be denied.</li>
	<li>If you wish to, click the "Change..." button to save the resulting file
		 in a location other than that specified. To keep things nice and tidy,
		 it's a good idea to pick a single directory to save all your .torrent
		 files in. Note that .torrent files are just the tiny little files that
		 explain to Transmission how to download the actual contents of the
		torrent.</li>
	<li>Check the box next to the "Open when created" option to tell 
		Transmission to start seeding after the file is created</li>
	<li>Click the "Create" button and wait for Transmission to create the 
		torrent file.</li>
	<li>Visit the <a href="<?php echo WWW_BASE_PATH ?>torrent/upload">upload 
		page</a> in your web browser.</li>
	<li>Fill out the necessary fields, pick the .torrent file that you created 
		in Transmission, and click the "Upload!" button.</li>
	<li>Assuming you see a green success message after clicking the "Upload!" 
		button, your torrent is now registered with
		<?php echo SITE_NAME ?>.</li>
	<li>Switch back to Transmission, right click (or hold "ctrl" while clicking) 
		on the torrent you created and uploaded, and choose "Update Tracker." If 
		the "Update Tracker" option is grayed out, stop and start the torrent 
		instead.</li>
	<li>The status of the torrent should change to "Seeding," and after a few 
		minutes the <?php echo SITE_NAME ?> website should list you as a seeder 
		of the torrent.</li>
	<li>Way to go! You've just added a new torrent to 
		<?php echo SITE_NAME ?>!</li>
</ol>