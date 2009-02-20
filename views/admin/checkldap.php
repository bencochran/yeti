<form enctype="multipart/form-data" class="disableUsers" action="<?php echo WWW_BASE_PATH ?>admin/checkldap" method="post">

<ul>
<?php foreach($bad_users as $user): ?>
	<li><input id="<?php echo $user->name ?>" type="checkbox" name="deactivate[]" value="<?php echo $user->name ?>" /><label class="checkbox" for="<?php echo $user->name ?>"> <?php echo $user->name ?> - <?php echo $user->email ?></label></li>
<?php endforeach; ?>
</ul>
<p>
<button type="submit" name="submit" value="submit">Deactivate Selected!</button>
</p>
</form>