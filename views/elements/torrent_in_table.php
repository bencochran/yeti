<tr class="<?php echo ($i%2) ? 'odd' : 'even' ?>">
	<td class="torrent"<?php if ($colspan) echo ' colspan="2"'?>>
		<a title="View torrent details" href="<?php echo WWW_BASE_PATH ?>torrent/<?php echo $torrent->fid; ?>"><?php echo $torrent->title; ?></a>
				
		<br />
		<span class="info">
			<?php echo pretty_size($torrent->size); ?>;
			added <?php $this->renderElement('datetime', array('time' => $torrent->ctime)) ?>
			by
			<?php $this->renderElement('user_byline', array('user' => $torrent->user)); ?>
			in category <a href="<?php echo WWW_BASE_PATH ?>browse/<?php echo $torrent->category->slug; ?>"><?php echo $torrent->category->name; ?></a>
		</span>
	</td>
	<td class="seeders"><?php echo $torrent->seeders; ?></td>
	<td class="leechers"><?php echo $torrent->leechers; ?></td>
	<td class="completed"><?php echo $torrent->completed; ?></td>
</tr>