<p>
	BitTorrent, especially private BitTorrent communities like <?php echo SITE_NAME ?>, can be a touch confusing at times.
	As a result, the admins at <?php echo SITE_NAME ?> have prepared what are hopefully helpful guides and explanations covering
	the trickier aspects of using the site.
</p>

<h3>What is a tracker?</h3>
<p>
	<?php echo SITE_NAME ?> is both a website and a BitTorrent tracker. You, of course, know what a website is. But what is a
	BitTorrent tracker and why is it needed? Think of a tracker as something like a roadmap. Each file on the <?php echo SITE_NAME ?>
	website isn't stored directly on the website. Instead, the files are stored on the computers of everyone who uses
	the website. When you click on a download link on the site, your computer (and your BitTorrent client) needs to
	know who has the hundreds of pieces that make up each file. The tracker part of <?php echo SITE_NAME ?> is what lets your computer
	know this information. Without the tracker, you couldn't download any files.
</p>

<h3>What are leechers, seeders, and uploaders?</h3>
<p>
	When you download any part of a file from someone through <?php echo SITE_NAME ?>, you are "leeching" that part of the file. But
	taking without giving back isn't really fair. So when somebody else wants a piece of the file you happen to have,
	your computer sends that piece out to them in a process called "seeding." Seeding and leeching happens all the time
	on the <?php echo SITE_NAME ?> tracker. Most BitTorrent software automatically enables this tit-for-tat system of sharing.
</p>

<p>
	Uploaders are people who add new torrents to the <?php echo SITE_NAME ?> website. Becoming an uploader is extremely easy. Just follow
	the upload tutorials below, and stop by the <a href="<?php echo WWW_BASE_PATH ?>torrent/upload">
	upload tab</a> when you're done. <?php echo SITE_NAME ?> always needs new content, so why not help out when you have a chance?
</p>

<h3>Which BitTorrent client should I use?</h3>
<p>
	We suggest using <a href="http://www.utorrent.com/">uTorrent</a> if you're on a PC running Windows. If you happen
	to be on a Mac or Linux, we suggest using <a href="http://www.transmissionbt.com/">Transmission</a>. There are a lot
	of other BitTorrent clients out there, but uTorrent and Transmission really are the cream of the crop.
</p>

<a name="upload"></a>

<h3>How do I create and upload a torrent?</h3>
<p>
	See our <a href="<?php echo WWW_BASE_PATH ?>help/utorrent">uTorrent upload guide</a> or our <a href="<?php echo WWW_BASE_PATH ?>help/transmission">Transmission upload guide</a>.
</p>

<h3>Why am I not showing up as a seeder even after I've uploaded my .torrent file?</h3>
<p>
	Once you've successfully added your .torrent file to <?php echo SITE_NAME ?>, you need to switch back to your BitTorrent client and
	either stop and start your torrent or tell your client to update the tracker. If you see an error message in your
	BitTorrent client about an "unregistered torrent," it means that you haven't done this stop+start / update tracker
	step.
</p>

<p>
	We realize that it's a bit of a pain to jump through all these hoops, but requiring you to register your torrent 
	helps keep the site cleaner and makes it easier to add new features.
</p>

<a name="contact"></a>
<h3>Contact</h3>
<p>
	Have a question about something? A bone to pick? Just want to say hello? Email <a href="mailto:<?php echo CONTACT_EMAIL ?>"><?php echo CONTACT_EMAIL ?></a> with questions, comments, or complaints.
</p>


<h3>Even more helpful info!</h3>
<p>
	<em>Coming soon!</em>
</p>
