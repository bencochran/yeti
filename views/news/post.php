<h3>Upload Form</h3>
<form enctype="multipart/form-data" class="post" action="<?php echo WWW_BASE_PATH ?>news/post" method="post">
	<p>
	<label for="user">
		User:
	</label>
	<input id="user" name="user" type="text" size="40" value="<?php echo $user->name ?>" />
	</p>
	
	<p>
	<label for="subject">
		Title:
	</label>
	<input id="subject" name="subject" type="text" size="40" value="<?php echo $subject ?>" />
	</p>
	
	<p>
	<label for="body">
		Body:
	</label>
	<textarea id="body" name="body"><?php echo $body ?></textarea>
	</p>
	
	<p>
	<button type="submit" name="submit" value="submit">Send!</button>
	</p>
</form>
