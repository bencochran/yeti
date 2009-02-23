<p>Yeti has to remember of a lot of things. Let's make sure his database is working and reachable. Note that you need to
create the database before beginning the configuration&mdash;we don't do this for you. And please don't give Yeti your
MySQL root account. That's just bad juju.</p>

<form class="setup" action="<?php echo WWW_BASE_PATH; ?>database" method="post">

	<div class="rows">
		<div class="row">
			<label for="hostname">Hostname</label>
			<input id="hostname" name="hostname" type="text" value="<?php echo $hostname; ?>" />
		</div>
		<div class="row">
			<label for="port">Port</label>
			<input id="port" name="port" type="text" value="<?php echo $port; ?>" />
		</div>
		<div class="row">
			<label for="user">User</label>
			<input id="user" name="user" type="text" value="<?php echo $user; ?>" />
		</div>
		<div class="row">
			<label for="password">Password</label>
			<input id="password" name="password" type="password" />
		</div>
		<div class="row">
			<label for="db_name">DB Name</label>
			<input id="db_name" name="db_name" type="text" value="<?php echo $db_name; ?>" />
		</div>	
	</div>

	<div class="confirm">
		<input type="submit" value="Check connection" />
	</div>
</form>