<?php $this->setLayoutVar('pageHead','Welcome to '.SITE_NAME.'!') ?>
<?php $this->setLayoutVar('pageTitle','Home') ?>
<p>
	<?php echo SITE_NAME ?> is a private BitTorrent tracker. Access is
	restricted to people on the local campus network at Carleton. It's 
	completely unofficial and unsanctioned by the administration. All content
	uploaded and downloaded is the sole responsibility of the users. Alright, 
	now that we've taken care of that, why not have a little fun?
</p>

<?php if (!empty($latest_news)): ?>
	<h3>Site News</h3>
	<?php echo $latest_news ?>
	<p><a href="<?php echo WWW_BASE_PATH ?>news">View the news archive &rarr;</a></p>
<?php endif; ?>

<h3>Charts!</h3>
<p>
	See what's really happening on <?php echo SITE_NAME ?>... <a href="/chart">Visit the charts section today!</a>
</p>

<h3>Newest Torrents</h3>
<?php if(!empty($torrents)): ?>
	<table class="torrents">
		<th class="torrent">Torrent</th>
		<th class="seeders"><abbr title="Seeders">Se</abbr></th>
		<th class="leechers"><abbr title="Leechers">Le</abbr></th>
		<th class="completed"><abbr title="Snatches">Sn</abbr></th>
		<?php $i = 0; foreach($torrents as $torrent): ?>
			<?php $this->renderElement('torrent_in_table', array('torrent' => &$torrent, 'i' => $i++)); ?>
		<?php endforeach; ?>
	</table>
	<?php if($total_torrents > 5): ?>
		<p><a href="<?php echo WWW_BASE_PATH ?>browse">Browse more of the <?php echo $total_torrents; ?> torrents on <?php echo SITE_NAME ?> &rarr;</a></p>
	<?php endif; ?>
<?php else: ?>
	<p><em>There are no new torrents!</em></p>
<?php endif; ?>
