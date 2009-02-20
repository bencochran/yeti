<?php
	// Lots of business in the view. Sorry.
	// At least it's strictly view-related business.
	$previous = $page-1;
	$next = $page+1;
	$last_page = ceil($items/$items_per_page);
	//$current = $page;
	if ($last_page > 1):
?>

<div class="pagination">
	<?php if ($page == 1): ?>
		<span class="disabled">&#171; Previous</span>
		<span class="current"><?php echo $page ?></span>
		<?php for($current=$page+1; $current<=$last_page-2 && $current<=7; $current++): ?>
			<a href="<?php echo $base_url ?>/<?php echo $current ?>"><?php echo $current ?></a>
		<?php endfor; ?>
		<?php if ($current < $last_page-2): ?>...<?php endif; ?>
		<?php if ($last_page-1 > $page):?> <a href="<?php echo $base_url ?>/<?php echo $last_page-1 ?>"><?php echo $last_page-1 ?></a><?php endif; ?>
		<a href="<?php echo $base_url ?>/<?php echo $last_page ?>"><?php echo $last_page ?></a>
		<a href="<?php echo $base_url ?>/<?php echo $next ?>" class="next">Next &#187;</a>
		
	<?php elseif($page == $last_page): ?>
		<a href="<?php echo $base_url ?>">&#171; Previous</a>
		<a href="<?php echo $base_url ?>">1</a>
		<?php if ($last_page > 2): ?><a href="<?php echo $base_url ?>/2">2</a><?php endif; ?>
		<?php if ($last_page-7 > 3): ?>...<?php endif; ?>
		<?php for($current = max($last_page-7,3); $current<$last_page; $current++): ?>
			<a href="<?php echo $base_url ?>/<?php echo $current ?>"><?php echo $current ?></a>
		<?php endfor; ?>
		<span class="current"><?php echo $page ?></span>
		<span class="disabled">Next &#187;</span>

	<?php else: ?>
		<a href="<?php echo $base_url ?>">&#171; Previous</a>
		<a href="<?php echo $base_url ?>">1</a>
		<?php if ($page > 2): ?><a href="<?php echo $base_url ?>/2">2</a><?php endif; ?>
		<?php if ($page-2 > 3): ?>...<?php endif; ?>
		<?php for($current=max($page-2,3); $current<$page; $current++): ?>
			<a href="<?php echo $base_url ?>/<?php echo $current ?>"><?php echo $current ?></a>
		<? endfor; ?>
		<span class="current"><?php echo $page ?></span>		
		<?php for($current=$page+1; $current<=$last_page-2 && $current<=$page+2; $current++): ?>
			<a href="<?php echo $base_url ?>/<?php echo $current ?>"><?php echo $current ?></a>
		<? endfor; ?>
		<?php if ($current <= $last_page-2): ?>...<?php endif; ?>
		<?php if ($last_page-1 > $page):?> <a href="<?php echo $base_url ?>/<?php echo $last_page-1 ?>"><?php echo $last_page-1 ?></a><?php endif; ?>
		<a href="<?php echo $base_url ?>/<?php echo $last_page ?>"><?php echo $last_page ?></a>
		<a href="<?php echo $base_url ?>/<?php echo $next ?>" class="next">Next &#187;</a>
	<?php endif;?>
</div>
<?php endif; ?>