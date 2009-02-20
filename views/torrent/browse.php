<h3>Jump to a Category</h3>

<?php $categories_html[] = '<a href="'. WWW_BASE_PATH . $browse_base . '">All</a>'; ?>
<?php foreach($categories as $category): ?>
	<?php $categories_html[] = '<a href="'. WWW_BASE_PATH . $browse_base . '/'.$category->slug.'">'.$category->name.'</a>'; ?>
<?php endforeach; ?>
<p><?php echo implode(', ',$categories_html); ?></p>

<h3>Search</h3>
<form class="filter" action="<?php echo WWW_BASE_PATH . $search_base; ?>" method="get">
	<table>
	<tr>
		<td class="category">
			<label for="category_cid">Category:</label>
		</td>
		<td class="search">
			<label for="search">Title contains:</label>
		</td>
		<td class="submit"></td>
	</tr>
	<tr>
		<td class="category">
			<select id="category_cid" name="category_cid">
				<option value="-1"<?php if($selected_category->cid == -1) {?> selected="selected"<?php } ?>>All</option>
				<?php foreach($categories as $category): ?>
					<option value="<?php echo $category->cid; ?>"<?php if ($category->cid == $selected_category->cid):?> selected="selected"<?php endif; ?>><?php echo $category->name; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td class="search">
			<input id="search" type="text" name="query" onfocus="this.select();" value="<?php echo $query ?>" />
		</td>
		<td class="submit">
			<button type="submit">Filter!</button>
		</td>
	</tr>
	</table>
</form>

<?php if(count($torrents) > 0): ?>
	<h3>Torrents Found (<?php echo $count; ?>)</h3>
	<table class="torrents">
		<tr>
			<th class="torrent">Torrent</th>
			<th class="seeders"><abbr title="Seeders">Se</abbr></th>
			<th class="leechers"><abbr title="Leechers">Le</abbr></th>
			<th class="completed"><abbr title="Snatches">Sn</abbr></th>
		</tr>
		<?php $i=0; foreach ($torrents as $torrent): ?>
			<?php $this->renderElement('torrent_in_table', array('torrent' => &$torrent, 'i' => $i++)); ?>
		<?php endforeach; ?>
	</table>
	
	<?php $this->renderElement('pagination', array('page'=>$page,'items'=>$count,'items_per_page'=>15,'base_url'=>$base_url)); ?>
<?php else: ?>
	<h3>Torrents Found (0)</h3>
	<?php if (empty($query)): ?>
		<p><em>This category is currently empty.</em></p>
	<?php else: ?>
		<p><em>Your search returned no results.</em></p>
	<?php endif; ?>
<?php endif; ?>
