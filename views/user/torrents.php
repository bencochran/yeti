<table class="torrents">
	<th class="torrent">Torrent</th>
	<th class="seeders"><abbr title="Seeders">Se</abbr></th>
	<th class="leechers"><abbr title="Leechers">Le</abbr></th>
	<th class="completed"><abbr title="Snatches">Sn</abbr></th>
	<?php $i=0; foreach ($torrents as $torrent): ?>
		<?php $this->renderElement('torrent_in_table', array('torrent' => &$torrent, 'i' => $i++)); ?>
	<?php endforeach; ?>
</table>
<?php $this->renderElement('pagination', array('page'=>$page,'items'=>$count,'items_per_page'=>15,'base_url'=>$base_url)); ?>

<p class="tools"><a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $user->name; ?>">&larr; Return to user profile</a></p>
