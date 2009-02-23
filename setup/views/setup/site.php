<p>To get started, we need a little info about the site. What's it's name? Where's it located? You know the drill.</p>

<form class="setup" action="<?php echo WWW_BASE_PATH; ?>site" method="post">

	<div class="rows">
		<div class="row">
			<label for="site_name">Site Name</label>
			<input id="site_name" name="site_name" type="text" value="<?php echo $site_name; ?>" />
		</div>
		<div class="row">
			<label for="base_path">Site Address</label>
			<input id="base_path" name="base_path" type="text" value="<?php echo $base_path; ?>" />
		</div>
		<div class="row">
			<label for="cookie_domain">Cookie Domain</label>
			<input id="cookie_domain" name="cookie_domain" type="text" value="<?php echo $cookie_domain; ?>" />
		</div>
	</div>

	<div class="confirm">
		<input name="submit" type="submit" value="Save and continue" />
	</div>
</form>