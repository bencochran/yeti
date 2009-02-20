<?php if (!empty($fail_message)): ?>
<p class="failure">
	<?php echo $fail_message; ?>
</p>
<?php endif; ?>

<form class="login" action="<?php echo WWW_BASE_PATH ?>user/login" method="post">
	<p>
		<label for="username">Username:</label>
		<input id="username" name="username" type="text" size="40" value="<?php echo $username; ?>" />
	</p>
	<p>
		<label for="password">Password:</label>
		<input id="password" name="password" type="password" size="40" />
		<?php if (!empty($referrer)): ?>
			<input id="referrer" name="referrer" type="hidden" value = "<?php echo $referrer; ?>" />
		<?php endif; ?>
	</p>
	<div class="buttons">
		<button class="submit" type="submit" name="submit" value="submit" >Login!</button>
		<p class="reglink">
			Did you <a href="<?php echo WWW_BASE_PATH ?>user/reset" title="Reset your password">forget your password</a>?
		</p>
		<p class="reglink">
			Perhaps you wanted to <a href="<?php echo WWW_BASE_PATH ?>user/register" title="Register for an account">register</a>?
		</p>
	</div>
</form>