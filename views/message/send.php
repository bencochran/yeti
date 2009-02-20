<h3>Upload Form</h3>
<form enctype="multipart/form-data" class="compose" action="<?php echo WWW_BASE_PATH ?>message/compose/<?php echo $to_user->name ?>" method="post">
	<p>
		To: 
		<?php $this->renderElement('user_byline', array('user'=>$to_user)) ?>
	</p>
	<p>
	<label for="Subject">
		Subject:
	</label>
	<input id="subject" name="subject" type="text" size="40" value="<?php echo $subject ?>"/>
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
