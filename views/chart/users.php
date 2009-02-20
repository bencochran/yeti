<table class="chart_users">
	<th class="rank">#</th>
	<th class="user">User</th>
	<th class="uploaded">Uploaded</th>
	<?php $rank=0; foreach($users as $user): ?>
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
