<?php if ($user->shown || $user->active || User::admin_logged_in()): ?>
	<a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $user->name; ?>"><?php echo $user->display_name; ?></a>
<?php else: ?>
	<em><?php echo $user->display_name ?></em>
<?php endif; ?>