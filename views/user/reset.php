<form class="login" action="<?php echo WWW_BASE_PATH ?>user/reset" method="post">
	<p>
		Forgot your password? No problem! Just give me your username or email address and I'll give you a second chance.
	</p>
	<p>
		<label for="username">Username or Email Address:</label>
		<input id="username" name="username" type="text" size="40" value="<?php echo $username; ?>" />
	</p>
	<div class="buttons">
		<button class="submit" type="submit" name="submit" value="submit" >Reset!</button>
	</div>
</form>