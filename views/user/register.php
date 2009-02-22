<p><strong>Please do not use your Carleton username or password below&mdash;come up with something unique
for your Massive account.</strong></p>

<p>We ask for your Carleton e-mail address so we can verify you are a current student. Once you've
filled out the form below, we'll send you an activation e-mail that you'll need to read before you
can login for the first time. Finally, we do store your e-mail address after activation just in case
you need to reset your password. Enjoy!</p>

<h3>Registration Form</h3>
<form class="register" action="<?php echo WWW_BASE_PATH; ?>user/register" method="post">
	<p>
		<label for="email">Carleton E-mail Address: <span>(include @carleton.edu; needed to activate)</span></label>
		<input id="email" name="email" type="text" size="40" value="<?php echo $email; ?>" />
	</p>
	<p>	
		<label for="username"><?php echo SITE_NAME ?> Username: <span>(must be at least 3 characters)</span></label>
		<input id="username" name="username" type="text" size="40" value="<?php echo $username; ?>" />
	</p>
	<p>	
		<label for="password"><?php echo SITE_NAME ?> Password: <span>(must be at least 8 characters)</span></label>
		<input id="password" name="password" type="password" size="40" />
	</p>
	<p>	
		<label for="password_match">Repeat Password:</label>
		<input id="password_match" name="password_match" type="password" size="40" />
	</p>
	<div class="buttons">	
		<button class="submit" type="submit" name="submit" value="submit">Register!</button>
	</div>
</form>