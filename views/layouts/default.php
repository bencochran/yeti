<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $pageTitle ?> <? if (substr($pageTitle, -10) != ' - '. SITE_NAME): ?> - <?php echo SITE_NAME; endif; ?></title>
		<?php

		if (isset($requiredCss)) {
			foreach ($requiredCss as $css => $use) {
				if ($use) echo '<link rel="stylesheet" href="' . WWW_CSS_PATH . $css . '" type="text/css" media="all" charset="utf-8" />' . "\n";
			}
		}

		if (isset($requiredJs)) {
			foreach ($requiredJs as $js => $use) {
				if ($use) echo '<script type="text/javascript" language="javascript" charset="utf-8" src="' . WWW_JS_PATH . $js . '"></script>' . "\n";
			}
		}

		?>
	</head>

	<body>
		<div class="header">
			<div class="wrap">
				<h1>
					<a href="<?php echo WWW_BASE_PATH ?>"><span class="title"><?php echo SITE_NAME ?></span></a>
				</h1>

				<div class="supernav">
					<ul class="tabs">
						<li<?php if ($tab == 'home'): ?> class="active"<?php endif; ?>>
							<a href="<?php echo WWW_BASE_PATH ?>" title="Go home">home</a>
						</li>
						<li<?php if ($tab == 'browse'): ?> class="active"<?php endif; ?>>
							<a href="<?php echo WWW_BASE_PATH ?>browse" title="Browse torrents">browse</a>
						</li>
						<li<?php if ($tab == 'upload'): ?> class="active"<?php endif; ?>>
							<a href="<?php echo WWW_BASE_PATH ?>torrent/upload" title="Upload a torrent">upload</a>
							</li>
						<li<?php if ($tab == 'requests'): ?> class="active"<?php endif; ?>>
							<a href="<?php echo WWW_BASE_PATH ?>request" title="View requests">requests</a>
							</li>
						<li<?php if ($tab == 'help'): ?> class="active"<?php endif; ?>>
							<a href="<?php echo WWW_BASE_PATH ?>help" title="Need help?">help</a>
							</li>
					</ul>

					<ul class="status">
						<?php if(empty($active_user)): ?>
							<li><a href="<?php echo WWW_BASE_PATH ?>user/login">login</a></li>
							<li><a href="<?php echo WWW_BASE_PATH ?>user/register">register</a></li>
						<?php else: ?>
							<li><a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $active_user->name; ?>">hey there, <?php echo $active_user->name; ?></a></li>
							<li<?php if($active_user->ratio_warn): ?> class="warn"<?php endif; ?>><a href="<?php echo WWW_BASE_PATH ?>user/<?php echo $active_user->name; ?>#ratio"><strong>ratio</strong> <?php echo $active_user->ratio; ?><?php echo $active_user->ratio_status; ?><?php if($active_user->ratio_warn): ?> (warning)<?php endif; ?></a></li>
							<?php if (!(defined('DISABLE_MESSAGING') && DISABLE_MESSAGING)): ?>							
							<li><a href="<?php echo WWW_BASE_PATH ?>message/inbox"><strong>inbox</strong> <?php echo $active_user->unread_messages; ?> unread</a></li>
							<?php endif; ?>
							<li><a href="<?php echo WWW_BASE_PATH ?>user/logout">logout</a></li>
						<?php endif; ?>
					</ul>
				</div>

				<div style="clear: both;"> </div>
			</div>
		</div>

		<div class="content">
			<div class="wrap">
				<?php if (!empty($pageHead)): ?>
					<h2><?php echo $pageHead ?></h2>
				<?php endif; ?>
				<?php $this->renderElement('flash') ?>
				<?php echo $layoutContent ?>
			</div>
		</div>

		<div class="footer">
			<div class="wrap">
				<p>
					<?php echo SITE_NAME ?> is a <a href="http://yetiapp.com">Yeti-powered</a> site. <?php $u = User::count(); echo $u; ?> users and climbing. Respect.
					<br />
					Questions about <?php echo SITE_NAME ?>? <a href="<?php echo WWW_BASE_PATH ?>contact" title="Bone to pick?">Contact us.</a>
				<?php if ($active_user->admin): ?>
					<br />
					<?php echo DB::$query_count; ?> queries performed. Took <?php echo microtime()-START_MICROTIME ?> microseconds to load this page.
				<?php endif; ?>
				</p>
			</div>
		</div>
	</body>
</html>
<?php Flash::clean(); ?>
