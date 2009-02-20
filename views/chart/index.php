<p>
	<?php echo SITE_NAME ?> is constantly pushing out and pulling in bits and bytes. The charts below should give you a feel for what's
	happening on the tracker at any given time. Note that we'll be adding new charts from time to time, so do check in
	periodically.
</p>

<a name="torrents-most-snatched"></a>
<h3>Torrents &mdash; Most Snatched</h3>
<table class="chart_torrents">
	<tr>
		<th class="rank">#</th>
		<th class="torrent">Torrent</th>
		<th class="seeders"><abbr title="Seeders">Se</abbr></th>
		<th class="leechers"><abbr title="Leechers">Le</abbr></th>
		<th class="completed"><abbr title="Snatches">Sn</abbr></th>
	</tr>
	<?php $rank=0; foreach ($most_snatched as $torrent): ?>
		<tr class="<?php echo ($rank++%2) ? 'odd' : 'even' ?>">
			<td class="rank">
				<?php echo $rank; ?>.
			</td>
			<td class="torrent">
				<a title="View torrent details" href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>"><?php echo $torrent->title; ?></a><br />

				<span class="info">
					<?php echo pretty_size($torrent->size); ?>;
					added <?php echo time_diff($torrent->ctime); ?>
					by
					<?php $this->renderElement('user_byline', array('user'=>$torrent->user)); ?>
					in category <a href="<?php echo WWW_BASE_PATH ?>browse/<?php echo $torrent->category->slug; ?>"><?php echo $torrent->category->name; ?></a>
				</span>
			</td>
			<td class="seeders"><?php echo $torrent->seeders; ?></td>
			<td class="leechers"><?php echo $torrent->leechers; ?></td>
			<td class="completed"><?php echo $torrent->completed; ?></td>
		</tr>
	<?php endforeach; ?>
</table>

<a name="users-top-uploaders"></a>
<h3>Users &mdash; Top Uploaders</h3>
<table class="chart_uploaders">
	<tr>
		<th class="rank">#</th>
		<th class="user">User</th>
		<th class="uploaded">Uploaded</th>
	</tr>
	<?php $rank=0; foreach($top_uploaders as $user): ?>
		<tr class="<?php echo ($rank++%2) ? 'odd' : 'even' ?>">
			<td class="rank">
				<?php echo $rank; ?>.
			</td>
			<td class="user">
				<a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $user->name; ?>"><?php echo $user->name; ?></a>
				<br />
				<span class="info">
				<?php echo pretty_size($user->downloaded); ?> downloaded;
				ratio <?php echo $user->ratio; ?>
				</span>
			</td>
			<td class="uploaded">
				<?php echo pretty_size($user->uploaded); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
