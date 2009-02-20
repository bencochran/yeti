<form class="login" action="<?php echo WWW_BASE_PATH ?>user/<?php echo $username ?>/reset/<?php echo $reset_code ?>" method="post">
	<p>
		Choose a new password for user <strong><?php echo $username ?></strong>.
	</p>
	<p>	
		<label for="password">New Password: <span>(must be at least 8 characters)</span></label>
		<input id="password" name="password" type="password" size="40" />
	</p>
	<p>	
		<label for="password_match">Repeat Password:</label>
		<input id="password_match" name="password_match" type="password" size="40" />
	</p>
	<div class="buttons">
		<button class="submit" type="submit" name="submit" value="submit" >Reset!</button>
	</div>
</form>