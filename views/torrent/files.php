		<h3>Files (<?php echo $count; ?>)</h3>
		<table class="files">
			<tr>
				<th class="file">File</th>
				<th class="size">Size</th>
			</tr>
			<?php $i=0; foreach($files as $file): ?>
				<?php $this->renderElement('file_in_table', array('file' => $file, 'i' => $i++)); ?>
			<?php endforeach; ?>
		</table>
		<?php $this->renderElement('pagination', array('page'=>$page,'items'=>$count,'items_per_page'=>25,'base_url'=>$base_url)); ?>

	<p class="tools"><a href="<?php echo WWW_BASE_PATH; ?>torrent/<?php echo $torrent->fid; ?>">&larr; Return to torrent details</a></p>
