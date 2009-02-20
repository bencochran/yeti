<tr class="<?php echo ($i%2) ? 'odd' : 'even' ?> ">
	<td class="file">
		<?php echo $file->path; ?>
	</td>
	<td class="size"><?php echo pretty_size($file->size); ?></td>
</tr>
