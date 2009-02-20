<?php if($is_active_user): ?>
	<a name="announce"></a>
	<h3>Announce URL</h3>
	<p><em>Keep this private. It's linked to your <?php echo SITE_NAME ?> identity. Anyone caught sharing Announce URLs will be banned.</em></p>
	<p><span class="announce"><?php echo $user->url_announce; ?></span></p>
	<p>Don't know what this is? <a href="<?php echo WWW_BASE_PATH ?>help#announce">Read about Announce URLs</a>.</p>
<?php endif; ?>

<?php if($user->ctime > 0 || $is_active_user || $active_user->admin): ?>
<h3>Info</h3>
<p>
	<?php if($is_active_user || $active_user->admin): ?>
		Email Address: <?php echo $user->email; ?> <em>(only visible to you)</em><br />
	<?php endif; ?>
	<?php if ($user->ctime > 0): ?>
		Member Since: <?php echo date('M d, Y, g:i a',$user->ctime); ?> <br />
	<?php endif; ?>
	<?php if ($user->last_seen > 0 && $active_user->admin): ?>
		Last Seen: <?php echo time_diff($user->last_seen); ?><br />
	<?php endif; ?>
</p>
<?php endif; ?>


<a name="ratio"></a>
<h3>Ratio & Stats</h3>
<?php if($is_active_user): ?><p><em>Ratios aren't currently enforced, but will be shortly. Keep an eye on yours. If it's lower than 0.20, you should start seeding more and leeching less.</em></p><?php endif; ?>
<p>
	Current ratio: <?php echo $user->ratio; ?><br />
	Total uploaded: <?php echo pretty_size($user->uploaded); ?><br />
	Total downloaded: <?php echo pretty_size($user->downloaded); ?>
</p>

<?php if($total_active == 0): ?>
	<h3>Active Torrents (0)</h3>
	<?php if($is_active_user): ?>
		<p><em>You don't have any active torrents. Your torrent client is probably offline, or you haven't gotten around to up/downloading anything.</em></p>
	<?php else: ?>
		<p><em><?php echo $user->name; ?> doesn't have any active torrents. Their torrent client is probably offline, or they haven't gotten around to up/downloading anything.</em></p>
	<?php endif; ?>
<?php else: ?>
	<h3>Active Torrents (<?php echo $total_active; ?>)</h3>
	<table class="torrents">
		<th class="torrent">Torrent</th>
		<th class="seeders"><abbr title="Seeders">Se</abbr></th>
		<th class="leechers"><abbr title="Leechers">Le</abbr></th>
		<th class="completed"><abbr title="Snatches">Sn</abbr></th>
		<?php $i=0; foreach ($active_torrents as $torrent): ?>
			<?php $this->renderElement('torrent_in_table', array('torrent' => $torrent, 'i' => $i++)); ?>
		<?php endforeach; ?>
	</table>
	<?php if($total_active > 5): ?>
	<p><a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $user->name; ?>/active">Browse more of these <?php echo $total_active; ?> active torrents &rarr;</a></p>
	<?php endif; ?>
<?php endif; ?>

<?php if($total_added == 0): ?>
	<h3>Uploaded Torrents (0)</h3>
	<?php if($is_active_user): ?>
		<p><em>You haven't uploaded any torrents.</em></p>
	<?php else: ?>
		<p><em><?php echo $user->name; ?> hasn't uploaded any torrents.</em></p>
	<?php endif; ?>
<?php else: ?>
	<h3>Uploaded Torrents (<?php echo $total_added; ?>)</h3>
	<table class="torrents">
		<th class="torrent">Torrent</th>
		<th class="seeders"><abbr title="Seeders">Se</abbr></th>
		<th class="leechers"><abbr title="Leechers">Le</abbr></th>
		<th class="completed"><abbr title="Snatches">Sn</abbr></th>
		<?php $i=0; foreach ($added_torrents as $torrent): ?>
			<?php $this->renderElement('torrent_in_table', array('torrent' => $torrent, 'i' => $i++)); ?>
		<?php endforeach; ?>
			</table>
	<?php if($total_added > 5): ?>
	<p><a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $user->name; ?>/uploads">Browse more of these <?php echo $total_added; ?> uploaded torrents &rarr;</a></p>
	<?php endif; ?>
<?php endif; ?>

<?php if($is_active_user): ?>
	<h3>Torrent Software</h3>
	<p>We strongly suggest using <a href="http://www.utorrent.com/">uTorrent</a> on Windows XP/Vista, and <a href="http://www.transmissionbt.com/">Transmission</a> on Mac OS X.</p>
<?php endif; ?>
